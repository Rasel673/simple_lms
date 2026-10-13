@extends('layouts.student')

@section('title', 'Student Dashboard')
@section('page_title', 'Student Overview')

@section('content')
<div class="space-y-8">
    <!-- Welcome Greeting -->
    <div class="bg-gradient-to-r from-indigo-700 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/20 rounded-full blur-2xl"></div>
        <div class="relative z-10 max-w-2xl">
            <span class="text-xs font-semibold uppercase tracking-wider text-indigo-300">Welcome Back</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">Hello, {{ auth()->user()->name }}!</h1>
            <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                Continue advancing your skills. Watch video lessons, complete assessments, and claim your verified certificates.
            </p>
        </div>
    </div>

    <!-- 4 Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">Purchased Courses</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-black text-slate-900">{{ $totalPurchased }}</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">In Progress</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-black text-slate-900">{{ $inProgressCoursesCount }}</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">Completed Courses</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-black text-slate-900">{{ $completedCoursesCount }}</span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-slate-500">Certificates Earned</span>
                <div class="w-8 h-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-black text-slate-900">{{ $certificatesCount }}</span>
        </div>
    </div>

    <!-- Active Enrolled Courses -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-900">My Active Courses</h3>
                <p class="text-xs text-slate-500">Pick up right where you left off.</p>
            </div>
            <a href="{{ route('student.my-courses') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                View All Enrolled →
            </a>
        </div>

        @if($coursesWithProgress->isEmpty())
            <div class="py-12 text-center max-w-sm mx-auto">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-800">You are not enrolled in any courses yet</h4>
                <p class="text-xs text-slate-500 mt-1">Explore our courses and start learning today.</p>
                <a href="{{ route('courses.index') }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold">
                    Browse Courses
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($coursesWithProgress as $item)
                    @php
                        $course = $item['course'];
                        $progress = $item['progress'];
                        $enrollment = $item['enrollment'];
                    @endphp
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-slate-100/60 transition-colors gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-16 h-14 rounded-xl bg-slate-900 overflow-hidden shrink-0">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-indigo-900 flex items-center justify-center text-white text-[10px] font-bold">LMS</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 block mb-0.5">{{ $course->category->name }}</span>
                                <h4 class="text-xs font-bold text-slate-900 truncate">{{ $course->title }}</h4>
                                <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-1">
                                    <span>{{ $progress['completed_lessons'] }}/{{ $progress['total_lessons'] }} Lessons</span>
                                    <span>•</span>
                                    <span>{{ $progress['progress_percentage'] }}% Completed</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end shrink-0">
                            <!-- Mini Progress bar -->
                            <div class="w-24 bg-slate-200 rounded-full h-2 hidden md:block overflow-hidden">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $progress['progress_percentage'] }}%"></div>
                            </div>

                            @if($progress['is_completed'])
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-bold text-[10px] rounded-lg">
                                    Completed
                                </span>
                            @endif

                            <a href="{{ route('student.learning', ['course' => $course->id]) }}" class="px-4 py-2 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white transition-colors shadow-xs">
                                Continue Learning
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recent Payments & Invoices -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-900">Recent Payments & Invoices</h3>
                <p class="text-xs text-slate-500">Track your course purchases, transaction IDs, and download official receipts.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                    Total Invested: ৳{{ number_format($totalInvested, 2) }}
                </span>
                <a href="{{ route('student.payments.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                    View All ({{ $recentOrders->count() }}) →
                </a>
            </div>
        </div>

        @if($recentOrders->isEmpty())
            <div class="py-8 text-center max-w-sm mx-auto">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <p class="text-xs font-medium text-slate-500">No payment transactions recorded yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">Invoice / Order</th>
                            <th class="py-3 px-4">Course(s)</th>
                            <th class="py-3 px-4">Amount</th>
                            <th class="py-3 px-4">Method / TrxID</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <a href="{{ route('student.payments.show', $order) }}" class="text-indigo-600 hover:underline font-mono">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="max-w-xs truncate font-medium text-slate-800">
                                        @foreach($order->items as $item)
                                            <span>{{ $item->course->title ?? 'Course' }}</span>@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-black text-slate-900">
                                    ৳{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-slate-800 uppercase">{{ $order->payment_method ?? 'Gateway' }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $order->transaction_id ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($order->payment_status === 'paid')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            ● Paid
                                        </span>
                                    @elseif($order->payment_status === 'pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            ● Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            ● {{ ucfirst($order->payment_status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 text-[11px] whitespace-nowrap">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <a href="{{ route('student.payments.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Receipt
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
