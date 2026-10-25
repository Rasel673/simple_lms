@extends('layouts.app')

@section('title', \App\Models\WebsiteSetting::get('meta_title', 'সহজ এলএমএস - অনলাইনে সহজে স্কিল শিখুন'))
@section('meta_description', \App\Models\WebsiteSetting::get('meta_description', 'অনলাইনে ভিডিও কোর্সের মাধ্যমে নতুন স্কিল শিখুন, কুইজে অংশ নিন এবং ভেরিফাইড সার্টিফিকেট অর্জন করুন।'))

@section('content')

<div x-data="{
    selectedCategory: 'all',
    searchQuery: '',
    activeCourseModal: {{ $autoOpenCourseSlug ? json_encode($autoOpenCourseSlug) : 'null' }},
    selectedCourse: null,
    courses: {{ json_encode($courses->map(function($c) use ($enrolledCourseIds) {
        return [
            'id' => $c->id,
            'slug' => $c->slug,
            'title' => $c->title,
            'short_description' => $c->short_description,
            'description' => $c->description,
            'category_id' => $c->category_id,
            'category_name' => $c->category->name,
            'price' => (float)$c->price,
            'effective_price' => (float)$c->effective_price,
            'has_discount' => (bool)$c->has_discount,
            'savings' => (float)($c->price - $c->effective_price),
            'duration' => $c->duration ?: 'নিজের সুবিধামতো',
            'thumbnail' => $c->thumbnail ? asset($c->thumbnail) : null,
            'learning_outcomes' => $c->learning_outcomes ?: [],
            'lessons_count' => $c->publishedLessons->count(),
            'lessons' => $c->publishedLessons->map(fn($l, $i) => [
                'order' => $i + 1,
                'title' => $l->title,
                'description' => $l->description,
                'duration' => $l->duration,
                'is_preview' => (bool)$l->is_preview
            ])->values(),
            'has_exam' => (bool)$c->finalExam,
            'passing_score' => $c->finalExam ? $c->finalExam->passing_percentage : 70,
            'is_enrolled' => in_array($c->id, $enrolledCourseIds),
            'checkout_url' => route('checkout.show', $c->slug),
            'learn_url' => route('student.learning', $c->id),
        ];
    })) }},
    init() {
        if (this.activeCourseModal) {
            let found = this.courses.find(c => c.slug === this.activeCourseModal);
            if (found) {
                this.selectedCourse = found;
            }
        }
    },
    openCourse(course) {
        this.selectedCourse = course;
        this.activeCourseModal = course.slug;
    },
    closeModal() {
        this.activeCourseModal = null;
        this.selectedCourse = null;
    },
    filteredCourses() {
        return this.courses.filter(c => {
            let matchesCategory = (this.selectedCategory === 'all' || c.category_id == this.selectedCategory);
            let matchesSearch = (!this.searchQuery || c.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || (c.short_description && c.short_description.toLowerCase().includes(this.searchQuery.toLowerCase())));
            return matchesCategory && matchesSearch;
        });
    }
}" class="relative">

    <!-- 1. Hero Background Slider Section -->
    <section id="hero-section" class="relative bg-slate-900 overflow-hidden">
        @if($sliders->isNotEmpty())
            <div x-data="{
                currentSlide: 0,
                slidesCount: {{ $sliders->count() }},
                timer: null,
                next() { this.currentSlide = (this.currentSlide + 1) % this.slidesCount },
                prev() { this.currentSlide = (this.currentSlide - 1 + this.slidesCount) % this.slidesCount },
                init() {
                    if (this.slidesCount > 1) {
                        this.timer = setInterval(() => this.next(), 6000);
                    }
                }
            }" class="relative w-full h-[360px] sm:h-[460px] md:h-[500px]">

                @foreach($sliders as $index => $slide)
                    <div x-show="currentSlide === {{ $index }}"
                         x-cloak
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute inset-0 w-full h-full">

                        <!-- Background Image -->
                        @if($slide->image)
                            <img src="{{ asset($slide->image) }}" alt="{{ $slide->heading ?: 'LMS Banner' }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900"></div>
                        @endif

                        <!-- Nullable Text & Link Overlay -->
                        @if($slide->heading || $slide->description || $slide->button_text)
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/45 to-transparent flex items-end sm:items-center p-6 sm:p-12 lg:p-20">
                                <div class="max-w-3xl space-y-4 text-white">
                                    @if($slide->heading)
                                        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white drop-shadow-md">
                                            {{ $slide->heading }}
                                        </h1>
                                    @endif

                                    @if($slide->description)
                                        <p class="text-xs sm:text-base text-slate-200 max-w-2xl leading-relaxed drop-shadow-sm font-normal">
                                            {{ $slide->description }}
                                        </p>
                                    @endif

                                    @if($slide->button_url)
                                        <div class="pt-2">
                                            <a href="{{ $slide->button_url }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl font-bold text-xs sm:text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/40 transition-all transform hover:-translate-y-0.5">
                                                {{ $slide->button_text ?: 'কোর্সসমূহ দেখুন' }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($slide->button_url)
                            <a href="{{ $slide->button_url }}" class="absolute inset-0 z-10" title="View details"></a>
                        @endif
                    </div>
                @endforeach

                <!-- Slider Controls -->
                @if($sliders->count() > 1)
                    <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 z-20 flex items-center gap-2">
                        @foreach($sliders as $index => $slide)
                            <button @click="currentSlide = {{ $index }}"
                                    :class="currentSlide === {{ $index }} ? 'w-8 bg-white' : 'w-2.5 bg-white/50 hover:bg-white/80'"
                                    class="h-2 rounded-full transition-all duration-300 shadow-sm"></button>
                        @endforeach
                    </div>

                    <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/30 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-xs transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/30 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-xs transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @endif
            </div>
        @else
            <!-- Minimal Banner Fallback -->
            <div class="relative py-20 px-6 sm:px-12 text-center text-white bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900">
                <span class="inline-block px-3 py-1 bg-indigo-500/20 text-indigo-300 rounded-full text-xs font-semibold mb-3 border border-indigo-500/30">
                    সহজে বাংলায় স্কিল শিখুন
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white mb-3">
                    {{ \App\Models\WebsiteSetting::get('site_name', 'সহজ এলএমএস') }}
                </h1>
                <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto mb-6">
                    {{ \App\Models\WebsiteSetting::get('site_tagline', 'সহজ ভিডিও লেসন, বাস্তবমুখী প্রজেক্ট ও ভেরিফাইড সার্টিফিকেশন।') }}
                </p>
                <div class="flex items-center justify-center gap-3">
                    <a href="#courses-section" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md">
                        কোর্সসমূহ দেখুন
                    </a>
                    <a href="#verify-section" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700">
                        সার্টিফিকেট যাচাই
                    </a>
                </div>
            </div>
        @endif
    </section>

    <!-- 1.5. Integrated School ERP 8 Core Pillars Showcase Section -->
    <section id="features-section" class="py-16 sm:py-20 bg-white border-b border-slate-200/80 relative overflow-hidden">
        <!-- Background subtle glow -->
        <div class="absolute top-1/2 left-0 w-96 h-96 bg-indigo-50/60 rounded-full blur-3xl pointer-events-none -translate-y-1/2 -translate-x-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-amber-50/60 rounded-full blur-3xl pointer-events-none translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-14">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 border border-indigo-100/80 text-xs font-bold text-indigo-700 mb-3 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                    <span>পূর্ণাঙ্গ ডিজিটাল স্কুল ম্যানেজমেন্ট ও ই-লার্নিং</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                    একটি আধুনিক প্ল্যাটফর্মেই <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-violet-600 to-amber-600">প্রতিষ্ঠান পরিচালনার সকল সুবিধা</span>
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 mt-3 leading-relaxed font-normal">
                    বায়োমেট্রিক উপস্থিতি থেকে শুরু করে শিক্ষার্থী ভর্তি, ক্লাস রুটিন, পরীক্ষার রেজাল্ট শিট, ফি কালেকশন এবং অনলাইন ভিডিও কোর্স—সবকিছু এখন একই ছাদের নিচে।
                </p>
            </div>

            <!-- 8 Feature Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1: Biometric Attendance -->
                <div class="group bg-white rounded-3xl p-6 border border-slate-200/90 hover:border-orange-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 border border-orange-100 text-orange-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 004.07 8.5"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-orange-600 bg-orange-50 px-2 py-0.5 rounded-md">স্মার্ট হাজিরা</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-orange-600 transition-colors">
                            ফিঙ্গারপ্রিন্ট হাজিরা
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            বায়োমেট্রিক ডিভাইসের মাধ্যমে রিয়েল-টাইম উপস্থিতি সিঙ্ক, শিক্ষার্থী ও শিক্ষকদের দৈনিক পাঞ্চ লগ এবং নিখুঁত উপস্থিতি রিপোর্ট।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-orange-600">
                        <span>স্বয়ংক্রিয় লগ সংরক্ষণ</span>
                        <span>✓ সক্রিয়</span>
                    </div>
                </div>

                <!-- Feature 2: Student Management -->
                <div class="group bg-white rounded-3xl p-6 border border-slate-200/90 hover:border-blue-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">ভর্তি ও প্রোফাইল</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                            শিক্ষার্থী ব্যবস্থাপনা
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            ডিজিটাল ভর্তি ফরম, রোল ও সেকশন বিভাজন, অভিভাবক প্রোফাইল, ব্লাড গ্রুপ এবং বাৎসরিক স্বয়ংক্রিয় ক্লাস প্রমোশন উইজার্ড।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-blue-600">
                        <span>ক্লাস প্রমোশন সুবিধা</span>
                        <span>✓ সক্রিয়</span>
                    </div>
                </div>

                <!-- Feature 3: Teacher & Staff -->
                <div class="group bg-white rounded-3xl p-6 border border-slate-200/90 hover:border-emerald-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">প্রশাসন</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">
                            শিক্ষক ও স্টাফ
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            শিক্ষক ও কর্মচারীদের সম্পূর্ণ প্রোফাইল, বিষয় ও ক্লাস বণ্টন, পদবি ব্যবস্থাপনা এবং নিয়মিত মাসিক স্যালারি পেমেন্ট ট্র্যাকিং।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-emerald-600">
                        <span>স্যালারি ও সাবজেক্ট বণ্টন</span>
                        <span>✓ সক্রিয়</span>
                    </div>
                </div>

                <!-- Feature 4: Academic Routine & Syllabus -->
                <div class="group bg-white rounded-3xl p-6 border border-slate-200/90 hover:border-purple-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-100 text-purple-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md">একাডেমিক</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-purple-600 transition-colors">
                            ক্লাস, সিলেবাস ও রুটিন
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            সাপ্তাহিক ডায়নামিক ক্লাস রুটিন গ্রিড, বিষয়ভিত্তিক সিলেবাস ফাইল ডাউনলোড, দৈনিক হোমওয়ার্ক এবং প্রাতিষ্ঠানিক বাৎসরিক ক্যালেন্ডার।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-purple-600">
                        <span>সাপ্তাহিক রুটিন গ্রিড</span>
                        <span>✓ সক্রিয়</span>
                    </div>
                </div>

                <!-- Feature 5: Exams & Results -->
                <div class="group bg-white rounded-3xl p-6 border border-slate-200/90 hover:border-rose-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">পরীক্ষা ও জিপিএ</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-rose-600 transition-colors">
                            পরীক্ষা ও ফলাফল
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            টার্ম পরীক্ষা ও শিডিউল তৈরি, এক ক্লিকে শ্রেণিভিত্তিক নম্বর এন্ট্রি, অটোমেটেড জিপিএ হিসাব এবং প্রিন্ট-রেডি প্রগ্রেস রিপোর্ট কার্ড।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-rose-600">
                        <span>প্রিন্ট-রেডি রিপোর্ট কার্ড</span>
                        <span>✓ সক্রিয়</span>
                    </div>
                </div>

                <!-- Feature 6: Fees & Payroll -->
                <div class="group bg-white rounded-3xl p-6 border border-slate-200/90 hover:border-cyan-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-cyan-50 border border-cyan-100 text-cyan-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-600 bg-cyan-50 px-2 py-0.5 rounded-md">অ্যাকাউন্টিং</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-cyan-600 transition-colors">
                            ফি ও বেতন ব্যবস্থাপনা
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            শ্রেণিভিত্তিক ফি ইনভয়েস জেনারেশন, বকেয়া তালিকা, তাত্ক্ষণিক ফি আদায় এবং অফিস ও শিক্ষার্থী দুই কপি বিশিষ্ট অফিশিয়াল মানি রসিদ প্রিন্ট।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-cyan-600">
                        <span>ডুয়াল মানি রসিদ প্রিন্ট</span>
                        <span>✓ সক্রিয়</span>
                    </div>
                </div>

                <!-- Feature 7: Reports & Analytics -->
                <div class="group bg-white rounded-3xl p-6 border border-slate-200/90 hover:border-indigo-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">পরিসংখ্যান</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                            রিপোর্ট ও বিশ্লেষণ
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            শ্রেণিভিত্তিক শিক্ষার্থী বিশ্লেষণ, উপস্থিতি শতকরা হার, আর্থিক আদায় ও বকেয়া সামারি এবং ১-ক্লিকে এক্সেল বা সিএসভি ফরম্যাটে ডাটা এক্সপোর্ট।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-indigo-600">
                        <span>এক্সেল / সিএসভি এক্সপোর্ট</span>
                        <span>✓ সক্রিয়</span>
                    </div>
                </div>

                <!-- Feature 8: Intuitive & Responsive -->
                <div class="group bg-white rounded-3xl p-6 border border-slate-200/90 hover:border-amber-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">সহজ ও দ্রুত</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 group-hover:text-amber-600 transition-colors">
                            সহজ ও ব্যবহারবান্ধব
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                            সম্পূর্ণ খাঁটি বাংলায় নির্মিত, মোবাইল ও কম্পিউটারে সমান পারদর্শী, দ্রুত লোডিং স্পিড এবং কোনো প্রযুক্তিগত জ্ঞান ছাড়াই পরিচালনযোগ্য।
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-amber-700">
                        <span>১০০% মোবাইল রেসপন্সিভ</span>
                        <span>✓ সক্রিয়</span>
                    </div>
                </div>
            </div>

            <!-- Institutional Stats Banner -->
            <div class="mt-14 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-8 sm:p-10 text-white shadow-xl border border-slate-800">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-slate-800/80">
                    <div class="pt-4 md:pt-0">
                        <span class="block text-3xl sm:text-4xl font-black text-amber-400">১০০০+</span>
                        <span class="text-xs text-slate-300 font-medium mt-1 block">নিবন্ধিত শিক্ষার্থী</span>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <span class="block text-3xl sm:text-4xl font-black text-emerald-400">৫০+</span>
                        <span class="text-xs text-slate-300 font-medium mt-1 block">শিক্ষক ও স্টাফ প্রোফাইল</span>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <span class="block text-3xl sm:text-4xl font-black text-cyan-400">৯৯.৯%</span>
                        <span class="text-xs text-slate-300 font-medium mt-1 block">বায়োমেট্রিক উপস্থিতি নির্ভুলতা</span>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <span class="block text-3xl sm:text-4xl font-black text-rose-400">২৪/৭</span>
                        <span class="text-xs text-slate-300 font-medium mt-1 block">ক্লাউড ও অটোমেশন সাপোর্ট</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Course Categories Filter Bar -->
    <section id="categories-section" class="py-5 bg-white border-b border-slate-100 sticky top-18 z-30 shadow-xs backdrop-blur-md bg-white/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                <!-- All Courses Tab -->
                <button @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100 font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                        class="px-4 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0">
                    <span>সকল কোর্স</span>
                    <span :class="selectedCategory === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" class="px-2 py-0.5 rounded-md text-[10px] font-bold">
                        {{ $courses->count() }}
                    </span>
                </button>

                <!-- Category Tabs -->
                @foreach($categories as $category)
                    <button @click="selectedCategory = '{{ $category->id }}'"
                            :class="selectedCategory === '{{ $category->id }}' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100 font-bold' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium'"
                            class="px-4 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0">
                        <span>{{ $category->name }}</span>
                        <span :class="selectedCategory === '{{ $category->id }}' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" class="px-2 py-0.5 rounded-md text-[10px] font-bold">
                            {{ $category->courses_count }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 3. All Courses Section with Live Search & Cards -->
    <section id="courses-section" class="py-14 bg-slate-50 border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">কোর্স ক্যাটালগ</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                        অনলাইন কোর্স ও সার্টিফিকেশন
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        উচ্চমানের সহজবোধ্য ভিডিও লেসন, কুইজ টেস্ট এবং ডাউনলোডযোগ্য ভেরিফাইড সার্টিফিকেট।
                    </p>
                </div>

                <!-- Instant Search Bar -->
                <div class="relative w-full max-w-xs">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="কোর্সের নাম দিয়ে খুঁজুন..."
                           class="w-full text-xs rounded-xl border-slate-200 bg-white pl-9 pr-8 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-2.5 text-xs text-slate-400 hover:text-slate-600">✕</button>
                </div>
            </div>

            <!-- Course Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="course in filteredCourses()" :key="course.id">
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group transform hover:-translate-y-1">
                        <!-- Thumbnail -->
                        <div class="relative h-48 bg-gradient-to-tr from-slate-900 to-indigo-950 overflow-hidden shrink-0">
                            <template x-if="course.thumbnail">
                                <img :src="course.thumbnail" :alt="course.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </template>
                            <template x-if="!course.thumbnail">
                                <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-tr from-indigo-900 via-slate-900 to-indigo-950">
                                    <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white mb-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-200 line-clamp-1" x-text="course.title"></span>
                                </div>
                            </template>

                            <!-- Category Badge -->
                            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-md text-slate-900 text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-sm" x-text="course.category_name"></span>

                            <!-- Discount Badge -->
                            <template x-if="course.has_discount">
                                <span class="absolute top-3 right-3 bg-rose-500 text-white text-[11px] font-bold uppercase px-2 py-0.5 rounded-md shadow-sm">
                                    বিশেষ ছাড়
                                </span>
                            </template>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <!-- Meta Pills -->
                                <div class="flex items-center gap-3 text-[11px] text-slate-500 mb-2.5">
                                    <span class="flex items-center gap-1 font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span x-text="course.duration"></span>
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1 font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span x-text="course.lessons_count + 'টি লেসন'"></span>
                                    </span>
                                </div>

                                <h3 class="text-base font-bold text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2 cursor-pointer" @click="openCourse(course)">
                                    <span x-text="course.title"></span>
                                </h3>

                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed mb-4" x-text="course.short_description"></p>
                            </div>

                            <!-- Pricing & Action Buttons -->
                            <div class="pt-4 border-t border-slate-100">
                                <div class="flex items-baseline justify-between mb-4">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-lg font-black text-slate-900" x-text="'৳' + course.effective_price.toFixed(2)"></span>
                                        <template x-if="course.has_discount">
                                            <span class="text-xs text-slate-400 line-through" x-text="'৳' + course.price.toFixed(2)"></span>
                                        </template>
                                    </div>
                                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md">
                                        সার্টিফিকেট সহ
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <!-- View Details Button -->
                                    <button @click="openCourse(course)" type="button" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors">
                                        বিস্তারিত দেখুন
                                    </button>

                                    <!-- Enrolled / Buy Button -->
                                    <template x-if="course.is_enrolled">
                                        <a :href="course.learn_url" class="inline-flex items-center justify-center px-3 py-2 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                            পড়া শুরু করুন
                                        </a>
                                    </template>
                                    <template x-if="!course.is_enrolled">
                                        <a :href="course.checkout_url" class="inline-flex items-center justify-center px-3 py-2 text-xs font-bold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition-colors">
                                            কোর্সটি কিনুন
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty Filter State -->
            <div x-show="filteredCourses().length === 0" x-cloak class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto">
                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <h3 class="text-base font-bold text-slate-800">কোনো কোর্স পাওয়া যায়নি</h3>
                <p class="text-xs text-slate-500 mt-1">অনুগ্রহ করে ক্যাটাগরি ফিল্টার রিসেট করুন বা অন্য নামে খুঁজুন।</p>
                <button @click="selectedCategory = 'all'; searchQuery = ''" class="inline-block mt-4 text-xs font-bold text-indigo-600 hover:underline">ফিল্টার রিসেট করুন</button>
            </div>
        </div>
    </section>

    <!-- 4. Course Details Modal -->
    <div x-show="activeCourseModal !== null"
         x-cloak
         @keydown.escape.window="closeModal()"
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true">

        <div x-show="activeCourseModal !== null"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity"
             @click="closeModal()"></div>

        <div class="min-h-screen px-4 text-center flex items-center justify-center p-0 sm:p-6">
            <div x-show="activeCourseModal !== null"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="closeModal()"
                 class="inline-block w-full max-w-3xl my-8 text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl overflow-hidden border border-slate-200">

                <template x-if="selectedCourse">
                    <div>
                        <!-- Modal Header Banner -->
                        <div class="relative bg-slate-900 text-white p-6 sm:p-8">
                            <button @click="closeModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center transition-colors">
                                ✕
                            </button>

                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30" x-text="selectedCourse.category_name"></span>
                                <span class="text-xs text-slate-400" x-text="selectedCourse.duration"></span>
                            </div>

                            <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight" x-text="selectedCourse.title"></h2>
                            <p class="text-xs sm:text-sm text-slate-300 mt-2 line-clamp-2" x-text="selectedCourse.short_description"></p>

                            <div class="flex items-baseline gap-3 mt-4 pt-4 border-t border-slate-800">
                                <span class="text-2xl font-extrabold text-white" x-text="'৳' + selectedCourse.effective_price.toFixed(2)"></span>
                                <template x-if="selectedCourse.has_discount">
                                    <span class="text-sm text-slate-400 line-through" x-text="'৳' + selectedCourse.price.toFixed(2)"></span>
                                </template>
                                <template x-if="selectedCourse.has_discount">
                                    <span class="text-xs font-bold text-rose-400 bg-rose-500/20 px-2 py-0.5 rounded-md" x-text="'সাশ্রয় ৳' + selectedCourse.savings.toFixed(2)"></span>
                                </template>
                            </div>
                        </div>

                        <!-- Modal Content Body -->
                        <div class="p-6 sm:p-8 space-y-6 max-h-[60vh] overflow-y-auto">
                            <!-- Learning Outcomes -->
                            <template x-if="selectedCourse.learning_outcomes && selectedCourse.learning_outcomes.length > 0">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        আপনি যা যা শিখবেন
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700">
                                        <template x-for="outcome in selectedCourse.learning_outcomes" :key="outcome">
                                            <div class="flex items-start gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                                <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                <span x-text="outcome"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Curriculum Playlist -->
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                                        কোর্স কারিকুলাম ও লেসনসমূহ (<span x-text="selectedCourse.lessons_count"></span>)
                                    </h4>
                                    <template x-if="selectedCourse.has_exam">
                                        <span class="text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md" x-text="'পরীক্ষায় পাস মার্ক: ' + selectedCourse.passing_score + '%'"></span>
                                    </template>
                                </div>

                                <div class="space-y-2">
                                    <template x-for="lesson in selectedCourse.lessons" :key="lesson.order">
                                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50 text-xs">
                                            <div class="flex items-center gap-3">
                                                <span class="w-5 h-5 rounded-md bg-white border border-slate-200 text-slate-700 font-bold text-[10px] flex items-center justify-center" x-text="lesson.order"></span>
                                                <div>
                                                    <p class="font-semibold text-slate-800" x-text="lesson.title"></p>
                                                    <p x-show="lesson.description" class="text-[11px] text-slate-400 line-clamp-1" x-text="lesson.description"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span x-show="lesson.duration" class="text-[11px] text-slate-400" x-text="lesson.duration"></span>
                                                <span x-show="lesson.is_preview" class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded">প্রিভিউ</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Action Footer -->
                        <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <button @click="closeModal()" type="button" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-semibold">
                                বন্ধ করুন
                            </button>

                            <div>
                                <template x-if="selectedCourse.is_enrolled">
                                    <a :href="selectedCourse.learn_url" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md">
                                        ক্লাস শুরু করুন
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </template>
                                <template x-if="!selectedCourse.is_enrolled">
                                    <a :href="selectedCourse.checkout_url" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200">
                                        কোর্সটিতে ভর্তি হোন
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- 5. On-Page Certificate Verification Tool -->
    <section id="verify-section" class="py-16 bg-white border-b border-slate-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">সনদ যাচাইকরণ</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">সার্টিফিকেটের সত্যতা যাচাই</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    সার্টিফিকেট আইডি নম্বরটি প্রবেশ করিয়ে তাত্ক্ষণিকভাবে সার্টিফিকেটের সত্যতা নিশ্চিত করুন।
                </p>
            </div>

            <!-- Lookup Form -->
            <form method="GET" action="{{ route('home') }}#verify-section" class="max-w-xl mx-auto mb-8 flex gap-2">
                <div class="relative w-full">
                    <input type="text"
                           name="verify_number"
                           value="{{ $searchedCertNumber }}"
                           required
                           placeholder="সার্টিফিকেট নম্বর লিখুন (যেমন: LMS-XXXX-XXXX-YYYY)"
                           class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 pl-10 pr-4 py-3 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 shadow-xs uppercase font-mono">
                    <svg class="w-5 h-5 text-indigo-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors shrink-0">
                    যাচাই করুন
                </button>
            </form>

            <!-- Verification Result Card -->
            @if($searchedCertNumber)
                @if($verifiedCertificate)
                    <div class="bg-gradient-to-br from-emerald-500/10 via-white to-slate-50 border-2 border-emerald-500/30 rounded-3xl p-6 sm:p-8 shadow-xl max-w-2xl mx-auto">
                        <div class="flex items-center gap-3 text-emerald-800 font-extrabold text-sm mb-4">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                ✓
                            </div>
                            <span>অফিসিয়ালি ভেরিফাইড সার্টিফিকেট</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">শিক্ষার্থীর নাম</span>
                                <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $verifiedCertificate->user->name }}</p>
                            </div>

                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">সার্টিফিকেট আইডি</span>
                                <p class="text-sm font-mono font-bold text-indigo-600 mt-0.5">{{ $verifiedCertificate->certificate_number }}</p>
                            </div>

                            <div class="sm:col-span-2 p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">সম্পন্নকৃত কোর্স</span>
                                <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $verifiedCertificate->course->title }}</p>
                            </div>

                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">ইস্যুর তারিখ</span>
                                <p class="text-xs font-semibold text-slate-800 mt-0.5">{{ $verifiedCertificate->issue_date->format('F d, Y') }}</p>
                            </div>

                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">স্ট্যাটাস</span>
                                <p class="text-xs font-semibold text-emerald-600 mt-0.5">বৈধ ও যাচাইকৃত</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-6 text-center max-w-lg mx-auto text-xs">
                        <span class="font-bold block text-sm mb-1">কোনো রেকর্ড পাওয়া যায়নি</span>
                        <span>আইডি নম্বর <strong class="font-mono">{{ $searchedCertNumber }}</strong> এর সাথে কোনো অফিশিয়াল সার্টিফিকেট খুঁজে পাওয়া যায়নি। অনুগ্রহ করে সঠিক নম্বরটি পুনরায় লিখুন।</span>
                    </div>
                @endif
            @endif
        </div>
    </section>

    <!-- 6. About Us Section -->
    <section id="about-section" class="py-20 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">আমাদের লক্ষ্য</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $settings['about_heading'] ?? 'সহজে প্রযুক্তি দক্ষতা অর্জন করুন' }}
                    </h2>
                    <div class="text-sm text-slate-600 leading-relaxed space-y-4">
                        {!! nl2br(e($settings['about_content'] ?? 'আমাদের মূল উদ্দেশ্য হলো সহজ ও সাবলীল ভাষায় প্রযুক্তি বিষয়ক মানসম্মত ভিডিও কোর্স পৌঁছে দেওয়া। নিজের সুবিধামতো সময়ে শিখুন, অনলাইন কুইজের মাধ্যমে দক্ষতা যাচাই করুন এবং প্রতিটি কোর্স সফলভাবে শেষ করে অর্জন করুন ভেরিফাইড সার্টিফিকেট।')) !!}
                    </div>

                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-slate-100 text-center">
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-xl font-extrabold text-indigo-600">১০০%</span>
                            <span class="text-[11px] text-slate-500 font-medium">ভিডিও লেসন</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-xl font-extrabold text-indigo-600">এমসিকিউ</span>
                            <span class="text-[11px] text-slate-500 font-medium">কুইজ ও পরীক্ষা</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-xl font-extrabold text-indigo-600">অনলাইন</span>
                            <span class="text-[11px] text-slate-500 font-medium">সার্টিফিকেট</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="relative bg-gradient-to-br from-indigo-900 via-indigo-950 to-slate-900 p-8 sm:p-10 rounded-3xl text-white shadow-2xl overflow-hidden border border-slate-800">
                        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-2xl"></div>

                        <div class="relative z-10 space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold">কেন আমাদের প্ল্যাটফর্মে শিখবেন?</h3>
                            <ul class="space-y-3.5 text-xs text-slate-300">
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>সহজ ও নিরবচ্ছিন্ন ভিডিও প্লেয়ার যেখানে স্বয়ংক্রিয় প্রগ্রেস সংরক্ষণ হয়</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>তাৎক্ষণিক মূল্যায়নসহ অনলাইন এমসিকিউ পরীক্ষা পদ্ধতি</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>যাচাইযোগ্য কিউআর কোড ও ইউনিক আইডি সমৃদ্ধ সনদপত্র</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>বিকাশ, নগদ, রকেট ও ব্যাংক কার্ডের মাধ্যমে সহজ পেমেন্ট সুবিধা</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. Contact Us Section -->
    <section id="contact-section" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Contact Info Cards -->
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">সরাসরি যোগাযোগ</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">আমাদের সাথে যোগাযোগ করুন</h2>
                        <p class="text-sm text-slate-500 mt-2">কোর্স বা পেমেন্ট সংক্রান্ত কোনো প্রশ্ন থাকলে আমাদের জানান। আমাদের সাপোর্ট টিম দ্রুত সহায়তা করবে।</p>
                    </div>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">অফিসের ঠিকানা</h4>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $settings['contact_address'] ?? 'ধানমন্ডি, ঢাকা, বাংলাদেশ' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">সাপোর্ট ইমেইল</h4>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $settings['contact_email'] ?? 'support@learnhub.com' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">হেল্পলাইন নম্বর</h4>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $settings['contact_phone'] ?? '+880 1700 000000' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-lg">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">আমাদের মেসেজ পাঠান</h3>
                        <p class="text-xs text-slate-500 mb-6">নিচের ফর্মটি পূরণ করে পাঠান, আমরা দ্রুত আপনার ইমেইলে উত্তর দেব।</p>

                        @if(session('contact_success'))
                            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ session('contact_success') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">আপনার পূর্ণ নাম *</label>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                    @error('name') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">আপনার ইমেইল অ্যাড্রেস *</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                    @error('email') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">বিষয় *</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="যেমন: কোর্স বা পেমেন্ট সংক্রান্ত তথ্য" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                @error('subject') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">আপনার বার্তা *</label>
                                <textarea name="message" rows="4" required placeholder="আপনার বার্তাটি এখানে লিখুন..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('message') }}</textarea>
                                @error('message') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-200 transition-all">
                                বার্তা পাঠান
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection
