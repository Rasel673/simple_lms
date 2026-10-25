@extends('layouts.admin')

@section('title', 'ফিঙ্গারপ্রিন্ট ও বায়োমেট্রিক হাজিরা')
@section('page_title', 'ফিঙ্গারপ্রিন্ট হাজিরা ব্যবস্থাপনা')

@section('content')
<div class="space-y-6">
    <!-- Header / Stats -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-orange-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004.07 8.5"></path></svg>
                <span>বায়োমেট্রিক হাজিরা সিস্টেম</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষার্থী ও শিক্ষক দৈনিক হাজিরা</h1>
            <p class="text-xs text-slate-500 mt-1">ফিঙ্গারপ্রিন্ট ডিভাইস ও ম্যানুয়াল উপস্থিতি রেকর্ডের সমন্বিত ট্র্যাকিং</p>
        </div>

        <div class="flex items-center gap-2">
            <form action="{{ route('admin.school.attendance.sync') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="type" value="{{ $type }}">
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 text-white font-semibold text-xs shadow-md shadow-orange-500/20 hover:from-orange-600 hover:to-amber-700 transition-all">
                    <svg class="w-4 h-4 animate-spin-hover" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    বায়োমেট্রিক ডিভাইস থেকে সিঙ্ক করুন
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <span class="text-xs text-slate-500">মোট তালিকাভুক্ত</span>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-100 bg-emerald-50/20 shadow-xs">
            <span class="text-xs text-emerald-700 font-medium">উপস্থিত (Present)</span>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['present'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-amber-100 bg-amber-50/20 shadow-xs">
            <span class="text-xs text-amber-700 font-medium">বিলম্বিত (Late)</span>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['late'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-rose-100 bg-rose-50/20 shadow-xs">
            <span class="text-xs text-rose-700 font-medium">অনুপস্থিত (Absent)</span>
            <p class="text-2xl font-bold text-rose-600 mt-1">{{ $stats['absent'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-blue-100 bg-blue-50/20 shadow-xs">
            <span class="text-xs text-blue-700 font-medium">উপস্থিতির হার</span>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['rate'] }}%</p>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.school.attendance.index') }}" class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-wrap items-center gap-3">
        <div class="flex items-center gap-2">
            <label class="text-xs font-semibold text-slate-700">ধরন:</label>
            <select name="type" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-1.5 px-2.5 focus:border-amber-500 focus:ring-amber-500">
                <option value="student" {{ $type === 'student' ? 'selected' : '' }}>শিক্ষার্থী (Students)</option>
                <option value="staff" {{ $type === 'staff' ? 'selected' : '' }}>শিক্ষক ও স্টাফ (Teachers & Staff)</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <label class="text-xs font-semibold text-slate-700">তারিখ:</label>
            <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-1.5 px-2.5 focus:border-amber-500 focus:ring-amber-500">
        </div>

        @if($type === 'student')
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-700">শ্রেণি:</label>
                <select name="class_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-1.5 px-2.5 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">সকল শ্রেণি</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            @if($classId && $sections->isNotEmpty())
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-slate-700">শাখা:</label>
                    <select name="section_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-1.5 px-2.5 focus:border-amber-500 focus:ring-amber-500">
                        <option value="">সকল শাখা</option>
                        @foreach($sections as $s)
                            <option value="{{ $s->id }}" {{ $sectionId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        @endif

        <button type="submit" class="px-4 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-medium hover:bg-slate-900 transition-colors">
            ফিল্টার করুন
        </button>
    </form>

    <!-- Attendance Form & Table -->
    <form action="{{ route('admin.school.attendance.save') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="type" value="{{ $type }}">

        <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                {{ $type === 'student' ? 'শিক্ষার্থী হাজিরা শিট' : 'শিক্ষক ও স্টাফ হাজিরা শিট' }} ({{ \Carbon\Carbon::parse($date)->format('d M, Y') }})
            </span>
            <button type="submit" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-xs">
                হাজিরা সংরক্ষণ করুন
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">রোল / আইডি</th>
                        <th class="px-4 py-3">নাম</th>
                        @if($type === 'student')
                            <th class="px-4 py-3">শ্রেণি ও শাখা</th>
                        @else
                            <th class="px-4 py-3">পদবি</th>
                        @endif
                        <th class="px-4 py-3">উপস্থিতি স্ট্যাটাস</th>
                        <th class="px-4 py-3">প্রবেশ সময় (In)</th>
                        <th class="px-4 py-3">প্রস্থান সময় (Out)</th>
                        <th class="px-4 py-3">উৎস (Source)</th>
                        <th class="px-4 py-3">মন্তব্য</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if($type === 'student')
                        @forelse($students as $st)
                            @php
                                $rec = $records->get($st->id);
                                $status = $rec ? $rec->status : 'present';
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-3 font-bold text-slate-800">#{{ $st->roll_number }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $st->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $st->admission_number }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-medium">
                                        {{ $st->academicClass->name ?? '' }} - {{ $st->section->name ?? '' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $st->id }}][status]" value="present" {{ $status === 'present' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-emerald-700 font-medium">উপস্থিত</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $st->id }}][status]" value="late" {{ $status === 'late' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                                            <span class="text-amber-700 font-medium">বিলম্ব</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $st->id }}][status]" value="absent" {{ $status === 'absent' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                                            <span class="text-rose-700 font-medium">অনুপস্থিত</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="time" name="attendance[{{ $st->id }}][in_time]" value="{{ $rec && $rec->check_in_time ? substr($rec->check_in_time, 0, 5) : '09:00' }}" class="text-xs rounded border-slate-200 py-1 px-2 focus:border-amber-500 focus:ring-amber-500 w-24">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="time" name="attendance[{{ $st->id }}][out_time]" value="{{ $rec && $rec->check_out_time ? substr($rec->check_out_time, 0, 5) : '14:00' }}" class="text-xs rounded border-slate-200 py-1 px-2 focus:border-amber-500 focus:ring-amber-500 w-24">
                                </td>
                                <td class="px-4 py-3">
                                    @if($rec && $rec->source === 'biometric')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 text-[10px] font-semibold">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 4a1 1 0 112 0v4a1 1 0 11-2 0V6zm1 8a1 1 0 100-2 1 1 0 000 2z"></path></svg>
                                            ফিঙ্গারপ্রিন্ট
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px]">ম্যানুয়াল</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="attendance[{{ $st->id }}][remarks]" value="{{ $rec ? $rec->remarks : '' }}" placeholder="মন্তব্য" class="text-xs rounded border-slate-200 py-1 px-2 focus:border-amber-500 focus:ring-amber-500 w-36">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400">কোনো শিক্ষার্থী পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    @else
                        @forelse($staffList as $stf)
                            @php
                                $rec = $records->get($stf->id);
                                $status = $rec ? $rec->status : 'present';
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-3 font-bold text-slate-800">{{ $stf->employee_id }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900">{{ $stf->name }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $stf->phone }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-medium border border-emerald-200">
                                        {{ $stf->designation->name ?? 'স্টাফ' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $stf->id }}][status]" value="present" {{ $status === 'present' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-emerald-700 font-medium">উপস্থিত</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $stf->id }}][status]" value="late" {{ $status === 'late' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                                            <span class="text-amber-700 font-medium">বিলম্ব</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="attendance[{{ $stf->id }}][status]" value="absent" {{ $status === 'absent' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                                            <span class="text-rose-700 font-medium">অনুপস্থিত</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="time" name="attendance[{{ $stf->id }}][in_time]" value="{{ $rec && $rec->check_in_time ? substr($rec->check_in_time, 0, 5) : '08:30' }}" class="text-xs rounded border-slate-200 py-1 px-2 focus:border-amber-500 focus:ring-amber-500 w-24">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="time" name="attendance[{{ $stf->id }}][out_time]" value="{{ $rec && $rec->check_out_time ? substr($rec->check_out_time, 0, 5) : '15:30' }}" class="text-xs rounded border-slate-200 py-1 px-2 focus:border-amber-500 focus:ring-amber-500 w-24">
                                </td>
                                <td class="px-4 py-3">
                                    @if($rec && $rec->source === 'biometric')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 text-[10px] font-semibold">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 4a1 1 0 112 0v4a1 1 0 11-2 0V6zm1 8a1 1 0 100-2 1 1 0 000 2z"></path></svg>
                                            বায়োমেট্রিক
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px]">ম্যানুয়াল</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="attendance[{{ $stf->id }}][remarks]" value="{{ $rec ? $rec->remarks : '' }}" placeholder="মন্তব্য" class="text-xs rounded border-slate-200 py-1 px-2 focus:border-amber-500 focus:ring-amber-500 w-36">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-slate-400">কোনো শিক্ষক/স্টাফ পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 flex justify-end bg-slate-50/50">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-xs">
                হাজিরা সংরক্ষণ করুন
            </button>
        </div>
    </form>
</div>
@endsection
