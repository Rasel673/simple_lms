@extends('layouts.student')

@section('title', 'Exam in Progress - ' . $exam->title)
@section('page_title', 'Assessment in Progress')

@section('content')
<div class="max-w-4xl mx-auto space-y-6"
     x-data="{
         secondsLeft: {{ $exam->duration_minutes * 60 }},
         timer: null,
         formatTime(s) {
             const m = Math.floor(s / 60);
             const sec = s % 60;
             return `${m.toString().padStart(2, '0')}:${sec.toString().padStart(2, '0')}`;
         },
         init() {
             this.timer = setInterval(() => {
                 if (this.secondsLeft > 0) {
                     this.secondsLeft--;
                 } else {
                     clearInterval(this.timer);
                     document.getElementById('exam-submit-form').submit();
                 }
             }, 1000);
         }
     }">

    <!-- Sticky Exam Status Header -->
    <div class="sticky top-20 z-20 bg-slate-900 text-white rounded-2xl p-4 sm:p-5 shadow-lg flex items-center justify-between border border-slate-800">
        <div>
            <h1 class="text-sm sm:text-base font-bold text-white truncate max-w-sm">{{ $exam->title }}</h1>
            <span class="text-[11px] text-slate-400">Attempt #{{ $attempt->attempt_number }} • Passing threshold: {{ $exam->passing_percentage }}%</span>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Time Left</span>
                <span class="text-lg sm:text-xl font-mono font-black text-amber-400" x-text="formatTime(secondsLeft)">
                    {{ sprintf('%02d:00', $exam->duration_minutes) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Questions Form -->
    <form id="exam-submit-form" method="POST" action="{{ route('student.exam.submit', ['exam' => $exam->id, 'attempt' => $attempt->id]) }}" class="space-y-6">
        @csrf

        @foreach($questions as $qIndex => $question)
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 font-extrabold text-xs flex items-center justify-center shrink-0 mt-0.5">
                            Q{{ $qIndex + 1 }}
                        </span>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">
                            {{ $question->question_text }}
                        </h3>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400 shrink-0">1 Mark</span>
                </div>

                <!-- Radio Options (Correct answers NOT exposed in HTML) -->
                <div class="space-y-2.5 pt-2 pl-1 sm:pl-10">
                    @foreach($question->options as $option)
                        <label class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/30 cursor-pointer transition-all">
                            <input type="radio"
                                   name="answers[{{ $question->id }}]"
                                   value="{{ $option->id }}"
                                   class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                            <span class="text-xs text-slate-800 font-medium">{{ $option->option_text }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Bottom Submit Button -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
            <p class="text-xs text-slate-500">Double check your selections before final submission.</p>
            <button type="submit" onclick="return confirm('Are you ready to submit your exam answers? You will receive immediate results.');" class="w-full sm:w-auto px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-md shadow-indigo-200 transition-all">
                Submit Examination Answers
            </button>
        </div>
    </form>
</div>
@endsection
