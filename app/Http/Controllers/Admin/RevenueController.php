<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class RevenueController extends Controller
{
    public function index()
    {
        // Revenue stats
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalInstructorPayouts = 0; // Mock, need payout model
        $platformCut = $totalRevenue * 0.2; // 20% platform cut

        // Payout requests (mock, need Payout model)
        $payoutRequests = collect([]); // Mock data

        // Transaction log
        $transactions = Enrollment::with(['user', 'course.instructor'])
            ->latest()
            ->paginate(25);

        return view('admin.revenue.index', compact(
            'totalRevenue', 'totalInstructorPayouts', 'platformCut', 'payoutRequests', 'transactions'
        ));
    }

    public function approvePayout($payoutId)
    {
        // Implement payout approval
        return back()->with('success', 'Payout approved.');
    }

    public function rejectPayout($payoutId)
    {
        // Implement payout rejection
        return back()->with('success', 'Payout rejected.');
    }
}
