<?php

namespace App\Services;

use App\Models\Course;
use App\Support\PublicImageStorage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CourseService
{
    public function getPublishedCourses(?int $categoryId = null, ?string $search = null, int $perPage = 9): LengthAwarePaginator
    {
        $query = Course::query()
            ->with(['category', 'lessons'])
            ->published();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function getCourseBySlug(string $slug): Course
    {
        return Course::where('slug', $slug)
            ->with(['category', 'publishedLessons', 'exams' => function ($q) {
                $q->where('is_published', true);
            }])
            ->firstOrFail();
    }

    public function getAdminCourses(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Course::query()->with(['category', 'lessons', 'enrollments']);

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function createCourse(array $data, $thumbnailFile = null): Course
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $count = 1;
        while (Course::where('slug', $data['slug'])->exists()) {
            $data['slug'] = "{$originalSlug}-" . $count++;
        }

        if ($thumbnailFile) {
            $path = PublicImageStorage::store($thumbnailFile, 'courses');
            $data['thumbnail'] = $path;
        }

        if (isset($data['learning_outcomes']) && is_string($data['learning_outcomes'])) {
            $lines = array_filter(array_map('trim', explode("\n", $data['learning_outcomes'])));
            $data['learning_outcomes'] = array_values($lines);
        }

        return Course::create($data);
    }

    public function updateCourse(Course $course, array $data, $thumbnailFile = null): Course
    {
        if (!empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
            // Ensure unique slug except current
            $originalSlug = $data['slug'];
            $count = 1;
            while (Course::where('slug', $data['slug'])->where('id', '!=', $course->id)->exists()) {
                $data['slug'] = "{$originalSlug}-" . $count++;
            }
        }

        if ($thumbnailFile) {
            PublicImageStorage::delete($course->thumbnail);
            $data['thumbnail'] = PublicImageStorage::store($thumbnailFile, 'courses');
        }

        if (isset($data['learning_outcomes']) && is_string($data['learning_outcomes'])) {
            $lines = array_filter(array_map('trim', explode("\n", $data['learning_outcomes'])));
            $data['learning_outcomes'] = array_values($lines);
        }

        $course->update($data);
        return $course;
    }

    public function deleteCourse(Course $course): bool
    {
        PublicImageStorage::delete($course->thumbnail);

        return $course->delete();
    }

    public function togglePublish(Course $course): Course
    {
        $course->status = $course->status === 'published' ? 'draft' : 'published';
        $course->save();
        return $course;
    }
}
