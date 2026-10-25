@extends('layouts.admin')

@section('title', 'ক্লাস রুটিন ও সময়সূচী')
@section('page_title', 'ক্লাস রুটিন ব্যবস্থাপনা')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-purple-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>অ্যাকাডেমিক সময়সূচী</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">শ্রেণিভিত্তিক ক্লাস রুটিন (Timetable)</h1>
            <p class="text-xs text-slate-500 mt-1">শ্রেণি, শাখা, বার এবং পিরিয়ড অনুযায়ী দৈনিক ক্লাস রুটিন তৈরি ও প্রদর্শন</p>
        </div>

        <!-- Filter / Submenu tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.school.academics.routines') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-purple-600 text-white shadow-xs">ক্লাস রুটিন</a>
            <a href="{{ route('admin.school.academics.syllabi') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">সিলেবাস</a>
            <a href="{{ route('admin.school.academics.homework') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">হোমওয়ার্ক</a>
            <a href="{{ route('admin.school.academics.calendar') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">ক্যালেন্ডার</a>
        </div>
    </div>

    <!-- Filter Class & Section -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.school.academics.routines') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-700">শ্রেণি নির্বাচন:</label>
                <select name="class_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-1.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">শ্রেণি নির্বাচন করুন</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            @if($classId && $sections->isNotEmpty())
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-slate-700">শাখা:</label>
                    <select name="section_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-1.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                        <option value="">সকল শাখা</option>
                        @foreach($sections as $s)
                            <option value="{{ $s->id }}" {{ $sectionId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <button type="submit" class="px-4 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors">
                রুটিন দেখুন
            </button>
        </form>
    </div>

    <!-- Add New Routine Period Form -->
    @if($classId)
        @php
            $currentClass = $classes->find($classId);
            $classSubjects = $currentClass ? $currentClass->subjects : collect();
        @endphp
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <h2 class="text-sm font-bold text-slate-800 mb-4">রুটিনে নতুন পিরিয়ড যুক্ত করুন ({{ $currentClass->name }})</h2>
            <form action="{{ route('admin.school.academics.routines.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-7 gap-3 items-end">
                @csrf
                <input type="hidden" name="academic_class_id" value="{{ $classId }}">
                <input type="hidden" name="section_id" value="{{ $sectionId }}">

                <div class="md:col-span-1">
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">বার (Day) *</label>
                    <select name="day_of_week" required class="w-full text-xs rounded-lg border-slate-200 py-2 px-2.5">
                        <option value="Saturday">শনিবার</option>
                        <option value="Sunday">রবিবার</option>
                        <option value="Monday">সোমবার</option>
                        <option value="Tuesday">মঙ্গলবার</option>
                        <option value="Wednesday">বুধবার</option>
                        <option value="Thursday">বৃহস্পতিবার</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">বিষয় (Subject) *</label>
                    <select name="subject_id" required class="w-full text-xs rounded-lg border-slate-200 py-2 px-2.5">
                        @foreach($classSubjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">শিক্ষক</label>
                    <select name="staff_profile_id" class="w-full text-xs rounded-lg border-slate-200 py-2 px-2.5">
                        <option value="">শিক্ষক নির্বাচন</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">শুরু ও শেষ সময় *</label>
                    <div class="flex items-center gap-1">
                        <input type="time" name="start_time" value="09:00" required class="w-full text-xs rounded border-slate-200 p-1.5">
                        <input type="time" name="end_time" value="09:45" required class="w-full text-xs rounded border-slate-200 p-1.5">
                    </div>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">কক্ষ নম্বর</label>
                    <input type="text" name="room_number" placeholder="১০১ নম্বর কক্ষ" class="w-full text-xs rounded-lg border-slate-200 py-2 px-2.5">
                </div>

                <div class="md:col-span-1">
                    <button type="submit" class="w-full py-2 px-3 rounded-lg bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700 transition-colors">
                        যুক্ত করুন
                    </button>
                </div>
            </form>
        </div>

        <!-- Weekly Timetable Grid -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">সাপ্তাহিক রুটিন শিট - {{ $currentClass->name }}</h2>
            </div>

            @php
                $days = [
                    'Saturday' => 'শনিবার (Saturday)',
                    'Sunday' => 'রবিবার (Sunday)',
                    'Monday' => 'সোমবার (Monday)',
                    'Tuesday' => 'মঙ্গলবার (Tuesday)',
                    'Wednesday' => 'বুধবার (Wednesday)',
                    'Thursday' => 'বৃহস্পতিবার (Thursday)',
                ];
            @endphp

            <div class="divide-y divide-slate-100">
                @foreach($days as $dayKey => $dayLabel)
                    <div class="p-4 flex flex-col md:flex-row md:items-start gap-4 hover:bg-slate-50/50 transition-colors">
                        <div class="w-40 shrink-0 font-bold text-xs text-slate-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            {{ $dayLabel }}
                        </div>

                        <div class="flex-1 flex flex-wrap gap-3">
                            @if(isset($routines[$dayKey]) && $routines[$dayKey]->isNotEmpty())
                                @foreach($routines[$dayKey] as $period)
                                    <div class="relative group bg-slate-50 rounded-xl p-3 border border-slate-200 min-w-[180px] shadow-xs">
                                        <div class="text-[11px] font-bold text-purple-700">{{ $period->subject->name ?? '' }}</div>
                                        <div class="text-[10px] text-slate-500 mt-0.5">
                                            {{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($period->end_time)->format('h:i A') }}
                                        </div>
                                        <div class="text-[10px] font-medium text-slate-700 mt-1 flex items-center justify-between">
                                            <span>{{ $period->teacher->name ?? 'শিক্ষক নির্ধারিত নয়' }}</span>
                                            @if($period->room_number)
                                                <span class="px-1.5 py-0.5 rounded bg-white border border-slate-200 text-[9px]">{{ $period->room_number }}</span>
                                            @endif
                                        </div>

                                        <form action="{{ route('admin.school.academics.routines.destroy', $period) }}" method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('পিরিয়ডটি মুছে ফেলতে চান?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded text-rose-500 hover:bg-rose-50">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-xs text-slate-400 italic py-1">এই দিনে কোনো ক্লাস নেই</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-400 text-xs">
            রুটিন দেখতে উপরে শ্রেণি নির্বাচন করুন।
        </div>
    @endif
</div>
@endsection
