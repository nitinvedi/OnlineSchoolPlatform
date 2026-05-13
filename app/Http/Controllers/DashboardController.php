<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Certificate;
use App\Models\LiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            return $this->studentDashboard();
        } elseif ($user->isInstructor()) {
            return $this->instructorDashboard();
        } elseif ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return redirect('/');
    }

    private function studentDashboard()
    {
        $userId = auth()->id();
        
        // Basic enrollments for pagination
        $enrollments = Enrollment::where('user_id', $userId)
            ->with(['course.instructor', 'course.category', 'course.lessons'])
            ->orderByDesc('updated_at')
            ->paginate(6);

        // Filter enrollments by status
        $enrollmentsInProgress = Enrollment::where('user_id', $userId)
            ->whereNull('completed_at')
            ->with(['course.instructor', 'course.category'])
            ->get();
            
        $enrollmentsCompleted = Enrollment::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->with(['course.instructor', 'course.category'])
            ->get();

        // Calculate stats
        $stats = [
            'enrolledCourses' => $enrollments->total(),
            'completedCourses' => $enrollmentsCompleted->count(),
            'learningHours' => $this->calculateLearningHours($userId),
            'streak' => $this->calculateStreak($userId),
        ];

        // Jump back in (continue learning)
        $jumpBackIn = Enrollment::where('user_id', $userId)
            ->with(['course.lessons' => function($q) { $q->orderBy('order'); }, 'completedLessons'])
            ->whereNull('completed_at')
            ->orderByDesc('updated_at')
            ->first();

        // Recommended courses
        $enrolledCategoryIds = Enrollment::where('user_id', $userId)
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->pluck('courses.category_id')
            ->unique();
            
        $enrolledCourseIds = Enrollment::where('user_id', $userId)->pluck('course_id');

        $recommendations = Course::whereIn('category_id', $enrolledCategoryIds)
            ->whereNotIn('id', $enrolledCourseIds)
            ->where('status', 'published')
            ->with('instructor', 'category')
            ->inRandomOrder()
            ->take(3)
            ->get();

        if ($recommendations->isEmpty()) {
            $recommendations = Course::whereNotIn('id', $enrolledCourseIds)
                ->where('status', 'published')
                ->with('instructor', 'category')
                ->orderByDesc('student_count')
                ->take(3)
                ->get();
        }

        // Upcoming deadlines (quizzes and live sessions)
        $enrolledCourseIds = Enrollment::where('user_id', $userId)->pluck('course_id');
        
        $upcomingQuizzes = Quiz::whereHas('lesson.course', function($q) use ($enrolledCourseIds) {
            $q->whereIn('id', $enrolledCourseIds);
        })
        ->with(['lesson.course'])
        ->get();

        $upcomingLiveSessions = LiveSession::whereIn('course_id', $enrolledCourseIds)
            ->with('course')
            ->whereIn('status', ['scheduled', 'live'])
            ->orderBy('scheduled_at')
            ->take(3)
            ->get();

        $upcomingDeadlines = collect()
            ->merge($upcomingLiveSessions->map(fn($s) => [
                'type' => 'live_session',
                'title' => $s->topic ?? 'Live Session',
                'course' => $s->course->title,
                'date' => $s->scheduled_at,
                'status' => $s->status,
                'id' => $s->id,
            ]))
            ->sortBy('date')
            ->take(5);

        // Certificates
        $certificates = Certificate::where('user_id', $userId)
            ->with('course')
            ->orderByDesc('issued_at')
            ->take(3)
            ->get();

        // Weekly activity chart
        $weeklyActivity = $this->getWeeklyActivityData($userId);

        // Badges
        $badges = auth()->user()->userBadges()
            ->with('badge')
            ->orderByDesc('earned_at')
            ->take(6)
            ->get();

        return view('dashboard.student', compact(
            'enrollments',
            'enrollmentsInProgress',
            'enrollmentsCompleted',
            'stats',
            'jumpBackIn',
            'recommendations',
            'upcomingDeadlines',
            'certificates',
            'weeklyActivity',
            'badges'
        ));
    }

    /**
     * Calculate total learning hours from lesson durations
     */
    private function calculateLearningHours($userId)
    {
        return Enrollment::where('user_id', $userId)
            ->with(['completedLessons' => function($q) {
                $q->select('lessons.id', 'lessons.duration_minutes');
            }])
            ->get()
            ->flatMap(fn($e) => $e->completedLessons)
            ->sum('duration_minutes') / 60;
    }

    /**
     * Calculate current learning streak (consecutive days with activity)
     */
    private function calculateStreak($userId)
    {
        $streak = 0;
        $currentDate = now()->startOfDay();
        
        while (true) {
            $hasActivity = Enrollment::where('user_id', $userId)
                ->whereDate('updated_at', $currentDate)
                ->exists();
                
            if (!$hasActivity) {
                break;
            }
            
            $streak++;
            $currentDate->subDay();
        }
        
        return $streak;
    }

    /**
     * Get weekly activity data (lessons completed per day for the last 7 days)
     */
    private function getWeeklyActivityData($userId)
    {
        $days = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $dayName = $date->format('D');
            
            $completedCount = DB::table('enrollment_lesson')
                ->join('enrollments', 'enrollment_lesson.enrollment_id', '=', 'enrollments.id')
                ->where('enrollments.user_id', $userId)
                ->whereDate('enrollment_lesson.created_at', $date)
                ->count();
            
            $days[] = [
                'day' => $dayName,
                'date' => $date->format('M d'),
                'lessons' => $completedCount,
            ];
        }
        
        return $days;
    }

    private function instructorDashboard()
    {
        $userId = auth()->id();
        $courses = Course::where('instructor_id', $userId)->with('category')->paginate(6);
        $totalStudents = Course::where('instructor_id', $userId)->sum('student_count');
        
        // Calculate real revenue from enrollments by this instructor
        $totalRevenue = \App\Models\Enrollment::whereHas('course', function($q) use ($userId) {
            $q->where('instructor_id', $userId);
        })->sum('amount_paid') ?? 0;

        $stats = [
            'courses' => Course::where('instructor_id', $userId)->count(),
            'totalStudents' => $totalStudents,
            'publishedCourses' => Course::where('instructor_id', $userId)->where('status', 'published')->count(),
            'totalRevenue' => $totalRevenue,
        ];

        $topCourse = Course::where('instructor_id', $userId)
            ->where('status', 'published')
            ->orderByDesc('student_count')
            ->first();

        $drafts = Course::where('instructor_id', $userId)
            ->where('status', 'draft')
            ->latest()
            ->take(3)
            ->get();

        $reviews = \App\Models\CourseReview::whereHas('course', function($q) use ($userId) {
                $q->where('instructor_id', $userId);
            })
            ->with(['course', 'user'])
            ->latest()
            ->take(4)
            ->get();

        // Calculate pending work (draft courses + unapproved reviews)
        $draftCount = Course::where('instructor_id', $userId)
            ->where('status', 'draft')
            ->count();
        $unapprovedReviews = \App\Models\CourseReview::whereHas('course', function($q) use ($userId) {
            $q->where('instructor_id', $userId);
        })
        ->where('is_approved', false)
        ->count();
        $pendingWork = $draftCount + $unapprovedReviews;

        // Build real revenue chart data from last 6 months
        $chartLabels = [];
        $chartData = [];
        $now = now();
        
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $chartLabels[] = $month->format('M');
            
            $monthRevenue = \App\Models\Enrollment::whereHas('course', function($q) use ($userId) {
                $q->where('instructor_id', $userId);
            })
            ->whereYear('created_at', $month->year)
            ->whereMonth('created_at', $month->month)
            ->sum('amount_paid') ?? 0;
            
            $chartData[] = $monthRevenue;
        }

        return view('dashboard.instructor', compact('courses', 'stats', 'topCourse', 'drafts', 'reviews', 'chartLabels', 'chartData', 'pendingWork'));
    }

    private function adminDashboard()
    {
        $now = now();
        $lastMonth = $now->copy()->subMonth();
        
        // Current stats
        $totalUsers = \App\Models\User::count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $totalRevenue = \App\Models\Payment::where('status', 'completed')->sum('amount') ?? 0;
        
        // Last month stats for MoM calculations
        $lastMonthUsers = \App\Models\User::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)->count();
        $lastMonthCourses = Course::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)->count();
        $lastMonthEnrollments = Enrollment::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)->count();
        $lastMonthRevenue = \App\Models\Payment::where('status', 'completed')
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->sum('amount') ?? 0;
        
        // Calculate MoM percentages
        $userMoM = $lastMonthUsers > 0 ? (($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100 : 0;
        $courseMoM = $lastMonthCourses > 0 ? (($totalCourses - $lastMonthCourses) / $lastMonthCourses) * 100 : 0;
        $enrollmentMoM = $lastMonthEnrollments > 0 ? (($totalEnrollments - $lastMonthEnrollments) / $lastMonthEnrollments) * 100 : 0;
        $revenueMoM = $lastMonthRevenue > 0 ? (($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
        
        $stats = [
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'totalEnrollments' => $totalEnrollments,
            'publishedCourses' => $publishedCourses,
            'totalRevenue' => $totalRevenue,
        ];

        // MoM changes (real data)
        $mom = [
            'users' => ($userMoM >= 0 ? '+' : '') . number_format($userMoM, 1) . '%',
            'revenue' => ($revenueMoM >= 0 ? '+' : '') . number_format($revenueMoM, 1) . '%',
            'courses' => ($courseMoM >= 0 ? '+' : '') . number_format($courseMoM, 1) . '%',
            'enrollments' => ($enrollmentMoM >= 0 ? '+' : '') . number_format($enrollmentMoM, 1) . '%'
        ];

        $recentCourses = Course::latest()->take(5)->with('instructor')->get();
        
        $categories = \App\Models\Category::withCount('courses')->orderByDesc('courses_count')->take(4)->get();

        $studentsCount = \App\Models\User::where('role', 'student')->count();
        $instructorsCount = \App\Models\User::where('role', 'instructor')->count();
        
        $demographics = [
            'students' => $studentsCount,
            'instructors' => $instructorsCount,
            'admins' => \App\Models\User::where('role', 'admin')->count()
        ];

        // Build real revenue chart data from last 6 months
        $chartLabels = [];
        $chartData = [];
        $monthNow = now();
        
        for ($i = 5; $i >= 0; $i--) {
            $month = $monthNow->copy()->subMonths($i);
            $chartLabels[] = $month->format('M');
            
            $monthRevenue = \App\Models\Payment::where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount') ?? 0;
            
            $chartData[] = $monthRevenue;
        }
        
        // Real pending approvals (instructors awaiting verification)
        $pendingApprovals = \App\Models\User::where('role', 'instructor')
            ->whereNull('verified_at')
            ->latest()->take(3)->get();

        // Real recent enrollments
        $recentActivity = Enrollment::with(['user', 'course'])->latest()->take(8)->get();

        return view('dashboard.admin', compact('stats', 'mom', 'recentCourses', 'categories', 'demographics', 'chartLabels', 'chartData', 'pendingApprovals', 'recentActivity'));
    }
}
