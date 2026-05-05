<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Enrollment;
use Illuminate\Http\Request;

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

        // Sort
        match ($request->input('sort', 'newest')) {
            'popular'   => $query->orderByDesc('student_count'),
            'rating'    => $query->orderByDesc('rating'),
            default     => $query->latest('published_at'),
        };

        $courses    = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('courses.index', compact('courses', 'categories'));
    }

    public function show(Course $course)
    {
        $enrolled = null;
        if (auth()->check()) {
            $enrolled = Enrollment::with('completedLessons')
                ->where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->first();
        }

        if ($course->status !== 'published' && $course->instructor_id !== auth()->id() && !auth()->user()?->isAdmin() && !$enrolled) {
            abort(403);
        }

        $course->load(['instructor', 'category', 'lessons' => function ($q) {
            $q->orderBy('order');
        }]);

        return view('courses.show', compact('course', 'enrolled'));
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

        Enrollment::create([
            'user_id'   => auth()->id(),
            'course_id' => $course->id,
        ]);

        $course->increment('student_count');

        return redirect()->back()->with('success', 'Successfully enrolled in course!');
    }
}
