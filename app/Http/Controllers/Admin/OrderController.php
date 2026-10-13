<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $orders = Order::with(['user', 'items.course', 'payments'])
            ->when($status, fn($q) => $q->where('payment_status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            })
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders', 'status', 'search'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.course.category', 'payments']);
        return view('admin.orders.show', compact('order'));
    }
}
