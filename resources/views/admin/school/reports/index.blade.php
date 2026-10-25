@extends('layouts.admin')

@section('title', 'রিপোর্ট ও বিশ্লেষণ')
@section('page_title', 'প্রাতিষ্ঠানিক অ্যানালিটিক্স ও রিপোর্ট')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span>অ্যানালিটিক্স ও রিপোর্ট হাব</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">প্রাতিষ্ঠানিক রিপোর্ট ও পারফরম্যান্স বিশ্লেষণ</h1>
            <p class="text-xs text-slate-500 mt-1">শিক্ষার্থী, শিক্ষক, হাজিরা, পরীক্ষার ফলাফল ও ফি আদায়ের 종합 রিপোর্ট</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.school.reports.export', ['type' => 'students']) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                শিক্ষার্থী তালিকা (CSV)
            </a>
            <a href="{{ route('admin.school.reports.export', ['type' => 'fees']) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-xs">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                ফি রেজিস্টার (CSV)
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                প্রিন্ট রিপোর্ট
            </button>
        </div>
    </div>

    <!-- Overview Counters -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-medium">মোট শিক্ষার্থী</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs">👥</span>
            </div>
            <p class="text-2xl font-black text-slate-800">{{ $totalStudents }} জন</p>
            <span class="text-[10px] text-slate-400">৫টি একাডেমিক শ্রেণিতে</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-medium">শিক্ষক ও কর্মকর্তা</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">🎓</span>
            </div>
            <p class="text-2xl font-black text-slate-800">{{ $totalTeachers }} জন</p>
            <span class="text-[10px] text-emerald-600 font-semibold">১০০% সক্রিয় স্টাফ</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-medium">মোট সংগৃহীত ফি</span>
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">৳</span>
            </div>
            <p class="text-2xl font-black text-emerald-600">৳{{ number_format($totalCollected, 2) }}</p>
            <span class="text-[10px] text-slate-400">সর্বমোট আদায়</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between text-slate-500 mb-2">
                <span class="text-xs font-medium">মোট বকেয়া ফি</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs">⚠️</span>
            </div>
            <p class="text-2xl font-black text-rose-600">৳{{ number_format($totalDues, 2) }}</p>
            <span class="text-[10px] text-rose-500 font-semibold">আদায়যোগ্য পাওনা</span>
        </div>
    </div>

    <!-- Multi-Module Detailed Analytics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- 1. Class-wise Student Distribution -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">শ্রেণিভিত্তিক শিক্ষার্থী সংখ্যা</h2>
                <span class="text-xs font-bold text-blue-600">মোট: {{ $totalStudents }} জন</span>
            </div>

            <div class="space-y-3">
                @foreach($classStrengths as $cls)
                    @php
                        $percentage = $totalStudents > 0 ? round(($cls->students_count / $totalStudents) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span>{{ $cls->name }}</span>
                            <span class="font-bold">{{ $cls->students_count }} জন ({{ $percentage }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 2. Today's Biometric Attendance Ratio -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">আজকের শিক্ষার্থী বায়োমেট্রিক হাজিরা</h2>
                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::today()->format('d M, Y') }}</span>
            </div>

            <div class="grid grid-cols-3 gap-3 text-center">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-100">
                    <span class="text-xs text-emerald-700 font-semibold block">উপস্থিত</span>
                    <span class="text-2xl font-black text-emerald-700 mt-1 block">{{ $studentAttendanceToday['present'] }}</span>
                </div>
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-100">
                    <span class="text-xs text-amber-700 font-semibold block">বিলম্ব</span>
                    <span class="text-2xl font-black text-amber-700 mt-1 block">{{ $studentAttendanceToday['late'] }}</span>
                </div>
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-100">
                    <span class="text-xs text-rose-700 font-semibold block">অনুপস্থিত</span>
                    <span class="text-2xl font-black text-rose-700 mt-1 block">{{ $studentAttendanceToday['absent'] }}</span>
                </div>
            </div>

            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-xs text-slate-600 flex items-center justify-between">
                <span>উপস্থিতি পর্যবেক্ষণ:</span>
                <span class="font-bold text-emerald-700">ডিভাইস সিঙ্ক ও নিয়মিত পাঞ্চ সক্রিয়</span>
            </div>
        </div>

        <!-- 3. Fee Collection by Fee Type -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">ফি খাতভিত্তিক আদায় ও পাওনা</h2>
            </div>

            <div class="space-y-3">
                @foreach($feeByType as $fItem)
                    @php $fRate = $fItem->total_amount > 0 ? round(($fItem->total_paid / $fItem->total_amount) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span class="font-bold">{{ $fItem->name }}</span>
                            <span>আদায়: ৳{{ number_format($fItem->total_paid, 0) }} / মোট: ৳{{ number_format($fItem->total_amount, 0) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-emerald-600 h-full rounded-full" style="width: {{ $fRate }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 4. Academic Examination Performance -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">পরীক্ষায় পাসের হার ও ফলাফল</h2>
            </div>

            <div class="space-y-3">
                @forelse($examStats as $exStat)
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-xs font-bold text-slate-900">{{ $exStat['name'] }}</h3>
                            <span class="text-[10px] text-slate-400">পরীক্ষার্থী খাতা মূল্যায়ন: {{ $exStat['total'] }} টি</span>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-black text-blue-600">{{ $exStat['rate'] }}%</span>
                            <span class="text-[10px] text-emerald-600 font-semibold block">উত্তীর্ণ: {{ $exStat['passed'] }} টি</span>
                        </div>
                    </div>
                @empty
                    <div class="text-xs text-slate-400 text-center py-4">কোনো পরীক্ষার ডাটা নেই</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
