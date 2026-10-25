<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Course;
use App\Models\Order;
use App\Models\StaffProfile;
use App\Models\StudentFeeInvoice;
use App\Models\StudentProfile;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCourses = Course::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalCourseSales = Order::where('payment_status', 'paid')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        $recentOrders = Order::with(['user', 'items.course'])
            ->latest()
            ->take(8)
            ->get();

        // School ERP Metrics
        $totalSchoolStudents = StudentProfile::count();
        $totalStaff = StaffProfile::count();
        $todayAttendanceCount = AttendanceRecord::where('date', Carbon::today()->toDateString())->where('status', 'present')->count();
        $totalSchoolFeesCollected = StudentFeeInvoice::sum('paid_amount');
        $totalSchoolFeesDue = max(0, StudentFeeInvoice::sum('amount') - $totalSchoolFeesCollected);

        return view('admin.dashboard', compact(
            'totalCourses',
            'totalStudents',
            'totalCourseSales',
            'totalRevenue',
            'recentOrders',
            'totalSchoolStudents',
            'totalStaff',
            'todayAttendanceCount',
            'totalSchoolFeesCollected',
            'totalSchoolFeesDue'
        ));
    }
}
