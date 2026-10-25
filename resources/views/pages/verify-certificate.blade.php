@extends('layouts.app')

@section('title', 'সার্টিফিকেট যাচাইকরণ - ' . config('app.name', 'সহজ এলএমএস'))
@section('meta_description', 'সহজ এলএমএস কর্তৃক ইস্যুকৃত সনদের সত্যতা ইউনিক আইডি নম্বরের মাধ্যমে যাচাই করুন।')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">সার্টিফিকেট যাচাইকরণ</h1>
        <p class="text-xs text-slate-500 mt-2 max-w-md mx-auto">ইস্যুকৃত সার্টিফিকেটের নিচে থাকা ইউনিক আইডি নম্বরটি লিখে সার্টিফিকেটের সত্যতা যাচাই করুন।</p>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs mb-10">
        <form method="GET" action="{{ route('certificate.verify') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="number" value="{{ $certNumber }}" required placeholder="যেমন: LMS-ABCD-1234-2026" class="w-full text-xs font-mono rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-3 pl-10 pr-3 uppercase">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors shrink-0">
                যাচাই করুন
            </button>
        </form>
    </div>

    <!-- Results Section -->
    @if($searched)
        @if($certificate)
            <div class="bg-white rounded-3xl border border-emerald-200 p-8 sm:p-10 shadow-lg relative overflow-hidden">
                <!-- Top verification ribbon -->
                <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider block">অফিসিয়ালি ভেরিফাইড সনদপত্র</span>
                            <h3 class="text-base font-extrabold text-slate-900">সার্টিফিকেট বৈধ ও প্রমাণিত</h3>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold bg-slate-100 text-slate-800 px-3 py-1 rounded-lg border border-slate-200">
                        {{ $certificate->certificate_number }}
                    </span>
                </div>

                <!-- Credential Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-1">শিক্ষার্থীর নাম</span>
                        <p class="font-bold text-slate-800 text-sm">{{ $certificate->user->name }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">কোর্স</span>
                        <p class="font-bold text-slate-800 text-sm">{{ $certificate->course->title }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">ইস্যুর তারিখ</span>
                        <p class="font-semibold text-slate-800">{{ $certificate->issue_date->format('F d, Y') }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">প্রদানকারী প্রতিষ্ঠান</span>
                        <p class="font-semibold text-slate-800">{{ config('app.name', 'সহজ এলএমএস') }} পরীক্ষা ও মূল্যায়ন বোর্ড</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5 text-emerald-700 font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        স্ট্যাটাস: সক্রিয় ও বৈধ
                    </span>
                    <a href="{{ route('courses.show', $certificate->course->slug) }}" class="text-indigo-600 hover:underline font-semibold">
                        কোর্সের বিবরণ দেখুন →
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-3xl border border-rose-200 p-8 text-center shadow-xs">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">কোনো সার্টিফিকেট পাওয়া যায়নি</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">আইডি নম্বর <span class="font-mono font-bold text-slate-800">"{{ $certNumber }}"</span> এর সাথে কোনো অফিশিয়াল সার্টিফিকেট খুঁজে পাওয়া যায়নি। অনুগ্রহ করে কোডটি যাচাই করে পুনরায় চেষ্টা করুন।</p>
            </div>
        @endif
    @endif
</div>
@endsection
