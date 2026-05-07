<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
        });

        // Live Sessions Management
        Route::resource('live-sessions', \App\Http\Controllers\LiveSessionController::class)->except(['index', 'show']);
        Route::post('live-sessions/{liveSession}/start', [\App\Http\Controllers\LiveSessionController::class, 'start'])->name('live-sessions.start');
        Route::post('live-sessions/{liveSession}/end', [\App\Http\Controllers\LiveSessionController::class, 'end'])->name('live-sessions.end');
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

