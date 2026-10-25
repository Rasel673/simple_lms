@extends('layouts.student')

@section('title', 'পেমেন্ট ইতিহাস')
@section('page_title', 'পেমেন্ট ইতিহাস')

@section('content')
<div class="space-y-8">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">পেমেন্ট ও অর্ডার বিবরণী</h1>
            <p class="text-xs text-slate-500 mt-1">আপনার কোর্স ক্রয়, লেনদেনের রশিদ ও পেমেন্ট স্ট্যাটাস দেখুন।</p>
        </div>
        <a href="{{ route('home') }}#courses-section" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            আরও কোর্স দেখুন
        </a>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">মোট পরিশোধিত</span>
                <p class="text-2xl font-black text-slate-900 mt-1">৳{{ number_format($totalSpent, 2) }}</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">সফল পেমেন্ট</span>
                <p class="text-2xl font-black text-slate-900 mt-1">{{ $paidOrdersCount }}</p>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500">মোট অর্ডার</span>
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
            <h3 class="text-base font-bold text-slate-900">লেনদেনের ইতিহাস</h3>
            <span class="text-xs text-slate-500 font-medium">{{ $orders->total() }}টি রেকর্ড</span>
        </div>

        @if($orders->isEmpty())
            <div class="p-12 text-center max-w-sm mx-auto">
                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">এখনো কোনো পেমেন্ট রেকর্ড নেই</h4>
                <p class="text-xs text-slate-500 mt-1">কোর্সে ভর্তি হলে আপনার ইনভয়েস ও পেমেন্ট বিবরণী এখানে প্রদর্শিত হবে।</p>
                <a href="{{ route('home') }}#courses-section" class="inline-block mt-4 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition-colors">
                    কোর্সগুলো দেখুন
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3.5 px-6">অর্ডার নং</th>
                            <th class="py-3.5 px-6">কোর্স</th>
                            <th class="py-3.5 px-6">পরিমাণ</th>
                            <th class="py-3.5 px-6">পদ্ধতি</th>
                            <th class="py-3.5 px-6">ট্রানজ্যাকশন আইডি</th>
                            <th class="py-3.5 px-6">স্ট্যাটাস</th>
                            <th class="py-3.5 px-6">তারিখ</th>
                            <th class="py-3.5 px-6 text-right">রশিদ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                    {{ $order->order_number }}
                                </td>

                                <td class="py-4 px-6">
                                    @if($order->items->isNotEmpty())
                                        @php $firstItem = $order->items->first(); @endphp
                                        <div class="flex items-center gap-3">
                                            @if($firstItem->course && $firstItem->course->thumbnail)
                                                <img src="{{ asset($firstItem->course->thumbnail) }}" alt="Thumbnail" class="w-10 h-8 rounded-lg object-cover border border-slate-200 shrink-0">
                                            @endif
                                            <div>
                                                <p class="font-bold text-slate-900 line-clamp-1">{{ $firstItem->course->title ?? 'কোর্স' }}</p>
                                                <span class="text-[11px] text-slate-400">{{ $firstItem->course->category->name ?? '' }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400">কোর্স বিবরণ</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 font-extrabold text-slate-900">
                                    ৳{{ number_format($order->total_amount, 2) }}
                                </td>

                                <td class="py-4 px-6">
                                    <span class="font-semibold text-slate-800 uppercase">{{ $order->payment_method ?? 'Gateway' }}</span>
                                </td>

                                <td class="py-4 px-6 font-mono text-slate-500 text-[11px]">
                                    {{ $order->transaction_id ?? 'N/A' }}
                                </td>

                                <td class="py-4 px-6">
                                    @if($order->payment_status === 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ● পরিশোধিত
                                        </span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            ● অপেক্ষমাণ
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            ● {{ ucfirst($order->payment_status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-slate-500 whitespace-nowrap">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>

                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <a href="{{ route('student.payments.show', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        রশিদ
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
