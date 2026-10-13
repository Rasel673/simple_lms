<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;

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

        return view('admin.dashboard', compact(
            'totalCourses',
            'totalStudents',
            'totalCourseSales',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
