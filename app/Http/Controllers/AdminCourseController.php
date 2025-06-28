<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'category']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $courses = $query->paginate(10)->appends($request->query());

        return view('admin.course.index', compact('courses'));
    }

    public function create()
    {
        $instructors = User::all();
        $categories = CourseCategory::all();
        return view('admin.course.create', compact('instructors', 'categories'));
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
            'instructor_id' => 'required|exists:users,id',
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

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $instructors = User::all();
        $categories = CourseCategory::all();
        return view('admin.course.edit', compact('course', 'instructors', 'categories'));
    }

    public function show(Course $course)
    {
        return view('admin.course.show', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'required|integer|min:0',
            'instructor_id' => 'required|exists:users,id',
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

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
