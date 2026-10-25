<?php

namespace App\Http\Controllers\Admin\School;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AttendanceRecord;
use App\Models\SchoolExam;
use App\Models\StaffProfile;
use App\Models\StudentFeeInvoice;
use App\Models\StudentMark;
use App\Models\StudentProfile;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolReportController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'overview');

        // Overview statistics
        $totalStudents = StudentProfile::count();
        $totalTeachers = StaffProfile::count();
        $totalClasses = AcademicClass::count();
        $totalInvoiced = StudentFeeInvoice::sum('amount');
        $totalCollected = StudentFeeInvoice::sum('paid_amount');
        $totalDues = max(0, $totalInvoiced - $totalCollected);

        // Attendance stats for today
        $today = Carbon::today()->toDateString();
        $studentAttendanceToday = [
            'present' => AttendanceRecord::where('attendance_type', 'student')->where('date', $today)->where('status', 'present')->count(),
            'late' => AttendanceRecord::where('attendance_type', 'student')->where('date', $today)->where('status', 'late')->count(),
            'absent' => AttendanceRecord::where('attendance_type', 'student')->where('date', $today)->where('status', 'absent')->count(),
        ];

        // Class wise strength
        $classStrengths = AcademicClass::withCount('students')->get();

        // Exam pass rates
        $exams = SchoolExam::all();
        $examStats = [];
        foreach ($exams as $ex) {
            $totalMarks = StudentMark::where('school_exam_id', $ex->id)->count();
            $passedMarks = StudentMark::where('school_exam_id', $ex->id)->where('grade', '!=', 'F')->count();
            $examStats[$ex->id] = [
                'name' => $ex->name,
                'total' => $totalMarks,
                'passed' => $passedMarks,
                'rate' => $totalMarks > 0 ? round(($passedMarks / $totalMarks) * 100, 1) : 0,
            ];
        }

        // Fee collection by fee type
        $feeByType = StudentFeeInvoice::select('fee_types.name', DB::raw('SUM(student_fee_invoices.paid_amount) as total_paid'), DB::raw('SUM(student_fee_invoices.amount) as total_amount'))
            ->join('fee_types', 'student_fee_invoices.fee_type_id', '=', 'fee_types.id')
            ->groupBy('fee_types.name')
            ->get();

        $schoolName = WebsiteSetting::get('site_name', 'আদর্শ উচ্চ বিদ্যালয় ও কলেজ');

        return view('admin.school.reports.index', compact(
            'tab', 'totalStudents', 'totalTeachers', 'totalClasses', 'totalInvoiced', 'totalCollected', 'totalDues',
            'studentAttendanceToday', 'classStrengths', 'examStats', 'feeByType', 'schoolName'
        ));
    }

    public function exportCsv(Request $request)
    {
        $type = $request->input('type', 'students');

        if ($type === 'students') {
            $students = StudentProfile::with('academicClass', 'section')->get();
            $filename = "students_report_" . date('Y-m-d') . ".csv";

            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function () use ($students) {
                $file = fopen('php://output', 'w');
                // UTF-8 BOM for Excel Bengali character compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, ['ভর্তি নং', 'নাম', 'শ্রেণি', 'শাখা', 'রোল', 'গ্রুপ', 'অভিভাবক', 'ফোন', 'স্ট্যাটাস']);

                foreach ($students as $st) {
                    fputcsv($file, [
                        $st->admission_number,
                        $st->name,
                        $st->academicClass ? $st->academicClass->name : '',
                        $st->section ? $st->section->name : '',
                        $st->roll_number,
                        $st->group,
                        $st->guardian_name,
                        $st->guardian_phone,
                        $st->status,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        if ($type === 'fees') {
            $invoices = StudentFeeInvoice::with('student', 'feeType')->get();
            $filename = "fees_report_" . date('Y-m-d') . ".csv";

            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
            ];

            $callback = function () use ($invoices) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, ['ইনভয়েস নং', 'শিক্ষার্থীর নাম', 'ফি টাইপ', 'মোট টাকা', 'পরিশোধিত', 'বকেয়া', 'স্ট্যাটাস', 'তারিখ']);

                foreach ($invoices as $inv) {
                    fputcsv($file, [
                        $inv->invoice_number,
                        $inv->student ? $inv->student->name : '',
                        $inv->feeType ? $inv->feeType->name : '',
                        $inv->amount,
                        $inv->paid_amount,
                        $inv->amount - $inv->paid_amount,
                        $inv->status,
                        $inv->payment_date ?: $inv->due_date,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back();
    }
}
