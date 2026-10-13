@extends('layouts.student')

@section('title', 'Invoice ' . $order->order_number)
@section('page_title', 'Order Receipt')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('student.payments.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Payment History
        </a>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-xs transition-colors">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Receipt
            </button>

            @if($order->payment_status === 'paid' && $order->items->isNotEmpty())
                <a href="{{ route('student.learning', ['course' => $order->items->first()->course_id]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all">
                    Start Learning
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            @endif
        </div>
    </div>

    <!-- Official Invoice Paper Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-sm space-y-8" id="invoice-printable">
        <!-- Invoice Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 border-b border-slate-100 pb-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ \App\Models\WebsiteSetting::get('site_name', 'LearnHub LMS') }}</h2>
                    <p class="text-xs text-slate-500 font-medium">Official Payment Receipt & Order Confirmation</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Order Reference</span>
                <p class="text-base font-mono font-extrabold text-slate-900 mt-0.5">{{ $order->order_number }}</p>
                <div class="mt-2">
                    @if($order->payment_status === 'paid')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            COMPLETED & VERIFIED
                        </span>
                    @elseif($order->payment_status === 'pending')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            PAYMENT PENDING
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-700">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Billing & Transaction Metadata -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs">
            <!-- Billed To -->
            <div class="space-y-1.5 p-5 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Student & Billing Details</span>
                <p class="text-sm font-bold text-slate-900">{{ $order->billing_name ?: $order->user->name }}</p>
                <p class="text-slate-600">{{ $order->billing_email ?: $order->user->email }}</p>
                @if($order->billing_phone)
                    <p class="text-slate-600 font-mono">{{ $order->billing_phone }}</p>
                @endif
            </div>

            <!-- Transaction Information -->
            <div class="space-y-1.5 p-5 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Transaction Record</span>
                <div class="flex items-center justify-between text-slate-600 pt-1">
                    <span>Payment Gateway:</span>
                    <span class="font-bold text-slate-800 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                </div>
                <div class="flex items-center justify-between text-slate-600">
                    <span>Transaction ID:</span>
                    <span class="font-mono font-bold text-indigo-700 select-all">{{ $order->transaction_id ?: 'Pending Gateway Assignment' }}</span>
                </div>
                <div class="flex items-center justify-between text-slate-600">
                    <span>Order Date:</span>
                    <span class="text-slate-800 font-medium">{{ $order->created_at->format('F d, Y - h:i A') }}</span>
                </div>
                @if($order->paid_at)
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Verified At:</span>
                        <span class="text-emerald-700 font-medium">{{ $order->paid_at->format('F d, Y - h:i A') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Itemized Course Products Table -->
        <div>
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Purchased Course Curriculum</h4>
            <div class="border border-slate-200 rounded-2xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-6">Course Description</th>
                            <th class="py-3 px-6 text-center">Category</th>
                            <th class="py-3 px-6 text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        @if($item->course && $item->course->thumbnail)
                                            <img src="{{ asset('storage/' . $item->course->thumbnail) }}" alt="Thumbnail" class="w-12 h-9 rounded-lg object-cover border border-slate-200 shrink-0">
                                        @else
                                            <div class="w-12 h-9 rounded-lg bg-indigo-900 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                                LMS
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $item->course ? $item->course->title : 'Course #' . $item->course_id }}</p>
                                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $item->course?->duration ?: 'Self-Paced' }} • Lifetime Access</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-indigo-50 text-indigo-700">
                                        {{ $item->course?->category?->name ?: 'General' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right font-extrabold text-slate-900 text-sm">
                                    ৳{{ number_format($item->price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Calculation & Official Seal -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 pt-4 border-t border-slate-100">
            <!-- Seal & Verification Note -->
            <div class="flex items-center gap-3 text-slate-500 text-xs max-w-sm">
                <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <span class="font-bold text-slate-800 block">Verified Purchase</span>
                    <span class="text-[11px] leading-tight block">This computer-generated receipt confirms course enrollment and lifetime learning privileges.</span>
                </div>
            </div>

            <!-- Total Box -->
            <div class="w-full sm:w-64 p-4 rounded-2xl bg-slate-900 text-white space-y-2">
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>Subtotal</span>
                    <span>৳{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-400">
                    <span>Gateway Fees</span>
                    <span>৳0.00</span>
                </div>
                <div class="border-t border-slate-800 pt-2 flex items-center justify-between font-black text-base text-white">
                    <span>Total Paid</span>
                    <span class="text-indigo-400">৳{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
@media print {
    aside, header, nav, .no-print, button {
        display: none !important;
    }
    body, main {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    #invoice-printable {
        border: none !important;
        box-shadow: none !important;
        padding: 1.5rem !important;
        max-width: 100% !important;
    }
}
</style>
@endpush
@endsection
