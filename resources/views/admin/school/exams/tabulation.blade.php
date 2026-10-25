@extends('layouts.admin')

@section('title', 'ফলাফল ও টেবুলেশন শিট')
@section('page_title', 'শ্রেণিভিত্তিক ফলাফল শিট')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-xl font-bold text-slate-800">পরীক্ষার ফলাফল ও টেবুলেশন শিট (Tabulation Sheet)</h1>
            <p class="text-xs text-slate-500 mt-1">শ্রেণিভিত্তিক সকল শিক্ষার্থীর বিষয়ওয়ারি নম্বর, জিপিএ, লেটার গ্রেড ও ট্রান্সক্রিপ্ট প্রিন্ট</p>
        </div>
        <div class="flex items-center gap-2">
            @if($results->isNotEmpty())
                <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    টেবুলেশন প্রিন্ট করুন
                </button>
            @endif
            <a href="{{ route('admin.school.exams.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                পরীক্ষা তালিকায় ফিরুন
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.school.exams.tabulation') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-700">পরীক্ষা:</label>
                <select name="exam_id" required class="text-xs rounded-lg border-slate-200 py-1.5 px-3">
                    <option value="">পরীক্ষা নির্বাচন করুন</option>
                    @foreach($exams as $ex)
                        <option value="{{ $ex->id }}" {{ $examId == $ex->id ? 'selected' : '' }}>{{ $ex->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-700">শ্রেণি:</label>
                <select name="class_id" required onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-1.5 px-3">
                    <option value="">শ্রেণি নির্বাচন করুন</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            @if($classId && $sections->isNotEmpty())
                <div class="flex items-center gap-2">
                    <label class="text-xs font-semibold text-slate-700">শাখা:</label>
                    <select name="section_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-1.5 px-3">
                        <option value="">সকল শাখা</option>
                        @foreach($sections as $sec)
                            <option value="{{ $sec->id }}" {{ $sectionId == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <button type="submit" class="px-4 py-1.5 rounded-lg bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors">
                ফলাফল দেখুন
            </button>
        </form>
    </div>

    <!-- Tabulation Table -->
    @if($results->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                    ফলাফল টেবুলেশন ব্রডশিট (মোট শিক্ষার্থী: {{ $results->count() }})
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-3 py-3">রোল</th>
                            <th class="px-3 py-3">নাম</th>
                            @foreach($subjects as $sub)
                                <th class="px-3 py-3 text-center border-l border-slate-200">{{ $sub->name }}</th>
                            @endforeach
                            <th class="px-3 py-3 text-center border-l border-slate-200">মোট নম্বর</th>
                            <th class="px-3 py-3 text-center">জিপিএ (GPA)</th>
                            <th class="px-3 py-3 text-center">গ্রেড</th>
                            <th class="px-3 py-3 text-center">রিপোর্ট কার্ড</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($results as $res)
                            @php $st = $res['student']; @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-3 py-3 font-bold text-slate-800">#{{ $st->roll_number }}</td>
                                <td class="px-3 py-3 font-semibold text-slate-900">{{ $st->name }}</td>

                                @foreach($subjects as $sub)
                                    @php $score = $res['scores'][$sub->id] ?? null; @endphp
                                    <td class="px-3 py-3 text-center border-l border-slate-100">
                                        @if($score && $score['marks'] !== null)
                                            <div class="font-bold text-slate-800">{{ $score['marks'] }}</div>
                                            <div class="text-[9px] font-semibold {{ $score['grade'] === 'F' ? 'text-rose-600' : 'text-slate-400' }}">{{ $score['grade'] }}</div>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="px-3 py-3 text-center font-bold text-slate-900 border-l border-slate-200 bg-slate-50/30">
                                    {{ $res['total_marks'] }}
                                </td>
                                <td class="px-3 py-3 text-center font-bold font-mono text-blue-700">
                                    {{ number_format($res['gpa'], 2) }}
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-md font-bold text-[10px] {{ $res['final_grade'] === 'A+' ? 'bg-emerald-100 text-emerald-800' : ($res['final_grade'] === 'F' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $res['final_grade'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <a href="{{ route('admin.school.exams.report_card', ['student' => $st->id, 'exam' => $examId]) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 font-semibold text-[10px] hover:bg-amber-100 border border-amber-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        প্রিন্ট কার্ড
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($examId && $classId)
        <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-400 text-xs">
            এই শ্রেণিতে কোনো ফলাফল তৈরি হয়নি বা নম্বর এন্ট্রি সম্পন্ন হয়নি।
        </div>
    @endif
</div>
@endsection
