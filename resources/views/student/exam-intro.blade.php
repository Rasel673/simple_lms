@extends('layouts.student')

@section('title', 'Exam - ' . $exam->title)
@section('page_title', 'MCQ Assessment')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Intro Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-xs space-y-6">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            Certification Assessment
        </div>

        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $exam->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">Course: <span class="font-bold text-slate-800">{{ $exam->course->title }}</span></p>
        </div>

        @if($exam->description)
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600 leading-relaxed">
                {{ $exam->description }}
            </div>
        @endif

        <!-- Rules & Metrics Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-100 text-center">
                <span class="text-xs font-semibold text-amber-800 block mb-1">Passing Score</span>
                <span class="text-xl font-black text-amber-900">{{ $exam->passing_percentage }}%</span>
            </div>

            <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-center">
                <span class="text-xs font-semibold text-indigo-800 block mb-1">Total Questions</span>
                <span class="text-xl font-black text-indigo-900">{{ $exam->questions->count() }}</span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                <span class="text-xs font-semibold text-slate-600 block mb-1">Duration</span>
                <span class="text-xl font-black text-slate-800">{{ $exam->duration_minutes }} Min</span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                <span class="text-xs font-semibold text-slate-600 block mb-1">Max Attempts</span>
                <span class="text-xl font-black text-slate-800">{{ $exam->max_attempts > 0 ? $exam->max_attempts : 'Unlimited' }}</span>
            </div>
        </div>

        <!-- Action / Launch -->
        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                @if($hasPassed)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        You have passed this assessment!
                    </span>
                @elseif(!$canAttempt)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                        Maximum attempts reached for this assessment.
                    </span>
                @else
                    <p class="text-xs text-slate-500">Ensure a stable internet connection before starting the timer.</p>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('student.learning', ['course' => $exam->course_id]) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Back to Lessons
                </a>

                @if($canAttempt)
                    <a href="{{ route('student.exam.start', $exam->id) }}" class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-md transition-all">
                        {{ $attempts->isEmpty() ? 'Start Exam Now' : 'Take Exam Retake' }} →
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Past Attempts History Table -->
    @if($attempts->isNotEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900">Your Assessment Attempts History</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-semibold text-[10px]">
                        <tr>
                            <th class="p-3">Attempt #</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Score</th>
                            <th class="p-3">Result</th>
                            <th class="p-3 text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($attempts as $attempt)
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-3 font-bold text-slate-900">#{{ $attempt->attempt_number }}</td>
                                <td class="p-3">{{ $attempt->started_at->format('M d, Y h:i A') }}</td>
                                <td class="p-3 font-semibold">{{ $attempt->score_percentage }}% ({{ $attempt->correct_answers }}/{{ $attempt->total_questions }})</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $attempt->is_passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $attempt->is_passed ? 'PASSED' : 'FAILED' }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('student.exam.result', ['exam' => $exam->id, 'attempt' => $attempt->id]) }}" class="text-indigo-600 font-bold hover:underline">
                                        View Breakdown
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
