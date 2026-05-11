<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\LiveSession;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(LiveSession $liveSession): View
    {
        $this->authorize('update', $liveSession->course);

        $attendances = $liveSession->attendances()
            ->with('user')
            ->paginate(20);

        $stats = [
            'total_students' => $liveSession->course->enrollments()->count(),
            'attended' => $liveSession->attendances()->where('status', 'attended')->count(),
            'late' => $liveSession->attendances()->where('status', 'late')->count(),
            'absent' => $liveSession->attendances()->where('status', 'absent')->count(),
        ];

        return view('attendance.index', compact('liveSession', 'attendances', 'stats'));
    }

    public function record(LiveSession $liveSession, Request $request)
    {
        $this->authorize('update', $liveSession->course);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:attended,late,absent',
            'notes' => 'nullable|string',
        ]);

        Attendance::updateOrCreate(
            ['live_session_id' => $liveSession->id, 'user_id' => $validated['user_id']],
            array_merge($validated, ['joined_at' => now()])
        );

        return back()->with('success', 'Attendance recorded.');
    }

    public function recordBulk(LiveSession $liveSession, Request $request)
    {
        $this->authorize('update', $liveSession->course);

        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.user_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:attended,late,absent',
        ]);

        foreach ($validated['attendances'] as $data) {
            Attendance::updateOrCreate(
                ['live_session_id' => $liveSession->id, 'user_id' => $data['user_id']],
                array_merge($data, ['joined_at' => now()])
            );
        }

        return back()->with('success', 'Attendance recorded for all students.');
    }

    public function participation(LiveSession $liveSession): View
    {
        $this->authorize('update', $liveSession->course);

        $participations = $liveSession->participations()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('attendance.participation', compact('liveSession', 'participations'));
    }

    public function recordParticipation(LiveSession $liveSession, Request $request)
    {
        $this->authorize('update', $liveSession->course);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:question_asked,comment,poll_response,raised_hand',
            'content' => 'nullable|string',
            'score' => 'nullable|integer|min:0|max:10',
        ]);

        $liveSession->participations()->create($validated);

        return back()->with('success', 'Participation recorded.');
    }
}
