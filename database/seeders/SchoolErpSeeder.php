<?php

namespace Database\Seeders;

use App\Models\AcademicCalendar;
use App\Models\AcademicClass;
use App\Models\AttendanceRecord;
use App\Models\ClassRoutine;
use App\Models\Designation;
use App\Models\FeeType;
use App\Models\GradeRule;
use App\Models\Homework;
use App\Models\SchoolExam;
use App\Models\Section;
use App\Models\StaffProfile;
use App\Models\StaffSalaryPayment;
use App\Models\StudentFeeInvoice;
use App\Models\StudentMark;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\Syllabus;
use App\Models\TeacherAssignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolErpSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Grade Rules (Bangladesh standard GPA 5.0 scale)
        $grades = [
            ['grade_name' => 'A+', 'min_mark' => 80.00, 'max_mark' => 100.00, 'grade_point' => 5.00, 'remarks' => 'অসাধারণ (Outstanding)'],
            ['grade_name' => 'A',  'min_mark' => 70.00, 'max_mark' => 79.99,  'grade_point' => 4.00, 'remarks' => 'খুব ভালো (Excellent)'],
            ['grade_name' => 'A-', 'min_mark' => 60.00, 'max_mark' => 69.99,  'grade_point' => 3.50, 'remarks' => 'ভালো (Very Good)'],
            ['grade_name' => 'B',  'min_mark' => 50.00, 'max_mark' => 59.99,  'grade_point' => 3.00, 'remarks' => 'সন্তোষজনক (Good)'],
            ['grade_name' => 'C',  'min_mark' => 40.00, 'max_mark' => 49.99,  'grade_point' => 2.00, 'remarks' => 'চলতিমান (Satisfactory)'],
            ['grade_name' => 'D',  'min_mark' => 33.00, 'max_mark' => 39.99,  'grade_point' => 1.00, 'remarks' => 'উত্তীর্ণ (Passing)'],
            ['grade_name' => 'F',  'min_mark' => 0.00,  'max_mark' => 32.99,  'grade_point' => 0.00, 'remarks' => 'অনুত্তীর্ণ (Failed)'],
        ];
        foreach ($grades as $g) {
            GradeRule::updateOrCreate(['grade_name' => $g['grade_name']], $g);
        }

        // 2. Academic Classes & Sections
        $classes = [
            ['name' => 'ষষ্ঠ শ্রেণি', 'numeric_value' => 6],
            ['name' => 'সপ্তম শ্রেণি', 'numeric_value' => 7],
            ['name' => 'অষ্টম শ্রেণি', 'numeric_value' => 8],
            ['name' => 'নবম শ্রেণি', 'numeric_value' => 9],
            ['name' => 'দশম শ্রেণি', 'numeric_value' => 10],
        ];

        $classModels = [];
        foreach ($classes as $c) {
            $classModel = AcademicClass::updateOrCreate(['name' => $c['name']], $c);
            $classModels[$c['numeric_value']] = $classModel;

            // Sections A and B for each
            Section::updateOrCreate(
                ['academic_class_id' => $classModel->id, 'name' => 'ক শাখা (Section A)'],
                ['capacity' => 50]
            );
            Section::updateOrCreate(
                ['academic_class_id' => $classModel->id, 'name' => 'খ শাখা (Section B)'],
                ['capacity' => 50]
            );
        }

        // 3. Subjects for Class 6, 7, 8, 9, 10
        $subjectList = [
            ['name' => 'বাংলা ১ম পত্র', 'code' => 'BAN101', 'total_marks' => 100, 'pass_marks' => 33],
            ['name' => 'ইংরেজি', 'code' => 'ENG102', 'total_marks' => 100, 'pass_marks' => 33],
            ['name' => 'গণিত', 'code' => 'MATH103', 'total_marks' => 100, 'pass_marks' => 33],
            ['name' => 'সাধারণ বিজ্ঞান', 'code' => 'SCI104', 'total_marks' => 100, 'pass_marks' => 33],
            ['name' => 'তথ্য ও যোগাযোগ প্রযুক্তি', 'code' => 'ICT105', 'total_marks' => 50, 'pass_marks' => 17],
            ['name' => 'ইসলাম ও নৈতিক শিক্ষা', 'code' => 'ISL106', 'total_marks' => 100, 'pass_marks' => 33],
        ];

        $class6Subjects = [];
        foreach ($classModels as $cls) {
            foreach ($subjectList as $sb) {
                $subModel = Subject::updateOrCreate(
                    ['academic_class_id' => $cls->id, 'name' => $sb['name']],
                    $sb
                );
                if ($cls->numeric_value === 6) {
                    $class6Subjects[$sb['code']] = $subModel;
                }
            }
        }

        // 4. Designations
        $desHead = Designation::updateOrCreate(['name' => 'প্রধান শিক্ষক'], ['role_type' => 'teacher', 'description' => 'বিদ্যালয় প্রধান']);
        $desSenior = Designation::updateOrCreate(['name' => 'সিনিয়র সহকারী শিক্ষক'], ['role_type' => 'teacher', 'description' => 'বিভাগীয় প্রধান']);
        $desAssistant = Designation::updateOrCreate(['name' => 'সহকারী শিক্ষক'], ['role_type' => 'teacher', 'description' => 'শ্রেণি ও বিষয়ভিত্তিক শিক্ষক']);
        $desAccountant = Designation::updateOrCreate(['name' => 'হিসাবরক্ষক'], ['role_type' => 'staff', 'description' => 'ফি ও বেতন কর্মকর্তা']);

        // 5. Staff & Teachers
        $teachersData = [
            [
                'employee_id' => 'EMP-2026-001',
                'name' => 'মো: রফিকুল ইসলাম',
                'designation_id' => $desHead->id,
                'phone' => '+880 1711 112233',
                'email' => 'headmaster@school.test',
                'qualification' => 'এম.এ, বি.এড (ঢাকা বিশ্ববিদ্যালয়)',
                'joining_date' => '2018-01-10',
                'monthly_salary' => 45000.00,
            ],
            [
                'employee_id' => 'EMP-2026-002',
                'name' => 'ফারহানা ইয়াসমিন',
                'designation_id' => $desSenior->id,
                'phone' => '+880 1712 223344',
                'email' => 'farhana@school.test',
                'qualification' => 'এম.এস.সি (গণিত)',
                'joining_date' => '2020-03-15',
                'monthly_salary' => 32000.00,
            ],
            [
                'employee_id' => 'EMP-2026-003',
                'name' => 'আহমেদ জুবায়ের',
                'designation_id' => $desAssistant->id,
                'phone' => '+880 1713 334455',
                'email' => 'zubair@school.test',
                'qualification' => 'এম.এ (ইংরেজি)',
                'joining_date' => '2021-07-01',
                'monthly_salary' => 28000.00,
            ],
            [
                'employee_id' => 'EMP-2026-004',
                'name' => 'কাজী কামরুল হাসান',
                'designation_id' => $desAccountant->id,
                'phone' => '+880 1714 445566',
                'email' => 'accountant@school.test',
                'qualification' => 'এম.বি.এ (অ্যাকাউন্টিং)',
                'joining_date' => '2022-01-05',
                'monthly_salary' => 25000.00,
            ],
        ];

        $staffModels = [];
        foreach ($teachersData as $tData) {
            $user = User::firstOrCreate(
                ['email' => $tData['email']],
                [
                    'name' => $tData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'phone' => $tData['phone'],
                ]
            );
            $tData['user_id'] = $user->id;
            $staffModels[] = StaffProfile::updateOrCreate(['employee_id' => $tData['employee_id']], $tData);
        }

        // 6. Teacher Assignments (Class 6 Math -> Farhana, English -> Zubair)
        $class6 = $classModels[6];
        $secA = $class6->sections()->first();
        if ($secA && isset($class6Subjects['MATH103']) && isset($staffModels[1])) {
            TeacherAssignment::updateOrCreate(
                [
                    'staff_profile_id' => $staffModels[1]->id,
                    'academic_class_id' => $class6->id,
                    'section_id' => $secA->id,
                    'subject_id' => $class6Subjects['MATH103']->id,
                ]
            );
        }
        if ($secA && isset($class6Subjects['ENG102']) && isset($staffModels[2])) {
            TeacherAssignment::updateOrCreate(
                [
                    'staff_profile_id' => $staffModels[2]->id,
                    'academic_class_id' => $class6->id,
                    'section_id' => $secA->id,
                    'subject_id' => $class6Subjects['ENG102']->id,
                ]
            );
        }

        // 7. Students (Class 6 and Class 7)
        $studentsData = [
            [
                'admission_number' => 'ADM-2026-001',
                'name' => 'তানভীর আহমেদ',
                'roll_number' => 1,
                'academic_class_id' => $class6->id,
                'section_id' => $secA ? $secA->id : null,
                'group' => 'General',
                'session_year' => 2026,
                'dob' => '2012-04-15',
                'gender' => 'male',
                'blood_group' => 'B+',
                'guardian_name' => 'মো: শফিকুল ইসলাম',
                'guardian_phone' => '+880 1711 998877',
                'address' => 'বাড়ি # ১২, রোড # ৪, ধানমন্ডি, ঢাকা',
            ],
            [
                'admission_number' => 'ADM-2026-002',
                'name' => 'নুসরাত জাহান মিম',
                'roll_number' => 2,
                'academic_class_id' => $class6->id,
                'section_id' => $secA ? $secA->id : null,
                'group' => 'General',
                'session_year' => 2026,
                'dob' => '2012-08-20',
                'gender' => 'female',
                'blood_group' => 'O+',
                'guardian_name' => 'মো: জহিরুল হক',
                'guardian_phone' => '+880 1712 887766',
                'address' => 'মিরপুর-১০, ঢাকা',
            ],
            [
                'admission_number' => 'ADM-2026-003',
                'name' => 'আসিফ মাহমুদ',
                'roll_number' => 3,
                'academic_class_id' => $class6->id,
                'section_id' => $secA ? $secA->id : null,
                'group' => 'General',
                'session_year' => 2026,
                'dob' => '2011-12-05',
                'gender' => 'male',
                'blood_group' => 'A+',
                'guardian_name' => 'মাহমুদুল হাসান',
                'guardian_phone' => '+880 1713 776655',
                'address' => 'উত্তরা সেক্টর ৭, ঢাকা',
            ],
            [
                'admission_number' => 'ADM-2026-004',
                'name' => 'সাদিয়া আক্তার',
                'roll_number' => 4,
                'academic_class_id' => $class6->id,
                'section_id' => $secA ? $secA->id : null,
                'group' => 'General',
                'session_year' => 2026,
                'dob' => '2012-02-18',
                'gender' => 'female',
                'blood_group' => 'AB+',
                'guardian_name' => 'আমজাদ হোসেন',
                'guardian_phone' => '+880 1714 665544',
                'address' => 'মোহাম্মদপুর, ঢাকা',
            ],
            [
                'admission_number' => 'ADM-2026-005',
                'name' => 'রায়হান কবির',
                'roll_number' => 5,
                'academic_class_id' => $class6->id,
                'section_id' => $secA ? $secA->id : null,
                'group' => 'General',
                'session_year' => 2026,
                'dob' => '2012-09-10',
                'gender' => 'male',
                'blood_group' => 'O+',
                'guardian_name' => 'কবির আহমেদ',
                'guardian_phone' => '+880 1715 554433',
                'address' => 'মহাখালী, ঢাকা',
            ],
        ];

        $studentModels = [];
        foreach ($studentsData as $st) {
            $studentModels[] = StudentProfile::updateOrCreate(['admission_number' => $st['admission_number']], $st);
        }

        // 8. Fingerprint & Biometric Attendance for today
        $today = Carbon::today()->toDateString();
        $fpTimes = ['08:45:00', '08:50:00', '09:05:00', '08:40:00', null];
        $statuses = ['present', 'present', 'late', 'present', 'absent'];

        foreach ($studentModels as $idx => $st) {
            AttendanceRecord::updateOrCreate(
                [
                    'attendance_type' => 'student',
                    'student_profile_id' => $st->id,
                    'date' => $today,
                ],
                [
                    'check_in_time' => $fpTimes[$idx],
                    'check_out_time' => $statuses[$idx] !== 'absent' ? '14:00:00' : null,
                    'status' => $statuses[$idx],
                    'source' => 'biometric',
                    'device_id' => 'FP-DEV-GATE1',
                    'remarks' => $statuses[$idx] === 'late' ? 'দেরিতে প্রবেশ' : null,
                ]
            );
        }

        // Attendance for Staff
        foreach ($staffModels as $stf) {
            AttendanceRecord::updateOrCreate(
                [
                    'attendance_type' => 'staff',
                    'staff_profile_id' => $stf->id,
                    'date' => $today,
                ],
                [
                    'check_in_time' => '08:30:00',
                    'check_out_time' => '15:30:00',
                    'status' => 'present',
                    'source' => 'biometric',
                    'device_id' => 'FP-DEV-OFFICE',
                    'remarks' => 'বায়োমেট্রিক পাঞ্চ ভেরিফাইড',
                ]
            );
        }

        // 9. Class Routine (Class 6 - Saturday to Wednesday)
        if ($secA && isset($class6Subjects['BAN101'], $class6Subjects['ENG102'], $class6Subjects['MATH103'])) {
            ClassRoutine::updateOrCreate(
                [
                    'academic_class_id' => $class6->id,
                    'section_id' => $secA->id,
                    'subject_id' => $class6Subjects['BAN101']->id,
                    'day_of_week' => 'Sunday',
                    'start_time' => '09:00:00',
                ],
                [
                    'end_time' => '09:45:00',
                    'staff_profile_id' => $staffModels[0]->id,
                    'room_number' => '১০১ নম্বর কক্ষ',
                ]
            );

            ClassRoutine::updateOrCreate(
                [
                    'academic_class_id' => $class6->id,
                    'section_id' => $secA->id,
                    'subject_id' => $class6Subjects['ENG102']->id,
                    'day_of_week' => 'Sunday',
                    'start_time' => '09:50:00',
                ],
                [
                    'end_time' => '10:35:00',
                    'staff_profile_id' => $staffModels[2]->id,
                    'room_number' => '১০১ নম্বর কক্ষ',
                ]
            );

            ClassRoutine::updateOrCreate(
                [
                    'academic_class_id' => $class6->id,
                    'section_id' => $secA->id,
                    'subject_id' => $class6Subjects['MATH103']->id,
                    'day_of_week' => 'Sunday',
                    'start_time' => '10:40:00',
                ],
                [
                    'end_time' => '11:25:00',
                    'staff_profile_id' => $staffModels[1]->id,
                    'room_number' => '১০১ নম্বর কক্ষ',
                ]
            );
        }

        // 10. Syllabus & Homework
        if (isset($class6Subjects['MATH103'])) {
            Syllabus::updateOrCreate(
                [
                    'academic_class_id' => $class6->id,
                    'subject_id' => $class6Subjects['MATH103']->id,
                    'title' => '১ম সাময়িক পরীক্ষার গণিত সিলেবাস ও পাঠ পরিকল্পনা',
                ],
                [
                    'description' => 'অধ্যায় ১: স্বাভাবিক সংখ্যা ও ভগ্নাংশ, অধ্যায় ২: অনুপাত ও শতকরা, অধ্যায় ৩: বীজগণিতীয় রাশিমালা।',
                    'session_year' => 2026,
                ]
            );

            Homework::updateOrCreate(
                [
                    'academic_class_id' => $class6->id,
                    'subject_id' => $class6Subjects['MATH103']->id,
                    'title' => 'অনুশীলনী ১.৩ এর মৌলিক গুণনীয়কের সাহায্যে ল.সা.গু নির্ণয়',
                ],
                [
                    'section_id' => $secA ? $secA->id : null,
                    'staff_profile_id' => $staffModels[1]->id,
                    'description' => 'মূল পাঠ্যবইয়ের পৃষ্ঠা ১৪ এর ১ থেকে ৮ পর্যন্ত সকল গাণিতিক সমস্যা খাতায় সমাধান করে আগামী ক্লাসে জমা দিতে হবে।',
                    'due_date' => Carbon::tomorrow()->toDateString(),
                ]
            );
        }

        // 11. Academic Calendar
        $events = [
            ['title' => 'পহেলা বৈশাখ ছুটি', 'event_type' => 'holiday', 'start_date' => '2026-04-14', 'end_date' => '2026-04-14', 'description' => 'বাংলা নববর্ষ উদযাপন'],
            ['title' => '১ম সাময়িক পরীক্ষা ২০২৬', 'event_type' => 'exam', 'start_date' => '2026-05-10', 'end_date' => '2026-05-25', 'description' => 'সকল শ্রেণির ১ম সাময়িক পরীক্ষা শুরু'],
            ['title' => 'বার্ষিক ক্রীড়া প্রতিযোগিতা', 'event_type' => 'sports', 'start_date' => '2026-02-15', 'end_date' => '2026-02-16', 'description' => 'স্কুল মাঠে আন্তঃশ্রেণি ক্রীড়া প্রতিযোগিতা'],
            ['title' => 'অভিভাবক সমাবেশ', 'event_type' => 'meeting', 'start_date' => '2026-03-05', 'end_date' => '2026-03-05', 'description' => 'শ্রেণিভিত্তিক অভিভাবক ও শিক্ষক মতবিনিময়'],
        ];
        foreach ($events as $ev) {
            AcademicCalendar::updateOrCreate(['title' => $ev['title']], $ev);
        }

        // 12. School Exams, Marks & Report Cards
        $schoolExam = SchoolExam::updateOrCreate(
            ['name' => '১ম সাময়িক পরীক্ষা ২০২৬', 'session_year' => 2026],
            [
                'start_date' => '2026-05-10',
                'end_date' => '2026-05-25',
                'is_published' => true,
            ]
        );

        // Student marks for Class 6 students
        $mockScores = [
            // Tanvir
            [
                'BAN101' => 88.00,
                'ENG102' => 82.00,
                'MATH103' => 95.00,
                'SCI104' => 86.00,
                'ICT105' => 45.00, // out of 50 -> 90%
            ],
            // Nusrat
            [
                'BAN101' => 92.00,
                'ENG102' => 89.00,
                'MATH103' => 91.00,
                'SCI104' => 90.00,
                'ICT105' => 48.00,
            ],
            // Asif
            [
                'BAN101' => 74.00,
                'ENG102' => 68.00,
                'MATH103' => 78.00,
                'SCI104' => 72.00,
                'ICT105' => 38.00,
            ],
            // Sadia
            [
                'BAN101' => 85.00,
                'ENG102' => 77.00,
                'MATH103' => 84.00,
                'SCI104' => 79.00,
                'ICT105' => 42.00,
            ],
            // Raihan
            [
                'BAN101' => 62.00,
                'ENG102' => 58.00,
                'MATH103' => 65.00,
                'SCI104' => 61.00,
                'ICT105' => 32.00,
            ],
        ];

        foreach ($studentModels as $sIdx => $st) {
            $scores = $mockScores[$sIdx] ?? $mockScores[0];
            foreach ($scores as $code => $markObtained) {
                if (isset($class6Subjects[$code])) {
                    $sub = $class6Subjects[$code];
                    // Normalize to 100 scale for grading calculation if total_marks is 50
                    $normalized = ($sub->total_marks > 0 && $sub->total_marks != 100) ? ($markObtained / $sub->total_marks) * 100 : $markObtained;
                    $gradeInfo = GradeRule::getGradeForMark($normalized);

                    StudentMark::updateOrCreate(
                        [
                            'school_exam_id' => $schoolExam->id,
                            'student_profile_id' => $st->id,
                            'subject_id' => $sub->id,
                        ],
                        [
                            'marks_obtained' => $markObtained,
                            'grade' => $gradeInfo['grade'],
                            'grade_point' => $gradeInfo['point'],
                            'remarks' => $gradeInfo['remarks'],
                        ]
                    );
                }
            }
        }

        // 13. Fee Types & Invoices
        $ft1 = FeeType::updateOrCreate(['name' => 'ভর্তি ফি'], ['default_amount' => 5000.00, 'description' => 'নতুন শিক্ষাবর্ষের ভর্তি ফি']);
        $ft2 = FeeType::updateOrCreate(['name' => 'মাসিক বেতন (টিউশন ফি)'], ['default_amount' => 1200.00, 'description' => 'প্রতি মাসের নিয়মিত বেতন']);
        $ft3 = FeeType::updateOrCreate(['name' => 'পরীক্ষা ফি'], ['default_amount' => 800.00, 'description' => '১ম সাময়িক পরীক্ষার ফি']);
        $ft4 = FeeType::updateOrCreate(['name' => 'সেশন চার্জ ও অন্যান্য ফি'], ['default_amount' => 2500.00, 'description' => 'বাৎসরিক উন্নয়ন ও ল্যাব ফি']);

        // Invoices for students
        foreach ($studentModels as $idx => $st) {
            $invNum = 'INV-2026-' . str_pad($st->id, 4, '0', STR_PAD_LEFT);
            $isPaid = $idx % 2 === 0;

            StudentFeeInvoice::updateOrCreate(
                ['invoice_number' => $invNum],
                [
                    'student_profile_id' => $st->id,
                    'fee_type_id' => $ft2->id,
                    'title' => 'মার্চ ২০২৬ মাসের বেতন',
                    'amount' => 1200.00,
                    'paid_amount' => $isPaid ? 1200.00 : 0.00,
                    'due_date' => '2026-03-15',
                    'status' => $isPaid ? 'paid' : 'unpaid',
                    'payment_date' => $isPaid ? '2026-03-08' : null,
                    'payment_method' => $isPaid ? 'bKash' : null,
                    'transaction_id' => $isPaid ? 'TRX' . rand(100000, 999999) : null,
                    'notes' => 'সময়মতো পরিশোধিত',
                ]
            );
        }

        // 14. Staff Salary Disbursements
        foreach ($staffModels as $sIdx => $stf) {
            StaffSalaryPayment::updateOrCreate(
                ['voucher_number' => 'SAL-2026-' . str_pad($stf->id, 4, '0', STR_PAD_LEFT)],
                [
                    'staff_profile_id' => $stf->id,
                    'month' => 'মার্চ',
                    'year' => 2026,
                    'basic_salary' => $stf->monthly_salary,
                    'bonus' => 1000.00,
                    'deductions' => 0.00,
                    'net_salary' => $stf->monthly_salary + 1000.00,
                    'payment_date' => '2026-04-01',
                    'payment_method' => 'Bank Transfer',
                    'status' => 'paid',
                    'notes' => 'মাসিক নিয়মিত বেতন ছাড়করণ',
                ]
            );
        }
    }
}
