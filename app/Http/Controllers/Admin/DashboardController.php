<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI Cards — all live from DB
        $totalUsers       = User::count();
        $newUsersThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();
        $totalCourses     = Course::count();
        $publishedCourses = Course::where('status', 'published')->count();
        $totalEnrollments = Enrollment::count();
        $totalRevenue     = Payment::where('status', 'completed')->sum('amount');
        $pendingApprovals = Course::where('status', 'pending')->count();

        // Recent enrollments — last 10, with user + course info
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'newUsersThisMonth', 'totalCourses', 'publishedCourses',
            'totalEnrollments', 'totalRevenue', 'pendingApprovals', 'recentEnrollments'
        ));
    }
}
