<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Course;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Exception;

class CheckoutController extends Controller
{
    protected OrderService $orderService;
    protected PaymentService $paymentService;

    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function show(Request $request, Course $course)
    {
        if (!auth()->check()) {
            return redirect()->guest(route('login'))
                ->with('info', 'Please log in or register an account to complete your course purchase.');
        }

        if (auth()->user()->hasPurchasedCourse($course->id)) {
            return redirect()->route('student.learning', ['course' => $course->id])
                ->with('info', 'You already own this course. Enjoy learning!');
        }

        $user = auth()->user();

        return view('pages.checkout', compact('course', 'user'));
    }

    public function process(CheckoutRequest $request)
    {
        $user = auth()->user();
        $course = Course::findOrFail($request->course_id);

        if ($user->hasPurchasedCourse($course->id)) {
            return redirect()->route('student.learning', ['course' => $course->id])
                ->with('info', 'You are already enrolled in this course.');
        }

        try {
            $order = $this->orderService->createOrderForCourse(
                $user,
                $course,
                [
                    'billing_name' => $request->billing_name,
                    'billing_email' => $request->billing_email,
                    'billing_phone' => $request->billing_phone,
                    'notes' => $request->notes,
                ],
                $request->payment_method
            );

            // Initiate Payment Gateway
            $paymentResult = $this->paymentService->initiatePayment($order, $request->payment_method);

            if ($paymentResult['status'] === 'success') {
                return redirect()->away($paymentResult['redirect_url']);
            }

            return redirect()->back()
                ->with('error', $paymentResult['message'] ?? 'Payment initiation failed. Please try again or use the test payment gateway.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
