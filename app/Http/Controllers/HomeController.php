<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\CategoryService;
use App\Services\CertificateService;
use App\Services\CourseService;
use App\Services\WebsiteSettingService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected CourseService $courseService;
    protected CategoryService $categoryService;
    protected WebsiteSettingService $settingService;
    protected CertificateService $certificateService;

    public function __construct(
        CourseService $courseService,
        CategoryService $categoryService,
        WebsiteSettingService $settingService,
        CertificateService $certificateService
    ) {
        $this->courseService = $courseService;
        $this->categoryService = $categoryService;
        $this->settingService = $settingService;
        $this->certificateService = $certificateService;
    }

    public function index(Request $request)
    {
        $sliders = $this->settingService->getActiveSliders();
        $categories = $this->categoryService->getActiveCategoriesWithCount();
        
        // Eager-load all published courses with lessons & exam for instant Alpine.js modal & category filtering
        $courses = Course::with(['category', 'publishedLessons', 'finalExam'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();

        $settings = $this->settingService->getAllGrouped();

        $enrolledCourseIds = [];
        if (auth()->check()) {
            $enrolledCourseIds = auth()->user()->enrolledCourses()->pluck('courses.id')->toArray();
        }

        // Handle On-Page Certificate Lookup
        $searchedCertNumber = $request->query('verify_number');
        $verifiedCertificate = null;
        if ($searchedCertNumber) {
            $verifiedCertificate = $this->certificateService->verifyCertificate($searchedCertNumber);
        }

        // Auto open course modal if slug passed (e.g. redirected from /courses/{slug})
        $autoOpenCourseSlug = $request->query('course');

        return view('pages.home', compact(
            'sliders',
            'categories',
            'courses',
            'settings',
            'enrolledCourseIds',
            'searchedCertNumber',
            'verifiedCertificate',
            'autoOpenCourseSlug'
        ));
    }
}
