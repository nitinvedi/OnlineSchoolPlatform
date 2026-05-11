<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Announcement $announcement): bool
    {
        // Only show published announcements to students/anyone
        if ($announcement->isPublished()) {
            return $user->isStudent() && $announcement->course->enrollments()->where('user_id', $user->id)->exists();
        }

        // Show draft/archived to instructor and admin
        if ($user->isInstructor() && $announcement->course->instructor_id === $user->id) {
            return true;
        }

        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isInstructor() || $user->isAdmin();
    }

    public function update(User $user, Announcement $announcement): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $announcement->course->instructor_id === $user->id && $announcement->user_id === $user->id;
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $announcement->course->instructor_id === $user->id && $announcement->user_id === $user->id;
    }
}
