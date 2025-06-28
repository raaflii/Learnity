<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use Illuminate\Http\Request;

class AdminCourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $categories = $query->paginate(10)->appends($request->query());

        return view('admin.course_category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.course_category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:course_categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        CourseCategory::create($validated);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Course category created successfully');
    }

    public function show(CourseCategory $courseCategory)
    {
        return view('admin.course_category.show', compact('courseCategory'));
    }

    public function edit(CourseCategory $courseCategory)
    {
        return view('admin.course_category.edit', compact('courseCategory'));
    }

    public function update(Request $request, CourseCategory $courseCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:course_categories,name,' . $courseCategory->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $courseCategory->update($validated);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Course category updated successfully');
    }

    public function destroy(CourseCategory $courseCategory)
    {
        $courseCategory->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Course category deleted');
    }
}
