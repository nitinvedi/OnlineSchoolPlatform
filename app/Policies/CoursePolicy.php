<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Any authenticated user can view the course list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Only instructors and admins can create courses.
     */
    public function create(User $user): bool
    {
        return $user->isInstructor() || $user->isAdmin();
    }

    /**
     * Only the owning instructor or an admin can update a course.
     */
    public function update(User $user, Course $course): bool
    {
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }

    /**
     * Only the owning instructor or an admin can delete a course.
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->isAdmin() || $course->instructor_id === $user->id;
    }
}
