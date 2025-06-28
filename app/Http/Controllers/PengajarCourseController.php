<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajarCourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('category')
            ->where('instructor_id', Auth::id());

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $courses = $query->paginate(10)->appends($request->query());

        return view('pengajar.course.index', compact('courses'));
    }

    public function create()
    {
        $categories = CourseCategory::all();
        return view('pengajar.course.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|integer|min:0',
            'category_id' => 'required|exists:course_categories,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'requirements' => 'nullable|string',
            'what_you_learn' => 'nullable|string',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['instructor_id'] = Auth::id();

        Course::create($validated);

        return redirect()->route('pengajar.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $this->authorizeInstructor($course);

        $categories = CourseCategory::all();
        return view('pengajar.course.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeInstructor($course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|integer|min:0',
            'category_id' => 'required|exists:course_categories,id',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'requirements' => 'nullable|string',
            'what_you_learn' => 'nullable|string',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $validated['is_published'] = $request->has('is_published');
        $validated['is_featured'] = $request->has('is_featured');

        $course->update($validated);

        return redirect()->route('pengajar.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $this->authorizeInstructor($course);
        $course->delete();
        return redirect()->route('pengajar.courses.index')->with('success', 'Course deleted successfully.');
    }

    public function show(Course $course)
    {
        $this->authorizeInstructor($course);
        return view('pengajar.course.show', compact('course'));
    }

    private function authorizeInstructor(Course $course)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course.');
        }
    }
}
