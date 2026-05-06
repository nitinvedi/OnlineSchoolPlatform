<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseReview::with(['user', 'course']);

        // Filter flagged reviews
        if ($request->flagged) {
            $query->where('is_flagged', true);
        }

        $reviews = $query->paginate(25);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(CourseReview $review)
    {
        $review->update(['is_flagged' => false, 'moderated_at' => now()]);
        return back()->with('success', 'Review approved.');
    }

    public function remove(CourseReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review removed.');
    }

    public function warnUser(CourseReview $review)
    {
        // Implement user warning
        return back()->with('success', 'User warned.');
    }
}
