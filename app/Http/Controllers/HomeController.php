<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Services\CourseService;
use App\Services\WebsiteSettingService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected CourseService $courseService;
    protected CategoryService $categoryService;
    protected WebsiteSettingService $settingService;

    public function __construct(
        CourseService $courseService,
        CategoryService $categoryService,
        WebsiteSettingService $settingService
    ) {
        $this->courseService = $courseService;
        $this->categoryService = $categoryService;
        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        $selectedCategory = $request->query('category') ? (int) $request->query('category') : null;
        $search = $request->query('q');

        $sliders = $this->settingService->getActiveSliders();
        $categories = $this->categoryService->getActiveCategoriesWithCount();
        $courses = $this->courseService->getPublishedCourses($selectedCategory, $search, 6);
        $settings = $this->settingService->getAllGrouped();

        return view('pages.home', compact('sliders', 'categories', 'courses', 'selectedCategory', 'search', 'settings'));
    }
}
