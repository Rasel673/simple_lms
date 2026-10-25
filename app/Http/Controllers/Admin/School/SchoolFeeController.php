<?php

namespace App\Http\Controllers\Admin\School;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\FeeType;
use App\Models\StaffProfile;
use App\Models\StaffSalaryPayment;
use App\Models\StudentFeeInvoice;
use App\Models\StudentProfile;
use App\Models\WebsiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SchoolFeeController extends Controller
{
    // Fees Management & Invoices
    public function index(Request $request)
    {
        $status = $request->input('status');
        $classId = $request->input('class_id');
        $search = $request->input('search');

        $classes = AcademicClass::all();
        $feeTypes = FeeType::all();

        $query = StudentFeeInvoice::with(['student.academicClass', 'student.section', 'feeType']);

        if ($status) {
            $query->where('status', $status);
        }
        if ($classId) {
            $query->whereHas('student', function ($q) use ($classId) {
                $q->where('academic_class_id', $classId);
            });
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                            ->orWhere('admission_number', 'like', "%{$search}%");
                    });
            });
        }

        $invoices = $query->latest()->paginate(20)->withQueryString();

        // Summary counters
        $stats = [
            'total_invoiced' => StudentFeeInvoice::sum('amount'),
            'total_collected' => StudentFeeInvoice::sum('paid_amount'),
            'total_due' => StudentFeeInvoice::where('status', '!=', 'paid')->sum(\Illuminate\Support\Facades\DB::raw('amount - paid_amount')),
            'paid_count' => StudentFeeInvoice::where('status', 'paid')->count(),
            'unpaid_count' => StudentFeeInvoice::where('status', 'unpaid')->count(),
        ];

        return view('admin.school.fees.index', compact('invoices', 'classes', 'feeTypes', 'status', 'classId', 'search', 'stats'));
    }

    public function generateInvoices(Request $request)
    {
        $request->validate([
            'academic_class_id' => 'required|exists:academic_classes,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'required|date',
        ]);

        $classId = $request->input('academic_class_id');
        $feeTypeId = $request->input('fee_type_id');
        $title = $request->input('title');
        $amount = $request->input('amount');
        $dueDate = $request->input('due_date');

        $students = StudentProfile::where('academic_class_id', $classId)->where('status', 'active')->get();
        $count = 0;

        foreach ($students as $st) {
            $invNum = 'INV-' . date('Y') . '-' . strtoupper(uniqid());
            StudentFeeInvoice::create([
                'invoice_number' => $invNum,
                'student_profile_id' => $st->id,
                'fee_type_id' => $feeTypeId,
                'title' => $title,
                'amount' => $amount,
                'paid_amount' => 0.00,
                'due_date' => $dueDate,
                'status' => 'unpaid',
            ]);
            $count++;
        }

        return redirect()->back()->with('success', $count . ' জন শিক্ষার্থীর জন্য ফি ইনভয়েস তৈরি হয়েছে।');
    }

    public function collectFee(Request $request, StudentFeeInvoice $invoice)
    {
        $request->validate([
            'paid_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string',
        ]);

        $paid = (float)$request->input('paid_amount');
        $newTotalPaid = $invoice->paid_amount + $paid;
        $status = $newTotalPaid >= $invoice->amount ? 'paid' : 'partial';

        $invoice->update([
            'paid_amount' => min($newTotalPaid, $invoice->amount),
            'status' => $status,
            'payment_date' => $request->input('payment_date'),
            'payment_method' => $request->input('payment_method'),
            'transaction_id' => $request->input('transaction_id'),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'ফি পেমেন্ট সফলভাবে গ্রহণ করা হয়েছে।');
    }

    // Printable Fee Receipt
    public function printReceipt(StudentFeeInvoice $invoice)
    {
        $invoice->load(['student.academicClass', 'student.section', 'feeType']);

        $schoolName = WebsiteSetting::get('site_name', 'আদর্শ উচ্চ বিদ্যালয় ও কলেজ');
        $schoolAddress = WebsiteSetting::get('contact_address', 'ঢাকা, বাংলাদেশ');
        $schoolPhone = WebsiteSetting::get('contact_phone', '+880 1711 000000');

        return view('admin.school.fees.receipt', compact('invoice', 'schoolName', 'schoolAddress', 'schoolPhone'));
    }

    // Staff Salaries / Payroll
    public function salaries(Request $request)
    {
        $month = $request->input('month', 'মার্চ');
        $year = $request->input('year', 2026);

        $staffList = StaffProfile::with('designation')->where('status', 'active')->get();
        $salaryPayments = StaffSalaryPayment::with('staff.designation')->latest()->paginate(20);

        return view('admin.school.fees.salaries', compact('staffList', 'salaryPayments', 'month', 'year'));
    }

    public function disburseSalary(Request $request)
    {
        $request->validate([
            'staff_profile_id' => 'required|exists:staff_profiles,id',
            'month' => 'required|string',
            'year' => 'required|integer',
            'basic_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        $basic = (float)$request->input('basic_salary');
        $bonus = (float)$request->input('bonus', 0);
        $deductions = (float)$request->input('deductions', 0);
        $net = ($basic + $bonus) - $deductions;

        $voucher = 'SAL-' . $request->input('year') . '-' . strtoupper(uniqid());

        StaffSalaryPayment::create([
            'voucher_number' => $voucher,
            'staff_profile_id' => $request->input('staff_profile_id'),
            'month' => $request->input('month'),
            'year' => $request->input('year'),
            'basic_salary' => $basic,
            'bonus' => $bonus,
            'deductions' => $deductions,
            'net_salary' => $net,
            'payment_date' => $request->input('payment_date'),
            'payment_method' => $request->input('payment_method'),
            'status' => 'paid',
            'notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'বেতন সফলভাবে প্রদান ও ভাউচার তৈরি হয়েছে।');
    }
}
