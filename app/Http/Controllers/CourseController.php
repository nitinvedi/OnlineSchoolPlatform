<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use App\Models\Wishlist;
use App\Notifications\CourseEnrolled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::where('status', 'published')
            ->with('instructor', 'category');

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('overview', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        // Level filter (only if column exists)
        if ($level = $request->input('level')) {
            if (Schema::hasColumn('courses', 'level')) {
                $query->where('level', $level);
            }
        }

        // Language filter (only if column exists)
        if ($language = $request->input('language')) {
            if (Schema::hasColumn('courses', 'language')) {
                $query->where('language', $language);
            }
        }

        // Price filters
        if ($request->filled('is_free')) {
            if ($request->input('is_free')) {
                $query->where('price', 0);
            }
        }
        if ($min = $request->input('price_min')) {
            $query->where('price', '>=', (float) $min);
        }
        if ($max = $request->input('price_max')) {
            $query->where('price', '<=', (float) $max);
        }

        // Rating min
        if ($ratingMin = $request->input('rating_min')) {
            $query->where('rating', '>=', (float) $ratingMin);
        }

        // Duration buckets (based on lessons.duration_minutes)
        if ($duration = $request->input('duration')) {
            // map buckets to minutes: under-2 = <120, 2-5 = 120-300, 5-10 = 300-600, 10plus = >600
            $query->whereHas('lessons', function ($q) use ($duration) {
                // We keep it simple: filter courses that have at least one lesson fitting the bucket
                return match ($duration) {
                    'under-2' => $q->where('duration_minutes', '<', 120),
                    '2-5' => $q->whereBetween('duration_minutes', [120, 300]),
                    '5-10' => $q->whereBetween('duration_minutes', [300, 600]),
                    '10plus' => $q->where('duration_minutes', '>', 600),
                    default => $q,
                };
            });
        }

        // Sort
        match ($request->input('sort', 'popular')) {
            'popular'      => $query->orderByDesc('student_count'),
            'rating'       => $query->orderByDesc('rating'),
            'price-asc'    => $query->orderBy('price'),
            'price-desc'   => $query->orderByDesc('price'),
            default        => $query->latest('published_at'),
        };

        $courses    = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        // count active filters for UI badge
        $appliedFilters = collect($request->only(['search','category','level','language','price_min','price_max','is_free','rating_min','duration','sort']))
            ->filter(fn($v) => filled($v))->count();

        return view('courses.index', compact('courses', 'categories', 'appliedFilters'));
    }

    /**
     * Live suggestions for search (titles, categories, instructors)
     */
    public function suggestions(Request $request)
    {
        $q = $request->input('q');
        if (! $q) {
            return response()->json(['data' => []]);
        }

        $titles = Course::where('status', 'published')
            ->where('title', 'like', "%{$q}%")
            ->limit(6)
            ->pluck('title')
            ->toArray();

        $categories = Category::where('name', 'like', "%{$q}%")->limit(6)->pluck('name')->toArray();

        $instructors = Course::where('status', 'published')
            ->whereHas('instructor', fn($iq) => $iq->where('name', 'like', "%{$q}%"))
            ->with('instructor')
            ->limit(6)
            ->get()
            ->pluck('instructor.name')
            ->toArray();

        $combined = array_values(array_unique(array_merge($titles, $categories, $instructors)));

        return response()->json(['data' => array_slice($combined, 0, 10)]);
    }

    public function show(Course $course)
    {
        $enrolled = null;
        $isWishlisted = false;
        
        if (auth()->check()) {
            $enrolled = Enrollment::with('completedLessons')
                ->where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->first();
            
            $isWishlisted = Wishlist::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->exists();
        }

        if ($course->status !== 'published' && $course->instructor_id !== auth()->id() && !auth()->user()?->isAdmin() && !$enrolled) {
            abort(403);
        }

        $course->load(['instructor', 'category', 'lessons' => function ($q) {
            $q->orderBy('order');
        }, 'liveSessions']);

        $course->loadCount('liveSessions');

        if ($course->instructor) {
            $course->instructor->loadCount('courses');
        }

        $reviews = $course->reviews()->with('user')->latest()->get();
        $reviewAverage = $reviews->avg('rating') ?? 0;

        $totalReviews = $reviews->count();
        $reviewDistribution = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $reviews->where('rating', $star)->count();
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            $reviewDistribution[$star] = [
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        return view('courses.show', compact('course', 'enrolled', 'isWishlisted', 'reviews', 'reviewAverage', 'reviewDistribution'));
    }

    public function enroll(Course $course)
    {
        if ($course->status !== 'published') {
            abort(403, 'You cannot enroll in an unpublished course.');
        }

        $enrolled = Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->exists();
        if ($enrolled) {
            return redirect()->back()->with('warning', 'You are already enrolled in this course.');
        }

        // If course has a price, redirect to payment
        if ($course->price && $course->price > 0) {
            return redirect()->route('payments.checkout', $course);
        }

        Enrollment::create([
            'user_id'   => auth()->id(),
            'course_id' => $course->id,
        ]);

        $course->increment('student_count');

        // Send enrollment notification
        auth()->user()->notify(new CourseEnrolled($course));

        return redirect()->back()->with('success', 'Successfully enrolled in course!');
    }

    public function toggleWishlist(Course $course)
    {
        auth()->user();
        
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Course removed from wishlist.';
            $isWishlisted = false;
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'course_id' => $course->id,
            ]);
            $message = 'Course added to wishlist.';
            $isWishlisted = true;
        }

        // Return JSON for AJAX or redirect
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'is_wishlisted' => $isWishlisted]);
        }

        return redirect()->back()->with('success', $message);
    }
}
