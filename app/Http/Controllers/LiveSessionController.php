<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LiveSessionController extends Controller
{
    /**
     * Display a listing of upcoming sessions for students.
     */
    public function index()
    {
        $user = auth()->user();

        // If instructor, show their own upcoming sessions
        if ($user->isInstructor()) {
            $sessions = LiveSession::where('instructor_id', $user->id)
                ->whereIn('status', ['scheduled', 'live'])
                ->with('course')
                ->orderBy('scheduled_at')
                ->get();
        } elseif ($user->isAdmin()) {
            // If admin, show all upcoming sessions
            $sessions = LiveSession::whereIn('status', ['scheduled', 'live'])
                ->with('course.instructor')
                ->orderBy('scheduled_at')
                ->get();
        } else {
            // For students, show sessions from enrolled courses
            $enrolledCourseIds = $user->enrollments()->pluck('course_id');
            $sessions = LiveSession::whereIn('course_id', $enrolledCourseIds)
                ->whereIn('status', ['scheduled', 'live'])
                ->with('course.instructor')
                ->orderBy('scheduled_at')
                ->get();
        }

        return view('live-sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new session.
     */
    public function create()
    {
        $courses = Course::where('instructor_id', auth()->id())
            ->withCount('enrollments as student_count')
            ->with(['enrollments.user:id,name,email'])
            ->orderBy('title')
            ->get()
            ->map(function ($course) {
                $course->enrolledStudents = $course->enrollments->map(fn($e) => [
                    'id'    => $e->user->id,
                    'name'  => $e->user->name,
                    'email' => $e->user->email,
                ])->values();
                return $course;
            });

        if ($courses->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'You need to create a course before scheduling a live session.');
        }

        return view('instructor.live-sessions.create', compact('courses'));
    }

    /**
     * Store a newly created session in storage.
     */
    public function store(Request $request)
    {
        $startNow = $request->boolean('start_now');

        $request->validate([
            'course_id'            => 'required|exists:courses,id',
            'title'                => 'required|string|max:255',
            'description'          => 'nullable|string',
            'scheduled_at'         => $startNow ? 'nullable|date' : 'required|date|after:now',
            'max_duration_minutes' => 'required|integer|min:15|max:300',
        ]);

        $course = Course::findOrFail($request->course_id);
        Gate::authorize('update', $course);

        $session = LiveSession::create([
            'course_id'            => $course->id,
            'instructor_id'        => auth()->id(),
            'title'                => $request->title,
            'description'          => $request->description,
            'scheduled_at'         => $startNow ? now() : $request->scheduled_at,
            'max_duration_minutes' => $request->max_duration_minutes,
            'status'               => $startNow ? 'live' : 'scheduled',
            'started_at'           => $startNow ? now() : null,
        ]);

        if ($startNow) {
            return redirect()
                ->route('live-sessions.show', $session)
                ->with('success', 'Your live class has started!');
        }

        return redirect()->route('dashboard')->with('success', 'Live session scheduled successfully.');
    }

    /**
     * Show the specified session (Lobby / Live Room).
     */
    public function show(LiveSession $liveSession)
    {
        $user = auth()->user();

        // Ensure user can access: instructor of the course, OR enrolled student
        $course = $liveSession->course;
        
        if ($course->instructor_id !== $user->id && !$user->isAdmin()) {
            // Check enrollment
            $isEnrolled = $course->enrollments()->where('user_id', $user->id)->exists();
            if (!$isEnrolled) {
                abort(403, 'You are not enrolled in the course for this session.');
            }
        }

        return view('live-sessions.show', compact('liveSession', 'course'));
    }

    /**
     * Show the form for editing the specified session.
     */
    public function edit(LiveSession $liveSession)
    {
        // Must be the instructor
        if ($liveSession->instructor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $courses = Course::where('instructor_id', auth()->id())->orderBy('title')->get();

        return view('instructor.live-sessions.edit', compact('liveSession', 'courses'));
    }

    /**
     * Update the specified session in storage.
     */
    public function update(Request $request, LiveSession $liveSession)
    {
        if ($liveSession->instructor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'max_duration_minutes' => 'required|integer|min:15|max:300',
        ]);

        $course = Course::findOrFail($request->course_id);
        Gate::authorize('update', $course);

        $liveSession->update([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'scheduled_at' => $request->scheduled_at,
            'max_duration_minutes' => $request->max_duration_minutes,
        ]);

        return redirect()->route('dashboard')->with('success', 'Live session updated successfully.');
    }

    /**
     * Remove the specified session from storage.
     */
    public function destroy(LiveSession $liveSession)
    {
        if ($liveSession->instructor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $liveSession->delete();

        return redirect()->route('dashboard')->with('success', 'Live session canceled.');
    }

    /**
     * Mark the session as live (started).
     */
    public function start(LiveSession $liveSession)
    {
        if ($liveSession->instructor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $liveSession->update([
            'status'     => 'live',
            'started_at' => now(),
        ]);

        // Redirect directly into the room
        return redirect()
            ->route('live-sessions.show', $liveSession)
            ->with('success', 'Class is now live!');
    }

    /**
     * Mark the session as ended.
     */
    public function end(LiveSession $liveSession)
    {
        if ($liveSession->instructor_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $liveSession->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Class has been ended.');
    }
}
