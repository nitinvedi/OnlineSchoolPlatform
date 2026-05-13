<?php

namespace App\Policies;

use App\Models\Discussion;
use App\Models\User;

class DiscussionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Discussion $discussion): bool
    {
        // Must be enrolled in the course
        if (!$discussion->course->enrollments()->where('user_id', $user->id)->exists() && !$user->isInstructor() && !$user->isAdmin()) {
            return false;
        }

        // Show open discussions to all enrolled users
        if ($discussion->isOpen()) {
            return true;
        }

        // Show closed/archived only to instructor and admin
        if ($user->isInstructor() && $discussion->course->instructor_id === $user->id) {
            return true;
        }

        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isStudent() || $user->isInstructor() || $user->isAdmin();
    }

    public function update(User $user, Discussion $discussion): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $discussion->user_id;
    }

    public function delete(User $user, Discussion $discussion): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isInstructor() && $discussion->course->instructor_id === $user->id) {
            return true;
        }

        return $user->id === $discussion->user_id;
    }

    public function pin(User $user, Discussion $discussion): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $discussion->course->instructor_id === $user->id;
    }

    public function close(User $user, Discussion $discussion): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $discussion->course->instructor_id === $user->id;
    }

    public function addReply(User $user, Discussion $discussion): bool
    {
        return $discussion->isOpen() && (
            $discussion->course->enrollments()->where('user_id', $user->id)->exists() ||
            $user->isInstructor() ||
            $user->isAdmin()
        );
    }
}
