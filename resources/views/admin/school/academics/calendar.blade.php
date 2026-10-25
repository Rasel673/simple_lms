@extends('layouts.admin')

@section('title', 'একাডেমিক ক্যালেন্ডার')
@section('page_title', 'একাডেমিক ক্যালেন্ডার ও বার্ষিক ছুটির তালিকা')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-purple-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>বার্ষিক দিনপঞ্জি</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">একাডেমিক ক্যালেন্ডার ও ইভেন্ট তালিকা</h1>
            <p class="text-xs text-slate-500 mt-1">পরীক্ষা, সরকারি ছুটি, সাংস্কৃতিক অনুষ্ঠান ও প্রাতিষ্ঠানিক সমাবেশের সময়সূচী</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.school.academics.routines') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">ক্লাস রুটিন</a>
            <a href="{{ route('admin.school.academics.syllabi') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">সিলেবাস</a>
            <a href="{{ route('admin.school.academics.homework') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">হোমওয়ার্ক</a>
            <a href="{{ route('admin.school.academics.calendar') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-purple-600 text-white shadow-xs">ক্যালেন্ডার</a>
        </div>
    </div>

    <!-- Add Event Form -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <h2 class="text-sm font-bold text-slate-800 mb-4">নতুন ইভেন্ট বা ছুটির দিন যুক্ত করুন</h2>
        <form action="{{ route('admin.school.academics.calendar.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">ইভেন্ট বা ছুটির নাম *</label>
                <input type="text" name="title" required placeholder="e.g. রমজানের ছুটি, ১ম সাময়িক পরীক্ষা, স্বাধীনতা দিবস" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ইভেন্টের ধরন *</label>
                <select name="event_type" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="holiday">ছুটি (Holiday)</option>
                    <option value="exam">পরীক্ষা (Exam)</option>
                    <option value="cultural">সাংস্কৃতিক (Cultural)</option>
                    <option value="sports">ক্রীড়া (Sports)</option>
                    <option value="meeting">সমাবেশ (Meeting)</option>
                    <option value="general">সাধারণ (General)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শুরুর তারিখ *</label>
                <input type="date" name="start_date" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শেষ তারিখ (ঐচ্ছিক)</label>
                <input type="date" name="end_date" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">বিবরণ</label>
                <input type="text" name="description" placeholder="সংক্ষিপ্ত বিবরণ" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700 transition-colors shadow-xs">
                    ইভেন্ট যুক্ত করুন
                </button>
            </div>
        </form>
    </div>

    <!-- Calendar Timeline View -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h2 class="text-xs font-bold text-slate-700 uppercase">আসন্ন ইভেন্ট ও ছুটির তালিকা</h2>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($events as $ev)
                <div class="p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 font-bold flex flex-col items-center justify-center border border-purple-200">
                            <span class="text-sm leading-none">{{ \Carbon\Carbon::parse($ev->start_date)->format('d') }}</span>
                            <span class="text-[9px] uppercase font-bold">{{ \Carbon\Carbon::parse($ev->start_date)->format('M') }}</span>
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-xs font-bold text-slate-900">{{ $ev->title }}</h3>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $ev->event_type === 'holiday' ? 'bg-rose-100 text-rose-700' : ($ev->event_type === 'exam' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $ev->event_type }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">
                                {{ \Carbon\Carbon::parse($ev->start_date)->format('d M, Y') }}
                                @if($ev->end_date && $ev->end_date !== $ev->start_date)
                                    - {{ \Carbon\Carbon::parse($ev->end_date)->format('d M, Y') }}
                                @endif
                                @if($ev->description)
                                    • <span class="text-slate-600">{{ $ev->description }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('admin.school.academics.calendar.destroy', $ev) }}" method="POST" onsubmit="return confirm('ইভেন্টটি মুছে ফেলতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    কোনো ইভেন্ট তালিকাভুক্ত নেই
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
