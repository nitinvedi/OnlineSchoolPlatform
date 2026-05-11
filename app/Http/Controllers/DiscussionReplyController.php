<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use Illuminate\Http\Request;

class DiscussionReplyController extends Controller
{
    public function store(Course $course, Discussion $discussion, Request $request)
    {
        $this->authorize('addReply', $discussion);

        $validated = $request->validate([
            'content' => 'required|string',
            'parent_reply_id' => 'nullable|exists:discussion_replies,id',
        ]);

        $discussion->addReply(
            auth()->user(),
            $validated['content'],
            $validated['parent_reply_id'] ?? null
        );

        return back()->with('success', 'Reply posted successfully.');
    }

    public function update(Course $course, Discussion $discussion, DiscussionReply $reply, Request $request)
    {
        $this->authorize('update', $reply);

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $reply->update($validated);

        return back()->with('success', 'Reply updated successfully.');
    }

    public function delete(Course $course, Discussion $discussion, DiscussionReply $reply)
    {
        $this->authorize('delete', $reply);

        $discussion->decrement('reply_count');
        $reply->delete();

        return back()->with('success', 'Reply deleted successfully.');
    }

    public function like(Course $course, Discussion $discussion, DiscussionReply $reply)
    {
        $reply->like();

        return back();
    }

    public function unlike(Course $course, Discussion $discussion, DiscussionReply $reply)
    {
        $reply->unlike();

        return back();
    }

    public function approve(Course $course, Discussion $discussion, DiscussionReply $reply)
    {
        $this->authorize('approve', $reply);

        $reply->approve();

        return back()->with('success', 'Reply approved.');
    }

    public function hide(Course $course, Discussion $discussion, DiscussionReply $reply)
    {
        $this->authorize('hide', $reply);

        $reply->hide();

        return back()->with('success', 'Reply hidden.');
    }
}
