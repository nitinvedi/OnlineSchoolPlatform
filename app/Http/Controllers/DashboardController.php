<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

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
        $enrollments = Enrollment::where('user_id', $userId)
            ->with(['course.instructor', 'course.category'])
            ->orderByDesc('updated_at')
            ->paginate(6);

        $stats = [
            'enrolledCourses' => Enrollment::where('user_id', $userId)->count(),
            'completedCourses' => Enrollment::where('user_id', $userId)->whereNotNull('completed_at')->count(),
            'learningHours' => 45,
        ];

        $jumpBackIn = Enrollment::where('user_id', $userId)
            ->with(['course.lessons' => function($q) { $q->orderBy('order'); }, 'completedLessons'])
            ->whereNull('completed_at')
            ->orderByDesc('updated_at')
            ->first();

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
            ->take(4)
            ->get();

        if ($recommendations->isEmpty()) {
            $recommendations = Course::whereNotIn('id', $enrolledCourseIds)
                ->where('status', 'published')
                ->with('instructor', 'category')
                ->orderByDesc('student_count')
                ->take(4)
                ->get();
        }

        return view('dashboard.student', compact('enrollments', 'stats', 'jumpBackIn', 'recommendations'));
    }

    private function instructorDashboard()
    {
        $userId = auth()->id();
        $courses = Course::where('instructor_id', $userId)->with('category')->paginate(6);
        $totalStudents = Course::where('instructor_id', $userId)->sum('student_count');
        $totalRevenue = $totalStudents * 49; // Mock calculation

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

        // Chart Data Mock
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $chartData = [120, 190, 300, 250, 420, 500];

        return view('dashboard.instructor', compact('courses', 'stats', 'topCourse', 'drafts', 'reviews', 'chartLabels', 'chartData'));
    }

    private function adminDashboard()
    {
        $stats = [
            'totalUsers' => \App\Models\User::count(),
            'totalCourses' => Course::count(),
            'totalEnrollments' => Enrollment::count(),
            'publishedCourses' => Course::where('status', 'published')->count(),
            'totalRevenue' => Enrollment::count() * 49, // Mock platform revenue
        ];

        // MoM changes (mock)
        $mom = [
            'users' => '+12.5%',
            'revenue' => '+8.2%',
            'courses' => '+4.1%',
            'enrollments' => '+15.3%'
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

        // Chart Data Mock
        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $chartData = [5000, 7200, 8500, 8100, 9600, 12000];
        
        // Mock pending approvals
        $pendingApprovals = \App\Models\User::where('role', 'instructor')->latest()->take(3)->get();

        return view('dashboard.admin', compact('stats', 'mom', 'recentCourses', 'categories', 'demographics', 'chartLabels', 'chartData', 'pendingApprovals'));
    }
}
