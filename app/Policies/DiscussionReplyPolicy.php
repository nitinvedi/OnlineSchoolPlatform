<?php

namespace App\Policies;

use App\Models\DiscussionReply;
use App\Models\User;

class DiscussionReplyPolicy
{
    public function update(User $user, DiscussionReply $reply): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $reply->user_id;
    }

    public function delete(User $user, DiscussionReply $reply): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isInstructor() && $reply->discussion->course->instructor_id === $user->id) {
            return true;
        }

        return $user->id === $reply->user_id;
    }

    public function approve(User $user, DiscussionReply $reply): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $reply->discussion->course->instructor_id === $user->id;
    }

    public function hide(User $user, DiscussionReply $reply): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $reply->discussion->course->instructor_id === $user->id;
    }
}
