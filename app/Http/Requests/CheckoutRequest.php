<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'billing_name' => ['required', 'string', 'max:100'],
            'billing_email' => ['required', 'email', 'max:150'],
            'billing_phone' => ['required', 'string', 'max:30'],
            'payment_method' => ['required', 'in:sslcommerz,test_gateway'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
