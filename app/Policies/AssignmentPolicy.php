<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Assignment $assignment): bool
    {
        // Instructors can view their own course's assignments
        // Students can view assignments in their enrolled courses
        if ($user->isInstructor() && $assignment->course->instructor_id === $user->id) {
            return true;
        }

        if ($user->isStudent() && $assignment->course->enrollments()->where('user_id', $user->id)->exists()) {
            return true;
        }

        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isInstructor() || $user->isAdmin();
    }

    public function update(User $user, Assignment $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $assignment->course->instructor_id === $user->id;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $assignment->course->instructor_id === $user->id;
    }

    public function grade(User $user, Assignment $assignment): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $assignment->course->instructor_id === $user->id;
    }

    public function submitAssignment(User $user, Assignment $assignment): bool
    {
        return $user->isStudent() && $assignment->course->enrollments()->where('user_id', $user->id)->exists();
    }
}
