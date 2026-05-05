<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorQuizController extends Controller
{
    /**
     * Show form to create or manage the quiz for a lesson.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        $this->authorizeInstructor($course);

        $quiz = Quiz::with('questions.options')
            ->where('lesson_id', $lesson->id)
            ->first();

        return view('instructor.quizzes.edit', compact('course', 'lesson', 'quiz'));
    }

    /**
     * Create (or replace) the quiz for a lesson.
     */
    public function store(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorizeInstructor($course);

        $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'pass_percentage' => ['required', 'integer', 'min:1', 'max:100'],
            'max_attempts'    => ['required', 'integer', 'min:0'],
            'questions'       => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.points'   => ['required', 'integer', 'min:1'],
            'questions.*.options'  => ['required', 'array', 'min:2'],
            'questions.*.options.*.text'       => ['required', 'string'],
            'questions.*.options.*.is_correct' => ['nullable'],
            'questions.*.correct' => ['required'],
        ]);

        DB::transaction(function () use ($request, $lesson) {
            // Delete existing quiz if any
            Quiz::where('lesson_id', $lesson->id)->delete();

            $quiz = Quiz::create([
                'lesson_id'       => $lesson->id,
                'title'           => $request->title,
                'description'     => $request->description,
                'pass_percentage' => $request->pass_percentage,
                'max_attempts'    => $request->max_attempts,
            ]);

            foreach ($request->questions as $qIndex => $qData) {
                $question = QuizQuestion::create([
                    'quiz_id'  => $quiz->id,
                    'question' => $qData['question'],
                    'order'    => $qIndex,
                    'points'   => $qData['points'] ?? 1,
                ]);

                $correctIndex = (int) $qData['correct'];
                foreach ($qData['options'] as $oIndex => $oData) {
                    QuizOption::create([
                        'quiz_question_id' => $question->id,
                        'option_text'      => $oData['text'],
                        'is_correct'       => ($oIndex === $correctIndex),
                        'order'            => $oIndex,
                    ]);
                }
            }
        });

        return redirect()
            ->route('instructor.quizzes.edit', [$course, $lesson])
            ->with('success', 'Quiz saved successfully!');
    }

    /**
     * Delete the quiz for a lesson.
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        $this->authorizeInstructor($course);

        Quiz::where('lesson_id', $lesson->id)->delete();

        return redirect()
            ->route('instructor.lessons.edit', [$course, $lesson])
            ->with('success', 'Quiz deleted.');
    }

    private function authorizeInstructor(Course $course): void
    {
        if ($course->instructor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}
