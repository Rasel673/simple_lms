@extends('layouts.admin')

@section('title', 'শিক্ষার্থী প্রমোশন')
@section('page_title', 'শিক্ষার্থী পরবর্তী শ্রেণিতে প্রমোশন')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষার্থী প্রমোশন ম্যানেজমেন্ট</h1>
            <p class="text-xs text-slate-500 mt-1">শিক্ষাবর্ষ শেষে এক শ্রেণি থেকে পরবর্তী শ্রেণিতে শিক্ষার্থীদের এক ক্লিকে প্রমোশন দিন</p>
        </div>
        <a href="{{ route('admin.school.students.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            শিক্ষার্থী তালিকায় ফিরুন
        </a>
    </div>

    <!-- Step 1: Select Source Class -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <h2 class="text-sm font-bold text-slate-800 mb-4">ধাপ ১: বর্তমান শ্রেণি ও শাখা নির্বাচন করুন</h2>
        <form method="GET" action="{{ route('admin.school.students.promotion') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-700 mb-1">বর্তমান শ্রেণি</label>
                <select name="from_class_id" required onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">শ্রেণি নির্বাচন করুন</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $fromClassId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            @if($fromClassId)
                @php $fromSections = \App\Models\Section::where('academic_class_id', $fromClassId)->get(); @endphp
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">বর্তমান শাখা</label>
                    <select name="from_section_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                        <option value="">সকল শাখা</option>
                        @foreach($fromSections as $fs)
                            <option value="{{ $fs->id }}" {{ $fromSectionId == $fs->id ? 'selected' : '' }}>{{ $fs->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="self-end">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors">
                    শিক্ষার্থী লোড করুন
                </button>
            </div>
        </form>
    </div>

    <!-- Step 2: Target Promotion Form -->
    @if($fromClassId && $students->isNotEmpty())
        <form action="{{ route('admin.school.students.promotion.process') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
                <h2 class="text-sm font-bold text-slate-800 mb-4">ধাপ ২: প্রমোশনের লক্ষ্য শ্রেণি ও শিক্ষাবর্ষ নির্ধারণ</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">পরবর্তী শ্রেণি (Target Class) *</label>
                        <select name="to_class_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                            <option value="">পরবর্তী শ্রেণি নির্বাচন করুন</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">পরবর্তী শিক্ষাবর্ষ *</label>
                        <input type="number" name="to_session_year" value="2027" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <!-- Student Selection List -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 uppercase">প্রমোশনের জন্য শিক্ষার্থী নির্বাচন (মোট: {{ $students->count() }})</span>
                    <button type="button" onclick="document.querySelectorAll('.student-cb').forEach(cb => cb.checked = !cb.checked)" class="text-xs font-semibold text-blue-600 hover:underline">
                        সবগুলো সিলেক্ট/আনসিলেক্ট
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 w-12 text-center">সিলেক্ট</th>
                                <th class="px-4 py-3">রোল</th>
                                <th class="px-4 py-3">ভর্তি নং</th>
                                <th class="px-4 py-3">নাম</th>
                                <th class="px-4 py-3">বর্তমান শ্রেণি ও শাখা</th>
                                <th class="px-4 py-3">স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($students as $st)
                                <tr class="hover:bg-slate-50/60">
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" name="student_ids[]" value="{{ $st->id }}" checked class="student-cb rounded text-amber-600 focus:ring-amber-500">
                                    </td>
                                    <td class="px-4 py-3 font-bold text-slate-800">#{{ $st->roll_number }}</td>
                                    <td class="px-4 py-3 font-mono text-slate-500">{{ $st->admission_number }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-900">{{ $st->name }}</td>
                                    <td class="px-4 py-3">{{ $st->academicClass->name ?? '' }} ({{ $st->section->name ?? '' }})</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                            {{ $st->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-semibold text-xs hover:bg-blue-700 transition-colors shadow-xs">
                        নির্বাচিত শিক্ষার্থীদের প্রমোশন নিশ্চিত করুন
                    </button>
                </div>
            </div>
        </form>
    @elseif($fromClassId)
        <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 text-xs">
            এই শ্রেণিতে কোনো শিক্ষার্থী পাওয়া যায়নি।
        </div>
    @endif
</div>
@endsection
