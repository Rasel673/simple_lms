<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Services\ExamService;
use Illuminate\Http\Request;
use Exception;

class ExamController extends Controller
{
    protected ExamService $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function show(Exam $exam)
    {
        $user = auth()->user();

        // Enforce authorization: must be enrolled in the course or admin
        if (!$user->hasPurchasedCourse($exam->course_id) && !$user->isAdmin()) {
            return redirect()->route('courses.show', $exam->course->slug)
                ->with('error', 'You must be enrolled in this course to take the exam.');
        }

        $attempts = $exam->attemptsForUser($user->id)->latest()->get();
        $canAttempt = $exam->canAttempt($user->id);
        $hasPassed = $exam->hasPassed($user->id);

        return view('student.exam-intro', compact('exam', 'attempts', 'canAttempt', 'hasPassed'));
    }

    public function start(Exam $exam)
    {
        $user = auth()->user();

        if (!$user->hasPurchasedCourse($exam->course_id) && !$user->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        try {
            $attempt = $this->examService->startAttempt($user, $exam);

            // Fetch questions with options WITHOUT the is_correct column in payload
            $questions = $exam->questions()
                ->with(['options' => function ($q) {
                    $q->select('id', 'question_id', 'option_text');
                }])
                ->get();

            return view('student.exam-take', compact('exam', 'attempt', 'questions'));
        } catch (Exception $e) {
            return redirect()->route('student.exam.show', $exam->id)
                ->with('error', $e->getMessage());
        }
    }

    public function submit(Request $request, Exam $exam, ExamAttempt $attempt)
    {
        $user = auth()->user();

        if ($attempt->user_id !== $user->id || $attempt->exam_id !== $exam->id) {
            abort(403, 'Invalid attempt.');
        }

        $answers = $request->input('answers', []); // [ question_id => selected_option_id ]

        $attempt = $this->examService->submitAttempt($attempt, $answers);

        return redirect()->route('student.exam.result', ['exam' => $exam->id, 'attempt' => $attempt->id])
            ->with('success', 'Your exam answers have been submitted and evaluated!');
    }

    public function result(Exam $exam, ExamAttempt $attempt)
    {
        $user = auth()->user();

        if ($attempt->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized to view this attempt result.');
        }

        $attempt->load(['answers.question.options', 'answers.selectedOption']);

        return view('student.exam-result', compact('exam', 'attempt'));
    }
}
