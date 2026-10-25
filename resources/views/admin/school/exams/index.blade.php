@extends('layouts.admin')

@section('title', 'পরীক্ষা ও ফলাফল ব্যবস্থাপনা')
@section('page_title', 'প্রাতিষ্ঠানিক পরীক্ষা ও গ্রেডিং')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-rose-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                <span>পরীক্ষা ও গ্রেডিং সিস্টেম</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">পরীক্ষা ও ফলাফল ব্যবস্থাপনা</h1>
            <p class="text-xs text-slate-500 mt-1">সাময়িক পরীক্ষা, নম্বর এন্ট্রি, অটোমেটেড জিপিএ ও প্রিন্ট উপযোগী মার্কশিট</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.school.exams.marks.entry') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700 transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                নম্বর এন্ট্রি করুন
            </a>
            <a href="{{ route('admin.school.exams.tabulation') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-rose-200 bg-rose-50/60 text-rose-700 font-semibold text-xs hover:bg-rose-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                ফলাফল ও টেবুলেশন শিট
            </a>
        </div>
    </div>

    <!-- Create New Exam Form -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <h2 class="text-sm font-bold text-slate-800 mb-4">নতুন পরীক্ষা তৈরি করুন</h2>
        <form action="{{ route('admin.school.exams.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">পরীক্ষার নাম *</label>
                <input type="text" name="name" required placeholder="e.g. ১ম সাময়িক পরীক্ষা ২০২৬, বার্ষিক পরীক্ষা ২০২৬" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শিক্ষাবর্ষ *</label>
                <input type="number" name="session_year" value="2026" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শুরুর তারিখ *</label>
                <input type="date" name="start_date" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">সমাপ্তির তারিখ</label>
                <input type="date" name="end_date" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div class="flex items-center gap-2 pb-3">
                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                    <input type="checkbox" name="is_published" value="1" checked class="rounded text-amber-600 focus:ring-amber-500">
                    <span>ফলাফল প্রকাশিত</span>
                </label>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-slate-800 text-white font-semibold text-xs hover:bg-slate-900 transition-colors">
                    পরীক্ষা তৈরি করুন
                </button>
            </div>
        </form>
    </div>

    <!-- Active Exams List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h2 class="text-xs font-bold text-slate-700 uppercase">সকল পরীক্ষা ও ফলাফল স্ট্যাটাস</h2>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($exams as $exam)
                <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2.5">
                            <h3 class="text-sm font-bold text-slate-900">{{ $exam->name }}</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $exam->is_published ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $exam->is_published ? 'প্রকাশিত' : 'অপ্রকাশিত' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500">
                            শিক্ষাবর্ষ: <span class="font-semibold text-slate-700">{{ $exam->session_year }}</span> |
                            তারিখ: {{ \Carbon\Carbon::parse($exam->start_date)->format('d M, Y') }}
                            @if($exam->end_date) - {{ \Carbon\Carbon::parse($exam->end_date)->format('d M, Y') }} @endif
                            | মোট এন্ট্রিকৃত নম্বর: <span class="font-bold text-slate-800">{{ $exam->marks_count }} টি</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.school.exams.marks.entry', ['exam_id' => $exam->id]) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                            নম্বর এন্ট্রি
                        </a>
                        <a href="{{ route('admin.school.exams.tabulation', ['exam_id' => $exam->id]) }}" class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold hover:bg-rose-100 transition-colors">
                            টেবুলেশন শিট
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">কোনো পরীক্ষা তৈরি করা হয়নি</div>
            @endforelse
        </div>
    </div>

    <!-- Bangladesh Standard GPA Grading Scale Matrix -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">জাতীয় শিক্ষাক্রম গ্রেডিং পলিসি (GPA Scale 5.00)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">লেটার গ্রেড</th>
                        <th class="px-4 py-2.5">নম্বরের পরিসর</th>
                        <th class="px-4 py-2.5">গ্রেড পয়েন্ট (GPA)</th>
                        <th class="px-4 py-2.5">মন্তব্য (Remarks)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($gradeRules as $gr)
                        <tr>
                            <td class="px-4 py-2.5 font-bold {{ $gr->grade_name === 'F' ? 'text-rose-600' : 'text-slate-800' }}">{{ $gr->grade_name }}</td>
                            <td class="px-4 py-2.5">{{ (int)$gr->min_mark }}% - {{ (int)$gr->max_mark }}%</td>
                            <td class="px-4 py-2.5 font-bold font-mono">{{ number_format($gr->grade_point, 2) }}</td>
                            <td class="px-4 py-2.5 text-slate-500">{{ $gr->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
