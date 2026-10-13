<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Services\CourseProgressService;
use Illuminate\Http\Request;

class MyCoursesController extends Controller
{
    protected CourseProgressService $progressService;

    public function __construct(CourseProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $enrollments = CourseEnrollment::with(['course.category', 'course.lessons'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        // Map progress
        $enrollments->getCollection()->transform(function ($enrollment) use ($user) {
            $enrollment->progressData = $this->progressService->getCourseProgress($user->id, $enrollment->course_id);
            return $enrollment;
        });

        return view('student.my-courses', compact('enrollments'));
    }
}
