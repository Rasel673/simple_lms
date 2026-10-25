<?php

namespace App\Http\Controllers\Admin\School;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use App\Models\AcademicClass;
use App\Models\ClassRoutine;
use App\Models\Homework;
use App\Models\Section;
use App\Models\StaffProfile;
use App\Models\Subject;
use App\Models\Syllabus;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    // Class Routines
    public function routines(Request $request)
    {
        $classId = $request->input('class_id');
        $sectionId = $request->input('section_id');

        $classes = AcademicClass::with('sections', 'subjects')->get();
        $sections = $classId ? Section::where('academic_class_id', $classId)->get() : collect();
        $teachers = StaffProfile::where('status', 'active')->get();

        $routines = collect();
        if ($classId) {
            $q = ClassRoutine::with(['subject', 'teacher', 'section'])->where('academic_class_id', $classId);
            if ($sectionId) {
                $q->where('section_id', $sectionId);
            }
            $routines = $q->orderBy('day_of_week')->orderBy('start_time')->get()->groupBy('day_of_week');
        }

        return view('admin.school.academics.routines', compact('classes', 'sections', 'classId', 'sectionId', 'teachers', 'routines'));
    }

    public function storeRoutine(Request $request)
    {
        $validated = $request->validate([
            'academic_class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'staff_profile_id' => 'nullable|exists:staff_profiles,id',
            'day_of_week' => 'required|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'start_time' => 'required',
            'end_time' => 'required',
            'room_number' => 'nullable|string',
        ]);

        ClassRoutine::create($validated);

        return redirect()->back()->with('success', 'ক্লাস রুটিন পিরিয়ড সফলভাবে যোগ করা হয়েছে।');
    }

    public function destroyRoutine(ClassRoutine $routine)
    {
        $routine->delete();
        return redirect()->back()->with('success', 'রুটিন পিরিয়ড সফলভাবে মুছে ফেলা হয়েছে।');
    }

    // Syllabi
    public function syllabi(Request $request)
    {
        $classId = $request->input('class_id');
        $classes = AcademicClass::with('subjects')->get();

        $query = Syllabus::with(['academicClass', 'subject']);
        if ($classId) {
            $query->where('academic_class_id', $classId);
        }
        $syllabi = $query->latest()->paginate(15)->withQueryString();

        return view('admin.school.academics.syllabi', compact('classes', 'classId', 'syllabi'));
    }

    public function storeSyllabus(Request $request)
    {
        $validated = $request->validate([
            'academic_class_id' => 'required|exists:academic_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session_year' => 'required|integer',
        ]);

        Syllabus::create($validated);

        return redirect()->back()->with('success', 'সিলেবাস ও পাঠ পরিকল্পনা সফলভাবে সংরক্ষিত হয়েছে।');
    }

    public function destroySyllabus(Syllabus $syllabus)
    {
        $syllabus->delete();
        return redirect()->back()->with('success', 'সিলেবাস মুছে ফেলা হয়েছে।');
    }

    // Homework
    public function homework(Request $request)
    {
        $classId = $request->input('class_id');
        $classes = AcademicClass::with('sections', 'subjects')->get();

        $query = Homework::with(['academicClass', 'section', 'subject', 'teacher']);
        if ($classId) {
            $query->where('academic_class_id', $classId);
        }
        $homeworkList = $query->latest()->paginate(15)->withQueryString();

        return view('admin.school.academics.homework', compact('classes', 'classId', 'homeworkList'));
    }

    public function storeHomework(Request $request)
    {
        $validated = $request->validate([
            'academic_class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        Homework::create($validated);

        return redirect()->back()->with('success', 'হোমওয়ার্ক/অ্যাসাইনমেন্ট তৈরি করা হয়েছে।');
    }

    public function destroyHomework(Homework $homework)
    {
        $homework->delete();
        return redirect()->back()->with('success', 'হোমওয়ার্ক মুছে ফেলা হয়েছে।');
    }

    // Academic Calendar
    public function calendar(Request $request)
    {
        $events = AcademicCalendar::orderBy('start_date', 'asc')->get();
        return view('admin.school.academics.calendar', compact('events'));
    }

    public function storeCalendar(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        AcademicCalendar::create($validated);

        return redirect()->back()->with('success', 'একাডেমিক ক্যালেন্ডারে ইভেন্ট যোগ করা হয়েছে।');
    }

    public function destroyCalendar(AcademicCalendar $calendar)
    {
        $calendar->delete();
        return redirect()->back()->with('success', 'ইভেন্ট মুছে ফেলা হয়েছে।');
    }
}
