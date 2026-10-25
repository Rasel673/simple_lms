@extends('layouts.admin')

@section('title', 'শিক্ষার্থী ব্যবস্থাপনা')
@section('page_title', 'শিক্ষার্থী তালিকা ও ভর্তি')

@section('content')
<div class="space-y-6">
    <!-- Header / Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>শিক্ষার্থী তথ্যভাণ্ডার</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষার্থী ভর্তি, রোল ও প্রোফাইল</h1>
            <p class="text-xs text-slate-500 mt-1">শ্রেণি, শাখা, রোল ও শিক্ষাবর্ষ অনুযায়ী শিক্ষার্থীর সম্পূর্ণ তথ্য সংরক্ষণ</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.school.students.promotion') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-blue-200 bg-blue-50/60 text-blue-700 text-xs font-semibold hover:bg-blue-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                শিক্ষার্থী প্রমোশন
            </a>
            <a href="{{ route('admin.school.students.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-xs shadow-amber-900/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                নতুন শিক্ষার্থী ভর্তি
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.school.students.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="নাম, ভর্তি নম্বর বা ফোন দিয়ে খুঁজুন..." class="w-full text-xs rounded-lg border-slate-200 py-2 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <select name="class_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-2 px-3 focus:border-amber-500 focus:ring-amber-500">
                <option value="">সকল শ্রেণি</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>

            @if($classId && $sections->isNotEmpty())
                <select name="section_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-2 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">সকল শাখা</option>
                    @foreach($sections as $s)
                        <option value="{{ $s->id }}" {{ $sectionId == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            @endif

            <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors">
                অনুসন্ধান
            </button>
            @if($search || $classId || $sectionId)
                <a href="{{ route('admin.school.students.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800">রিসেট</a>
            @endif
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">রোল</th>
                        <th class="px-4 py-3.5">ভর্তি নং</th>
                        <th class="px-4 py-3.5">শিক্ষার্থীর নাম</th>
                        <th class="px-4 py-3.5">শ্রেণি ও শাখা</th>
                        <th class="px-4 py-3.5">গ্রুপ</th>
                        <th class="px-4 py-3.5">অভিভাবক ও ফোন</th>
                        <th class="px-4 py-3.5">রক্তের গ্রুপ</th>
                        <th class="px-4 py-3.5">স্ট্যাটাস</th>
                        <th class="px-4 py-3.5 text-right">পদক্ষেপ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 font-bold text-slate-800">
                                <span class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 font-bold flex items-center justify-center border border-amber-200">
                                    {{ $student->roll_number }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-medium text-slate-500">{{ $student->admission_number }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-slate-900">{{ $student->name }}</div>
                                <div class="text-[10px] text-slate-400">সেশন: {{ $student->session_year }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium">
                                    {{ $student->academicClass->name ?? '' }}
                                    @if($student->section)
                                        ({{ $student->section->name }})
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3.5">{{ $student->group }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-medium text-slate-800">{{ $student->guardian_name ?? '—' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $student->guardian_phone ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($student->blood_group)
                                    <span class="px-1.5 py-0.5 rounded bg-rose-50 text-rose-600 font-bold text-[10px] border border-rose-200">
                                        {{ $student->blood_group }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $student->status === 'active' ? 'bg-emerald-100 text-emerald-800' : ($student->status === 'promoted' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $student->status === 'active' ? 'নিয়মিত' : ($student->status === 'promoted' ? 'প্রমোটেড' : $student->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.school.students.show', $student) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-slate-100 transition-colors" title="প্রোফাইল দেখুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.school.students.edit', $student) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-slate-100 transition-colors" title="সম্পাদনা">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.school.students.destroy', $student) }}" method="POST" class="inline-block" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই শিক্ষার্থীকে মুছে ফেলতে চান?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="মুছে ফেলুন">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-slate-400">কোনো শিক্ষার্থীর তথ্য পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
