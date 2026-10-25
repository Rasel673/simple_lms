@extends('layouts.admin')

@section('title', 'শিক্ষার্থী প্রোফাইল - ' . $student->name)
@section('page_title', 'শিক্ষার্থীর পূর্ণাঙ্গ প্রোফাইল')

@section('content')
<div class="space-y-6">
    <!-- Profile Card Header -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white font-bold text-2xl flex items-center justify-center shadow-md shadow-orange-500/20">
                {{ mb_substr($student->name, 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-800">{{ $student->name }}</h1>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                        {{ $student->status === 'active' ? 'নিয়মিত শিক্ষার্থী' : $student->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    ভর্তি নম্বর: <span class="font-mono font-semibold text-slate-700">{{ $student->admission_number }}</span> |
                    শ্রেণি: <span class="font-semibold text-slate-700">{{ $student->academicClass->name ?? '' }}</span> |
                    রোল: <span class="font-semibold text-slate-700">#{{ $student->roll_number }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.school.students.edit', $student) }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                তথ্য সম্পাদনা
            </a>
            <a href="{{ route('admin.school.students.index') }}" class="px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors">
                তালিকায় ফিরুন
            </a>
        </div>
    </div>

    <!-- Personal & Guardian Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-3">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">ব্যক্তিগত ও অ্যাকাডেমিক বিবরণ</h2>
            <div class="grid grid-cols-2 gap-y-2 text-xs">
                <span class="text-slate-400">শ্রেণি:</span>
                <span class="font-semibold text-slate-800">{{ $student->academicClass->name ?? '' }}</span>
                <span class="text-slate-400">শাখা:</span>
                <span class="font-semibold text-slate-800">{{ $student->section->name ?? 'N/A' }}</span>
                <span class="text-slate-400">গ্রুপ:</span>
                <span class="font-semibold text-slate-800">{{ $student->group }}</span>
                <span class="text-slate-400">শিক্ষাবর্ষ:</span>
                <span class="font-semibold text-slate-800">{{ $student->session_year }}</span>
                <span class="text-slate-400">লিঙ্গ:</span>
                <span class="font-semibold text-slate-800">{{ $student->gender === 'male' ? 'ছাত্র' : 'ছাত্রী' }}</span>
                <span class="text-slate-400">রক্তের গ্রুপ:</span>
                <span class="font-semibold text-rose-600">{{ $student->blood_group ?? '—' }}</span>
                <span class="text-slate-400">জন্ম তারিখ:</span>
                <span class="font-semibold text-slate-800">{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d M, Y') : '—' }}</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-3">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2">অভিভাবক ও যোগাযোগের তথ্য</h2>
            <div class="grid grid-cols-2 gap-y-2 text-xs">
                <span class="text-slate-400">অভিভাবকের নাম:</span>
                <span class="font-semibold text-slate-800">{{ $student->guardian_name ?? '—' }}</span>
                <span class="text-slate-400">মোবাইল নম্বর:</span>
                <span class="font-semibold text-slate-800">{{ $student->guardian_phone ?? '—' }}</span>
                <span class="text-slate-400">বর্তমান ঠিকানা:</span>
                <span class="font-semibold text-slate-800">{{ $student->address ?? '—' }}</span>
                <span class="text-slate-400">ভর্তির তারিখ:</span>
                <span class="font-semibold text-slate-800">{{ $student->created_at->format('d M, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Tabbed Exam Marks & Fee Invoices -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">পরীক্ষার ফলাফল ও নম্বর বিবরণী</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">বিষয়</th>
                        <th class="px-4 py-3">পূর্ণমান</th>
                        <th class="px-4 py-3">প্রাপ্ত নম্বর</th>
                        <th class="px-4 py-3">লেটার গ্রেড</th>
                        <th class="px-4 py-3">গ্রেড পয়েন্ট</th>
                        <th class="px-4 py-3">মন্তব্য</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($student->marks as $m)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $m->subject->name ?? '' }}</td>
                            <td class="px-4 py-3">{{ $m->subject->total_marks ?? 100 }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $m->marks_obtained }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-md font-bold text-[10px] {{ $m->grade === 'A+' ? 'bg-emerald-100 text-emerald-800' : ($m->grade === 'F' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $m->grade }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-semibold">{{ $m->grade_point }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $m->remarks ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-6 text-slate-400">এখনো কোনো পরীক্ষার নম্বর এন্ট্রি করা হয়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
