<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseReviewController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();

        // Cegah duplicate review
        $alreadyReviewed = CourseReview::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You have already submitted a review for this course.');
        }

        CourseReview::create([
            'user_id' => $userId,
            'course_id' => $course->id,
            'enrollment_id' => CourseEnrollment::where('user_id', $userId)
                ->where('course_id', $course->id)
                ->value('id'),
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        $avg = CourseReview::where('course_id', $course->id)->avg('rating');
        $count = CourseReview::where('course_id', $course->id)->count();

        Course::where('id', $course->id)->update([
            'average_rating' => round($avg, 2),
            'total_reviews' => $count,
        ]);

        return redirect()->back()->with('success', 'Review submitted!');
    }
}
