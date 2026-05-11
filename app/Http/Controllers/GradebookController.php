<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Gradebook;
use Illuminate\View\View;

class GradebookController extends Controller
{
    public function show(Course $course): View
    {
        // Only students and instructors can view the gradebook
        if (auth()->user()->isStudent()) {
            $gradebook = $course->gradebooks()
                ->where('user_id', auth()->id())
                ->firstOrFail();

            return view('gradebook.show', compact('course', 'gradebook'));
        }

        $this->authorize('update', $course);

        $gradebooks = $course->gradebooks()
            ->with('user')
            ->orderBy('final_grade', 'desc')
            ->paginate(20);

        return view('gradebook.index', compact('course', 'gradebooks'));
    }

    public function studentGrades(Course $course): View
    {
        $this->authorize('update', $course);

        $student = auth()->user();
        $gradebook = $course->gradebooks()
            ->where('user_id', $student->id)
            ->firstOrFail();

        $assignments = $course->assignments()
            ->with(['submissions' => function ($query) use ($student) {
                $query->where('user_id', $student->id)->with('grade');
            }])
            ->paginate(10);

        return view('gradebook.student', compact('course', 'gradebook', 'assignments'));
    }
}
