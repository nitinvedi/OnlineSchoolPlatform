<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\Announcement;
use App\Models\Course;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\ReviewSubmission;
use App\Policies\AssignmentPolicy;
use App\Policies\AnnouncementPolicy;
use App\Policies\CoursePolicy;
use App\Policies\DiscussionPolicy;
use App\Policies\DiscussionReplyPolicy;
use App\Policies\ReviewSubmissionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(Assignment::class, AssignmentPolicy::class);
        Gate::policy(Announcement::class, AnnouncementPolicy::class);
        Gate::policy(Discussion::class, DiscussionPolicy::class);
        Gate::policy(DiscussionReply::class, DiscussionReplyPolicy::class);
        Gate::policy(ReviewSubmission::class, ReviewSubmissionPolicy::class);
    }
}
