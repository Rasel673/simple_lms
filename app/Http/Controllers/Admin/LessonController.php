<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\LessonService;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    protected LessonService $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    public function index(Course $course)
    {
        $lessons = $course->lessons()->get();
        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    public function create(Course $course)
    {
        $nextOrder = ($course->lessons()->max('order') ?? 0) + 1;
        return view('admin.lessons.create', compact('course', 'nextOrder'));
    }

    public function store(LessonRequest $request, Course $course)
    {
        $this->lessonService->createLesson($course, $request->validated());

        return redirect()->route('admin.courses.lessons.index', $course->id)
            ->with('success', 'Lesson added successfully.');
    }

    public function edit(Course $course, Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    public function update(LessonRequest $request, Course $course, Lesson $lesson)
    {
        $this->lessonService->updateLesson($lesson, $request->validated());

        return redirect()->route('admin.courses.lessons.index', $course->id)
            ->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $this->lessonService->deleteLesson($lesson);

        return redirect()->route('admin.courses.lessons.index', $course->id)
            ->with('success', 'Lesson removed successfully.');
    }

    public function reorder(Request $request, Course $course)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:lessons,id'],
        ]);

        $this->lessonService->reorderLessons($request->input('order'));

        return response()->json(['success' => true]);
    }
}
