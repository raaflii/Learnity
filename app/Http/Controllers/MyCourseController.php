<?php

namespace App\Http\Controllers;

use App\Models\CourseProgressLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyCourseController extends Controller
{
    public function index(Request $request)
    {
        // Get user's enrolled courses
        $user = Auth::user();
        $query = $request->get('q');
        $status = $request->get('status');

        // Get enrolled courses with progress data
        $enrolledCourses = $user->enrollments()
            ->with(['course' => function($q) {
                $q->with('instructor', 'category');
            }])
            ->when($query, function($q, $query) {
                $q->whereHas('course', function($courseQuery) use ($query) {
                    $courseQuery->where('title', 'LIKE', "%{$query}%")
                               ->orWhere('description', 'LIKE', "%{$query}%");
                });
            })
            ->when($status, function($q, $status) {
                if ($status === 'not-started') {
                    $q->where('progress_percentage', 0);
                } elseif ($status === 'in-progress') {
                    $q->where('progress_percentage', '>', 0)->where('progress_percentage', '<', 100);
                } elseif ($status === 'completed') {
                    $q->where('progress_percentage', 100);
                }
            })
            ->get();

        // Transform data for the view
        $myCourses = [];
        $totalHours = 0;
        $completedCount = 0;
        $inProgressCount = 0;

        foreach ($enrolledCourses as $enrollment) {
            $course = $enrollment->course;
            $progress = (float) $enrollment->progress_percentage;

            $totalLessons = $enrollment->course->topics->flatMap->videos->count();

            $completedLessons = CourseProgressLog::where('user_id', $user->id)
                ->where('course_id', $enrollment->course_id)
                ->distinct('video_id')
                ->count('video_id');

            if ($progress == 0.0) {
                $status = 'not-started';
            } elseif ($progress < 100.0) {
                $status = 'in-progress';
            } else {
                $status = 'completed';
            }

            $myCourses[$course->id] = [
                'title' => $course->title,
                'description' => $course->description,
                'instructor' => $course->instructor->full_name ?? 'Unknown',
                'level' => $course->level,
                'progress' => $progress,
                'status' => $status,
                'last_accessed' => $enrollment->last_accessed_at ?
                    \Carbon\Carbon::parse($enrollment->last_accessed_at)->diffForHumans() : null,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'next_lesson_id' => $enrollment->next_lesson_id,
            ];

            $totalHours += $course->duration_hours ?? 0;

            if ($status === 'completed') {
                $completedCount++;
            } elseif ($status === 'in-progress') {
                $inProgressCount++;
            }
            
        }

        // Color gradients for cards
        $gradients = [
            'from-blue-500 to-purple-600',
            'from-purple-500 to-pink-600',
            'from-pink-500 to-red-600',
            'from-red-500 to-orange-600',
            'from-orange-500 to-yellow-600',
            'from-green-500 to-blue-600',
            'from-indigo-500 to-purple-600',
            'from-teal-500 to-green-600',
        ];


        return view('siswa.my-course', compact(
            'myCourses',
            'gradients',
            'totalHours',
            'completedCount',
            'inProgressCount',
            'totalLessons',
            'completedLessons',
        ));
    }
}