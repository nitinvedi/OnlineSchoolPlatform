<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonManagementController;
use Illuminate\Support\Facades\Route;

// Frontend Improvements Demo Page
Route::get('/demo/improvements', function () {
    return view('improvements-demo');
})->name('improvements.demo');

Route::get('/', function () {
    // Prepare homepage data
    $topCourses = \App\Models\Course::where('status', 'published')
        ->with('instructor', 'category')
        ->orderByDesc('student_count')
        ->take(6)
        ->get();

    // Trusted company logos - configurable list
    $trustedLogos = ['Google', 'Meta', 'Amazon', 'Apple', 'Netflix', 'Stripe', 'Figma'];

    // Testimonials - can be pulled from database if you create a Testimonial model
    $testimonials = [
        [
            'quote'   => 'LiveSchool helped me ship my first SaaS product while learning design, strategy, and mentorship in one program.',
            'name'    => 'Maya Patel',
            'role'    => 'Product Designer',
            'company' => 'Spark Labs',
            'rating'  => 5,
            'initial' => 'M',
        ],
        [
            'quote'   => 'The live sessions and project review loop made every lesson feel immediately useful and applicable.',
            'name'    => 'Damien Li',
            'role'    => 'Growth Lead',
            'company' => 'Nova Growth',
            'rating'  => 4.8,
            'initial' => 'D',
        ],
        [
            'quote'   => 'A premium learning experience with the right balance of direction and creative freedom for every level.',
            'name'    => 'Arielle Moore',
            'role'    => 'Creative Strategist',
            'company' => 'Pulse Studio',
            'rating'  => 4.9,
            'initial' => 'A',
        ],
    ];

    // Instructors - can be pulled from database
    $instructors = \App\Models\User::where('role', 'instructor')
        ->with('courses')
        ->withCount('courses')
        ->orderByDesc('courses_count')
        ->take(3)
        ->get()
        ->map(function($instructor) {
            return [
                'name' => $instructor->name,
                'expertise' => $instructor->bio ?? 'Expert Instructor',
                'courses' => $instructor->courses_count ?? 0,
                'followers' => number_format(rand(5000, 50000)),
                'rating' => number_format(rand(45, 50) / 10, 1),
                'initial' => substr($instructor->name, 0, 1),
            ];
        });

    // FAQ items - can be pulled from database if you create a FAQ model
    $faqItems = [
        ['q' => 'Is there a free trial?',          'a' => 'Yes — every course has free preview lessons. No credit card required to explore.'],
        ['q' => 'What if I want a refund?',        'a' => 'We offer a 30-day no-questions-asked money-back guarantee on all paid enrollments.'],
        ['q' => 'Do I get a certificate?',         'a' => 'Yes. Every completed course earns a verifiable certificate you can share on LinkedIn.'],
        ['q' => 'Can I learn at my own pace?',     'a' => 'Absolutely. All lessons are on-demand. Live sessions are recorded for async access.'],
        ['q' => 'Are live sessions mandatory?',    'a' => 'No — live sessions are optional but highly recommended for feedback and community.'],
    ];

    return view('welcome', compact('topCourses', 'trustedLogos', 'testimonials', 'instructors', 'faqItems'));
});

// Demo pages for pricing and checkout UI
Route::get('/pricing', function () { return view('pricing'); })->name('pricing');
Route::get('/checkout', function () { return view('checkout'); })->name('checkout');
Route::get('/payments/failed', function () { return view('payments.failed'); })->name('payments.failed');
Route::get('/account/billing', function () { return view('account.billing'); })->middleware('auth')->name('account.billing');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/suggestions', [CourseController::class, 'suggestions'])->name('courses.suggestions');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->middleware('auth')->name('courses.enroll');
Route::post('/courses/{course}/wishlist', [CourseController::class, 'toggleWishlist'])->middleware('auth')->name('courses.wishlist.toggle');

// Payment routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/courses/{course}/checkout', [\App\Http\Controllers\PaymentController::class, 'createCheckoutSession'])->name('payments.checkout');
    Route::get('/payments/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payments.success');
});
Route::post('/payments/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payments.webhook');

// Lesson routes (authenticated users only)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [LessonController::class, 'markCompleted'])->name('lessons.complete');

    // Quiz routes (student)
    Route::get('/courses/{course}/lessons/{lesson}/quiz/{quiz}', [\App\Http\Controllers\QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/courses/{course}/lessons/{lesson}/quiz/{quiz}/submit', [\App\Http\Controllers\QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/courses/{course}/lessons/{lesson}/quiz/{quiz}/result/{attempt}', [\App\Http\Controllers\QuizController::class, 'result'])->name('quizzes.result');

    // Assignment routes (student & instructor)
    Route::get('/courses/{course}/assignments', [\App\Http\Controllers\AssignmentController::class, 'index'])->name('courses.assignments.index');
    Route::get('/courses/{course}/assignments/create', [\App\Http\Controllers\AssignmentController::class, 'create'])->name('courses.assignments.create');
    Route::post('/courses/{course}/assignments', [\App\Http\Controllers\AssignmentController::class, 'store'])->name('courses.assignments.store');
    Route::get('/courses/{course}/assignments/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'show'])->name('courses.assignments.show');
    Route::get('/courses/{course}/assignments/{assignment}/edit', [\App\Http\Controllers\AssignmentController::class, 'edit'])->name('courses.assignments.edit');
    Route::put('/courses/{course}/assignments/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'update'])->name('courses.assignments.update');
    Route::delete('/courses/{course}/assignments/{assignment}', [\App\Http\Controllers\AssignmentController::class, 'delete'])->name('courses.assignments.delete');

    // Assignment submission routes
    Route::post('/courses/{course}/assignments/{assignment}/submit', [\App\Http\Controllers\AssignmentSubmissionController::class, 'store'])->name('assignments.submissions.store');
    Route::post('/courses/{course}/assignments/{assignment}/submissions/{submission}/submit', [\App\Http\Controllers\AssignmentSubmissionController::class, 'submit'])->name('assignments.submissions.submit');
    Route::post('/courses/{course}/assignments/{assignment}/submissions/{submission}/grade', [\App\Http\Controllers\AssignmentSubmissionController::class, 'grade'])->name('assignments.submissions.grade');

    // Gradebook routes
    Route::get('/courses/{course}/gradebook', [\App\Http\Controllers\GradebookController::class, 'show'])->name('courses.gradebook.show');
    Route::get('/courses/{course}/gradebook/student', [\App\Http\Controllers\GradebookController::class, 'studentGrades'])->name('courses.gradebook.student');

    // Announcement routes
    Route::get('/courses/{course}/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('courses.announcements.index');
    Route::get('/courses/{course}/announcements/create', [\App\Http\Controllers\AnnouncementController::class, 'create'])->name('courses.announcements.create');
    Route::post('/courses/{course}/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('courses.announcements.store');
    Route::get('/courses/{course}/announcements/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'show'])->name('courses.announcements.show');
    Route::get('/courses/{course}/announcements/{announcement}/edit', [\App\Http\Controllers\AnnouncementController::class, 'edit'])->name('courses.announcements.edit');
    Route::put('/courses/{course}/announcements/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'update'])->name('courses.announcements.update');
    Route::delete('/courses/{course}/announcements/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'delete'])->name('courses.announcements.delete');

    // Discussion routes
    Route::get('/courses/{course}/discussions', [\App\Http\Controllers\DiscussionController::class, 'index'])->name('courses.discussions.index');
    Route::get('/courses/{course}/discussions/create', [\App\Http\Controllers\DiscussionController::class, 'create'])->name('courses.discussions.create');
    Route::post('/courses/{course}/discussions', [\App\Http\Controllers\DiscussionController::class, 'store'])->name('courses.discussions.store');
    Route::get('/courses/{course}/discussions/{discussion}', [\App\Http\Controllers\DiscussionController::class, 'show'])->name('courses.discussions.show');
    Route::get('/courses/{course}/discussions/{discussion}/edit', [\App\Http\Controllers\DiscussionController::class, 'edit'])->name('courses.discussions.edit');
    Route::put('/courses/{course}/discussions/{discussion}', [\App\Http\Controllers\DiscussionController::class, 'update'])->name('courses.discussions.update');
    Route::delete('/courses/{course}/discussions/{discussion}', [\App\Http\Controllers\DiscussionController::class, 'delete'])->name('courses.discussions.delete');
    Route::post('/courses/{course}/discussions/{discussion}/pin', [\App\Http\Controllers\DiscussionController::class, 'pin'])->name('courses.discussions.pin');
    Route::post('/courses/{course}/discussions/{discussion}/close', [\App\Http\Controllers\DiscussionController::class, 'close'])->name('courses.discussions.close');

    // Discussion replies
    Route::post('/courses/{course}/discussions/{discussion}/replies', [\App\Http\Controllers\DiscussionReplyController::class, 'store'])->name('discussions.replies.store');
    Route::put('/courses/{course}/discussions/{discussion}/replies/{reply}', [\App\Http\Controllers\DiscussionReplyController::class, 'update'])->name('discussions.replies.update');
    Route::delete('/courses/{course}/discussions/{discussion}/replies/{reply}', [\App\Http\Controllers\DiscussionReplyController::class, 'delete'])->name('discussions.replies.delete');
    Route::post('/courses/{course}/discussions/{discussion}/replies/{reply}/like', [\App\Http\Controllers\DiscussionReplyController::class, 'like'])->name('discussions.replies.like');
    Route::post('/courses/{course}/discussions/{discussion}/replies/{reply}/unlike', [\App\Http\Controllers\DiscussionReplyController::class, 'unlike'])->name('discussions.replies.unlike');
    Route::post('/courses/{course}/discussions/{discussion}/replies/{reply}/approve', [\App\Http\Controllers\DiscussionReplyController::class, 'approve'])->name('discussions.replies.approve');
    Route::post('/courses/{course}/discussions/{discussion}/replies/{reply}/hide', [\App\Http\Controllers\DiscussionReplyController::class, 'hide'])->name('discussions.replies.hide');

    // Review submission routes
    Route::get('/courses/{course}/review', [\App\Http\Controllers\ReviewSubmissionController::class, 'create'])->name('reviews.create');
    Route::post('/courses/{course}/review', [\App\Http\Controllers\ReviewSubmissionController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewSubmissionController::class, 'delete'])->name('reviews.delete');

    // Attendance routes
    Route::get('/live-sessions/{liveSession}/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('live-sessions.attendance.index');
    Route::post('/live-sessions/{liveSession}/attendance', [\App\Http\Controllers\AttendanceController::class, 'record'])->name('live-sessions.attendance.record');
    Route::post('/live-sessions/{liveSession}/attendance/bulk', [\App\Http\Controllers\AttendanceController::class, 'recordBulk'])->name('live-sessions.attendance.bulk');
    Route::get('/live-sessions/{liveSession}/participation', [\App\Http\Controllers\AttendanceController::class, 'participation'])->name('live-sessions.participation.index');
    Route::post('/live-sessions/{liveSession}/participation', [\App\Http\Controllers\AttendanceController::class, 'recordParticipation'])->name('live-sessions.participation.record');

    // Certificate routes
    Route::get('/certificates/{certificate}', [\App\Http\Controllers\CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [\App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');
});

// Authenticated general routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Live sessions — student / general access
    Route::get('/live-sessions', [\App\Http\Controllers\LiveSessionController::class, 'index'])->name('live-sessions.index');
    Route::get('/live-sessions/{liveSession}', [\App\Http\Controllers\LiveSessionController::class, 'show'])->name('live-sessions.show');
});

// Instructor management routes
Route::middleware(['auth', 'verified'])
    ->prefix('instructor')
    ->name('instructor.')
    ->group(function () {
        // Course CRUD
        Route::resource('courses', CourseManagementController::class)->except(['show']);

        // Lesson CRUD — nested under a course
        Route::prefix('courses/{course}')->group(function () {
            Route::resource('lessons', LessonManagementController::class)
                ->except(['index', 'show'])
                ->names([
                    'create'  => 'lessons.create',
                    'store'   => 'lessons.store',
                    'edit'    => 'lessons.edit',
                    'update'  => 'lessons.update',
                    'destroy' => 'lessons.destroy',
                ]);

            // Quiz builder routes (instructor)
            Route::get('lessons/{lesson}/quiz', [\App\Http\Controllers\InstructorQuizController::class, 'edit'])->name('quizzes.edit');
            Route::post('lessons/{lesson}/quiz', [\App\Http\Controllers\InstructorQuizController::class, 'store'])->name('quizzes.store');
            Route::delete('lessons/{lesson}/quiz', [\App\Http\Controllers\InstructorQuizController::class, 'destroy'])->name('quizzes.destroy');

            // Review management routes (instructor)
            Route::get('reviews', [\App\Http\Controllers\ReviewSubmissionController::class, 'index'])->name('reviews.index');
            Route::post('reviews/{review}/approve', [\App\Http\Controllers\ReviewSubmissionController::class, 'approve'])->name('reviews.approve');
            Route::post('reviews/{review}/reject', [\App\Http\Controllers\ReviewSubmissionController::class, 'reject'])->name('reviews.reject');
        });

        // Live Sessions Management
        Route::resource('live-sessions', \App\Http\Controllers\LiveSessionController::class)->except(['index', 'show']);
        Route::post('live-sessions/{liveSession}/start', [\App\Http\Controllers\LiveSessionController::class, 'start'])->name('live-sessions.start');
        Route::post('live-sessions/{liveSession}/end', [\App\Http\Controllers\LiveSessionController::class, 'end'])->name('live-sessions.end');
        Route::get('live-sessions/{liveSession}/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('live-sessions.attendance.index');
        Route::post('live-sessions/{liveSession}/attendance', [\App\Http\Controllers\AttendanceController::class, 'record'])->name('live-sessions.attendance.record');
        Route::post('live-sessions/{liveSession}/attendance/bulk', [\App\Http\Controllers\AttendanceController::class, 'recordBulk'])->name('live-sessions.attendance.bulk');
        Route::get('live-sessions/{liveSession}/participation', [\App\Http\Controllers\AttendanceController::class, 'participation'])->name('live-sessions.participation.index');
        Route::post('live-sessions/{liveSession}/participation', [\App\Http\Controllers\AttendanceController::class, 'recordParticipation'])->name('live-sessions.participation.record');
    });

require __DIR__.'/auth.php';

// Admin routes
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'store', 'edit']);
        Route::post('users/{user}/update-role', [App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update-role');
        Route::post('users/{user}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('users.suspend');
        Route::post('users/{user}/unsuspend', [App\Http\Controllers\Admin\UserController::class, 'unsuspend'])->name('users.unsuspend');
        Route::post('users/bulk-action', [App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::get('users-export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');

        Route::resource('courses', App\Http\Controllers\Admin\CourseController::class)->except(['create', 'store', 'edit']);
        Route::post('courses/{course}/approve', [App\Http\Controllers\Admin\CourseController::class, 'approve'])->name('courses.approve');
        Route::post('courses/{course}/reject', [App\Http\Controllers\Admin\CourseController::class, 'reject'])->name('courses.reject');
        Route::post('courses/{course}/unpublish', [App\Http\Controllers\Admin\CourseController::class, 'unpublish'])->name('courses.unpublish');
        Route::post('courses/{course}/feature', [App\Http\Controllers\Admin\CourseController::class, 'feature'])->name('courses.feature');

        Route::get('revenue', [App\Http\Controllers\Admin\RevenueController::class, 'index'])->name('revenue.index');
        Route::post('revenue/payouts/{payout}/approve', [App\Http\Controllers\Admin\RevenueController::class, 'approvePayout'])->name('revenue.approve-payout');
        Route::post('revenue/payouts/{payout}/reject', [App\Http\Controllers\Admin\RevenueController::class, 'rejectPayout'])->name('revenue.reject-payout');

        Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class)->only(['index']);
        Route::post('reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
        Route::delete('reviews/{review}/remove', [App\Http\Controllers\Admin\ReviewController::class, 'remove'])->name('reviews.remove');
        Route::post('reviews/{review}/warn-user', [App\Http\Controllers\Admin\ReviewController::class, 'warnUser'])->name('reviews.warn-user');

        Route::resource('reports', App\Http\Controllers\Admin\ReportController::class)->only(['index']);
        Route::post('reports/{report}/dismiss', [App\Http\Controllers\Admin\ReportController::class, 'dismiss'])->name('reports.dismiss');
        Route::post('reports/{report}/remove-content', [App\Http\Controllers\Admin\ReportController::class, 'removeContent'])->name('reports.remove-content');
        Route::post('reports/{report}/warn-user', [App\Http\Controllers\Admin\ReportController::class, 'warnUser'])->name('reports.warn-user');
        Route::post('reports/{report}/ban-user', [App\Http\Controllers\Admin\ReportController::class, 'banUser'])->name('reports.ban-user');

        Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/toggle-maintenance', [App\Http\Controllers\Admin\SettingController::class, 'toggleMaintenance'])->name('settings.toggle-maintenance');
        Route::post('settings/coupons', [App\Http\Controllers\Admin\SettingController::class, 'createCoupon'])->name('settings.create-coupon');
        Route::delete('settings/coupons/{coupon}', [App\Http\Controllers\Admin\SettingController::class, 'expireCoupon'])->name('settings.expire-coupon');
    });

