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

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->middleware('auth')->name('courses.enroll');

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

