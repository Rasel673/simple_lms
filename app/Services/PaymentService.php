<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Initiate payment for an order.
     */
    public function initiatePayment(Order $order, string $gateway = 'sslcommerz'): array
    {
        if ($gateway === 'test_gateway') {
            return $this->processTestGateway($order);
        }

        return $this->initiateSslCommerz($order);
    }

    /**
     * Test / Sandbox direct gateway for testing & development.
     */
    public function processTestGateway(Order $order): array
    {
        $transactionId = 'TEST-' . strtoupper(bin2hex(random_bytes(6)));

        $payment = Payment::create([
            'order_id' => $order->id,
            'transaction_id' => $transactionId,
            'gateway' => 'test_gateway',
            'amount' => $order->total_amount,
            'currency' => config('services.sslcommerz.currency', 'BDT'),
            'status' => 'successful',
            'gateway_response' => ['simulated' => true, 'timestamp' => now()->toIso8601String()],
            'verified_at' => now(),
        ]);

        $this->orderService->markOrderAsPaid($order, $transactionId, ['method' => 'test_gateway']);

        return [
            'status' => 'success',
            'redirect_url' => route('student.my-courses'),
            'message' => 'Payment simulated successfully. You are now enrolled in the course!',
        ];
    }

    /**
     * Initiate SSLCommerz Session.
     */
    public function initiateSslCommerz(Order $order): array
    {
        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');
        $mode = config('services.sslcommerz.mode', 'sandbox');
        $currency = config('services.sslcommerz.currency', 'BDT');

        $apiUrl = $mode === 'live'
            ? 'https://securepay.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php';

        $tranId = 'TXN-' . $order->order_number;

        $postData = [
            'store_id' => $storeId,
            'store_passwd' => $storePassword,
            'total_amount' => $order->total_amount,
            'currency' => $currency,
            'tran_id' => $tranId,
            'success_url' => route('payment.sslcommerz.success'),
            'fail_url' => route('payment.sslcommerz.fail'),
            'cancel_url' => route('payment.sslcommerz.cancel'),
            'ipn_url' => route('payment.sslcommerz.ipn'),

            // Customer info
            'cus_name' => $order->billing_name ?? $order->user->name,
            'cus_email' => $order->billing_email ?? $order->user->email,
            'cus_phone' => $order->billing_phone ?? $order->user->phone ?? '01700000000',
            'cus_add1' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_country' => 'Bangladesh',

            // Shipment info
            'shipping_method' => 'NO',
            'num_of_item' => $order->items->count(),
            'product_name' => 'Online Course Enrollment',
            'product_category' => 'Education',
            'product_profile' => 'non-physical-goods',
        ];

        try {
            $response = Http::asForm()->timeout(15)->post($apiUrl, $postData);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['status']) && $result['status'] === 'SUCCESS' && !empty($result['GatewayPageURL'])) {
                    // Record pending payment
                    Payment::updateOrCreate(
                        ['order_id' => $order->id, 'transaction_id' => $tranId],
                        [
                            'gateway' => 'sslcommerz',
                            'amount' => $order->total_amount,
                            'currency' => $currency,
                            'status' => 'pending',
                            'gateway_response' => $result,
                        ]
                    );

                    return [
                        'status' => 'success',
                        'redirect_url' => $result['GatewayPageURL'],
                    ];
                }

                $errorMessage = $result['failedreason'] ?? 'SSLCommerz session creation failed.';
                Log::warning('SSLCommerz initiation response error: ' . json_encode($result));
            } else {
                $errorMessage = 'SSLCommerz server connection error.';
            }
        } catch (Exception $e) {
            Log::error('SSLCommerz initiation exception: ' . $e->getMessage());
            $errorMessage = $e->getMessage();
        }

        return [
            'status' => 'error',
            'message' => 'Unable to connect to SSLCommerz: ' . $errorMessage . '. (You can use the Test Payment Gateway to verify course purchasing).',
        ];
    }

    /**
     * Server-to-server verification of SSLCommerz payment.
     */
    public function verifySslCommerzPayment(array $data): bool
    {
        $valId = $data['val_id'] ?? null;
        $tranId = $data['tran_id'] ?? null;

        if (!$valId || !$tranId) {
            return false;
        }

        $orderNumber = str_replace('TXN-', '', $tranId);
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            Log::warning("SSLCommerz verification: Order not found for tran_id: {$tranId}");
            return false;
        }

        // If already paid, idempotent return
        if ($order->isPaid()) {
            return true;
        }

        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');
        $mode = config('services.sslcommerz.mode', 'sandbox');

        $validationUrl = $mode === 'live'
            ? 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php';

        try {
            $response = Http::timeout(15)->get($validationUrl, [
                'val_id' => $valId,
                'store_id' => $storeId,
                'store_passwd' => $storePassword,
                'v' => 1,
                'format' => 'json',
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $status = $result['status'] ?? '';
                $amount = (float) ($result['amount'] ?? 0);
                $currency = $result['currency'] ?? '';

                // Verify status and amount match
                if (($status === 'VALID' || $status === 'VALIDATED') && abs($amount - (float) $order->total_amount) < 0.01) {
                    Payment::updateOrCreate(
                        ['order_id' => $order->id, 'transaction_id' => $tranId],
                        [
                            'gateway' => 'sslcommerz',
                            'amount' => $amount,
                            'currency' => $currency,
                            'status' => 'successful',
                            'gateway_response' => $result,
                            'verified_at' => now(),
                        ]
                    );

                    $this->orderService->markOrderAsPaid($order, $tranId, $result);
                    return true;
                } else {
                    Log::warning("SSLCommerz validation failed check: Status {$status}, Amount {$amount}");
                }
            }
        } catch (Exception $e) {
            Log::error('SSLCommerz validation exception: ' . $e->getMessage());
        }

        Payment::updateOrCreate(
            ['order_id' => $order->id, 'transaction_id' => $tranId],
            [
                'status' => 'failed',
                'gateway_response' => $data,
            ]
        );

        $this->orderService->markOrderAsFailed($order, $tranId, $data);
        return false;
    }
}
