<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\CourseProgressService;
use Illuminate\Http\Request;

class CourseLearningController extends Controller
{
    protected CourseProgressService $progressService;

    public function __construct(CourseProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function show(Request $request, Course $course, ?Lesson $lesson = null)
    {
        $user = auth()->user();

        // Enforce authorization
        if (!$user->hasPurchasedCourse($course->id) && !$user->isAdmin()) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You must purchase this course to access the lessons.');
        }

        $lessons = $course->publishedLessons;

        if ($lessons->isEmpty()) {
            return redirect()->route('student.my-courses')
                ->with('info', 'This course does not currently have published lessons.');
        }

        // Default to requested lesson or the first incomplete lesson or first lesson
        if (!$lesson || $lesson->course_id !== $course->id) {
            $completedLessonIds = LessonProgress::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('is_completed', true)
                ->pluck('lesson_id')
                ->toArray();

            $nextIncomplete = $lessons->first(fn($l) => !in_array($l->id, $completedLessonIds));
            $currentLesson = $nextIncomplete ?: $lessons->first();
        } else {
            $currentLesson = $lesson;
        }

        // Find previous and next lesson
        $currentIndex = $lessons->search(fn($l) => $l->id === $currentLesson->id);
        $prevLesson = $currentIndex > 0 ? $lessons->get($currentIndex - 1) : null;
        $nextLesson = $currentIndex < ($lessons->count() - 1) ? $lessons->get($currentIndex + 1) : null;

        // Progress details
        $progress = $this->progressService->getCourseProgress($user->id, $course->id);
        $isCurrentCompleted = $currentLesson->isCompletedBy($user->id);

        return view('student.learning', compact(
            'course',
            'lessons',
            'currentLesson',
            'prevLesson',
            'nextLesson',
            'progress',
            'isCurrentCompleted'
        ));
    }

    /**
     * Mark lesson as completed on server and advance.
     */
    public function toggleComplete(Request $request, Course $course, Lesson $lesson)
    {
        $user = auth()->user();

        if (!$user->hasPurchasedCourse($course->id) && !$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $isCompleted = $this->progressService->toggleLessonProgress($user->id, $lesson->id);
        $progress = $this->progressService->getCourseProgress($user->id, $course->id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_completed' => $isCompleted,
                'progress' => $progress,
            ]);
        }

        return redirect()->back()->with('success', $isCompleted ? 'Lesson marked as completed!' : 'Lesson marked as incomplete.');
    }
}
