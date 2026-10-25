@extends('layouts.admin')

@section('title', 'হোমওয়ার্ক ও অ্যাসাইনমেন্ট')
@section('page_title', 'হোমওয়ার্ক ও অ্যাসাইনমেন্ট')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-purple-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>দৈনিক বাড়ির কাজ</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">হোমওয়ার্ক ও বাড়ির কাজ ব্যবস্থাপনা</h1>
            <p class="text-xs text-slate-500 mt-1">শ্রেণি ও বিষয়ভিত্তিক শিক্ষার্থীদের হোমওয়ার্ক প্রদান এবং জমার তারিখ নির্ধারণ</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.school.academics.routines') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">ক্লাস রুটিন</a>
            <a href="{{ route('admin.school.academics.syllabi') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">সিলেবাস</a>
            <a href="{{ route('admin.school.academics.homework') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-purple-600 text-white shadow-xs">হোমওয়ার্ক</a>
            <a href="{{ route('admin.school.academics.calendar') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">ক্যালেন্ডার</a>
        </div>
    </div>

    <!-- Create Homework Form -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <h2 class="text-sm font-bold text-slate-800 mb-4">নতুন হোমওয়ার্ক প্রদান করুন</h2>
        <form action="{{ route('admin.school.academics.homework.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <label class="block text-xs font-semibold text-slate-700 mb-1">জমা দেওয়ার শেষ তারিখ (Due Date) *</label>
                <input type="date" name="due_date" value="{{ \Carbon\Carbon::tomorrow()->toDateString() }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">হোমওয়ার্কের শিরোনাম *</label>
                <input type="text" name="title" required placeholder="e.g. অনুশীলনী ১.৩ এর অংকসমূহ" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">কাজের বিস্তারিত নির্দেশনা *</label>
                <textarea name="description" rows="3" required placeholder="হোমওয়ার্কের প্রশ্নের বিবরণ এবং নির্দেশনা..." class="w-full text-xs rounded-xl border-slate-200 p-3"></textarea>
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700 transition-colors shadow-xs">
                    হোমওয়ার্ক প্রকাশ করুন
                </button>
            </div>
        </form>
    </div>

    <!-- Homework List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h2 class="text-xs font-bold text-slate-700 uppercase">প্রদত্ত হোমওয়ার্কের তালিকা</h2>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($homeworkList as $hw)
                <div class="p-5 flex flex-col md:flex-row md:items-start justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-bold text-[10px] border border-purple-200">
                                {{ $hw->academicClass->name ?? '' }} - {{ $hw->subject->name ?? '' }}
                            </span>
                            <span class="text-xs font-bold text-slate-800">{{ $hw->title }}</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $hw->description }}</p>
                        <div class="text-[10px] text-slate-400 flex items-center gap-3 pt-1">
                            <span>প্রদানকারী: {{ $hw->teacher->name ?? 'শিক্ষক' }}</span>
                            <span>•</span>
                            <span class="text-rose-600 font-semibold">জমা দেওয়ার শেষ সময়: {{ \Carbon\Carbon::parse($hw->due_date)->format('d M, Y') }}</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.school.academics.homework.destroy', $hw) }}" method="POST" onsubmit="return confirm('হোমওয়ার্কটি মুছে ফেলতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-300 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-10 text-center text-slate-400 text-xs">
                    কোনো হোমওয়ার্ক যুক্ত করা হয়নি
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
