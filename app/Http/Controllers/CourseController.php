<?php

namespace App\Http\Controllers;

use App\Models\CourseEnrollment;
use App\Models\CourseProgressLog;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseTopic;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $categoryId = $request->get('category');
        $gradients = $this->getGradients();

        $coursesQuery = Course::published()->with([
            'instructor', 'category', 'topics.videos', 'enrollments'
        ]);

        if ($query) {
            $coursesQuery->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->orWhere('short_description', 'like', '%' . $query . '%')
                ->orWhereHas('instructor', function($subQuery) use ($query) {
                    $subQuery->where('first_name', 'like', '%' . $query . '%')
                            ->orWhere('last_name', 'like', '%' . $query . '%');
                })
                ->orWhereHas('category', function($subQuery) use ($query) {
                    $subQuery->where('name', 'like', '%' . $query . '%');
                });
            });
        }

        if ($categoryId) {
            $coursesQuery->where('category_id', $categoryId);
        }

        $courses = $coursesQuery->get();

        $coursesArray = [];
        foreach ($courses as $course) {
            $coursesArray[$course->id] = [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'instructor' => optional($course->instructor)->first_name
                    ? trim(optional($course->instructor)->first_name . ' ' . optional($course->instructor)->last_name)
                    : 'Unknown',
                'level' => ucfirst($course->level),
                'rating' => $course->average_rating,
                'students' => $course->enrollments->count(),
                'duration' => $course->duration_hours . ' hours',
                'videoUrl' => $course->thumbnail,
                'topics' => $course->topics->pluck('title')->toArray(),
                'price' => (float) $course->price,
                'lessons' => collect($course->topics->all())->map(function ($topic) {
                    return [
                        'topic_title' => $topic->title,
                        'videos' => $topic->videos->map(function ($video) {
                            return [
                                'id' => $video->id,
                                'title' => $video->title,
                                'duration' => gmdate("H:i:s", $video->duration_seconds),
                                'is_free' => $video->is_free,
                            ];
                        })->toArray()
                    ];
                })->toArray()
            ];
        }

        $categories = CourseCategory::where('is_active', true)->get();

        return view('siswa.search', [
            'courses' => $coursesArray,
            'gradients' => $gradients,
            'query' => $query,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
        ]);
    }

    public function enroll(Request $request, $courseId)
    {
        $userId = Auth::id() ?? 9;

        $exists = CourseEnrollment::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        if (!$exists) {
            CourseEnrollment::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'enrolled_at' => now(),
            ]);
        }

        return redirect()->route('courses.show', $courseId)
            ->with('success', 'You have successfully enrolled.');
    }



    public function show($id)
    {
        $userId = Auth::id() ?? 9; 

        $course = Course::published()
            ->with(['instructor', 'category', 'topics.videos'])
            ->findOrFail($id);

        $enrollment = CourseEnrollment::where('course_id', $id)
            ->where('user_id', $userId)
            ->first(); 

        return view('siswa.course-detail', compact('course', 'enrollment'));
    }

    public function showLesson($courseId, $lessonId)
    {
        $userId = Auth::id() ?? 9;
        $course = Course::published()->with(['topics.videos'])->findOrFail($courseId);

        // Temukan lesson
        $lesson = null;
        $currentTopic = null;

        foreach ($course->topics as $topic) {
            $video = $topic->videos->where('id', $lessonId)->first();
            if ($video) {
                $lesson = $video;
                $currentTopic = $topic;
                break;
            }
        }

        if (!$lesson) abort(404);

        CourseProgressLog::firstOrCreate([
            'user_id' => $userId,
            'course_id' => $courseId,
            'video_id' => $lesson->id,
        ]);

        // Hitung progres
        $totalLessons = $course->topics->map(function($t) { return $t->videos->count(); })->sum();
        $completed = CourseProgressLog::where('user_id', $userId)
                        ->where('course_id', $courseId)
                        ->count();
        $progressPercentage = round(($completed / max($totalLessons, 1)) * 100, 2);

        DB::table('course_enrollments')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->update(['progress_percentage' => $progressPercentage]);

        if ($progressPercentage == 100) {
            DB::table('course_enrollments')
                ->where('user_id', $userId)
                ->where('course_id', $courseId)
                ->update(['completed_at' => now()]);
        }

        // Navigasi & UI
        $allVideos = collect();
        foreach ($course->topics as $topic) {
            foreach ($topic->videos as $video) {
                $allVideos->push($video);
            }
        }

        $currentIndex = $allVideos->search(fn($v) => $v->id == $lessonId);
        $previousLesson = $currentIndex > 0 ? $allVideos[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allVideos->count() - 1 ? $allVideos[$currentIndex + 1] : null;
        $lessonNumber = $currentIndex + 1;

        return view('siswa.lesson-detail', compact(
            'course', 'lesson', 'previousLesson', 'nextLesson',
            'progressPercentage', 'totalLessons', 'lessonNumber'
        ));
    }

    private function getGradients()
    {
        return [
            'from-blue-500 to-purple-600',
            'from-green-500 to-teal-600', 
            'from-orange-500 to-red-600',
            'from-purple-500 to-pink-600',
            'from-indigo-500 to-blue-600',
            'from-pink-500 to-rose-600',
            'from-yellow-500 to-orange-600',
            'from-red-500 to-pink-600',
            'from-teal-500 to-cyan-600',
            'from-violet-500 to-purple-600'
        ];
    }
}