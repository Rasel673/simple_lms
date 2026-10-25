<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactMessageController as AdminMessageController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\WebsiteContentController as AdminContentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Student\CertificateController as StudentCertificateController;
use App\Http\Controllers\Student\CourseLearningController as StudentLearningController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use App\Http\Controllers\Student\MyCoursesController as StudentMyCoursesController;
use App\Http\Controllers\Student\PaymentHistoryController as StudentPaymentHistoryController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| cPanel Setup & Maintenance Helper (For hosting without Terminal / SSH)
|--------------------------------------------------------------------------
*/
Route::get('/cpanel-setup', function (\Illuminate\Http\Request $request) {
    if ($request->query('token') !== 'setup_simple_lms_2026') {
        abort(403, 'Unauthorized access: Invalid or missing token.');
    }

    $results = [];

    // 1. Storage link
    try {
        Artisan::call('storage:link');
        $results['storage:link'] = trim(Artisan::output()) ?: 'Storage link created successfully!';
    } catch (\Throwable $e) {
        $results['storage:link'] = 'Note: ' . $e->getMessage();
    }

    // 2. Clear application caches
    try {
        Artisan::call('optimize:clear');
        $results['optimize:clear'] = trim(Artisan::output()) ?: 'All caches cleared successfully!';
    } catch (\Throwable $e) {
        $results['optimize:clear'] = 'Error: ' . $e->getMessage();
    }

    return response()->json([
        'success' => true,
        'message' => 'cPanel maintenance completed successfully!',
        'tasks' => $results
    ], 200, [], JSON_PRETTY_PRINT);
});


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses', fn() => redirect('/#courses-section'))->name('courses.index');
Route::get('/courses/{slug}', fn($slug) => redirect('/?course=' . $slug . '#courses-section'))->name('courses.show');
Route::get('/about', fn() => redirect('/#about-section'))->name('about');
Route::get('/contact', fn() => redirect('/#contact-section'))->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/verify-certificate/{number?}', function ($number = null) {
    return $number ? redirect('/?verify_number=' . $number . '#verify-section') : redirect('/#verify-section');
})->name('certificate.verify');
Route::get('/privacy-policy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/terms-and-conditions', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/terms-conditions', fn() => redirect()->route('legal.terms'));

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Checkout Routes
|--------------------------------------------------------------------------
*/
Route::middleware('student')->group(function () {
    Route::get('/checkout/{course:slug}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});

/*
|--------------------------------------------------------------------------
| Payment Gateway Callback Routes (CSRF exempt in bootstrap/app.php)
|--------------------------------------------------------------------------
*/
Route::prefix('payment/sslcommerz')->name('payment.sslcommerz.')->group(function () {
    Route::post('/success', [PaymentController::class, 'sslSuccess'])->name('success');
    Route::post('/fail', [PaymentController::class, 'sslFail'])->name('fail');
    Route::post('/cancel', [PaymentController::class, 'sslCancel'])->name('cancel');
    Route::post('/ipn', [PaymentController::class, 'sslIpn'])->name('ipn');
});

/*
|--------------------------------------------------------------------------
| Student Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware('student')->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-courses', [StudentMyCoursesController::class, 'index'])->name('my-courses');

    // Learning Interface
    Route::get('/learn/{course}/{lesson?}', [StudentLearningController::class, 'show'])->name('learning');
    Route::post('/learn/{course}/{lesson}/complete', [StudentLearningController::class, 'toggleComplete'])->name('learning.toggle');

    // Examination
    Route::get('/exams/{exam}', [StudentExamController::class, 'show'])->name('exam.show');
    Route::get('/exams/{exam}/start', [StudentExamController::class, 'start'])->name('exam.start');
    Route::post('/exams/{exam}/submit/{attempt}', [StudentExamController::class, 'submit'])->name('exam.submit');
    Route::get('/exams/{exam}/result/{attempt}', [StudentExamController::class, 'result'])->name('exam.result');

    // Certificates
    Route::get('/certificates', [StudentCertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate}', [StudentCertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [StudentCertificateController::class, 'download'])->name('certificates.download');

    // Payment History & Invoices
    Route::get('/payments', [StudentPaymentHistoryController::class, 'index'])->name('payments.index');
    Route::get('/payments/{order}', [StudentPaymentHistoryController::class, 'show'])->name('payments.show');
});

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Course Management
    Route::resource('courses', AdminCourseController::class);
    Route::patch('/courses/{course}/publish', [AdminCourseController::class, 'togglePublish'])->name('courses.publish');

    // Lesson Management
    Route::prefix('courses/{course}/lessons')->name('courses.lessons.')->group(function () {
        Route::get('/', [AdminLessonController::class, 'index'])->name('index');
        Route::get('/create', [AdminLessonController::class, 'create'])->name('create');
        Route::post('/', [AdminLessonController::class, 'store'])->name('store');
        Route::get('/{lesson}/edit', [AdminLessonController::class, 'edit'])->name('edit');
        Route::put('/{lesson}', [AdminLessonController::class, 'update'])->name('update');
        Route::delete('/{lesson}', [AdminLessonController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [AdminLessonController::class, 'reorder'])->name('reorder');
    });

    // Category Management
    Route::resource('categories', AdminCategoryController::class);

    // Exam Management
    Route::resource('exams', AdminExamController::class);
    Route::get('/exams/{exam}/questions', [AdminExamController::class, 'questions'])->name('exams.questions');
    Route::post('/exams/{exam}/questions', [AdminExamController::class, 'storeQuestion'])->name('exams.questions.store');
    Route::delete('/exams/{exam}/questions/{question}', [AdminExamController::class, 'destroyQuestion'])->name('exams.questions.destroy');

    // Student Management
    Route::get('/students', [AdminStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [AdminStudentController::class, 'show'])->name('students.show');

    // Order & Payment Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

    // Website Content Management
    Route::get('/content/settings', [AdminContentController::class, 'settings'])->name('content.settings');
    Route::post('/content/settings', [AdminContentController::class, 'updateSettings'])->name('content.settings.update');

    Route::get('/content/sliders', [AdminContentController::class, 'sliders'])->name('content.sliders');
    Route::post('/content/sliders', [AdminContentController::class, 'storeSlider'])->name('content.sliders.store');
    Route::put('/content/sliders/{slider}', [AdminContentController::class, 'updateSlider'])->name('content.sliders.update');
    Route::delete('/content/sliders/{slider}', [AdminContentController::class, 'destroySlider'])->name('content.sliders.destroy');

    // Contact Messages
    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

    /*
    |----------------------------------------------------------------------
    | School & Institutional Management Suite Routes (মূল ফিচারসমূহ)
    |----------------------------------------------------------------------
    */
    Route::prefix('school')->name('school.')->group(function () {
        // 1. Biometric / Fingerprint Attendance
        Route::get('/attendance', [\App\Http\Controllers\Admin\School\AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance/save', [\App\Http\Controllers\Admin\School\AttendanceController::class, 'save'])->name('attendance.save');
        Route::post('/attendance/sync', [\App\Http\Controllers\Admin\School\AttendanceController::class, 'syncBiometric'])->name('attendance.sync');

        // 2. Student Management & Promotion
        Route::get('/students/promotion', [\App\Http\Controllers\Admin\School\SchoolStudentController::class, 'promotion'])->name('students.promotion');
        Route::post('/students/promotion', [\App\Http\Controllers\Admin\School\SchoolStudentController::class, 'processPromotion'])->name('students.promotion.process');
        Route::resource('students', \App\Http\Controllers\Admin\School\SchoolStudentController::class);

        // 3. Staff & Teacher Management & Allocations
        Route::get('/staff/assignments', [\App\Http\Controllers\Admin\School\StaffController::class, 'assignments'])->name('staff.assignments');
        Route::post('/staff/assignments', [\App\Http\Controllers\Admin\School\StaffController::class, 'storeAssignment'])->name('staff.assignments.store');
        Route::delete('/staff/assignments/{assignment}', [\App\Http\Controllers\Admin\School\StaffController::class, 'deleteAssignment'])->name('staff.assignments.destroy');
        Route::resource('staff', \App\Http\Controllers\Admin\School\StaffController::class);

        // 4. Classes, Routine, Syllabus, Homework, Calendar
        Route::get('/routines', [\App\Http\Controllers\Admin\School\AcademicController::class, 'routines'])->name('academics.routines');
        Route::post('/routines', [\App\Http\Controllers\Admin\School\AcademicController::class, 'storeRoutine'])->name('academics.routines.store');
        Route::delete('/routines/{routine}', [\App\Http\Controllers\Admin\School\AcademicController::class, 'destroyRoutine'])->name('academics.routines.destroy');

        Route::get('/syllabi', [\App\Http\Controllers\Admin\School\AcademicController::class, 'syllabi'])->name('academics.syllabi');
        Route::post('/syllabi', [\App\Http\Controllers\Admin\School\AcademicController::class, 'storeSyllabus'])->name('academics.syllabi.store');
        Route::delete('/syllabi/{syllabus}', [\App\Http\Controllers\Admin\School\AcademicController::class, 'destroySyllabus'])->name('academics.syllabi.destroy');

        Route::get('/homework', [\App\Http\Controllers\Admin\School\AcademicController::class, 'homework'])->name('academics.homework');
        Route::post('/homework', [\App\Http\Controllers\Admin\School\AcademicController::class, 'storeHomework'])->name('academics.homework.store');
        Route::delete('/homework/{homework}', [\App\Http\Controllers\Admin\School\AcademicController::class, 'destroyHomework'])->name('academics.homework.destroy');

        Route::get('/calendar', [\App\Http\Controllers\Admin\School\AcademicController::class, 'calendar'])->name('academics.calendar');
        Route::post('/calendar', [\App\Http\Controllers\Admin\School\AcademicController::class, 'storeCalendar'])->name('academics.calendar.store');
        Route::delete('/calendar/{calendar}', [\App\Http\Controllers\Admin\School\AcademicController::class, 'destroyCalendar'])->name('academics.calendar.destroy');

        // 5. Exams, Marks Entry & Report Cards
        Route::get('/exams', [\App\Http\Controllers\Admin\School\ExamReportCardController::class, 'index'])->name('exams.index');
        Route::post('/exams', [\App\Http\Controllers\Admin\School\ExamReportCardController::class, 'storeExam'])->name('exams.store');
        Route::get('/exams/marks-entry', [\App\Http\Controllers\Admin\School\ExamReportCardController::class, 'marksEntry'])->name('exams.marks.entry');
        Route::post('/exams/marks-entry', [\App\Http\Controllers\Admin\School\ExamReportCardController::class, 'saveMarks'])->name('exams.marks.save');
        Route::get('/exams/tabulation', [\App\Http\Controllers\Admin\School\ExamReportCardController::class, 'tabulation'])->name('exams.tabulation');
        Route::get('/exams/report-card/{student}/{exam}', [\App\Http\Controllers\Admin\School\ExamReportCardController::class, 'reportCard'])->name('exams.report_card');

        // 6. Fees & Salary Management
        Route::get('/fees', [\App\Http\Controllers\Admin\School\SchoolFeeController::class, 'index'])->name('fees.index');
        Route::post('/fees/generate', [\App\Http\Controllers\Admin\School\SchoolFeeController::class, 'generateInvoices'])->name('fees.generate');
        Route::post('/fees/{invoice}/collect', [\App\Http\Controllers\Admin\School\SchoolFeeController::class, 'collectFee'])->name('fees.collect');
        Route::get('/fees/{invoice}/receipt', [\App\Http\Controllers\Admin\School\SchoolFeeController::class, 'printReceipt'])->name('fees.receipt');

        Route::get('/salaries', [\App\Http\Controllers\Admin\School\SchoolFeeController::class, 'salaries'])->name('fees.salaries');
        Route::post('/salaries/disburse', [\App\Http\Controllers\Admin\School\SchoolFeeController::class, 'disburseSalary'])->name('fees.salaries.disburse');

        // 7. Reports & Analytics
        Route::get('/reports', [\App\Http\Controllers\Admin\School\SchoolReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [\App\Http\Controllers\Admin\School\SchoolReportController::class, 'exportCsv'])->name('reports.export');
    });
});
