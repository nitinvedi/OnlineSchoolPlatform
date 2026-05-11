<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Course $course): View
    {
        $announcements = $course->announcements()
            ->when(auth()->user()->isStudent(), function ($query) {
                return $query->where('status', 'published');
            })
            ->latest()
            ->paginate(15);

        return view('announcements.index', compact('course', 'announcements'));
    }

    public function show(Course $course, Announcement $announcement): View
    {
        $this->authorize('view', $announcement);

        return view('announcements.show', compact('course', 'announcement'));
    }

    public function create(Course $course): View
    {
        $this->authorize('create', Announcement::class);
        $this->authorize('update', $course);

        return view('announcements.create', compact('course'));
    }

    public function store(Course $course, Request $request)
    {
        $this->authorize('create', Announcement::class);
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'in:draft,published',
            'send_notification' => 'boolean',
        ]);

        $announcement = $course->announcements()->create(
            array_merge($validated, ['user_id' => auth()->id()])
        );

        if ($validated['status'] === 'published') {
            $announcement->publish();
            // TODO: Send notification to enrolled students
        }

        return redirect()->route('courses.announcements.show', [$course, $announcement])
            ->with('success', 'Announcement created successfully.');
    }

    public function edit(Course $course, Announcement $announcement): View
    {
        $this->authorize('update', $announcement);

        return view('announcements.edit', compact('course', 'announcement'));
    }

    public function update(Course $course, Announcement $announcement, Request $request)
    {
        $this->authorize('update', $announcement);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'in:draft,published,archived',
            'send_notification' => 'boolean',
        ]);

        $wasPublished = $announcement->isPublished();
        $announcement->update($validated);

        if ($validated['status'] === 'published' && !$wasPublished) {
            $announcement->publish();
            // TODO: Send notification to enrolled students
        }

        return redirect()->route('courses.announcements.show', [$course, $announcement])
            ->with('success', 'Announcement updated successfully.');
    }

    public function delete(Course $course, Announcement $announcement)
    {
        $this->authorize('delete', $announcement);

        $announcement->delete();

        return redirect()->route('courses.announcements.index', $course)
            ->with('success', 'Announcement deleted successfully.');
    }
}
