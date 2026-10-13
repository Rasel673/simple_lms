<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();
$app->instance('request', \Illuminate\Http\Request::create('/'));

use App\Models\User;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\CourseEnrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamAttempt;
use App\Models\Certificate;
use App\Models\ContactMessage;
use App\Services\CourseProgressService;
use App\Services\CertificateService;
use App\Services\ExamService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\ContactMessageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

echo "=== STARTING COMPREHENSIVE LMS END-TO-END VALIDATION ===\n\n";

$passCount = 0;
$failCount = 0;

function assertCondition($name, $condition, $details = '') {
    global $passCount, $failCount;
    if ($condition) {
        echo "✓ PASS: $name\n";
        $passCount++;
    } else {
        echo "✗ FAIL: $name - $details\n";
        $failCount++;
    }
}

// 1. Check users
$admin = User::where('role', 'admin')->first();
$student = User::where('role', 'student')->first();
assertCondition("Admin user exists", $admin !== null, "Admin not found in DB");
assertCondition("Student user exists", $student !== null, "Student not found in DB");

// 2. Check courses and lessons
$course = Course::with(['publishedLessons', 'finalExam.questions.options'])->first();
assertCondition("Course exists with slug '{$course?->slug}'", $course !== null);
assertCondition("Course has published lessons", $course && $course->publishedLessons->count() > 0, "Lessons count: " . ($course ? $course->publishedLessons->count() : 0));
assertCondition("Course has final exam", $course && $course->finalExam !== null);

// 3. Test Contact Form Submission via Service
echo "\n--- Testing Contact Form Submission ---\n";
$contactService = app(ContactMessageService::class);
$contactMsg = $contactService->storeMessage([
    'name' => 'John Tester',
    'email' => 'john@test.com',
    'subject' => 'Inquiry about Web Development course',
    'message' => 'Hello, is this course suitable for beginners with no coding experience?'
]);
assertCondition("Contact message stored in database", $contactMsg !== null && $contactMsg->subject === 'Inquiry about Web Development course');

// 4. Test Checkout & Payment Simulation
echo "\n--- Testing Checkout & Enrollment Flow ---\n";
Auth::login($student);

// Clean previous enrollments for this test student and course to have clean run
CourseEnrollment::where('user_id', $student->id)->where('course_id', $course->id)->delete();
Certificate::where('user_id', $student->id)->where('course_id', $course->id)->delete();
LessonProgress::where('user_id', $student->id)->where('course_id', $course->id)->delete();
ExamAttempt::where('user_id', $student->id)->where('exam_id', $course->finalExam->id)->delete();

$orderService = app(OrderService::class);
$paymentService = app(PaymentService::class);

$order = $orderService->createOrderForCourse($student, $course, [
    'billing_name' => $student->name,
    'billing_email' => $student->email,
    'billing_phone' => '01700000000'
], 'test_gateway');
assertCondition("Order created with pending status", $order && $order->payment_status === 'pending');

// Test payment processing via test gateway
$payResult = $paymentService->processTestGateway($order);
assertCondition("Payment processed successfully via test simulator", $payResult['status'] === 'success');
assertCondition("Order marked as paid", $order->fresh()->payment_status === 'paid');

$isEnrolled = CourseEnrollment::where('user_id', $student->id)->where('course_id', $course->id)->exists();
assertCondition("Student is successfully enrolled in course", $isEnrolled);

// 5. Test Learning & Lesson Completion
echo "\n--- Testing Lesson Progress & Learning Tracking ---\n";
$progressService = app(CourseProgressService::class);
$lessons = $course->publishedLessons;
$firstLesson = $lessons->first();

// Complete the first lesson
$progressService->markLessonComplete($student->id, $firstLesson->id);
$progressStats = $progressService->getCourseProgress($student->id, $course->id);
assertCondition("First lesson marked completed", $progressStats['completed_lessons'] >= 1);
echo "   Progress stats: {$progressStats['completed_lessons']}/{$progressStats['total_lessons']} ({$progressStats['progress_percentage']}%)\n";

// Complete all remaining lessons
foreach ($lessons as $lesson) {
    $progressService->markLessonComplete($student->id, $lesson->id);
}
$fullLessonStats = $progressService->getCourseProgress($student->id, $course->id);
assertCondition("All lessons completed (100% video progress)", $fullLessonStats['progress_percentage'] == 100);

// Check that course is NOT completed yet because final exam is not passed
assertCondition("Course NOT completed before passing final exam", $fullLessonStats['is_completed'] === false);

// 6. Test Exam Submission & Scoring
echo "\n--- Testing MCQ Examination Flow ---\n";
$examService = app(ExamService::class);
$exam = $course->finalExam;
$questions = $exam->questions()->with('options')->get();
assertCondition("Exam has questions configured", $questions->count() > 0, "Questions: " . $questions->count());

$attempt = $examService->startAttempt($student, $exam);
assertCondition("Exam attempt initiated", $attempt && $attempt->attempt_number >= 1);

// Prepare 100% correct answers
$answers = [];
foreach ($questions as $q) {
    $correctOption = $q->options->where('is_correct', true)->first();
    if ($correctOption) {
        $answers[$q->id] = $correctOption->id;
    }
}

$evaluatedAttempt = $examService->submitAttempt($attempt, $answers);
assertCondition("Exam attempt evaluated on server", $evaluatedAttempt->completed_at !== null);
assertCondition("Exam passed with required score", $evaluatedAttempt->is_passed === true);
echo "   Exam result: Score {$evaluatedAttempt->score_percentage}% ({$evaluatedAttempt->correct_answers}/{$evaluatedAttempt->total_questions} correct), Passed: " . ($evaluatedAttempt->is_passed ? 'YES' : 'NO') . "\n";

// Now check if course completion requirements are met!
$allDoneAfterExam = $progressService->getCourseProgress($student->id, $course->id);
assertCondition("Course is fully completed after 100% lessons + passed exam", $allDoneAfterExam['is_completed'] === true);

// 7. Test Certificate Generation & PDF
echo "\n--- Testing Certificate Generation & Verification ---\n";
$certService = app(CertificateService::class);
$certificate = Certificate::where('user_id', $student->id)->where('course_id', $course->id)->first();
if (!$certificate) {
    $certificate = $certService->getOrCreateCertificate($student->id, $course->id);
}
assertCondition("Certificate generated with unique number", $certificate !== null && !empty($certificate->certificate_number));
echo "   Generated Certificate Number: " . $certificate->certificate_number . "\n";

// Test duplicate prevention
$cert2 = $certService->getOrCreateCertificate($student->id, $course->id);
assertCondition("Duplicate certificate prevented (same ID returned)", $certificate->id === $cert2->id);

// Test PDF Generation
$pdf = $certService->generatePdf($certificate);
$pdfOutput = $pdf->output();
assertCondition("PDF certificate generated successfully as raw stream", !empty($pdfOutput) && strlen($pdfOutput) > 1000);
echo "   Generated PDF size: " . strlen($pdfOutput) . " bytes\n";

// Test Public Certificate Verification
$verificationReq = Request::create('/verify-certificate/' . $certificate->certificate_number, 'GET');
$verificationResp = $kernel->handle($verificationReq);
assertCondition("Public certificate verification endpoint returns HTTP 200", $verificationResp->getStatusCode() === 200);

// 8. Test Admin Panel Routes
echo "\n--- Testing Admin Panel Security and Pages ---\n";
Auth::login($admin);

$adminRoutes = [
    '/admin/dashboard',
    '/admin/courses',
    '/admin/courses/' . $course->id . '/edit',
    '/admin/courses/' . $course->id . '/lessons',
    '/admin/categories',
    '/admin/exams',
    '/admin/exams/' . $exam->id . '/questions',
    '/admin/students',
    '/admin/students/' . $student->id,
    '/admin/orders',
    '/admin/orders/' . $order->id,
    '/admin/content/settings',
    '/admin/content/sliders',
    '/admin/messages',
];

foreach ($adminRoutes as $adminUri) {
    $req = Request::create($adminUri, 'GET');
    $resp = $kernel->handle($req);
    $status = $resp->getStatusCode();
    assertCondition("Admin route $adminUri returns HTTP 200", $status === 200, "Received HTTP $status");
}

// 9. Test Student Panel Routes
echo "\n--- Testing Student Panel Routes ---\n";
Auth::login($student);

$studentRoutes = [
    '/student/dashboard',
    '/student/my-courses',
    '/student/learn/' . $course->id,
    '/student/exams/' . $exam->id,
    '/student/certificates',
    '/student/certificates/' . $certificate->id,
];

foreach ($studentRoutes as $studentUri) {
    $req = Request::create($studentUri, 'GET');
    $resp = $kernel->handle($req);
    $status = $resp->getStatusCode();
    assertCondition("Student route $studentUri returns HTTP 200", $status === 200, "Received HTTP $status");
}

// 10. Test Unauthorized Access Protection
echo "\n--- Testing Security: Student Cannot Access Admin Panel ---\n";
Auth::login($student);
$unauthReq = Request::create('/admin/dashboard', 'GET');
$unauthResp = $kernel->handle($unauthReq);
// Should redirect (302) with error
assertCondition("Student blocked from Admin Panel", in_array($unauthResp->getStatusCode(), [302, 403]), "Status: " . $unauthResp->getStatusCode());

echo "\n=======================================================\n";
echo "SUMMARY: Passed: $passCount, Failed: $failCount\n";
echo "=======================================================\n";

if ($failCount > 0) {
    exit(1);
}
exit(0);
