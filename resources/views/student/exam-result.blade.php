@extends('layouts.student')

@section('title', 'পরীক্ষার ফলাফল - ' . $exam->title)
@section('page_title', 'পরীক্ষার ফলাফল')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Result Summary Banner -->
    <div class="bg-white rounded-3xl border {{ $attempt->is_passed ? 'border-emerald-300' : 'border-rose-300' }} p-8 sm:p-10 shadow-lg text-center space-y-6">
        <div class="w-16 h-16 rounded-3xl {{ $attempt->is_passed ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }} flex items-center justify-center mx-auto shadow-inner">
            @if($attempt->is_passed)
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            @else
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            @endif
        </div>

        <div>
            <span class="inline-block px-3.5 py-1 rounded-full text-xs font-black uppercase tracking-wider mb-2 {{ $attempt->is_passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                {{ $attempt->is_passed ? 'অভিনন্দন! আপনি উত্তীর্ণ হয়েছেন' : 'উত্তীর্ণ হতে পারেননি' }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">
                প্রাপ্ত নম্বর: {{ $attempt->score_percentage }}%
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                প্রয়োজনীয় পাস মার্ক: {{ $exam->passing_percentage }}% • চেষ্টা নং #{{ $attempt->attempt_number }}
            </p>
        </div>

        <!-- Breakdown cards -->
        <div class="grid grid-cols-3 gap-4 max-w-lg mx-auto pt-2">
            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200">
                <span class="text-[11px] text-slate-500 block">মোট প্রশ্ন</span>
                <span class="text-lg font-bold text-slate-800">{{ $attempt->total_questions }}টি</span>
            </div>
            <div class="p-3 bg-emerald-50 rounded-2xl border border-emerald-100">
                <span class="text-[11px] text-emerald-700 block">সঠিক উত্তর</span>
                <span class="text-lg font-bold text-emerald-700">{{ $attempt->correct_answers }}টি</span>
            </div>
            <div class="p-3 bg-rose-50 rounded-2xl border border-rose-100">
                <span class="text-[11px] text-rose-700 block">ভুল উত্তর</span>
                <span class="text-lg font-bold text-rose-700">{{ $attempt->incorrect_answers }}টি</span>
            </div>
        </div>

        <!-- Call to actions -->
        <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
            @if($attempt->is_passed)
                <a href="{{ route('student.certificates.index') }}" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition-all">
                    সার্টিফিকেট দেখুন ও ডাউনলোড করুন →
                </a>
            @elseif($exam->canAttempt(auth()->id()))
                <a href="{{ route('student.exam.start', $exam->id) }}" class="px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-md transition-all">
                    আবার পরীক্ষা দিন →
                </a>
            @endif

            <a href="{{ route('student.learning', ['course' => $exam->course_id]) }}" class="px-5 py-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs transition-colors">
                কোর্স লেসনে ফিরে যান
            </a>
        </div>
    </div>

    <!-- Question-by-Question Review -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">উত্তরমালার পর্যালোচনা</h3>

        <div class="space-y-6">
            @foreach($attempt->answers as $index => $ans)
                @php
                    $q = $ans->question;
                    $selected = $ans->selectedOption;
                    $correct = $q->correctOption;
                @endphp
                <div class="p-4 sm:p-5 rounded-2xl border {{ $ans->is_correct ? 'border-emerald-200 bg-emerald-50/20' : 'border-rose-200 bg-rose-50/20' }} space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-md font-bold text-xs flex items-center justify-center shrink-0 {{ $ans->is_correct ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                                {{ $ans->is_correct ? '✓' : '✗' }}
                            </span>
                            <h4 class="text-xs font-bold text-slate-900 leading-snug">
                                প্রশ্ন {{ $index + 1 }}. {{ $q->question_text }}
                            </h4>
                        </div>
                    </div>

                    <div class="text-xs space-y-1.5 pl-8">
                        <p class="text-slate-600">
                            <span class="font-semibold text-slate-500">আপনার উত্তর:</span>
                            <span class="{{ $ans->is_correct ? 'text-emerald-700 font-bold' : 'text-rose-700 font-bold' }}">
                                {{ $selected ? $selected->option_text : '(কোনো উত্তর দেওয়া হয়নি)' }}
                            </span>
                        </p>

                        @if(!$ans->is_correct && $correct)
                            <p class="text-slate-600">
                                <span class="font-semibold text-slate-500">সঠিক উত্তর:</span>
                                <span class="text-emerald-700 font-bold">{{ $correct->option_text }}</span>
                            </p>
                        @endif

                        @if($q->explanation)
                            <p class="text-[11px] text-slate-500 italic mt-1 bg-white/60 p-2 rounded-lg border border-slate-100">
                                <span class="font-bold">ব্যাখ্যা:</span> {{ $q->explanation }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
