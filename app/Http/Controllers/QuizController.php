<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Show the quiz to the student.
     */
    public function show(Course $course, Lesson $lesson, Quiz $quiz)
    {
        $this->checkAccess($course, $lesson, $quiz);

        $quiz->load('questions.options');
        $enrollment = $this->getEnrollment($course);

        // Count previous attempts
        $attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->count();

        // Check if max attempts reached
        $maxAttemptsReached = $quiz->max_attempts > 0 && $attemptCount >= $quiz->max_attempts;

        // Get best attempt
        $bestAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->orderByDesc('percentage')
            ->first();

        return view('quizzes.show', compact(
            'course', 'lesson', 'quiz', 'enrollment',
            'attemptCount', 'maxAttemptsReached', 'bestAttempt'
        ));
    }

    /**
     * Submit and grade a quiz attempt.
     */
    public function submit(Request $request, Course $course, Lesson $lesson, Quiz $quiz)
    {
        $this->checkAccess($course, $lesson, $quiz);

        $quiz->load('questions.options');

        // Check attempt limit
        $attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->count();

        if ($quiz->max_attempts > 0 && $attemptCount >= $quiz->max_attempts) {
            return back()->with('error', 'You have reached the maximum number of attempts.');
        }

        $answers = $request->input('answers', []);
        $score   = 0;
        $maxScore = 0;

        foreach ($quiz->questions as $question) {
            $maxScore += $question->points;
            $selectedOptionId = $answers[$question->id] ?? null;

            if ($selectedOptionId) {
                $correctOption = $question->correctOption();
                if ($correctOption && (int) $selectedOptionId === $correctOption->id) {
                    $score += $question->points;
                }
            }
        }

        $percentage = $maxScore > 0 ? (int) round(($score / $maxScore) * 100) : 0;
        $passed     = $percentage >= $quiz->pass_percentage;

        $attempt = QuizAttempt::create([
            'quiz_id'      => $quiz->id,
            'user_id'      => auth()->id(),
            'score'        => $score,
            'max_score'    => $maxScore,
            'percentage'   => $percentage,
            'passed'       => $passed,
            'answers'      => $answers,
            'completed_at' => now(),
        ]);

        // If passed, auto-mark the lesson as complete
        if ($passed) {
            $enrollment = $this->getEnrollment($course);
            if ($enrollment) {
                $alreadyCompleted = $enrollment->completedLessons()
                    ->where('lesson_id', $lesson->id)->exists();

                if (!$alreadyCompleted) {
                    $enrollment->completedLessons()->attach($lesson->id);
                    $completedCount = $enrollment->completedLessons()->count();
                    $totalLessons   = $course->lessons()->count();
                    $progressPercent = $totalLessons > 0
                        ? (int) (($completedCount / $totalLessons) * 100)
                        : 0;

                    $enrollment->update([
                        'lessons_completed' => $completedCount,
                        'progress_percent'  => $progressPercent,
                    ]);

                    $enrollment->checkCompletion();
                }
            }
        }

        return redirect()->route('quizzes.result', [$course, $lesson, $quiz, $attempt]);
    }

    /**
     * Show the result of a specific quiz attempt.
     */
    public function result(Course $course, Lesson $lesson, Quiz $quiz, QuizAttempt $attempt)
    {
        $this->checkAccess($course, $lesson, $quiz);

        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $quiz->load('questions.options');

        return view('quizzes.result', compact('course', 'lesson', 'quiz', 'attempt'));
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function checkAccess(Course $course, Lesson $lesson, Quiz $quiz): void
    {
        if ($lesson->course_id !== $course->id || $quiz->lesson_id !== $lesson->id) {
            abort(404);
        }

        $user = auth()->user();
        $isOwnerOrAdmin = $course->instructor_id === $user->id || $user->isAdmin();
        if (!$isOwnerOrAdmin) {
            $enrolled = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)->exists();
            if (!$enrolled) {
                abort(403, 'You must be enrolled to take this quiz.');
            }
        }
    }

    private function getEnrollment(Course $course): ?Enrollment
    {
        return Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();
    }
}
