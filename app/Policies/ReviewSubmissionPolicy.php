<?php

namespace App\Policies;

use App\Models\ReviewSubmission;
use App\Models\User;

class ReviewSubmissionPolicy
{
    public function create(User $user): bool
    {
        return $user->isStudent();
    }

    public function view(User $user, ReviewSubmission $review): bool
    {
        if ($user->isStudent() && $user->id === $review->user_id) {
            return true;
        }

        if ($user->isInstructor() && $review->course->instructor_id === $user->id) {
            return true;
        }

        return $user->isAdmin();
    }

    public function update(User $user, ReviewSubmission $review): bool
    {
        // Only allow updates before submission is reviewed
        if ($review->isPending() && $user->isStudent() && $user->id === $review->user_id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, ReviewSubmission $review): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isStudent() && $user->id === $review->user_id && $review->isPending();
    }

    public function approve(User $user, ReviewSubmission $review): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $review->course->instructor_id === $user->id;
    }

    public function reject(User $user, ReviewSubmission $review): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isInstructor() && $review->course->instructor_id === $user->id;
    }
}
