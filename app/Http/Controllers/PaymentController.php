<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;
    protected OrderService $orderService;

    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
    }

    public function sslSuccess(Request $request)
    {
        $data = $request->all();
        Log::info('SSLCommerz Success Callback received: ', $data);

        $verified = $this->paymentService->verifySslCommerzPayment($data);

        if ($verified) {
            return redirect()->route('student.my-courses')
                ->with('success', 'Payment verified successfully! Welcome to your new course. Start learning now!');
        }

        return redirect()->route('courses.index')
            ->with('error', 'Payment verification failed or was invalid. Please contact support if your account was charged.');
    }

    public function sslFail(Request $request)
    {
        $data = $request->all();
        Log::warning('SSLCommerz Fail Callback received: ', $data);

        $tranId = $data['tran_id'] ?? null;
        if ($tranId) {
            $orderNumber = str_replace('TXN-', '', $tranId);
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order) {
                $this->orderService->markOrderAsFailed($order, $tranId, $data);
            }
        }

        return redirect()->route('courses.index')
            ->with('error', 'Payment transaction failed. Please try again.');
    }

    public function sslCancel(Request $request)
    {
        $data = $request->all();
        Log::info('SSLCommerz Cancel Callback received: ', $data);

        $tranId = $data['tran_id'] ?? null;
        if ($tranId) {
            $orderNumber = str_replace('TXN-', '', $tranId);
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order) {
                $this->orderService->markOrderAsCancelled($order);
            }
        }

        return redirect()->route('courses.index')
            ->with('info', 'Payment was cancelled. You have not been charged.');
    }

    public function sslIpn(Request $request)
    {
        $data = $request->all();
        Log::info('SSLCommerz IPN notification received: ', $data);

        $verified = $this->paymentService->verifySslCommerzPayment($data);

        return response()->json([
            'status' => $verified ? 'success' : 'failed',
            'message' => $verified ? 'IPN processed and verified.' : 'IPN verification failed.',
        ], 200);
    }
}
