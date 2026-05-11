<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentSubmissionController extends Controller
{
    public function store(Course $course, Assignment $assignment, Request $request)
    {
        $this->authorize('submitAssignment', $assignment);

        $validated = $request->validate([
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        $submission = $assignment->submissions()
            ->firstOrCreate(['user_id' => auth()->id()]);

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store("submissions/assignment-{$assignment->id}", 'public');
            $submission->file_path = $filePath;
        }

        $submission->content = $validated['content'] ?? $submission->content;
        $submission->save();

        return redirect()->route('courses.assignments.show', [$course, $assignment])
            ->with('success', 'Submission saved as draft.');
    }

    public function submit(Course $course, Assignment $assignment)
    {
        $this->authorize('submitAssignment', $assignment);

        $submission = $assignment->submissions()
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $submission->submit();

        return redirect()->route('courses.assignments.show', [$course, $assignment])
            ->with('success', 'Assignment submitted successfully.');
    }

    public function grade(Course $course, Assignment $assignment, AssignmentSubmission $submission, Request $request)
    {
        $this->authorize('grade', $assignment);

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:' . $assignment->points,
            'feedback' => 'nullable|string',
        ]);

        Grade::updateOrCreate(
            ['assignment_submission_id' => $submission->id],
            array_merge($validated, ['graded_by' => auth()->id()])
        );

        $submission->update([
            'status' => 'graded',
            'graded_at' => now(),
        ]);

        return redirect()->route('courses.assignments.show', [$course, $assignment])
            ->with('success', 'Assignment graded successfully.');
    }
}
