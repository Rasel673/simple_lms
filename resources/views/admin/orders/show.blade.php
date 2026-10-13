@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)
@section('page_title', 'Order Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Orders
        </a>
    </div>

    <!-- Order Header -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-extrabold text-slate-900 font-mono">{{ $order->order_number }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($order->payment_status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                    {{ $order->payment_status }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>

        <div class="text-right">
            <span class="text-xs text-slate-400 block">Total Amount</span>
            <span class="text-2xl font-black text-slate-900">৳{{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>

    <!-- Student & Billing Info -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-3">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Student Account</h3>
            <div class="text-xs text-slate-700 space-y-1">
                <p><span class="text-slate-400">Name:</span> <strong class="text-slate-900">{{ $order->user->name }}</strong></p>
                <p><span class="text-slate-400">Account Email:</span> {{ $order->user->email }}</p>
                <p><span class="text-slate-400">Account ID:</span> #{{ $order->user->id }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-xs space-y-3">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Billing Contact</h3>
            <div class="text-xs text-slate-700 space-y-1">
                <p><span class="text-slate-400">Billing Name:</span> {{ $order->billing_name ?: $order->user->name }}</p>
                <p><span class="text-slate-400">Billing Email:</span> {{ $order->billing_email ?: $order->user->email }}</p>
                <p><span class="text-slate-400">Billing Phone:</span> {{ $order->billing_phone ?: 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Purchased Items</h3>

        <div class="space-y-3">
            @foreach($order->items as $item)
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-900 text-white font-bold text-xs flex items-center justify-center shrink-0">
                            LMS
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">{{ $item->course->title }}</h4>
                            <span class="text-[10px] text-slate-500">{{ $item->course->category->name }}</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-slate-900">৳{{ number_format($item->price, 2) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Payment Transaction Logs (Auditable) -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
            <span>Payment Gateway Logs (Auditable)</span>
            <span class="text-[10px] uppercase font-bold text-slate-400">Method: {{ $order->payment_method }}</span>
        </h3>

        @if($order->payments->isEmpty())
            <p class="text-xs text-slate-400 py-3 text-center">No payment gateway callback logs registered.</p>
        @else
            <div class="space-y-4">
                @foreach($order->payments as $payment)
                    <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50 space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-bold text-slate-800">Txn: {{ $payment->transaction_id }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $payment->status === 'successful' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                {{ $payment->status }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px] text-slate-500 pt-1">
                            <div>Amount: <strong class="text-slate-800">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong></div>
                            <div>Gateway: <strong class="text-slate-800">{{ $payment->gateway }}</strong></div>
                            <div>Created: {{ $payment->created_at->format('M d, H:i') }}</div>
                            <div>Verified: {{ $payment->verified_at ? $payment->verified_at->format('M d, H:i') : 'Unverified' }}</div>
                        </div>

                        @if(!empty($payment->gateway_response))
                            <details class="pt-2 text-[10px] text-slate-500">
                                <summary class="cursor-pointer text-indigo-600 font-semibold hover:underline">View Raw Gateway Response Payload</summary>
                                <pre class="p-3 mt-1 bg-slate-900 text-emerald-400 rounded-xl overflow-x-auto text-[10px] font-mono">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                            </details>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
