<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentHistoryController extends Controller
{
    /**
     * Display a listing of the student's orders and payment history.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->with(['items.course', 'payments'])
            ->latest()
            ->paginate(10);

        $totalSpent = $user->orders()
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $totalOrders = $user->orders()->count();

        $paidOrdersCount = $user->orders()
            ->where('payment_status', 'paid')
            ->count();

        $pendingOrdersCount = $user->orders()
            ->where('payment_status', 'pending')
            ->count();

        return view('student.payments.index', compact(
            'orders',
            'totalSpent',
            'totalOrders',
            'paidOrdersCount',
            'pendingOrdersCount'
        ));
    }

    /**
     * Display the specified order / invoice details.
     */
    public function show(Order $order)
    {
        // Security check: ensure student owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['items.course.category', 'payments', 'user']);

        return view('student.payments.show', compact('order'));
    }
}
