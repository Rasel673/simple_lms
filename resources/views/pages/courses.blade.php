@extends('layouts.app')

@section('title', 'সকল অনলাইন কোর্স - ' . config('app.name', 'সহজ এলএমএস'))
@section('meta_description', 'আমাদের সকল প্র্যাকটিক্যাল ভিডিও কোর্স ব্রাউজ করুন। ওয়েব ডেভেলপমেন্ট, ফ্রিল্যান্সিং, মোবাইল অ্যাপস এবং স্কিল ডেভেলপমেন্ট শিখুন সার্টিফিকেট সহ।')

@section('content')
<div class="bg-slate-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="text-xs font-semibold uppercase tracking-wider text-indigo-400">কোর্স ক্যাটালগ</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1">আমাদের সকল কোর্স অন্বেষণ করুন</h1>
        <p class="text-sm text-slate-300 mt-2 max-w-2xl">দক্ষতা অর্জন ও ক্যারিয়ার গড়ার লক্ষ্যে তৈরি সহজ, ধাপে ধাপে বাংলা ভিডিও লেকচার ও লাইফটাইম অ্যাক্সেস।</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Filter bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-6 mb-10 shadow-xs">
        <form method="GET" action="{{ route('courses.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <div class="md:col-span-5 relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="কোর্সের নাম বা বিষয় দিয়ে খুঁজুন..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 pl-9 pr-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="md:col-span-4">
                <select name="category" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">সকল ক্যাটাগরি</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->courses_count }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition-colors">
                    খুঁজুন
                </button>
                @if($search || $selectedCategory)
                    <a href="{{ route('courses.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs rounded-xl font-medium transition-colors" title="মুছে ফেলুন">
                        রিসেট
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Courses Grid -->
    @if($courses->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-md mx-auto">
            <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <h3 class="text-base font-bold text-slate-800">কোনো কোর্স পাওয়া যায়নি</h3>
            <p class="text-xs text-slate-500 mt-1">অন্য কোনো কিওয়ার্ড বা ফিল্টার ব্যবহার করে আবার চেষ্টা করুন।</p>
            <a href="{{ route('courses.index') }}" class="inline-block mt-4 text-xs font-semibold text-indigo-600 hover:underline">সকল কোর্স দেখুন</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($courses as $course)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group transform hover:-translate-y-1">
                    <!-- Thumbnail -->
                    <div class="relative h-48 bg-gradient-to-tr from-slate-900 to-indigo-950 overflow-hidden shrink-0">
                        @if($course->thumbnail)
                            <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-tr from-indigo-900 via-slate-900 to-indigo-950">
                                <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <span class="text-xs font-semibold text-slate-200 line-clamp-1">{{ $course->title }}</span>
                            </div>
                        @endif

                        <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-slate-900 text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-sm">
                            {{ $course->category->name }}
                        </span>

                        @if($course->has_discount)
                            <span class="absolute top-3 right-3 bg-rose-500 text-white text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md shadow-sm">
                                ছাড় চলছে
                            </span>
                        @endif
                    </div>

                    <!-- Course Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 text-[11px] text-slate-500 mb-2.5">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $course->duration ?: 'স্বচ্ছন্দ গতি' }}
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    {{ $course->lessons->count() }}টি লেকচার
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2">
                                <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                            </h3>

                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4">
                                {{ $course->short_description }}
                            </p>
                        </div>

                        <!-- Pricing & Action Buttons -->
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex items-baseline justify-between mb-4">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-lg font-black text-slate-900">
                                        ৳{{ number_format($course->effective_price, 2) }}
                                    </span>
                                    @if($course->has_discount)
                                        <span class="text-xs text-slate-400 line-through">
                                            ৳{{ number_format($course->price, 2) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                    সার্টিফিকেট সহ
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors">
                                    বিস্তারিত দেখুন
                                </a>

                                @auth
                                    @if(auth()->user()->hasPurchasedCourse($course->id))
                                        <a href="{{ route('student.learning', ['course' => $course->id]) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                            পড়া চালিয়ে যান
                                        </a>
                                    @else
                                        <a href="{{ route('checkout.show', $course->slug) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition-colors">
                                            কোর্সটি কিনুন
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('checkout.show', $course->slug) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition-colors">
                                        কোর্সটি কিনুন
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            {{ $courses->links() }}
        </div>
    @endif
</div>
@endsection
