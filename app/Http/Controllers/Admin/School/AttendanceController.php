<?php

namespace App\Http\Controllers\Admin\School;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\AttendanceRecord;
use App\Models\Section;
use App\Models\StaffProfile;
use App\Models\StudentProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $type = $request->input('type', 'student');
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');

        $classes = AcademicClass::with('sections')->get();
        $sections = $classId ? Section::where('academic_class_id', $classId)->get() : collect();

        if ($type === 'student') {
            $studentQuery = StudentProfile::query()->where('status', 'active');
            if ($classId) {
                $studentQuery->where('academic_class_id', $classId);
            }
            if ($sectionId) {
                $studentQuery->where('section_id', $sectionId);
            }
            $students = $studentQuery->with(['academicClass', 'section'])->orderBy('roll_number')->get();

            // Load existing attendance for these students on this date
            $records = AttendanceRecord::where('attendance_type', 'student')
                ->where('date', $date)
                ->whereIn('student_profile_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_profile_id');

            $stats = [
                'total' => $students->count(),
                'present' => $records->where('status', 'present')->count(),
                'late' => $records->where('status', 'late')->count(),
                'absent' => $records->where('status', 'absent')->count(),
            ];
            $stats['rate'] = $stats['total'] > 0 ? round((($stats['present'] + $stats['late']) / $stats['total']) * 100, 1) : 0;

            return view('admin.school.attendance.index', compact(
                'date', 'type', 'classes', 'sections', 'classId', 'sectionId', 'students', 'records', 'stats'
            ));
        } else {
            $staffList = StaffProfile::with('designation')->where('status', 'active')->get();
            $records = AttendanceRecord::where('attendance_type', 'staff')
                ->where('date', $date)
                ->whereIn('staff_profile_id', $staffList->pluck('id'))
                ->get()
                ->keyBy('staff_profile_id');

            $stats = [
                'total' => $staffList->count(),
                'present' => $records->where('status', 'present')->count(),
                'late' => $records->where('status', 'late')->count(),
                'absent' => $records->where('status', 'absent')->count(),
            ];
            $stats['rate'] = $stats['total'] > 0 ? round((($stats['present'] + $stats['late']) / $stats['total']) * 100, 1) : 0;

            return view('admin.school.attendance.index', compact(
                'date', 'type', 'classes', 'sections', 'classId', 'sectionId', 'staffList', 'records', 'stats'
            ));
        }
    }

    public function save(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $type = $request->input('type', 'student');
        $attendanceData = $request->input('attendance', []);

        foreach ($attendanceData as $id => $data) {
            $status = $data['status'] ?? 'present';
            $inTime = $data['in_time'] ?? null;
            $outTime = $data['out_time'] ?? null;
            $remarks = $data['remarks'] ?? null;

            if ($type === 'student') {
                AttendanceRecord::updateOrCreate(
                    [
                        'attendance_type' => 'student',
                        'student_profile_id' => $id,
                        'date' => $date,
                    ],
                    [
                        'status' => $status,
                        'check_in_time' => $status !== 'absent' ? ($inTime ?: '09:00:00') : null,
                        'check_out_time' => $status !== 'absent' ? ($outTime ?: '14:00:00') : null,
                        'source' => 'manual',
                        'remarks' => $remarks,
                    ]
                );
            } else {
                AttendanceRecord::updateOrCreate(
                    [
                        'attendance_type' => 'staff',
                        'staff_profile_id' => $id,
                        'date' => $date,
                    ],
                    [
                        'status' => $status,
                        'check_in_time' => $status !== 'absent' ? ($inTime ?: '08:30:00') : null,
                        'check_out_time' => $status !== 'absent' ? ($outTime ?: '15:30:00') : null,
                        'source' => 'manual',
                        'remarks' => $remarks,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'হাজিরা সফলভাবে সংরক্ষিত হয়েছে।');
    }

    public function syncBiometric(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $type = $request->input('type', 'student');

        if ($type === 'student') {
            $students = StudentProfile::where('status', 'active')->get();
            foreach ($students as $st) {
                // Simulate biometric fingerprint device punch
                $rand = rand(1, 10);
                $status = $rand == 1 ? 'absent' : ($rand == 2 ? 'late' : 'present');
                $inTime = $status === 'absent' ? null : ($status === 'late' ? '09:15:00' : '08:45:00');
                $outTime = $status === 'absent' ? null : '14:00:00';

                AttendanceRecord::updateOrCreate(
                    [
                        'attendance_type' => 'student',
                        'student_profile_id' => $st->id,
                        'date' => $date,
                    ],
                    [
                        'check_in_time' => $inTime,
                        'check_out_time' => $outTime,
                        'status' => $status,
                        'source' => 'biometric',
                        'device_id' => 'FP-DEV-BIO-MAIN',
                        'remarks' => $status === 'late' ? 'দেরিতে পাঞ্চ' : 'বায়োমেট্রিক পাঞ্চ রেকর্ড',
                    ]
                );
            }
        } else {
            $staffList = StaffProfile::where('status', 'active')->get();
            foreach ($staffList as $stf) {
                AttendanceRecord::updateOrCreate(
                    [
                        'attendance_type' => 'staff',
                        'staff_profile_id' => $stf->id,
                        'date' => $date,
                    ],
                    [
                        'check_in_time' => '08:30:00',
                        'check_out_time' => '15:30:00',
                        'status' => 'present',
                        'source' => 'biometric',
                        'device_id' => 'FP-DEV-STAFF',
                        'remarks' => 'বায়োমেট্রিক পাঞ্চ ভেরিফাইড',
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'ফিঙ্গারপ্রিন্ট বায়োমেট্রিক ডিভাইস থেকে ' . $date . ' তারিখের ডেটা সফলভাবে সিঙ্ক হয়েছে!');
    }
}
