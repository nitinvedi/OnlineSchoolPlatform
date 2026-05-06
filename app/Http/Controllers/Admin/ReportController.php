<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportable']);

        // Filter by type
        if ($request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(25);

        return view('admin.reports.index', compact('reports'));
    }

    public function dismiss(Report $report)
    {
        $report->update(['status' => 'dismissed']);
        return back()->with('success', 'Report dismissed.');
    }

    public function removeContent(Report $report)
    {
        // Delete the reported content
        $report->reportable->delete();
        $report->update(['status' => 'removed']);
        return back()->with('success', 'Content removed.');
    }

    public function warnUser(Report $report)
    {
        // Implement user warning
        $report->update(['status' => 'warned']);
        return back()->with('success', 'User warned.');
    }

    public function banUser(Report $report)
    {
        // Ban the user who posted the content
        $user = $report->reportable->user ?? $report->reportable;
        $user->update(['suspended_at' => now()]);
        $report->update(['status' => 'banned']);
        return back()->with('success', 'User banned.');
    }
}
