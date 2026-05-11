<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ReviewSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewSubmissionController extends Controller
{
    public function create(Course $course): View
    {
        $this->authorize('create', ReviewSubmission::class);

        $existing = $course->reviewSubmissions()
            ->where('user_id', auth()->id())
            ->first();

        return view('reviews.create', compact('course', 'existing'));
    }

    public function store(Course $course, Request $request)
    {
        $this->authorize('create', ReviewSubmission::class);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = $course->reviewSubmissions()->updateOrCreate(
            ['user_id' => auth()->id()],
            array_merge($validated, ['status' => 'pending'])
        );

        return redirect()->route('courses.show', $course)
            ->with('success', 'Review submitted successfully. It will be displayed after approval.');
    }

    public function index(Course $course): View
    {
        $this->authorize('update', $course);

        $reviews = $course->reviewSubmissions()
            ->with('user')
            ->paginate(15);

        return view('reviews.index', compact('course', 'reviews'));
    }

    public function approve(Course $course, ReviewSubmission $review)
    {
        $this->authorize('approve', $review);

        $review->approve();

        return back()->with('success', 'Review approved.');
    }

    public function reject(Course $course, ReviewSubmission $review, Request $request)
    {
        $this->authorize('reject', $review);

        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        $review->reject($validated['reason']);

        return back()->with('success', 'Review rejected.');
    }

    public function delete(ReviewSubmission $review)
    {
        $this->authorize('delete', $review);

        $course = $review->course;
        $review->delete();

        return redirect()->route('courses.show', $course)
            ->with('success', 'Review deleted.');
    }
}
