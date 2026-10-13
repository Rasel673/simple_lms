@extends('layouts.student')

@section('title', 'Payment History')
@section('page_title', 'Payment History')

@section('content')
<div class="space-y-8">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Payment & Order History</h1>
            <p class="text-xs text-slate-500 mt-1">Review your course purchases, transaction receipts, and payment status.</p>
        </div>
        <a href="{{ route('home') }}#courses-section" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Browse More Courses
        </a>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">Total Invested</span>
                <p class="text-2xl font-black text-slate-900 mt-1">৳{{ number_format($totalSpent, 2) }}</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">Completed Payments</span>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $paidOrdersCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">Total Orders</span>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalOrders }}</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>
    </div>

    <!-- Payments Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Transaction History</h3>
            <span class="text-xs text-slate-500 font-medium">{{ $orders->total() }} Records</span>
        </div>

        @if($orders->isEmpty())
            <div class="p-12 text-center max-w-sm mx-auto">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">No payment records yet</h4>
                <p class="text-xs text-slate-500 mt-1">When you enroll in courses, your invoices and payment confirmations will appear here.</p>
                <a href="{{ route('home') }}#courses-section" class="inline-block mt-4 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition-colors">
                    Explore Courses
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-6">Order #</th>
                            <th class="py-3.5 px-6">Purchased Course</th>
                            <th class="py-3.5 px-6">Amount</th>
                            <th class="py-3.5 px-6">Method</th>
                            <th class="py-3.5 px-6">Transaction ID</th>
                            <th class="py-3.5 px-6">Status</th>
                            <th class="py-3.5 px-6">Date</th>
                            <th class="py-3.5 px-6 text-right">Invoice</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Order Number -->
                                <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                    {{ $order->order_number }}
                                </td>

                                <!-- Course(s) -->
                                <td class="py-4 px-6">
                                    @if($order->items->isNotEmpty())
                                        @php $firstItem = $order->items->first(); @endphp
                                        <div class="flex items-center gap-3">
                                            @if($firstItem->course && $firstItem->course->thumbnail)
                                                <img src="{{ asset('storage/' . $firstItem->course->thumbnail) }}" alt="Thumbnail" class="w-10 h-8 rounded-lg object-cover border border-slate-200 shrink-0">
                                            @else
                                                <div class="w-10 h-8 rounded-lg bg-indigo-900 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                                    LMS
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 truncate max-w-[200px]">
                                                    {{ $firstItem->course ? $firstItem->course->title : 'Course #' . $firstItem->course_id }}
                                                </p>
                                                @if($order->items->count() > 1)
                                                    <span class="text-[10px] text-slate-400">+ {{ $order->items->count() - 1 }} more item</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">No items</span>
                                    @endif
                                </td>

                                <!-- Amount -->
                                <td class="py-4 px-6 font-extrabold text-slate-900">
                                    ৳{{ number_format($order->total_amount, 2) }}
                                </td>

                                <!-- Payment Method -->
                                <td class="py-4 px-6 text-slate-600">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-[11px] font-medium text-slate-700 uppercase">
                                        {{ str_replace('_', ' ', $order->payment_method) }}
                                    </span>
                                </td>

                                <!-- Transaction ID -->
                                <td class="py-4 px-6">
                                    @if($order->transaction_id)
                                        <span class="font-mono text-[11px] text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md select-all">
                                            {{ $order->transaction_id }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">—</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6">
                                    @if($order->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Paid
                                        </span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Failed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Date -->
                                <td class="py-4 px-6 text-slate-500 text-[11px] whitespace-nowrap">
                                    {{ $order->created_at->format('M d, Y') }}
                                    <span class="block text-[10px] text-slate-400">{{ $order->created_at->format('h:i A') }}</span>
                                </td>

                                <!-- Action -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($order->payment_status === 'pending' && $order->items->isNotEmpty())
                                            <a href="{{ route('checkout.show', $order->items->first()->course->slug) }}" class="px-2.5 py-1 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-bold text-[11px] transition-colors">
                                                Pay
                                            </a>
                                        @endif
                                        <a href="{{ route('student.payments.show', $order->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 text-slate-700 hover:text-indigo-600 font-semibold text-xs transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Receipt
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="p-6 border-t border-slate-100 flex justify-center">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
