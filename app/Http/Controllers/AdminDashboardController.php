<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseTopic;
use App\Models\TopicVideo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role->name !== 'admin') {
            abort(401, 'Unauthorized'); 
        }

        // Get monthly enrollment data for chart
        $monthlyEnrollments = CourseEnrollment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('count', 'month')
        ->toArray();

        // Fill missing months with 0
        $enrollmentData = [];
        for ($i = 1; $i <= 12; $i++) {
            $enrollmentData[] = $monthlyEnrollments[$i] ?? 0;
        }

        // Get recent activities
        $recentEnrollments = CourseEnrollment::with(['user', 'course'])
            ->latest()
            ->take(5)
            ->get();

        // Get top courses by enrollment
        $topCourses = Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get();

        // Calculate growth percentages (compared to last month)
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $currentMonthUsers = User::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
        
        $lastMonthUsers = User::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        $userGrowth = $lastMonthUsers > 0 ? 
            round((($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1) : 0;

        $currentMonthEnrollments = CourseEnrollment::whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->count();
        
        $lastMonthEnrollments = CourseEnrollment::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        $enrollmentGrowth = $lastMonthEnrollments > 0 ? 
            round((($currentMonthEnrollments - $lastMonthEnrollments) / $lastMonthEnrollments) * 100, 1) : 0;

        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'totalUsers' => User::count(),
            'totalInstructors' => User::where('role_id', 2)->count(),
            'totalStudents' => User::where('role_id', 3)->count(),
            'totalCourses' => Course::count(),
            'totalTopics' => TopicVideo::count(),
            'totalLessons' => CourseTopic::count(),
            'totalEnrollments' => CourseEnrollment::count(),
            'averageRating' => number_format(Course::avg('average_rating'), 2),
            'userGrowth' => $userGrowth,
            'enrollmentGrowth' => $enrollmentGrowth,
            'enrollmentData' => $enrollmentData,
            'recentEnrollments' => $recentEnrollments,
            'topCourses' => $topCourses,
        ]);
    }
}