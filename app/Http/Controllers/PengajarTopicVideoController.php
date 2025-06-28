<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTopic;
use App\Models\TopicVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajarTopicVideoController extends Controller
{
    public function index(Course $course, CourseTopic $topic)
    {
        $videos = $topic->videos()->paginate(10);;
        return view('pengajar.topic_video.index', compact('course', 'topic', 'videos'));
    }

    public function create(Course $course, CourseTopic $topic)
    {
        return view('pengajar.topic_video.create', compact('course', 'topic'));
    }

    public function store(Request $request, Course $course, CourseTopic $topic)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_type' => 'required|in:youtube,local,vimeo',
            'video_path' => 'required|string|max:500',
            'duration_seconds' => 'required|integer|min:0',
            'order_index' => 'required|integer|min:0',
        ]);

        $data['topic_id'] = $topic->id;
        TopicVideo::create($data);

        return redirect()->route('pengajar.courses.topics.video.index', [$course, $topic])
                         ->with('success', 'Video added successfully.');
    }

    public function edit(Course $course, CourseTopic $topic, TopicVideo $video)
    {
        return view('pengajar.topic_video.edit', compact('course', 'topic', 'video'));
    }

    public function show(Course $course, CourseTopic $topic, TopicVideo $video)
    {
        return view('pengajar.topic_video.show', compact('course', 'topic', 'video'));
    }

    public function update(Request $request, Course $course, CourseTopic $topic, TopicVideo $video)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_type' => 'required|in:youtube,local,vimeo',
            'video_path' => 'required|string|max:500',
            'duration_seconds' => 'required|integer|min:0',
            'order_index' => 'required|integer|min:0',
        ]);

        $video->update($data);

        return redirect()->route('pengajar.courses.topics.video.index', [$course, $topic])
                         ->with('success', 'Video updated successfully.');
    }

    public function destroy(Course $course, CourseTopic $topic, TopicVideo $video)
    {
        $video->delete();

        return redirect()->route('pengajar.courses.topics.video.index', [$course, $topic])
                         ->with('success', 'Video deleted successfully.');
    }
}
