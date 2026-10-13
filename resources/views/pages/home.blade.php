@extends('layouts.app')

@section('title', \App\Models\WebsiteSetting::get('meta_title', 'LearnHub LMS - Sell & Learn Online Courses'))
@section('meta_description', \App\Models\WebsiteSetting::get('meta_description', 'Discover modern video courses, pass MCQ assessments, and earn verified certificates.'))

@section('content')

    <!-- B. Hero Slider Section -->
    @if($sliders->isNotEmpty())
        <section x-data="{
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
        }" class="relative bg-slate-900 text-white overflow-hidden py-16 sm:py-24">
            <!-- Background Glow -->
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/40 to-slate-900 pointer-events-none"></div>
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="relative min-h-[360px] flex items-center">
                    @foreach($sliders as $index => $slide)
                        <div x-show="currentSlide === {{ $index }}"
                             x-cloak
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 translate-x-8"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 translate-x-0"
                             x-transition:leave-end="opacity-0 -translate-x-8"
                             class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8 items-center absolute inset-0">
                            
                            <div class="lg:col-span-8 space-y-6">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                                    Featured Academic Program
                                </span>
                                <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight">
                                    {{ $slide->heading }}
                                </h1>
                                <p class="text-base sm:text-lg text-slate-300 max-w-2xl leading-relaxed">
                                    {{ $slide->description }}
                                </p>
                                <div class="pt-2 flex flex-wrap items-center gap-4">
                                    @if($slide->button_url)
                                        <a href="{{ $slide->button_url }}" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5">
                                            {{ $slide->button_text ?: 'Explore Now' }}
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        </a>
                                    @endif
                                    <a href="#courses-section" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-semibold text-sm bg-slate-800/80 hover:bg-slate-700 text-slate-200 border border-slate-700 transition-all">
                                        View Curriculum
                                    </a>
                                </div>
                            </div>

                            <div class="hidden lg:block lg:col-span-4">
                                @if($slide->image)
                                    <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->heading }}" class="rounded-2xl shadow-2xl border border-slate-800 object-cover w-full h-72">
                                @else
                                    <div class="rounded-2xl bg-gradient-to-tr from-indigo-800/40 to-slate-800/60 p-8 border border-slate-700/60 shadow-2xl flex flex-col justify-between h-72">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs uppercase font-bold tracking-wider text-indigo-400">Accredited Learning</span>
                                            <div class="flex -space-x-2">
                                                <div class="w-7 h-7 rounded-full bg-indigo-500 border-2 border-slate-900 flex items-center justify-center text-[10px] font-bold">100%</div>
                                                <div class="w-7 h-7 rounded-full bg-emerald-500 border-2 border-slate-900 flex items-center justify-center text-[10px] font-bold">PDF</div>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white mb-1">Instant Verified Certificates</h3>
                                            <p class="text-xs text-slate-400">Complete all lessons and pass the MCQ test to receive your verified credentials immediately.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Slider Controls -->
                @if($sliders->count() > 1)
                    <div class="flex items-center justify-between mt-8 pt-4 border-t border-slate-800/60">
                        <div class="flex items-center gap-2">
                            @foreach($sliders as $index => $slide)
                                <button @click="currentSlide = {{ $index }}"
                                        :class="currentSlide === {{ $index }} ? 'w-8 bg-indigo-500' : 'w-2.5 bg-slate-700 hover:bg-slate-600'"
                                        class="h-2 rounded-full transition-all duration-300"></button>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="prev()" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <button @click="next()" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- C. Course Categories Section -->
    <section class="py-16 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Top Disciplines</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3">Explore by Subject Category</h2>
                <p class="text-sm text-slate-500 mt-2">Filter courses by discipline and build industry-standard competencies.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                <!-- All Courses Card -->
                <a href="{{ route('home') }}#courses-section"
                   class="group p-5 rounded-2xl border transition-all duration-200 text-center flex flex-col items-center justify-center {{ !$selectedCategory ? 'border-indigo-500 bg-indigo-50/50 shadow-sm' : 'border-slate-200 bg-white hover:border-indigo-300 hover:shadow-md' }}">
                    <div class="w-12 h-12 rounded-xl {{ !$selectedCategory ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-indigo-600 group-hover:text-white' }} flex items-center justify-center mb-3 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">All Categories</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ \App\Models\Course::published()->count() }} Courses</p>
                </a>

                @foreach($categories as $category)
                    <a href="{{ route('home', ['category' => $category->id]) }}#courses-section"
                       class="group p-5 rounded-2xl border transition-all duration-200 text-center flex flex-col items-center justify-center {{ $selectedCategory == $category->id ? 'border-indigo-500 bg-indigo-50/50 shadow-sm' : 'border-slate-200 bg-white hover:border-indigo-300 hover:shadow-md' }}">
                        <div class="w-12 h-12 rounded-xl {{ $selectedCategory == $category->id ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-indigo-600 group-hover:text-white' }} flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors truncate w-full">{{ $category->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ $category->courses_count }} Courses</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- D. All Courses Section -->
    <section id="courses-section" class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Certified Curriculum</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                        @if($selectedCategory)
                            Filtered Courses
                        @else
                            All Published Courses
                        @endif
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">High-definition video lectures, MCQ tests, and automated certificates.</p>
                </div>

                <!-- Search form on homepage -->
                <form method="GET" action="{{ route('home') }}#courses-section" class="flex items-center gap-2 max-w-sm w-full">
                    @if($selectedCategory)
                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                    @endif
                    <div class="relative w-full">
                        <input type="text" name="q" value="{{ $search }}" placeholder="Search course by title..." class="w-full text-xs rounded-xl border-slate-200 bg-white pl-9 pr-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition-colors shrink-0">Search</button>
                    @if($selectedCategory || $search)
                        <a href="{{ route('home') }}#courses-section" class="p-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs rounded-xl transition-colors shrink-0" title="Clear filters">✕</a>
                    @endif
                </form>
            </div>

            <!-- Course Cards Grid -->
            @if($courses->isEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto">
                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <h3 class="text-base font-bold text-slate-800">No courses found</h3>
                    <p class="text-xs text-slate-500 mt-1">There are no courses matching your selected filter.</p>
                    <a href="{{ route('home') }}#courses-section" class="inline-block mt-4 text-xs font-semibold text-indigo-600 hover:underline">View All Courses</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($courses as $course)
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group transform hover:-translate-y-1">
                            <!-- Thumbnail -->
                            <div class="relative h-48 bg-gradient-to-tr from-slate-900 to-indigo-950 overflow-hidden shrink-0">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-tr from-indigo-900 via-slate-900 to-indigo-950">
                                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white mb-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-200 line-clamp-1">{{ $course->title }}</span>
                                    </div>
                                @endif

                                <!-- Category Badge -->
                                <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-slate-900 text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-sm">
                                    {{ $course->category->name }}
                                </span>

                                @if($course->has_discount)
                                    <span class="absolute top-3 right-3 bg-rose-500 text-white text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md shadow-sm">
                                        Sale
                                    </span>
                                @endif
                            </div>

                            <!-- Course Body -->
                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <!-- Meta pills -->
                                    <div class="flex items-center gap-3 text-[11px] text-slate-500 mb-2.5">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $course->duration ?: 'Self-Paced' }}
                                        </span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            {{ $course->lessons->count() }} Lessons
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
                                            Certificate Included
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors">
                                            View Details
                                        </a>

                                        @auth
                                            @if(auth()->user()->hasPurchasedCourse($course->id))
                                                <a href="{{ route('student.learning', ['course' => $course->id]) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                                    Continue
                                                </a>
                                            @else
                                                <a href="{{ route('checkout.show', $course->slug) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition-colors">
                                                    Buy Course
                                                </a>
                                            @endif
                                        @else
                                            <a href="{{ route('checkout.show', $course->slug) }}" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition-colors">
                                                Buy Course
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    {{ $courses->fragment('courses-section')->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- E. About Us Section -->
    <section id="about-section" class="py-20 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Academy Mission</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $settings['about_heading'] }}
                    </h2>
                    <div class="text-sm text-slate-600 leading-relaxed space-y-4">
                        {!! nl2br(e($settings['about_content'])) !!}
                    </div>

                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-slate-100 text-center">
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-xl font-extrabold text-indigo-600">100%</span>
                            <span class="text-[11px] text-slate-500 font-medium">Video Lessons</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-xl font-extrabold text-indigo-600">Verified</span>
                            <span class="text-[11px] text-slate-500 font-medium">MCQ Tests</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <span class="block text-xl font-extrabold text-indigo-600">PDF</span>
                            <span class="text-[11px] text-slate-500 font-medium">Certificates</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="relative bg-gradient-to-br from-indigo-900 via-indigo-950 to-slate-900 p-8 sm:p-10 rounded-3xl text-white shadow-2xl overflow-hidden border border-slate-800">
                        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-2xl"></div>

                        <div class="relative z-10 space-y-6">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold">Why Learn With Our Platform?</h3>
                            <ul class="space-y-3.5 text-xs text-slate-300">
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Distraction-free learning interface with progress retention</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Server-calculated exam assessments with immediate grading</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Authentic QR & Number verifiable PDF certificates</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Seamless Bangladeshi payment gateways via SSLCommerz</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- F. Contact Us Section -->
    <section id="contact-section" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Contact Info Cards -->
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Direct Inquiry</span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Get in Touch With Us</h2>
                        <p class="text-sm text-slate-500 mt-2">Have a question about a course, syllabus, or payment? Our support desk is here to assist.</p>
                    </div>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Campus Location</h4>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $settings['contact_address'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Support Email</h4>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $settings['contact_email'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Direct Hotline</h4>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $settings['contact_phone'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-lg">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Send a Message</h3>
                        <p class="text-xs text-slate-500 mb-6">Fill in the form below and we will respond via email within 24 hours.</p>

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
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Your Full Name *</label>
                                    <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                    @error('name') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Your Email Address *</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                    @error('email') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Subject *</label>
                                <input type="text" name="subject" value="{{ old('subject') }}" placeholder="e.g. Question about payment or curriculum" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                                @error('subject') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Your Message *</label>
                                <textarea name="message" rows="4" required placeholder="Write your message here..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('message') }}</textarea>
                                @error('message') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-200 transition-all">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
