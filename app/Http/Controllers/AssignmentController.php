<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Course $course): View
    {
        $this->authorize('view', $course);

        $assignments = $course->assignments()
            ->when(auth()->user()->isStudent(), function ($query) {
                return $query->where('status', 'published');
            })
            ->orderBy('order')
            ->paginate(12);

        return view('assignments.index', compact('course', 'assignments'));
    }

    public function show(Course $course, Assignment $assignment): View
    {
        $this->authorize('view', $assignment);

        $submission = null;
        if (auth()->user()->isStudent()) {
            $submission = $assignment->submissions()
                ->where('user_id', auth()->id())
                ->first();
        }

        $submissions = [];
        if (auth()->user()->isInstructor() || auth()->user()->isAdmin()) {
            $submissions = $assignment->submissions()
                ->with('user', 'grade')
                ->paginate(15);
        }

        return view('assignments.show', compact('course', 'assignment', 'submission', 'submissions'));
    }

    public function create(Course $course): View
    {
        $this->authorize('create', Assignment::class);
        $this->authorize('update', $course);

        return view('assignments.create', compact('course'));
    }

    public function store(Course $course, Request $request)
    {
        $this->authorize('create', Assignment::class);
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points' => 'required|integer|min:1|max:1000',
            'due_date' => 'nullable|date|after:today',
            'status' => 'in:draft,published',
        ]);

        $assignment = $course->assignments()->create(array_merge(
            $validated,
            ['order' => $course->assignments()->max('order') + 1]
        ));

        return redirect()->route('courses.assignments.show', [$course, $assignment])
            ->with('success', 'Assignment created successfully.');
    }

    public function edit(Course $course, Assignment $assignment): View
    {
        $this->authorize('update', $assignment);

        return view('assignments.edit', compact('course', 'assignment'));
    }

    public function update(Course $course, Assignment $assignment, Request $request)
    {
        $this->authorize('update', $assignment);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'points' => 'required|integer|min:1|max:1000',
            'due_date' => 'nullable|date',
            'status' => 'in:draft,published,archived',
        ]);

        $assignment->update($validated);

        return redirect()->route('courses.assignments.show', [$course, $assignment])
            ->with('success', 'Assignment updated successfully.');
    }

    public function delete(Course $course, Assignment $assignment)
    {
        $this->authorize('delete', $assignment);

        $assignment->delete();

        return redirect()->route('courses.assignments.index', $course)
            ->with('success', 'Assignment deleted successfully.');
    }
}
