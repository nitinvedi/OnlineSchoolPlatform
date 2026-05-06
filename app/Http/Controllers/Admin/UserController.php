<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Filter by role
        if ($request->role) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->status) {
            if ($request->status === 'active') {
                $query->whereNull('suspended_at');
            } elseif ($request->status === 'suspended') {
                $query->whereNotNull('suspended_at');
            }
        }

        // Filter by join date range
        if ($request->join_start) {
            $query->where('created_at', '>=', $request->join_start);
        }
        if ($request->join_end) {
            $query->where('created_at', '<=', $request->join_end);
        }

        $users = $query->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['enrollments.course', 'courses']); // Assuming relationships
        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:student,instructor,admin'
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully.');
    }

    public function suspend(User $user)
    {
        $user->update(['suspended_at' => now()]);
        return back()->with('success', 'User suspended.');
    }

    public function unsuspend(User $user)
    {
        $user->update(['suspended_at' => null]);
        return back()->with('success', 'User unsuspended.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $userIds = $request->user_ids;

        if ($action === 'suspend') {
            User::whereIn('id', $userIds)->update(['suspended_at' => now()]);
        } elseif ($action === 'unsuspend') {
            User::whereIn('id', $userIds)->update(['suspended_at' => null]);
        } elseif ($action === 'delete') {
            User::whereIn('id', $userIds)->delete();
        }

        return back()->with('success', 'Bulk action completed.');
    }

    public function export(Request $request)
    {
        // Implement CSV export
        $users = User::all();
        $filename = 'users_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Join Date', 'Status']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->created_at->format('Y-m-d'),
                    $user->suspended_at ? 'Suspended' : 'Active'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
