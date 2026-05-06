<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'category']);

        // Search
        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by instructor
        if ($request->instructor_id) {
            $query->where('instructor_id', $request->instructor_id);
        }

        $courses = $query->paginate(25);

        return view('admin.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load(['instructor', 'category', 'lessons', 'enrollments.user']);
        return view('admin.courses.show', compact('course'));
    }

    public function approve(Course $course)
    {
        $course->update(['status' => 'published']);
        return back()->with('success', 'Course approved and published.');
    }

    public function reject(Request $request, Course $course)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $course->update(['status' => 'rejected', 'rejection_reason' => $request->reason]);
        return back()->with('success', 'Course rejected.');
    }

    public function unpublish(Course $course)
    {
        $course->update(['status' => 'draft']);
        return back()->with('success', 'Course unpublished.');
    }

    public function feature(Course $course)
    {
        $course->update(['is_featured' => !$course->is_featured]);
        return back()->with('success', $course->is_featured ? 'Course featured.' : 'Course unfeatured.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return back()->with('success', 'Course deleted.');
    }
}
