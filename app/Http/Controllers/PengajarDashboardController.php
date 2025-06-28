<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\CourseTopic;
use App\Models\TopicVideo;
use App\Models\CourseEnrollment;

class PengajarDashboardController extends Controller
{
    public function index()
    {
        $instructorId = Auth::id();
        
        // Statistik umum
        $totalCourses = Course::where('instructor_id', $instructorId)->count();
        $publishedCourses = Course::where('instructor_id', $instructorId)
            ->where('is_published', true)
            ->count();
        
        $totalEnrollments = CourseEnrollment::whereHas('course', function($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId);
        })->count();
        
        $totalRevenue = CourseEnrollment::join('courses', 'course_enrollments.course_id', '=', 'courses.id')
            ->where('courses.instructor_id', $instructorId)
            ->sum('courses.price');
        
        // Course dengan enrollment terbanyak
        $topCourses = Course::where('instructor_id', $instructorId)
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->limit(5)
            ->get();
        
        // Statistik enrollment per bulan (6 bulan terakhir)
        $enrollmentStats = CourseEnrollment::join('courses', 'course_enrollments.course_id', '=', 'courses.id')
            ->where('courses.instructor_id', $instructorId)
            ->select(
                DB::raw('DATE_FORMAT(course_enrollments.enrolled_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total_enrollments'),
                DB::raw('SUM(courses.price) as revenue')
            )
            ->where('course_enrollments.enrolled_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();
        
        // Course dengan detail lengkap
        $courses = Course::where('instructor_id', $instructorId)
            ->withCount([
                'enrollments',
                'topics',
                'topics as published_topics_count' => function($query) {
                    $query->where('is_published', true);
                }
            ])
            ->with([
                'topics' => function($query) {
                    $query->withCount('videos')->orderBy('order_index');
                },
                'category'
            ])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Statistik video per course
        $videoStats = Course::where('instructor_id', $instructorId)
            ->with(['topics.videos'])
            ->get()
            ->map(function($course) {
                $totalVideos = $course->topics->sum(function($topic) {
                    return $topic->videos->count();
                });
                $totalDuration = $course->topics->sum(function($topic) {
                    return $topic->videos->sum('duration_seconds');
                });
                
                return [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'total_videos' => $totalVideos,
                    'total_duration' => $totalDuration,
                    'total_duration_formatted' => gmdate('H:i:s', $totalDuration)
                ];
            });
        
        // Recent enrollments
        $recentEnrollments = CourseEnrollment::join('courses', 'course_enrollments.course_id', '=', 'courses.id')
            ->join('users', 'course_enrollments.user_id', '=', 'users.id')
            ->where('courses.instructor_id', $instructorId)
            ->select(
                'course_enrollments.*',
                'courses.title as course_title',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as student_name"),
                'users.email as student_email'
            )
            ->orderBy('course_enrollments.enrolled_at', 'desc')
            ->limit(10)
            ->get();

        
        return view('pengajar.dashboard', compact(
            'totalCourses',
            'publishedCourses',
            'totalEnrollments',
            'totalRevenue',
            'topCourses',
            'enrollmentStats',
            'courses',
            'videoStats',
            'recentEnrollments'
        ));
    }
    
    public function courseDetail($id)
    {
        $course = Course::where('instructor_id', Auth::id())
            ->where('id', $id)
            ->withCount('enrollments')
            ->with([
                'topics' => function($query) {
                    $query->withCount('videos')
                        ->with(['videos' => function($videoQuery) {
                            $videoQuery->orderBy('order_index');
                        }])
                        ->orderBy('order_index');
                },
                'category',
                'enrollments' => function($query) {
                    $query->with('user')
                        ->orderBy('enrolled_at', 'desc');
                }
            ])
            ->firstOrFail();
        
        // Statistik progress siswa
        $progressStats = CourseEnrollment::where('course_id', $id)
            ->select(
                DB::raw('CASE 
                    WHEN progress_percentage = 0 THEN "Belum Mulai"
                    WHEN progress_percentage < 50 THEN "Dalam Progress"
                    WHEN progress_percentage < 100 THEN "Hampir Selesai"
                    ELSE "Selesai"
                END as status'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();
        
        // Revenue dari course ini
        $courseRevenue = CourseEnrollment::where('course_id', $id)
            ->join('courses', 'course_enrollments.course_id', '=', 'courses.id')
            ->sum('courses.price');
        
        // Enrollment per bulan untuk course ini
        $monthlyEnrollments = CourseEnrollment::where('course_id', $id)
            ->select(
                DB::raw('DATE_FORMAT(enrolled_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('enrolled_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();
        
        return view('pengajar.components.detail-course', compact(
            'course',
            'progressStats',
            'courseRevenue',
            'monthlyEnrollments'
        ));
    }
}