@extends('layouts.student')

@section('title', 'পরীক্ষা - ' . $exam->title)
@section('page_title', 'সার্টিফিকেট মূল্যায়ন পরীক্ষা')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Intro Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-xs space-y-6">
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-amber-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            সার্টিফিকেশন পরীক্ষা
        </div>

        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $exam->title }}</h1>
            <p class="text-xs text-slate-500 mt-1">কোর্স: <span class="font-bold text-slate-800">{{ $exam->course->title }}</span></p>
        </div>

        @if($exam->description)
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600 leading-relaxed">
                {{ $exam->description }}
            </div>
        @endif

        <!-- Rules & Metrics Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-100 text-center">
                <span class="text-xs font-semibold text-amber-800 block mb-1">পাস মার্ক</span>
                <span class="text-xl font-black text-amber-900">{{ $exam->passing_percentage }}%</span>
            </div>

            <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-100 text-center">
                <span class="text-xs font-semibold text-indigo-800 block mb-1">মোট প্রশ্ন</span>
                <span class="text-xl font-black text-indigo-900">{{ $exam->questions->count() }}টি</span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                <span class="text-xs font-semibold text-slate-600 block mb-1">সময়</span>
                <span class="text-xl font-black text-slate-800">{{ $exam->duration_minutes }} মিনিট</span>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-center">
                <span class="text-xs font-semibold text-slate-600 block mb-1">সর্বোচ্চ সুযোগ</span>
                <span class="text-xl font-black text-slate-800">{{ $exam->max_attempts > 0 ? $exam->max_attempts . ' বার' : 'সীমাহীন' }}</span>
            </div>
        </div>

        <!-- Action / Launch -->
        <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                @if($hasPassed)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        আপনি এই পরীক্ষায় পাস করেছেন!
                    </span>
                @elseif(!$canAttempt)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                        এই পরীক্ষার সর্বোচ্চ সুযোগ শেষ হয়ে গেছে।
                    </span>
                @else
                    <p class="text-xs text-slate-500">পরীক্ষা শুরু করার পূর্বে একটি নিরবচ্ছিন্ন ইন্টারনেট সংযোগ নিশ্চিত করুন।</p>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('student.learning', ['course' => $exam->course_id]) }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    লেসনে ফিরুন
                </a>

                @if($canAttempt)
                    <a href="{{ route('student.exam.start', $exam->id) }}" onclick="return confirm('আপনি কি পরীক্ষা শুরু করতে প্রস্তুত? সময় গণনা শুরু হবে।');" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition-all">
                        <span>{{ $exam->attempts()->where('user_id', auth()->id())->count() > 0 ? 'আবার পরীক্ষা দিন' : 'পরীক্ষা শুরু করুন' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
