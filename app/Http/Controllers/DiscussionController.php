<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscussionController extends Controller
{
    public function index(Course $course): View
    {
        $discussions = $course->discussions()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('last_reply_at', 'desc')
            ->paginate(15);

        return view('discussions.index', compact('course', 'discussions'));
    }

    public function show(Course $course, Discussion $discussion): View
    {
        $this->authorize('view', $discussion);

        $discussion->incrementViewCount();

        $replies = $discussion->replies()
            ->whereNull('parent_reply_id')
            ->with('user', 'childReplies.user')
            ->paginate(20);

        return view('discussions.show', compact('course', 'discussion', 'replies'));
    }

    public function create(Course $course): View
    {
        $this->authorize('create', Discussion::class);

        return view('discussions.create', compact('course'));
    }

    public function store(Course $course, Request $request)
    {
        $this->authorize('create', Discussion::class);

        // Verify user is enrolled in course
        if (!$course->enrollments()->where('user_id', auth()->id())->exists() && !auth()->user()->isInstructor()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $discussion = $course->discussions()->create(
            array_merge($validated, ['user_id' => auth()->id()])
        );

        return redirect()->route('courses.discussions.show', [$course, $discussion])
            ->with('success', 'Discussion created successfully.');
    }

    public function edit(Course $course, Discussion $discussion): View
    {
        $this->authorize('update', $discussion);

        return view('discussions.edit', compact('course', 'discussion'));
    }

    public function update(Course $course, Discussion $discussion, Request $request)
    {
        $this->authorize('update', $discussion);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $discussion->update($validated);

        return redirect()->route('courses.discussions.show', [$course, $discussion])
            ->with('success', 'Discussion updated successfully.');
    }

    public function delete(Course $course, Discussion $discussion)
    {
        $this->authorize('delete', $discussion);

        $discussion->delete();

        return redirect()->route('courses.discussions.index', $course)
            ->with('success', 'Discussion deleted successfully.');
    }

    public function pin(Course $course, Discussion $discussion)
    {
        $this->authorize('pin', $discussion);

        $discussion->update(['is_pinned' => !$discussion->is_pinned]);

        return back()->with('success', 'Discussion pinned status updated.');
    }

    public function close(Course $course, Discussion $discussion)
    {
        $this->authorize('close', $discussion);

        $discussion->close();

        return back()->with('success', 'Discussion closed.');
    }
}
