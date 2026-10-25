<?php

namespace App\Http\Controllers\Admin\School;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Designation;
use App\Models\Section;
use App\Models\StaffProfile;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $designationId = $request->input('designation_id');
        $search = $request->input('search');

        $designations = Designation::all();
        $query = StaffProfile::with(['designation', 'assignments.academicClass', 'assignments.subject']);

        if ($designationId) {
            $query->where('designation_id', $designationId);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $staff = $query->paginate(15)->withQueryString();

        return view('admin.school.staff.index', compact('staff', 'designations', 'designationId', 'search'));
    }

    public function create()
    {
        $designations = Designation::all();
        return view('admin.school.staff.create', compact('designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|unique:staff_profiles,employee_id',
            'designation_id' => 'required|exists:designations,id',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'qualification' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'monthly_salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        StaffProfile::create($validated);

        return redirect()->route('admin.school.staff.index')
            ->with('success', 'শিক্ষক/স্টাফের তথ্য সফলভাবে যোগ করা হয়েছে।');
    }

    public function edit(StaffProfile $staff)
    {
        $designations = Designation::all();
        return view('admin.school.staff.edit', compact('staff', 'designations'));
    }

    public function update(Request $request, StaffProfile $staff)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|unique:staff_profiles,employee_id,' . $staff->id,
            'designation_id' => 'required|exists:designations,id',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'qualification' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'monthly_salary' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $staff->update($validated);

        return redirect()->route('admin.school.staff.index')
            ->with('success', 'শিক্ষক/স্টাফের তথ্য সফলভাবে আপডেট হয়েছে।');
    }

    public function destroy(StaffProfile $staff)
    {
        $staff->delete();
        return redirect()->route('admin.school.staff.index')
            ->with('success', 'শিক্ষক/স্টাফ প্রোফাইল সফলভাবে মুছে ফেলা হয়েছে।');
    }

    // Teacher Class & Subject Allocation
    public function assignments(Request $request)
    {
        $assignments = TeacherAssignment::with(['staffProfile.designation', 'academicClass', 'section', 'subject'])->paginate(20);
        $teachers = StaffProfile::where('status', 'active')->get();
        $classes = AcademicClass::with('sections', 'subjects')->get();

        return view('admin.school.staff.assignments', compact('assignments', 'teachers', 'classes'));
    }

    public function storeAssignment(Request $request)
    {
        $validated = $request->validate([
            'staff_profile_id' => 'required|exists:staff_profiles,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        TeacherAssignment::updateOrCreate(
            [
                'staff_profile_id' => $validated['staff_profile_id'],
                'academic_class_id' => $validated['academic_class_id'],
                'section_id' => $validated['section_id'] ?? null,
                'subject_id' => $validated['subject_id'],
            ],
            $validated
        );

        return redirect()->back()->with('success', 'শিক্ষককে ক্লাস ও বিষয় সফলভাবে বরাদ্দ করা হয়েছে।');
    }

    public function deleteAssignment(TeacherAssignment $assignment)
    {
        $assignment->delete();
        return redirect()->back()->with('success', 'শ্রেণি ও বিষয় বরাদ্দ বাতিল করা হয়েছে।');
    }
}
