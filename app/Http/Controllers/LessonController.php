<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a specific lesson
     */
    public function show(Course $course, Lesson $lesson)
    {
        // Verify lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        // Check if user has access (enrolled, instructor, or admin)
        $user = auth()->user();
        $enrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$enrolled && $user->role !== 'instructor' && $user->role !== 'admin') {
            // Check if this lesson is free
            if (!$lesson->is_free) {
                abort(403, 'You do not have access to this lesson.');
            }
        }

        // Get all lessons for navigation
        $lessons = $course->lessons()->orderBy('order')->get();
        $currentLessonIndex = $lessons->search(function ($l) use ($lesson) {
            return $l->id === $lesson->id;
        });

        $previousLesson = $currentLessonIndex > 0 ? $lessons[$currentLessonIndex - 1] : null;
        $nextLesson = $currentLessonIndex < $lessons->count() - 1 ? $lessons[$currentLessonIndex + 1] : null;

        // Sequential Lesson Locking: Students must complete all previous lessons
        if ($enrolled && !$enrolled->isLessonCompleted($lesson)) {
            // Instructor/admin bypasses the lock
            if ($user->role !== 'instructor' && $user->role !== 'admin') {
                $previousLessons = $lessons->take($currentLessonIndex);
                foreach ($previousLessons as $prev) {
                    if (!$enrolled->isLessonCompleted($prev)) {
                        return redirect()->route('lessons.show', [$course, $prev])
                            ->with('error', 'You must complete previous lessons before moving ahead.');
                    }
                }
            }
        }

        // Mark lesson as started if enrolled
        if ($enrolled) {
            $enrolled->touch(); // Update last activity timestamp
        }

        return view('lessons.show', [
            'course' => $course->load('instructor', 'category'),
            'lesson' => $lesson,
            'lessons' => $lessons,
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
            'currentIndex' => $currentLessonIndex + 1,
            'totalLessons' => $lessons->count(),
            'enrolled' => $enrolled,
        ]);
    }

    /**
     * Mark a lesson as completed
     */
    public function markCompleted(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $user = auth()->user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        // Check if lesson already marked as completed
        $alreadyCompleted = $enrollment->completedLessons()->where('lesson_id', $lesson->id)->exists();

        if (!$alreadyCompleted) {
            // Mark as completed
            $enrollment->completedLessons()->attach($lesson->id);

            // Update completion count
            $completedCount = $enrollment->completedLessons()->count();
            $totalLessons = $course->lessons()->count();
            $progressPercent = (int) (($completedCount / $totalLessons) * 100);

            $enrollment->update([
                'lessons_completed' => $completedCount,
                'progress_percent' => $progressPercent,
            ]);

            // Check if course is completed and generate certificate
            $enrollment->checkCompletion();
        }

        return back()->with('success', 'Lesson marked as complete!');
    }
}
