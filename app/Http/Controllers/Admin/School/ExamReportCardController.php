<?php

namespace App\Http\Controllers\Admin\School;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\GradeRule;
use App\Models\SchoolExam;
use App\Models\Section;
use App\Models\StudentMark;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;

class ExamReportCardController extends Controller
{
    public function index()
    {
        $exams = SchoolExam::withCount('marks')->latest()->get();
        $gradeRules = GradeRule::orderBy('min_mark', 'desc')->get();
        return view('admin.school.exams.index', compact('exams', 'gradeRules'));
    }

    public function storeExam(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'session_year' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        SchoolExam::create($validated);

        return redirect()->back()->with('success', 'নতুন পরীক্ষা সফলভাবে তৈরি করা হয়েছে।');
    }

    // Mark Entry Sheet
    public function marksEntry(Request $request)
    {
        $exams = SchoolExam::all();
        $classes = AcademicClass::with('sections', 'subjects')->get();

        $examId = $request->input('exam_id');
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
        $subjectId = $request->input('subject_id');

        $sections = $classId ? Section::where('academic_class_id', $classId)->get() : collect();
        $subjects = $classId ? Subject::where('academic_class_id', $classId)->get() : collect();

        $students = collect();
        $marks = collect();
        $selectedSubject = null;

        if ($examId && $classId && $subjectId) {
            $selectedSubject = Subject::find($subjectId);
            $q = StudentProfile::where('academic_class_id', $classId);
            if ($sectionId) {
                $q->where('section_id', $sectionId);
            }
            $students = $q->orderBy('roll_number')->get();

            $marks = StudentMark::where('school_exam_id', $examId)
                ->where('subject_id', $subjectId)
                ->whereIn('student_profile_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_profile_id');
        }

        return view('admin.school.exams.marks_entry', compact(
            'exams', 'classes', 'sections', 'subjects', 'examId', 'classId', 'sectionId', 'subjectId', 'students', 'marks', 'selectedSubject'
        ));
    }

    public function saveMarks(Request $request)
    {
        $request->validate([
            'school_exam_id' => 'required|exists:school_exams,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|array',
        ]);

        $examId = $request->input('school_exam_id');
        $subjectId = $request->input('subject_id');
        $marksInput = $request->input('marks', []);

        $subject = Subject::findOrFail($subjectId);

        foreach ($marksInput as $studentId => $markData) {
            $obtained = isset($markData['obtained']) && $markData['obtained'] !== '' ? (float)$markData['obtained'] : null;
            if ($obtained !== null) {
                // Normalize to 100 for grade point
                $normalized = ($subject->total_marks > 0 && $subject->total_marks != 100) ? ($obtained / $subject->total_marks) * 100 : $obtained;
                $gradeInfo = GradeRule::getGradeForMark($normalized);

                StudentMark::updateOrCreate(
                    [
                        'school_exam_id' => $examId,
                        'student_profile_id' => $studentId,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'marks_obtained' => $obtained,
                        'grade' => $gradeInfo['grade'],
                        'grade_point' => $gradeInfo['point'],
                        'remarks' => $gradeInfo['remarks'],
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'পরীক্ষার নম্বর ও গ্রেডিং সফলভাবে সংরক্ষিত হয়েছে।');
    }

    // Tabulation Sheet
    public function tabulation(Request $request)
    {
        $exams = SchoolExam::all();
        $classes = AcademicClass::with('sections')->get();

        $examId = $request->input('exam_id');
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');

        $sections = $classId ? Section::where('academic_class_id', $classId)->get() : collect();
        $subjects = $classId ? Subject::where('academic_class_id', $classId)->get() : collect();
        $results = collect();

        if ($examId && $classId) {
            $studentsQuery = StudentProfile::where('academic_class_id', $classId);
            if ($sectionId) {
                $studentsQuery->where('section_id', $sectionId);
            }
            $students = $studentsQuery->orderBy('roll_number')->get();

            $allMarks = StudentMark::where('school_exam_id', $examId)
                ->whereIn('student_profile_id', $students->pluck('id'))
                ->get()
                ->groupBy('student_profile_id');

            foreach ($students as $student) {
                $stMarks = $allMarks->get($student->id, collect())->keyBy('subject_id');
                $totalMarks = 0;
                $totalPoints = 0;
                $hasFailed = false;
                $subjectScores = [];

                foreach ($subjects as $sub) {
                    $m = $stMarks->get($sub->id);
                    $obtained = $m ? $m->marks_obtained : 0;
                    $gp = $m ? $m->grade_point : 0;
                    $gr = $m ? $m->grade : 'F';

                    if ($obtained < $sub->pass_marks) {
                        $hasFailed = true;
                    }

                    $totalMarks += $obtained;
                    $totalPoints += $gp;
                    $subjectScores[$sub->id] = [
                        'marks' => $obtained,
                        'grade' => $gr,
                        'point' => $gp,
                    ];
                }

                $gpa = count($subjects) > 0 ? round($totalPoints / count($subjects), 2) : 0.00;
                if ($hasFailed) {
                    $finalGrade = 'F';
                    $gpa = 0.00;
                } else {
                    $gradeRule = GradeRule::where('grade_point', '<=', $gpa)->orderBy('grade_point', 'desc')->first();
                    $finalGrade = $gradeRule ? $gradeRule->grade_name : 'F';
                }

                $results->push([
                    'student' => $student,
                    'scores' => $subjectScores,
                    'total_marks' => $totalMarks,
                    'gpa' => $gpa,
                    'final_grade' => $finalGrade,
                    'is_passed' => !$hasFailed,
                ]);
            }
        }

        return view('admin.school.exams.tabulation', compact(
            'exams', 'classes', 'sections', 'subjects', 'examId', 'classId', 'sectionId', 'results'
        ));
    }

    // Printable Report Card
    public function reportCard(StudentProfile $student, SchoolExam $exam)
    {
        $student->load(['academicClass', 'section']);
        $subjects = Subject::where('academic_class_id', $student->academic_class_id)->get();
        $marks = StudentMark::where('school_exam_id', $exam->id)
            ->where('student_profile_id', $student->id)
            ->get()
            ->keyBy('subject_id');

        $totalMarks = 0;
        $totalPoints = 0;
        $hasFailed = false;

        foreach ($subjects as $sub) {
            $m = $marks->get($sub->id);
            $obtained = $m ? $m->marks_obtained : 0;
            $gp = $m ? $m->grade_point : 0;

            if ($obtained < $sub->pass_marks) {
                $hasFailed = true;
            }
            $totalMarks += $obtained;
            $totalPoints += $gp;
        }

        $gpa = count($subjects) > 0 ? round($totalPoints / count($subjects), 2) : 0.00;
        if ($hasFailed) {
            $finalGrade = 'F';
            $gpa = 0.00;
        } else {
            $gradeRule = GradeRule::where('grade_point', '<=', $gpa)->orderBy('grade_point', 'desc')->first();
            $finalGrade = $gradeRule ? $gradeRule->grade_name : 'F';
        }

        $schoolName = WebsiteSetting::get('site_name', 'আদর্শ উচ্চ বিদ্যালয় ও কলেজ');
        $schoolAddress = WebsiteSetting::get('contact_address', 'ঢাকা, বাংলাদেশ');
        $schoolPhone = WebsiteSetting::get('contact_phone', '+880 1711 000000');

        return view('admin.school.exams.report_card', compact(
            'student', 'exam', 'subjects', 'marks', 'totalMarks', 'gpa', 'finalGrade', 'hasFailed', 'schoolName', 'schoolAddress', 'schoolPhone'
        ));
    }
}
