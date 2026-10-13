<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamRequest;
use App\Http\Requests\QuestionRequest;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Services\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected ExamService $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index(Request $request)
    {
        $exams = Exam::with(['course', 'questions'])
            ->latest()
            ->paginate(12);

        return view('admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();
        return view('admin.exams.create', compact('courses'));
    }

    public function store(ExamRequest $request)
    {
        $course = Course::findOrFail($request->course_id);
        $exam = $this->examService->createExam($course, $request->validated());

        return redirect()->route('admin.exams.questions', $exam->id)
            ->with('success', "Exam created. Now add questions for this exam.");
    }

    public function edit(Exam $exam)
    {
        $courses = Course::orderBy('title')->get();
        return view('admin.exams.edit', compact('exam', 'courses'));
    }

    public function update(ExamRequest $request, Exam $exam)
    {
        $this->examService->updateExam($exam, $request->validated());

        return redirect()->route('admin.exams.index')
            ->with('success', "Exam '{$exam->title}' updated successfully.");
    }

    public function destroy(Exam $exam)
    {
        $this->examService->deleteExam($exam);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Exam removed successfully.');
    }

    /**
     * Questions management for a specific exam.
     */
    public function questions(Exam $exam)
    {
        $exam->load(['questions.options', 'course']);
        return view('admin.exams.questions', compact('exam'));
    }

    public function storeQuestion(QuestionRequest $request, Exam $exam)
    {
        $validated = $request->validated();

        $this->examService->addQuestion(
            $exam,
            [
                'question_text' => $validated['question_text'],
                'explanation' => $validated['explanation'] ?? null,
                'marks' => $validated['marks'] ?? 1,
                'order' => $validated['order'] ?? null,
            ],
            $validated['options'],
            (int) $validated['correct_option']
        );

        return redirect()->route('admin.exams.questions', $exam->id)
            ->with('success', 'Question added successfully.');
    }

    public function destroyQuestion(Exam $exam, Question $question)
    {
        $this->examService->deleteQuestion($question);

        return redirect()->route('admin.exams.questions', $exam->id)
            ->with('success', 'Question removed successfully.');
    }
}
