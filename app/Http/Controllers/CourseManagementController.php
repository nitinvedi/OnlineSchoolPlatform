<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseManagementController extends Controller
{
    /**
     * List all courses owned by the authenticated instructor.
     */
    public function index()
    {
        Gate::authorize('viewAny', Course::class);

        $courses = Course::where('instructor_id', auth()->id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('instructor.courses.index', compact('courses'));
    }

    /**
     * Show the form to create a new course.
     */
    public function create()
    {
        Gate::authorize('create', Course::class);

        $categories = Category::orderBy('name')->get();

        return view('instructor.courses.create', compact('categories'));
    }

    /**
     * Persist a new course.
     */
    public function store(StoreCourseRequest $request)
    {
        Gate::authorize('create', Course::class);

        // DEBUG: log incoming request for diagnosis of missing description
        Log::debug('Course store payload:', $request->only(['title','description','overview','category_id','status']));

        $slug = $this->generateUniqueSlug($request->title);

        $course = Course::create([
            'title'         => $request->title,
            'slug'          => $slug,
            'description'   => $request->description,
            'overview'      => $request->overview,
            'category_id'   => $request->category_id,
            'instructor_id' => auth()->id(),
            'status'        => $request->status,
            'thumbnail_url' => $this->handleThumbnailUpload($request, null),
            'published_at'  => $request->status === 'published' ? now() : null,
        ]);

        return redirect()
            ->route('instructor.courses.edit', $course)
            ->with('success', 'Course created! Now add some lessons.');
    }

    /**
     * Show the form to edit an existing course.
     */
    public function edit(Course $course)
    {
        Gate::authorize('update', $course);

        $categories = Category::orderBy('name')->get();
        $lessons    = $course->lessons()->orderBy('order')->get();

        return view('instructor.courses.edit', compact('course', 'categories', 'lessons'));
    }

    /**
     * Update an existing course.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        Gate::authorize('update', $course);

        $publishedAt = $course->published_at;
        if ($request->status === 'published' && ! $publishedAt) {
            if ($course->lessons()->count() === 0) {
                return redirect()->back()
                    ->withErrors(['status' => 'You cannot publish a course without any lessons. Please add at least one lesson first.'])
                    ->withInput();
            }
            $publishedAt = now();
        }

        $course->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'overview'      => $request->overview,
            'category_id'   => $request->category_id,
            'status'        => $request->status,
            'thumbnail_url' => $this->handleThumbnailUpload($request, $course->thumbnail_url),
            'published_at'  => $publishedAt,
        ]);

        return redirect()
            ->route('instructor.courses.edit', $course)
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Soft-delete a course and all its lessons.
     */
    public function destroy(Course $course)
    {
        Gate::authorize('delete', $course);

        $course->delete(); // Soft delete — data is preserved

        return redirect()
            ->route('instructor.courses.index')
            ->with('success', 'Course deleted. Your students\' progress has been preserved.');
    }

    // -------------------------------------------------------------------------

    /**
     * Handle an optional thumbnail upload. Returns the stored path (or old path if no new file).
     */
    private function handleThumbnailUpload($request, ?string $existing): ?string
    {
        if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
            // Delete old uploaded file if it exists
            if ($existing && str_starts_with($existing, 'thumbnails/')) {
                Storage::disk('public')->delete($existing);
            }
            return $request->file('thumbnail')->store('thumbnails', 'public');
        }

        // If a string URL was provided (like an external link) and no file uploaded
        if ($request->filled('thumbnail_url')) {
            return $request->thumbnail_url;
        }

        // No new file uploaded and no URL — keep the existing value
        return $existing;
    }

    /**
     * Generate a URL slug that is unique in the courses table.
     */
    private function generateUniqueSlug(string $title): string
    {
        $base  = Str::slug($title);
        $slug  = $base;
        $count = 1;

        while (Course::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
