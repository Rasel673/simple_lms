<?php

namespace App\Http\Controllers\Admin\School;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Section;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class SchoolStudentController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');
        $search = $request->input('search');

        $classes = AcademicClass::with('sections')->get();
        $sections = $classId ? Section::where('academic_class_id', $classId)->get() : collect();

        $query = StudentProfile::with(['academicClass', 'section']);

        if ($classId) {
            $query->where('academic_class_id', $classId);
        }
        if ($sectionId) {
            $query->where('section_id', $sectionId);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('admission_number', 'like', "%{$search}%")
                    ->orWhere('guardian_phone', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('academic_class_id')->orderBy('roll_number')->paginate(20)->withQueryString();

        return view('admin.school.students.index', compact('students', 'classes', 'sections', 'classId', 'sectionId', 'search'));
    }

    public function create()
    {
        $classes = AcademicClass::with('sections')->get();
        return view('admin.school.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'admission_number' => 'required|string|unique:student_profiles,admission_number',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'roll_number' => 'required|integer|min:1',
            'group' => 'required|string',
            'session_year' => 'required|integer',
            'dob' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        StudentProfile::create($validated);

        return redirect()->route('admin.school.students.index')
            ->with('success', 'শিক্ষার্থীর তথ্য এবং ভর্তি সফলভাবে সম্পন্ন হয়েছে।');
    }

    public function show(StudentProfile $student)
    {
        $student->load(['academicClass', 'section', 'marks.subject', 'feeInvoices.feeType', 'attendances']);
        return view('admin.school.students.show', compact('student'));
    }

    public function edit(StudentProfile $student)
    {
        $classes = AcademicClass::with('sections')->get();
        $sections = Section::where('academic_class_id', $student->academic_class_id)->get();
        return view('admin.school.students.edit', compact('student', 'classes', 'sections'));
    }

    public function update(Request $request, StudentProfile $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'admission_number' => 'required|string|unique:student_profiles,admission_number,' . $student->id,
            'academic_class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'roll_number' => 'required|integer|min:1',
            'group' => 'required|string',
            'session_year' => 'required|integer',
            'dob' => 'nullable|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $student->update($validated);

        return redirect()->route('admin.school.students.index')
            ->with('success', 'শিক্ষার্থীর তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy(StudentProfile $student)
    {
        $student->delete();
        return redirect()->route('admin.school.students.index')
            ->with('success', 'শিক্ষার্থী সফলভাবে মুছে ফেলা হয়েছে।');
    }

    // Promotion Module
    public function promotion(Request $request)
    {
        $classes = AcademicClass::with('sections')->get();
        $fromClassId = $request->input('from_class_id');
        $fromSectionId = $request->input('from_section_id');

        $students = collect();
        if ($fromClassId) {
            $q = StudentProfile::where('academic_class_id', $fromClassId);
            if ($fromSectionId) {
                $q->where('section_id', $fromSectionId);
            }
            $students = $q->orderBy('roll_number')->get();
        }

        return view('admin.school.students.promotion', compact('classes', 'fromClassId', 'fromSectionId', 'students'));
    }

    public function processPromotion(Request $request)
    {
        $request->validate([
            'to_class_id' => 'required|exists:academic_classes,id',
            'to_section_id' => 'nullable|exists:sections,id',
            'to_session_year' => 'required|integer',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:student_profiles,id',
        ]);

        $toClassId = $request->input('to_class_id');
        $toSectionId = $request->input('to_section_id');
        $toYear = $request->input('to_session_year');
        $studentIds = $request->input('student_ids');

        foreach ($studentIds as $id) {
            $st = StudentProfile::find($id);
            if ($st) {
                $st->academic_class_id = $toClassId;
                if ($toSectionId) {
                    $st->section_id = $toSectionId;
                }
                $st->session_year = $toYear;
                $st->status = 'promoted';
                $st->save();
            }
        }

        return redirect()->route('admin.school.students.index')
            ->with('success', count($studentIds) . ' জন শিক্ষার্থী পরবর্তী শ্রেণিতে সফলভাবে প্রমোশন পেয়েছে।');
    }
}
