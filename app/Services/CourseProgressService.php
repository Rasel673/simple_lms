<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\DB;

class CourseProgressService
{
    public function getCourseProgress(int $userId, int $courseId): array
    {
        $course = Course::with(['publishedLessons', 'finalExam'])->findOrFail($courseId);
        $totalLessons = $course->publishedLessons->count();

        $completedLessonsCount = LessonProgress::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('is_completed', true)
            ->count();

        $progressPercentage = $totalLessons > 0 ? min(100, round(($completedLessonsCount / $totalLessons) * 100)) : 100;

        $hasRequiredExam = $course->finalExam !== null && $course->finalExam->is_published;
        $hasPassedExam = true;

        if ($hasRequiredExam) {
            $hasPassedExam = $course->finalExam->hasPassed($userId);
        }

        $isAllLessonsCompleted = ($totalLessons === 0) || ($completedLessonsCount >= $totalLessons);
        $isCompleted = $isAllLessonsCompleted && $hasPassedExam;

        return [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessonsCount,
            'progress_percentage' => $progressPercentage,
            'is_all_lessons_completed' => $isAllLessonsCompleted,
            'has_required_exam' => $hasRequiredExam,
            'has_passed_exam' => $hasPassedExam,
            'is_completed' => $isCompleted,
            'final_exam' => $course->finalExam,
        ];
    }

    public function markLessonComplete(int $userId, int $lessonId): LessonProgress
    {
        $lesson = Lesson::findOrFail($lessonId);

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => $userId,
                'lesson_id' => $lessonId,
            ],
            [
                'course_id' => $lesson->course_id,
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        $this->checkAndCompleteCourse($userId, $lesson->course_id);

        return $progress;
    }

    public function toggleLessonProgress(int $userId, int $lessonId): bool
    {
        $lesson = Lesson::findOrFail($lessonId);
        $progress = LessonProgress::where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();

        if ($progress && $progress->is_completed) {
            $progress->update([
                'is_completed' => false,
                'completed_at' => null,
            ]);

            // Revert enrollment completed status if completed
            CourseEnrollment::where('user_id', $userId)
                ->where('course_id', $lesson->course_id)
                ->where('status', 'completed')
                ->update([
                    'status' => 'active',
                    'completed_at' => null,
                ]);

            return false;
        } else {
            $this->markLessonComplete($userId, $lessonId);
            return true;
        }
    }

    public function checkAndCompleteCourse(int $userId, int $courseId): bool
    {
        $progress = $this->getCourseProgress($userId, $courseId);

        if ($progress['is_completed']) {
            DB::transaction(function () use ($userId, $courseId) {
                // Update enrollment
                CourseEnrollment::where('user_id', $userId)
                    ->where('course_id', $courseId)
                    ->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                // Automatically generate certificate
                app(CertificateService::class)->getOrCreateCertificate($userId, $courseId);
            });

            return true;
        }

        return false;
    }
}
