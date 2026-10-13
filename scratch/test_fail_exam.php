<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();
$app->instance('request', \Illuminate\Http\Request::create('/'));

use App\Models\User;
use App\Models\Course;
use App\Services\ExamService;
use App\Services\CourseProgressService;

$student = User::where('role', 'student')->first();
$course = Course::first();
$exam = $course->finalExam;

$examService = app(ExamService::class);
$progressService = app(CourseProgressService::class);

// Start attempt with all WRONG answers
$attempt = $examService->startAttempt($student, $exam);
$wrongAnswers = [];
foreach ($exam->questions as $q) {
    $wrongAnswers[$q->id] = 999999; // Non-existent option
}

$evaluated = $examService->submitAttempt($attempt, $wrongAnswers);
echo "Failed Attempt Result: Score {$evaluated->score_percentage}%, Passed: " . ($evaluated->is_passed ? 'YES' : 'NO') . "\n";

if ($evaluated->is_passed === false && $evaluated->score_percentage == 0) {
    echo "✓ PASS: Failed exam correctly evaluated as failed with 0% score.\n";
} else {
    echo "✗ FAIL: Failed exam miscalculated.\n";
    exit(1);
}
