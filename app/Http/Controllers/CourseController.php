<?php

namespace App\Http\Controllers;

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
        $selectedCategory = $request->query('category') ? (int) $request->query('category') : null;
        $search = $request->query('q');

        $categories = $this->categoryService->getActiveCategoriesWithCount();
        $courses = $this->courseService->getPublishedCourses($selectedCategory, $search, 9);

        return view('pages.courses', compact('courses', 'categories', 'selectedCategory', 'search'));
    }

    public function show(string $slug)
    {
        $course = $this->courseService->getCourseBySlug($slug);
        $isEnrolled = auth()->check() ? auth()->user()->hasPurchasedCourse($course->id) : false;

        return view('pages.course-details', compact('course', 'isEnrolled'));
    }
}
