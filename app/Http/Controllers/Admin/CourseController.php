<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Services\CategoryService;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected CourseService $courseService;
    protected CategoryService $categoryService;

    public function __construct(CourseService $courseService, CategoryService $categoryService)
    {
        $this->courseService = $courseService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $search = $request->query('q');
        $courses = $this->courseService->getAdminCourses($search, 12);

        return view('admin.courses.index', compact('courses', 'search'));
    }

    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.courses.create', compact('categories'));
    }

    public function store(CourseRequest $request)
    {
        $data = $request->validated();
        $thumbnail = $request->file('thumbnail');

        $course = $this->courseService->createCourse($data, $thumbnail);

        return redirect()->route('admin.courses.index')
            ->with('success', "Course '{$course->title}' created successfully.");
    }

    public function edit(Course $course)
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.courses.edit', compact('course', 'categories'));
    }

    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->validated();
        $thumbnail = $request->file('thumbnail');

        $this->courseService->updateCourse($course, $data, $thumbnail);

        return redirect()->route('admin.courses.index')
            ->with('success', "Course '{$course->title}' updated successfully.");
    }

    public function destroy(Course $course)
    {
        $title = $course->title;
        $this->courseService->deleteCourse($course);

        return redirect()->route('admin.courses.index')
            ->with('success', "Course '{$title}' deleted successfully.");
    }

    public function togglePublish(Course $course)
    {
        $this->courseService->togglePublish($course);

        return redirect()->back()
            ->with('success', "Course status changed to '{$course->status}'.");
    }
}
