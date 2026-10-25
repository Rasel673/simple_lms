@extends('layouts.admin')

@section('title', 'অ্যাডমিন ড্যাশবোর্ড')
@section('page_title', 'ওভারভিউ ড্যাশবোর্ড')

@section('content')
<div class="space-y-8">

    <!-- Top Welcome & Real-Time Institutional Status Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 shadow-xl border border-slate-800">
        <div class="absolute -right-8 -bottom-8 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-48 h-48 bg-indigo-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1.5 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-semibold text-amber-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>সমন্বিত স্কুল ইআরপি ও অনলাইন লার্নিং ম্যানেজমেন্ট স্যুট</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    স্বাগতম, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                    প্রতিষ্ঠানের বায়োমেট্রিক হাজিরা, শিক্ষার্থী ভর্তি, ক্লাস রুটিন, পরীক্ষার গ্রেডিং এবং অনলাইন কোর্স বিক্রয়ের সার্বিক চিত্র এক নজরে দেখুন।
                </p>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('admin.school.students.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-md transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span>নতুন শিক্ষার্থী ভর্তি</span>
                </a>
                <a href="{{ route('admin.school.fees.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-semibold text-xs border border-white/20 backdrop-blur-md transition-all">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>ফি আদায় ও ইনভয়েস</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 1. School Management Suite Overview Cards (Pillars from Infographic) -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    প্রতিষ্ঠান পরিচালনা ওভারভিউ (School ERP Modules)
                </h2>
                <p class="text-xs text-slate-500">শিক্ষার্থী ভর্তি, শিক্ষক হাজিরা, একাডেমিক ক্যালেন্ডার ও ফি আদায়ের রিয়েল-টাইম পরিসংখ্যান</p>
            </div>
            <a href="{{ route('admin.school.reports.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 flex items-center gap-1 transition-colors">
                <span>পূর্ণাঙ্গ রিপোর্ট ও এক্সপোর্ট</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Students -->
            <a href="{{ route('admin.school.students.index') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md hover:border-blue-400 transition-all flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 block mb-1">মোট শিক্ষার্থী ভর্তি</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">{{ $totalSchoolStudents }}</span>
                        <span class="text-xs font-semibold text-slate-500">জন</span>
                    </div>
                    <span class="text-[10px] text-blue-600 font-bold mt-1 inline-flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        ১ম থেকে ১০ম শ্রেণি
                    </span>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </a>

            <!-- Total Staff -->
            <a href="{{ route('admin.school.staff.index') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md hover:border-emerald-400 transition-all flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 block mb-1">শিক্ষক ও স্টাফ সংখ্যা</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-slate-900 group-hover:text-emerald-600 transition-colors">{{ $totalStaff }}</span>
                        <span class="text-xs font-semibold text-slate-500">জন</span>
                    </div>
                    <span class="text-[10px] text-emerald-600 font-bold mt-1 inline-flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        সক্রিয় শিক্ষক মণ্ডলী
                    </span>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
            </a>

            <!-- Today Attendance -->
            <a href="{{ route('admin.school.attendance.index') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md hover:border-orange-400 transition-all flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 block mb-1">আজকের বায়োমেট্রিক উপস্থিতি</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black text-emerald-600">{{ $todayAttendanceCount }}</span>
                        <span class="text-xs font-semibold text-slate-500">জন উপস্থিত</span>
                    </div>
                    <span class="text-[10px] text-orange-600 font-bold mt-1 inline-flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                        ফিঙ্গারপ্রিন্ট অটো সিঙ্ক
                    </span>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004.07 8.5"></path></svg>
                </div>
            </a>

            <!-- Fees & Dues -->
            <a href="{{ route('admin.school.fees.index') }}" class="group bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs hover:shadow-md hover:border-cyan-400 transition-all flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 block mb-1">ফি আদায় / বকেয়া</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-2xl font-black text-slate-900">৳{{ number_format($totalSchoolFeesCollected, 0) }}</span>
                    </div>
                    <span class="text-[10px] text-rose-500 font-bold mt-1 block">
                        বকেয়া: ৳{{ number_format($totalSchoolFeesDue, 0) }}
                    </span>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </a>
        </div>
    </div>

    <!-- 2. Core Modules Interactive Quick Grid (8 Institutional Features) -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">প্রধান মডিউল ও অ্যাকশন (ERP Core Pillars)</h3>
                <p class="text-xs text-slate-500">প্রয়োজনীয় প্রতিটি মডিউলে সরাসরি প্রবেশের কুইক শর্টকাট</p>
            </div>
            <span class="text-[11px] font-semibold text-slate-400 bg-slate-100 px-3 py-1 rounded-full">৮টি প্রধান শাখা</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 sm:gap-4 pt-2">
            <!-- 1. Attendance -->
            <a href="{{ route('admin.school.attendance.index') }}" class="group p-4 rounded-2xl bg-gradient-to-br from-orange-50/80 to-amber-50/40 border border-orange-200/60 hover:border-orange-400 hover:shadow-md text-center transition-all">
                <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004.07 8.5"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block group-hover:text-orange-600 transition-colors">হাজিরা লগ</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">ডিভাইস সিঙ্ক</span>
            </a>

            <!-- 2. Admission -->
            <a href="{{ route('admin.school.students.create') }}" class="group p-4 rounded-2xl bg-gradient-to-br from-blue-50/80 to-indigo-50/40 border border-blue-200/60 hover:border-blue-400 hover:shadow-md text-center transition-all">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block group-hover:text-blue-600 transition-colors">নতুন ভর্তি</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">রেজিস্ট্রেশন</span>
            </a>

            <!-- 3. Teacher Allocation -->
            <a href="{{ route('admin.school.staff.assignments') }}" class="group p-4 rounded-2xl bg-gradient-to-br from-emerald-50/80 to-teal-50/40 border border-emerald-200/60 hover:border-emerald-400 hover:shadow-md text-center transition-all">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block group-hover:text-emerald-600 transition-colors">শিক্ষক বণ্টন</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">বিষয় ও ক্লাস</span>
            </a>

            <!-- 4. Class Routine -->
            <a href="{{ route('admin.school.academics.routines') }}" class="group p-4 rounded-2xl bg-gradient-to-br from-purple-50/80 to-violet-50/40 border border-purple-200/60 hover:border-purple-400 hover:shadow-md text-center transition-all">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block group-hover:text-purple-600 transition-colors">ক্লাস রুটিন</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">সাপ্তাহিক গ্রিড</span>
            </a>

            <!-- 5. Marks Entry -->
            <a href="{{ route('admin.school.exams.marks.entry') }}" class="group p-4 rounded-2xl bg-gradient-to-br from-rose-50/80 to-pink-50/40 border border-rose-200/60 hover:border-rose-400 hover:shadow-md text-center transition-all">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block group-hover:text-rose-600 transition-colors">নম্বর এন্ট্রি</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">ফলাফল ও জিপিএ</span>
            </a>

            <!-- 6. Fee Collection -->
            <a href="{{ route('admin.school.fees.index') }}" class="group p-4 rounded-2xl bg-gradient-to-br from-cyan-50/80 to-sky-50/40 border border-cyan-200/60 hover:border-cyan-400 hover:shadow-md text-center transition-all">
                <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block group-hover:text-cyan-600 transition-colors">ফি আদায়</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">মানি রসিদ প্রিন্ট</span>
            </a>

            <!-- 7. Analytics -->
            <a href="{{ route('admin.school.reports.index') }}" class="group p-4 rounded-2xl bg-gradient-to-br from-indigo-50/80 to-violet-50/40 border border-indigo-200/60 hover:border-indigo-400 hover:shadow-md text-center transition-all">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <span class="text-xs font-bold text-slate-800 block group-hover:text-indigo-600 transition-colors">অ্যানালিটিক্স</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">সিএসভি রিপোর্ট</span>
            </a>
        </div>
    </div>

    <!-- 3. Online Courses & E-Learning Stats -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                ই-লার্নিং ও অনলাইন কোর্স পারফরম্যান্স (E-Learning LMS Stats)
            </h3>
            <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                কোর্স তালিকা →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">মোট কোর্স</span>
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalCourses }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">নিবন্ধিত শিক্ষার্থী</span>
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalStudents }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">কোর্স বিক্রয় সংখ্যা</span>
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalCourseSales }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">অনলাইন মোট রাজস্ব</span>
                    <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600">৳{{ number_format($totalRevenue, 2) }}</span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Quick Shortcuts & Actions -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">কনটেন্ট ও কোর্স অ্যাকশন (Content Management)</h3>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                নতুন কোর্স প্রকাশ করুন
            </a>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs transition-colors">
                + নতুন ক্যাটাগরি
            </a>
            <a href="{{ route('admin.exams.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs transition-colors">
                + অনলাইন কুইজ পরীক্ষা
            </a>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors">
                লেনদেন ও অর্ডার তালিকা
            </a>
        </div>
    </div>

    <!-- 5. Recent Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">সাম্প্রতিক কোর্স অর্ডার ও পেমেন্ট</h3>
                <p class="text-xs text-slate-500">অনলাইনে সফল ও অপেক্ষমান কোর্সের এনরোলমেন্ট ডাটা</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">
                সকল অর্ডার দেখুন →
            </a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="py-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <p class="text-xs">এখনও কোনো অর্ডার সম্পন্ন হয়নি।</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider">
                        <tr>
                            <th class="p-3">অর্ডার নম্বর</th>
                            <th class="p-3">শিক্ষার্থী</th>
                            <th class="p-3">কোর্সের নাম</th>
                            <th class="p-3">মূল্য</th>
                            <th class="p-3">পেমেন্ট স্ট্যাটাস</th>
                            <th class="p-3">তারিখ</th>
                            <th class="p-3 text-right">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="p-3 font-mono font-bold text-slate-900">{{ $order->order_number }}</td>
                                <td class="p-3">
                                    <span class="font-semibold text-slate-900 block">{{ $order->user->name }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $order->user->email }}</span>
                                </td>
                                <td class="p-3 font-medium text-slate-800 truncate max-w-xs">
                                    {{ $order->items->first()?->course?->title ?? 'N/A' }}
                                </td>
                                <td class="p-3 font-bold text-slate-900">৳{{ number_format($order->total_amount, 2) }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($order->payment_status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                        {{ $order->payment_status === 'paid' ? 'পরিশোধিত' : ($order->payment_status === 'pending' ? 'অপেক্ষমাণ' : $order->payment_status) }}
                                    </span>
                                </td>
                                <td class="p-3 text-slate-500">{{ $order->created_at->format('d M, Y h:i A') }}</td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 font-bold hover:underline">
                                        বিস্তারিত
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
