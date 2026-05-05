<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Facades\Gate;

class LessonManagementController extends Controller
{
    /**
     * Show the form to add a new lesson to a course.
     */
    public function create(Course $course)
    {
        Gate::authorize('update', $course);

        // Default order = next available slot
        $nextOrder = $course->lessons()->max('order') + 1;

        return view('instructor.lessons.create', compact('course', 'nextOrder'));
    }

    /**
     * Store a new lesson.
     */
    public function store(StoreLessonRequest $request, Course $course)
    {
        Gate::authorize('update', $course);

        $order = $request->order ?? ($course->lessons()->max('order') + 1);

        $videoUrl = $request->video_url;

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('lessons', 'public');
            $videoUrl = $path; // we'll use asset('storage/' . $path) in the view
        }

        $course->lessons()->create([
            'title'            => $request->title,
            'content'          => $request->content,
            'description'      => $request->description,
            'type'             => $request->type,
            'video_url'        => $videoUrl,
            'duration_minutes' => $request->duration_minutes,
            'is_free'          => $request->is_free,
            'order'            => $order,
        ]);

        return redirect()
            ->route('instructor.courses.edit', $course)
            ->with('success', 'Lesson added successfully.');
    }

    /**
     * Show the form to edit an existing lesson.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        Gate::authorize('update', $course);

        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        return view('instructor.lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Update a lesson.
     */
    public function update(UpdateLessonRequest $request, Course $course, Lesson $lesson)
    {
        Gate::authorize('update', $course);

        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $videoUrl = $request->video_url;

        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('lessons', 'public');
            $videoUrl = $path;
        }

        $lesson->update([
            'title'            => $request->title,
            'content'          => $request->content,
            'description'      => $request->description,
            'type'             => $request->type,
            'video_url'        => $videoUrl,
            'duration_minutes' => $request->duration_minutes,
            'is_free'          => $request->is_free,
            'order'            => $request->order ?? $lesson->order,
        ]);

        return redirect()
            ->route('instructor.courses.edit', $course)
            ->with('success', 'Lesson updated successfully.');
    }

    /**
     * Delete a lesson.
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        Gate::authorize('update', $course);

        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $lesson->delete();

        return redirect()
            ->route('instructor.courses.edit', $course)
            ->with('success', 'Lesson deleted.');
    }
}
