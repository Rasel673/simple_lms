<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Services\CourseProgressService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected CourseProgressService $progressService;

    public function __construct(CourseProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        $enrollments = CourseEnrollment::with(['course.lessons', 'course.category'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $totalPurchased = $enrollments->count();
        $completedCoursesCount = $enrollments->where('status', 'completed')->count();
        $inProgressCoursesCount = $totalPurchased - $completedCoursesCount;
        $certificatesCount = $user->certificates()->count();

        // Attach progress details to each course
        $coursesWithProgress = $enrollments->map(function ($enrollment) use ($user) {
            $progressData = $this->progressService->getCourseProgress($user->id, $enrollment->course_id);
            return [
                'enrollment' => $enrollment,
                'course' => $enrollment->course,
                'progress' => $progressData,
            ];
        });

        return view('student.dashboard', compact(
            'totalPurchased',
            'completedCoursesCount',
            'inProgressCoursesCount',
            'certificatesCount',
            'coursesWithProgress'
        ));
    }
}
