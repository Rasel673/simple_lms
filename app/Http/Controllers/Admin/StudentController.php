<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        $students = User::where('role', 'student')
            ->withCount(['enrollments', 'certificates', 'examAttempts'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('admin.students.index', compact('students', 'search'));
    }

    public function show(User $student)
    {
        if (!$student->isStudent()) {
            abort(404);
        }

        $student->load([
            'enrollments.course',
            'orders.items.course',
            'examAttempts.exam.course',
            'certificates.course',
        ]);

        return view('admin.students.show', compact('student'));
    }
}
