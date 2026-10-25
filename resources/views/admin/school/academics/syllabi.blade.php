@extends('layouts.admin')

@section('title', 'সিলেবাস ও পাঠ পরিকল্পনা')
@section('page_title', 'সিলেবাস ও পাঠ পরিকল্পনা')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-purple-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>পাঠ্যক্রম ও সিলেবাস</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">বিষয়ভিত্তিক পাঠ পরিকল্পনা ও সিলেবাস</h1>
            <p class="text-xs text-slate-500 mt-1">প্রতিটি বিষয়ের পরীক্ষা সিলেবাস ও পাঠ পরিকল্পনা সংরক্ষণ করুন</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.school.academics.routines') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">ক্লাস রুটিন</a>
            <a href="{{ route('admin.school.academics.syllabi') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-purple-600 text-white shadow-xs">সিলেবাস</a>
            <a href="{{ route('admin.school.academics.homework') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">হোমওয়ার্ক</a>
            <a href="{{ route('admin.school.academics.calendar') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">ক্যালেন্ডার</a>
        </div>
    </div>

    <!-- Create Syllabus Form -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <h2 class="text-sm font-bold text-slate-800 mb-4">নতুন সিলেবাস বা পাঠ পরিকল্পনা যোগ করুন</h2>
        <form action="{{ route('admin.school.academics.syllabi.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শ্রেণি *</label>
                <select name="academic_class_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="">শ্রেণি নির্বাচন করুন</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">বিষয় *</label>
                <select name="subject_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="">বিষয় নির্বাচন করুন</option>
                    @foreach($classes as $c)
                        @foreach($c->subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $c->name }} - {{ $sub->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শিক্ষাবর্ষ *</label>
                <input type="number" name="session_year" value="2026" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">সিলেবাসের শিরোনাম *</label>
                <input type="text" name="title" required placeholder="e.g. ১ম সাময়িক পরীক্ষা সিলেবাস" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">অধ্যায় ও পাঠ পরিকল্পনার বিস্তারিত বিবরণ</label>
                <textarea name="description" rows="3" placeholder="অধ্যায় ১: ..., অধ্যায় ২: ... ইত্যাদি বিস্তারিত লিখুন" class="w-full text-xs rounded-xl border-slate-200 p-3"></textarea>
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700 transition-colors shadow-xs">
                    সিলেবাস সংরক্ষণ করুন
                </button>
            </div>
        </form>
    </div>

    <!-- Syllabus List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($syllabi as $s)
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-3 relative group">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-bold text-[10px] border border-purple-200">
                            {{ $s->academicClass->name ?? '' }} - {{ $s->subject->name ?? '' }}
                        </span>
                        <h3 class="text-sm font-bold text-slate-800 mt-2">{{ $s->title }}</h3>
                        <span class="text-[10px] text-slate-400">শিক্ষাবর্ষ: {{ $s->session_year }}</span>
                    </div>

                    <form action="{{ route('admin.school.academics.syllabi.destroy', $s) }}" method="POST" onsubmit="return confirm('সিলেবাসটি মুছে ফেলতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-500 rounded-lg hover:bg-rose-50 transition-colors" title="মুছে ফেলুন">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>

                @if($s->description)
                    <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 whitespace-pre-line leading-relaxed">
                        {{ $s->description }}
                    </p>
                @endif
            </div>
        @empty
            <div class="col-span-2 bg-white p-10 rounded-2xl border border-slate-200 text-center text-slate-400 text-xs">
                কোনো সিলেবাস যুক্ত করা হয়নি
            </div>
        @endforelse
    </div>
</div>
@endsection
