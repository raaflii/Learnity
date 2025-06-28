<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Event;
use App\Models\CourseEnrollment;
use App\Models\TopicVideo;
use App\Models\VideoProgress;
use App\Models\QuizAttempt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->role || $user->role->name !== 'student') {
            abort(401, 'Unauthorized'); 
        }
        
        // Get dashboard statistics
        $stats = [
            'total_courses' => Course::where('is_published', true)->count(),
            'total_lessons' => TopicVideo::count(), // Using topic_videos as lessons
            'upcoming_events' => Event::where('user_id', $user->id)
                ->where('start_time', '>', now())
                ->where('status', 'scheduled')
                ->count(),
            'completed_courses' => CourseEnrollment::where('user_id', $user->id)
                ->whereNotNull('completed_at')
                ->count(),
        ];

        // Get recent courses that user is enrolled in (latest 4)
        $recentCourses = Course::whereHas('enrollments', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with([
                'instructor:id,first_name,last_name', 
                'topics.videos',
                'enrollments' => function($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            ->latest('updated_at')
            ->limit(4)
            ->get()
            ->map(function($course) {
                // Calculate total lessons (videos) count
                $course->lessons_count = $course->topics->map(function($topic) {
                    return $topic->videos->count();
                })->sum();
                
                // Get instructor name
                $course->instructor_name = $course->instructor->first_name . ' ' . $course->instructor->last_name;
                
                return $course;
            });

        // Get upcoming events for current user (next 5)
        $upcomingEvents = Event::where('user_id', $user->id)
            ->where('start_time', '>', now())
            ->where('status', 'scheduled')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        // Get today's events for current user
        $todayEvents = Event::where('user_id', $user->id)
            ->whereDate('start_time', Carbon::today())
            ->where('status', 'scheduled')
            ->orderBy('start_time')
            ->get()
            ->map(function($event) {
                $event->getFormattedStartTimeAttribute = Carbon::parse($event->start_time)->format('H:i');
                return $event;
            });

        // Get popular courses (by enrollment count)
        $popularCourses = Course::where('is_published', true)
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->limit(3)
            ->get()
            ->map(function($course) {
                // Add students count and rating (you might want to add a ratings table)
                $course->students = $course->enrollments_count;
                $course->rating = rand(40, 50) / 10; // Mock rating, replace with actual rating calculation
                return $course;
            });

        // Get recent activity for current user
        $recentActivity = $this->getRecentActivity($user);

        // Get learning progress for current user
        $learningProgress = $this->getLearningProgress($user);

        return view('siswa.dashboard', [
            'title' => 'Dashboard',
            'user' => $user,
            'stats' => $stats,
            'recentCourses' => $recentCourses,
            'upcomingEvents' => $upcomingEvents,
            'todayEvents' => $todayEvents,
            'popularCourses' => $popularCourses,
            'recentActivity' => $recentActivity,
            'learningProgress' => $learningProgress,
        ]);
    }

    private function getRecentActivity($user)
    {
        $activities = collect();

        // Recent enrollments
        $recentEnrollments = CourseEnrollment::where('user_id', $user->id)
            ->with('course:id,title')
            ->latest()
            ->limit(2)
            ->get();

        foreach ($recentEnrollments as $enrollment) {
            $activities->push([
                'type' => 'course_enrolled',
                'description' => "Enrolled in {$enrollment->course->title}",
                'time' => Carbon::parse($enrollment->enrolled_at)->diffForHumans(),
                'icon' => 'academic-cap',
                'color' => 'blue'
            ]);
        }

        // Recent events created
        $recentEvents = Event::where('user_id', $user->id)
            ->latest()
            ->limit(2)
            ->get();

        foreach ($recentEvents as $event) {
            $activities->push([
                'type' => 'event_created',
                'description' => "Created event \"{$event->title}\"",
                'time' => $event->created_at->diffForHumans(),
                'icon' => 'calendar',
                'color' => 'purple'
            ]);
        }

        // Recent quiz attempts

        return $activities->sortByDesc('time')->take(4)->values()->all();
    }

    private function getLearningProgress($user)
    {
        // Courses in progress (enrolled but not completed)
        $coursesInProgress = CourseEnrollment::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->count();

        // Courses completed this month
        $completedThisMonth = CourseEnrollment::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)
            ->count();


        return [
            'courses_in_progress' => $coursesInProgress,
            'completed_this_month' => $completedThisMonth,
        ];
    }
}