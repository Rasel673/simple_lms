<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Support\Str;

class LessonService
{
    public function createLesson(Course $course, array $data): Lesson
    {
        $data['course_id'] = $course->id;

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (!isset($data['order']) || $data['order'] <= 0) {
            $data['order'] = ($course->lessons()->max('order') ?? 0) + 1;
        }

        return Lesson::create($data);
    }

    public function updateLesson(Lesson $lesson, array $data): Lesson
    {
        if (!empty($data['title']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $lesson->update($data);
        return $lesson;
    }

    public function deleteLesson(Lesson $lesson): bool
    {
        return $lesson->delete();
    }

    public function reorderLessons(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            Lesson::where('id', $id)->update(['order' => $index + 1]);
        }
    }
}
