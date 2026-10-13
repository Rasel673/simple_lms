<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAttemptAnswer;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class ExamService
{
    public function createExam(Course $course, array $data): Exam
    {
        $data['course_id'] = $course->id;
        return Exam::create($data);
    }

    public function updateExam(Exam $exam, array $data): Exam
    {
        $exam->update($data);
        return $exam;
    }

    public function deleteExam(Exam $exam): bool
    {
        return $exam->delete();
    }

    public function addQuestion(Exam $exam, array $questionData, array $optionsData, int $correctOptionIndex): Question
    {
        return DB::transaction(function () use ($exam, $questionData, $optionsData, $correctOptionIndex) {
            $questionData['exam_id'] = $exam->id;
            if (!isset($questionData['order']) || $questionData['order'] <= 0) {
                $questionData['order'] = ($exam->questions()->max('order') ?? 0) + 1;
            }

            $question = Question::create($questionData);

            foreach ($optionsData as $index => $optionText) {
                if (trim($optionText) === '') {
                    continue;
                }

                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => trim($optionText),
                    'is_correct' => ($index === $correctOptionIndex),
                ]);
            }

            return $question;
        });
    }

    public function updateQuestion(Question $question, array $questionData, array $optionsData, int $correctOptionIndex): Question
    {
        return DB::transaction(function () use ($question, $questionData, $optionsData, $correctOptionIndex) {
            $question->update($questionData);

            // Delete old options and recreate
            $question->options()->delete();

            foreach ($optionsData as $index => $optionText) {
                if (trim($optionText) === '') {
                    continue;
                }

                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => trim($optionText),
                    'is_correct' => ($index === $correctOptionIndex),
                ]);
            }

            return $question;
        });
    }

    public function deleteQuestion(Question $question): bool
    {
        return $question->delete();
    }

    /**
     * Start an exam attempt for student.
     */
    public function startAttempt(User $user, Exam $exam): ExamAttempt
    {
        if (!$exam->canAttempt($user->id)) {
            throw new Exception('Maximum attempt limit reached for this examination.');
        }

        $nextAttemptNumber = ($exam->attemptsForUser($user->id)->max('attempt_number') ?? 0) + 1;

        return ExamAttempt::create([
            'user_id' => $user->id,
            'exam_id' => $exam->id,
            'attempt_number' => $nextAttemptNumber,
            'started_at' => now(),
        ]);
    }

    /**
     * Submit answers and calculate score on the server.
     * Answers format: [ question_id => selected_option_id ]
     */
    public function submitAttempt(ExamAttempt $attempt, array $answers): ExamAttempt
    {
        if ($attempt->completed_at) {
            return $attempt; // already submitted
        }

        return DB::transaction(function () use ($attempt, $answers) {
            $exam = $attempt->exam;
            $questions = $exam->questions()->with('options')->get();

            $totalQuestions = $questions->count();
            $correctCount = 0;
            $incorrectCount = 0;

            foreach ($questions as $question) {
                $rawSelectedId = isset($answers[$question->id]) ? (int) $answers[$question->id] : null;
                $matchedOption = $rawSelectedId ? $question->options->firstWhere('id', $rawSelectedId) : null;
                $validOptionId = $matchedOption ? $matchedOption->id : null;

                $correctOption = $question->options->firstWhere('is_correct', true);
                $isCorrect = false;

                if ($validOptionId && $correctOption && $correctOption->id === $validOptionId) {
                    $isCorrect = true;
                    $correctCount++;
                } else {
                    $incorrectCount++;
                }

                ExamAttemptAnswer::create([
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option_id' => $validOptionId,
                    'is_correct' => $isCorrect,
                ]);
            }

            $scorePercentage = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100, 2) : 0;
            $isPassed = $scorePercentage >= $exam->passing_percentage;

            $attempt->update([
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctCount,
                'incorrect_answers' => $incorrectCount,
                'score_percentage' => $scorePercentage,
                'is_passed' => $isPassed,
                'completed_at' => now(),
            ]);

            // If passed, check if course progress is now 100% and can issue certificate
            if ($isPassed) {
                app(CourseProgressService::class)->checkAndCompleteCourse($attempt->user_id, $exam->course_id);
            }

            return $attempt;
        });
    }
}
