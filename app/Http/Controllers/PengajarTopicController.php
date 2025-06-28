<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajarTopicController extends Controller
{
    protected function ensureCourseOwnedByUser(Course $course)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403, 'You are not authorized to manage this course.');
        }
    }

    public function index(Course $course)
    {
        $this->ensureCourseOwnedByUser($course);

        $topics = $course->topics()->orderBy('order_index')->paginate(10);

        return view('pengajar.topic.index', compact('course', 'topics'));
    }

    public function create(Course $course)
    {
        $this->ensureCourseOwnedByUser($course);

        return view('pengajar.topic.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->ensureCourseOwnedByUser($course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_index' => 'required|integer|min:0',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $validated['course_id'] = $course->id;

        CourseTopic::create($validated);

        return redirect()
            ->route('pengajar.courses.topics.index', $course)
            ->with('success', 'Topic created successfully.');
    }

    public function edit(Course $course, CourseTopic $topic)
    {
        $this->ensureCourseOwnedByUser($course);

        return view('pengajar.topic.edit', compact('course', 'topic'));
    }

    public function show(Course $course, CourseTopic $topic)
    {
        return view('pengajar.topic.show', compact('course', 'topic'));
    }

    public function update(Request $request, Course $course, CourseTopic $topic)
    {
        $this->ensureCourseOwnedByUser($course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_index' => 'required|integer|min:0',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $topic->update($validated);

        return redirect()
            ->route('pengajar.courses.topics.index', $course)
            ->with('success', 'Topic updated successfully.');
    }

    public function destroy(Course $course, CourseTopic $topic)
    {
        $this->ensureCourseOwnedByUser($course);

        $topic->delete();

        return redirect()
            ->route('pengajar.courses.topics.index', $course)
            ->with('success', 'Topic deleted.');
    }
}
