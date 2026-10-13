<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class OrderService
{
    /**
     * Create an order for a course purchase.
     */
    public function createOrderForCourse(User $user, Course $course, array $billingDetails = [], string $paymentMethod = 'sslcommerz'): Order
    {
        // Check if user is already enrolled
        if ($user->hasPurchasedCourse($course->id)) {
            throw new Exception('You are already enrolled in this course.');
        }

        return DB::transaction(function () use ($user, $course, $billingDetails, $paymentMethod) {
            $orderNumber = 'ORD-' . strtoupper(Str::random(6)) . '-' . time();
            $amount = $course->effective_price;

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'total_amount' => $amount,
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'billing_name' => $billingDetails['billing_name'] ?? $user->name,
                'billing_email' => $billingDetails['billing_email'] ?? $user->email,
                'billing_phone' => $billingDetails['billing_phone'] ?? $user->phone,
                'notes' => $billingDetails['notes'] ?? null,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'course_id' => $course->id,
                'price' => $amount,
            ]);

            return $order;
        });
    }

    /**
     * Idempotently mark order as paid and grant student access.
     */
    public function markOrderAsPaid(Order $order, string $transactionId, array $gatewayResponse = []): void
    {
        if ($order->payment_status === 'paid') {
            return; // Already processed idempotently
        }

        DB::transaction(function () use ($order, $transactionId, $gatewayResponse) {
            $order->update([
                'payment_status' => 'paid',
                'transaction_id' => $transactionId,
                'paid_at' => now(),
            ]);

            // Grant enrollment for each course in the order
            foreach ($order->items as $item) {
                CourseEnrollment::firstOrCreate(
                    [
                        'user_id' => $order->user_id,
                        'course_id' => $item->course_id,
                    ],
                    [
                        'order_id' => $order->id,
                        'enrolled_at' => now(),
                        'status' => 'active',
                    ]
                );
            }
        });
    }

    public function markOrderAsFailed(Order $order, ?string $transactionId = null, array $gatewayResponse = []): void
    {
        if ($order->payment_status === 'paid') {
            return;
        }

        $order->update([
            'payment_status' => 'failed',
            'transaction_id' => $transactionId ?? $order->transaction_id,
        ]);
    }

    public function markOrderAsCancelled(Order $order): void
    {
        if ($order->payment_status === 'paid') {
            return;
        }

        $order->update([
            'payment_status' => 'cancelled',
        ]);
    }
}
