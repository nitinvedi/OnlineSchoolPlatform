<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI Cards
        $totalUsers = User::count();
        $activeThisMonth = User::where('created_at', '>=', now()->startOfMonth())->count();
        $totalCourses = Course::count();
        $totalRevenue = Enrollment::sum('price'); // Assuming price is stored in enrollments
        $pendingApprovals = Course::where('status', 'pending')->count();

        // Revenue chart data (last 12 months)
        $revenueChart = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueChart[] = [
                'month' => $month->format('M Y'),
                'revenue' => Enrollment::whereBetween('created_at', [$month->startOfMonth(), $month->endOfMonth()])->sum('price')
            ];
        }

        // New signups chart (last 30 days)
        $signupsChart = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $signupsChart[] = [
                'date' => $date->format('M d'),
                'signups' => User::whereDate('created_at', $date)->count()
            ];
        }

        // Recent activity
        $recentActivity = Enrollment::with(['user', 'course'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($enrollment) {
                return [
                    'message' => "{$enrollment->user->name} enrolled in {$enrollment->course->title}",
                    'time' => $enrollment->created_at->diffForHumans()
                ];
            });

        return view('admin.dashboard', compact(
            'totalUsers', 'activeThisMonth', 'totalCourses', 'totalRevenue', 'pendingApprovals',
            'revenueChart', 'signupsChart', 'recentActivity'
        ));
    }
}
