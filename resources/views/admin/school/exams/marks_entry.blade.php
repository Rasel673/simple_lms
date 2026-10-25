@extends('layouts.admin')

@section('title', 'পরীক্ষার নম্বর এন্ট্রি শিট')
@section('page_title', 'পরীক্ষার নম্বর এন্ট্রি ও ফলাফল')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-xl font-bold text-slate-800">বিষয়ভিত্তিক পরীক্ষার নম্বর এন্ট্রি</h1>
            <p class="text-xs text-slate-500 mt-1">পরীক্ষা, শ্রেণি ও বিষয় নির্বাচন করে রোল অনুযায়ী শিক্ষার্থীদের প্রাপ্ত নম্বর এন্ট্রি দিন</p>
        </div>
        <a href="{{ route('admin.school.exams.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            পরীক্ষা তালিকায় ফিরুন
        </a>
    </div>

    <!-- Selection Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.school.exams.marks.entry') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">পরীক্ষা *</label>
                <select name="exam_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="">পরীক্ষা নির্বাচন করুন</option>
                    @foreach($exams as $ex)
                        <option value="{{ $ex->id }}" {{ $examId == $ex->id ? 'selected' : '' }}>{{ $ex->name }} ({{ $ex->session_year }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শ্রেণি *</label>
                <select name="class_id" required onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="">শ্রেণি নির্বাচন করুন</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শাখা (ঐচ্ছিক)</label>
                <select name="section_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="">সকল শাখা</option>
                    @foreach($sections as $sec)
                        <option value="{{ $sec->id }}" {{ $sectionId == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">বিষয় *</label>
                <select name="subject_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="">বিষয় নির্বাচন করুন</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}" {{ $subjectId == $sub->id ? 'selected' : '' }}>{{ $sub->name }} (পূর্ণমান: {{ $sub->total_marks }})</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-800 text-white font-semibold text-xs hover:bg-slate-900 transition-colors">
                    নম্বর শিট লোড করুন
                </button>
            </div>
        </form>
    </div>

    <!-- Marks Entry Table -->
    @if($examId && $classId && $subjectId && $selectedSubject)
        <form action="{{ route('admin.school.exams.marks.save') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            @csrf
            <input type="hidden" name="school_exam_id" value="{{ $examId }}">
            <input type="hidden" name="subject_id" value="{{ $subjectId }}">

            <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                        নম্বর তালিকা: {{ $selectedSubject->name }} (পূর্ণমান: {{ $selectedSubject->total_marks }} | পাস নম্বর: {{ $selectedSubject->pass_marks }})
                    </h2>
                </div>
                <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-xs">
                    নম্বর সংরক্ষণ করুন
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3">রোল</th>
                            <th class="px-4 py-3">ভর্তি নং</th>
                            <th class="px-4 py-3">শিক্ষার্থীর নাম</th>
                            <th class="px-4 py-3">প্রাপ্ত নম্বর (সর্বোচ্চ {{ (int)$selectedSubject->total_marks }})</th>
                            <th class="px-4 py-3">বর্তমান গ্রেড ও পয়েন্ট</th>
                            <th class="px-4 py-3">মন্তব্য</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $st)
                            @php
                                $m = $marks->get($st->id);
                            @endphp
                            <tr class="hover:bg-slate-50/60">
                                <td class="px-4 py-3.5 font-bold text-slate-800">#{{ $st->roll_number }}</td>
                                <td class="px-4 py-3.5 font-mono text-slate-500">{{ $st->admission_number }}</td>
                                <td class="px-4 py-3.5 font-bold text-slate-900">{{ $st->name }}</td>
                                <td class="px-4 py-3.5">
                                    <input type="number" step="0.5" min="0" max="{{ $selectedSubject->total_marks }}" name="marks[{{ $st->id }}][obtained]" value="{{ $m ? $m->marks_obtained : '' }}" placeholder="0.00" class="w-28 text-xs font-bold rounded-lg border-slate-300 py-1.5 px-2.5 focus:border-amber-500 focus:ring-amber-500">
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($m)
                                        <span class="px-2 py-0.5 rounded font-bold text-[10px] {{ $m->grade === 'A+' ? 'bg-emerald-100 text-emerald-800' : ($m->grade === 'F' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $m->grade }} (GPA: {{ $m->grade_point }})
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">এন্ট্রি হয়নি</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="text-slate-500">{{ $m ? $m->remarks : '—' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400">কোনো শিক্ষার্থী পাওয়া যায়নি</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700 transition-colors shadow-xs">
                    নম্বর সংরক্ষণ করুন
                </button>
            </div>
        </form>
    @else
        <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-400 text-xs">
            নম্বর এন্ট্রি করতে উপরে পরীক্ষা, শ্রেণি ও বিষয় নির্বাচন করুন।
        </div>
    @endif
</div>
@endsection
