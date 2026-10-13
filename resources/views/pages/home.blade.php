@extends('layouts.app')

@section('title', \App\Models\WebsiteSetting::get('meta_title', 'LearnHub LMS - Sell & Learn Online Courses'))
@section('meta_description', \App\Models\WebsiteSetting::get('meta_description', 'Discover modern video courses, pass MCQ assessments, and earn verified certificates.'))

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
            'duration' => $c->duration ?: 'Self-Paced',
            'thumbnail' => $c->thumbnail ? asset('storage/' . $c->thumbnail) : null,
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
            let matchesSearch = (!this.searchQuery || c.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || c.short_description.toLowerCase().includes(this.searchQuery.toLowerCase()));
            return matchesCategory && matchesSearch;
        });
    }
}" class="relative">

    <!-- 1. Hero Background Image Slider Section -->
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
                        this.timer = setInterval(() => this.next(), 5000);
                    }
                }
            }" class="relative w-full h-[360px] sm:h-[460px] md:h-[520px] lg:h-[560px]">

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
                            <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->heading ?: 'LMS Banner' }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900"></div>
                        @endif

                        <!-- Nullable Text & Link Overlay -->
                        @if($slide->heading || $slide->description || $slide->button_text)
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent flex items-end sm:items-center p-6 sm:p-12 lg:p-20">
                                <div class="max-w-3xl space-y-4 text-white">
                                    @if($slide->heading)
                                        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white drop-shadow-md">
                                            {{ $slide->heading }}
                                        </h1>
                                    @endif

                                    @if($slide->description)
                                        <p class="text-xs sm:text-base text-slate-200 max-w-2xl leading-relaxed drop-shadow-sm">
                                            {{ $slide->description }}
                                        </p>
                                    @endif

                                    @if($slide->button_url)
                                        <div class="pt-2">
                                            <a href="{{ $slide->button_url }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-xs sm:text-sm bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/40 transition-all transform hover:-translate-y-0.5">
                                                {{ $slide->button_text ?: 'Explore Now' }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($slide->button_url)
                            <!-- Clickable background image link when no text is present -->
                            <a href="{{ $slide->button_url }}" class="absolute inset-0 z-10" title="View details"></a>
                        @endif
                    </div>
                @endforeach

                <!-- Slider Controls (Only shown if more than 1 slide) -->
                @if($sliders->count() > 1)
                    <!-- Navigation Dots -->
                    <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 z-20 flex items-center gap-2">
                        @foreach($sliders as $index => $slide)
                            <button @click="currentSlide = {{ $index }}"
                                    :class="currentSlide === {{ $index }} ? 'w-8 bg-white' : 'w-2.5 bg-white/50 hover:bg-white/80'"
                                    class="h-2 rounded-full transition-all duration-300 shadow-sm"></button>
                        @endforeach
                    </div>

                    <!-- Prev/Next Controls -->
                    <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/30 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-xs transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/30 hover:bg-black/60 text-white flex items-center justify-center backdrop-blur-xs transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @endif
            </div>
        @else
            <!-- Minimal Static Banner Fallback -->
            <div class="relative py-20 px-6 sm:px-12 text-center text-white bg-gradient-to-r from-slate-950 via-indigo-950 to-slate-900">
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white mb-3">
                    {{ \App\Models\WebsiteSetting::get('site_name', 'LearnHub LMS') }}
                </h1>
                <p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto mb-6">
                    {{ \App\Models\WebsiteSetting::get('site_tagline', 'Learn practical high-demand tech skills, pass MCQ exams, and earn certified credentials.') }}
                </p>
                <div class="flex items-center justify-center gap-3">
                    <a href="#courses-section" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md">
                        Browse Courses
                    </a>
                    <a href="#verify-section" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs border border-slate-700">
                        Verify Certificate
                    </a>
                </div>
            </div>
        @endif
    </section>

    <!-- 2. Course Categories Filter Bar -->
    <section id="categories-section" class="py-8 bg-white border-b border-slate-100 sticky top-18 z-30 shadow-xs backdrop-blur-md bg-white/95">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <!-- All Courses Tab -->
                <button @click="selectedCategory = 'all'"
                        :class="selectedCategory === 'all' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100 font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium'"
                        class="px-4 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0">
                    <span>All Disciplines</span>
                    <span :class="selectedCategory === 'all' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600'" class="px-1.5 py-0.2 rounded-md text-[10px]">
                        {{ $courses->count() }}
                    </span>
                </button>

                <!-- Category Tabs -->
                @foreach($categories as $category)
                    <button @click="selectedCategory = '{{ $category->id }}'"
                            :class="selectedCategory === '{{ $category->id }}' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100 font-bold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium'"
                            class="px-4 py-2 rounded-xl text-xs whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0">
                        <span>{{ $category->name }}</span>
                        <span :class="selectedCategory === '{{ $category->id }}' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600'" class="px-1.5 py-0.2 rounded-md text-[10px]">
                            {{ $category->courses_count }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 3. All Courses Section with Live Search & Cards -->
    <section id="courses-section" class="py-16 bg-slate-50 border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Curriculum Catalog</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                        Online Courses & Certifications
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        High-definition video lectures, MCQ tests, and automated verifiable certificates.
                    </p>
                </div>

                <!-- Instant Search Bar -->
                <div class="relative w-full max-w-xs">
                    <input type="text"
                           x-model="searchQuery"
                           placeholder="Filter courses by name..."
                           class="w-full text-xs rounded-xl border-slate-200 bg-white pl-9 pr-8 py-2.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <button x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-2.5 text-xs text-slate-400 hover:text-slate-600">✕</button>
                </div>
            </div>

            <!-- Course Cards Grid (Rendered via Alpine for Instant Filter) -->
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
                            <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-slate-900 text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-sm" x-text="course.category_name"></span>

                            <!-- Discount Badge -->
                            <template x-if="course.has_discount">
                                <span class="absolute top-3 right-3 bg-rose-500 text-white text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md shadow-sm">
                                    Sale
                                </span>
                            </template>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <!-- Meta Pills -->
                                <div class="flex items-center gap-3 text-[11px] text-slate-500 mb-2.5">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span x-text="course.duration"></span>
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span x-text="course.lessons_count + ' Lessons'"></span>
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
                                    <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                        Certificate Included
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <!-- View Details Button (Opens Modal) -->
                                    <button @click="openCourse(course)" type="button" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors">
                                        Details & Lessons
                                    </button>

                                    <!-- Enrolled / Buy Button -->
                                    <template x-if="course.is_enrolled">
                                        <a :href="course.learn_url" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                            Continue
                                        </a>
                                    </template>
                                    <template x-if="!course.is_enrolled">
                                        <a :href="course.checkout_url" class="inline-flex items-center justify-center px-3 py-2 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition-colors">
                                            Buy Course
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
                <h3 class="text-base font-bold text-slate-800">No courses match your search</h3>
                <p class="text-xs text-slate-500 mt-1">Try resetting the category filter or searching with different terms.</p>
                <button @click="selectedCategory = 'all'; searchQuery = ''" class="inline-block mt-4 text-xs font-semibold text-indigo-600 hover:underline">Reset Filters</button>
            </div>
        </div>
    </section>

    <!-- 4. Course Details Popup Modal (A1 Recommendation) -->
    <div x-show="activeCourseModal !== null"
         x-cloak
         @keydown.escape.window="closeModal()"
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true">

        <!-- Backdrop overlay -->
        <div x-show="activeCourseModal !== null"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs transition-opacity"
             @click="closeModal()"></div>

        <!-- Modal Dialog -->
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
                                    <span class="text-xs font-bold text-rose-400 bg-rose-500/20 px-2 py-0.5 rounded-md" x-text="'Save ৳' + selectedCourse.savings.toFixed(2)"></span>
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
                                        What You Will Learn
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700">
                                        <template x-for="outcome in selectedCourse.learning_outcomes" :key="outcome">
                                            <div class="flex items-start gap-2 p-2 rounded-lg bg-slate-50 border border-slate-100">
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
                                        Curriculum & Lessons (<span x-text="selectedCourse.lessons_count"></span>)
                                    </h4>
                                    <template x-if="selectedCourse.has_exam">
                                        <span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md" x-text="'Exam Pass Score: ' + selectedCourse.passing_score + '%'"></span>
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
                                                <span x-show="lesson.is_preview" class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded">Preview</span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Action Footer -->
                        <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                            <button @click="closeModal()" type="button" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-white text-xs font-semibold">
                                Close
                            </button>

                            <div>
                                <template x-if="selectedCourse.is_enrolled">
                                    <a :href="selectedCourse.learn_url" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md">
                                        Continue Learning
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </template>
                                <template x-if="!selectedCourse.is_enrolled">
                                    <a :href="selectedCourse.checkout_url" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-200">
                                        Buy Course Now
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

    <!-- 5. On-Page Certificate Verification Tool (Unified Section) -->
    <section id="verify-section" class="py-16 bg-white border-b border-slate-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Credential Registry</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Verify Certificate Authenticity</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">
                    Enter any certificate identification number to instantly verify graduate authenticity.
                </p>
            </div>

            <!-- Lookup Form -->
            <form method="GET" action="{{ route('home') }}#verify-section" class="max-w-xl mx-auto mb-8 flex gap-2">
                <div class="relative w-full">
                    <input type="text"
                           name="verify_number"
                           value="{{ $searchedCertNumber }}"
                           required
                           placeholder="Enter Certificate Number e.g. LMS-XXXX-XXXX-YYYY"
                           class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 pl-10 pr-4 py-3 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 shadow-xs uppercase font-mono">
                    <svg class="w-5 h-5 text-indigo-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md transition-colors shrink-0">
                    Verify Now
                </button>
            </form>

            <!-- Verification Result Card (If Searched) -->
            @if($searchedCertNumber)
                @if($verifiedCertificate)
                    <div class="bg-gradient-to-br from-emerald-500/10 via-white to-slate-50 border-2 border-emerald-500/30 rounded-3xl p-6 sm:p-8 shadow-xl max-w-2xl mx-auto">
                        <div class="flex items-center gap-3 text-emerald-800 font-extrabold text-sm mb-4">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                ✓
                            </div>
                            <span>OFFICIALLY VERIFIED CERTIFICATE</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">Awarded Student</span>
                                <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $verifiedCertificate->user->name }}</p>
                            </div>

                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">Credential ID</span>
                                <p class="text-sm font-mono font-bold text-indigo-600 mt-0.5">{{ $verifiedCertificate->certificate_number }}</p>
                            </div>

                            <div class="sm:col-span-2 p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">Completed Program</span>
                                <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $verifiedCertificate->course->title }}</p>
                            </div>

                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">Issue Date</span>
                                <p class="text-xs font-semibold text-slate-800 mt-0.5">{{ $verifiedCertificate->issue_date->format('F d, Y') }}</p>
                            </div>

                            <div class="p-3 bg-white rounded-xl border border-slate-200/80">
                                <span class="text-[10px] uppercase font-bold text-slate-400">Issuer Status</span>
                                <p class="text-xs font-semibold text-emerald-600 mt-0.5">Authentic & Legitimate</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-6 text-center max-w-lg mx-auto text-xs">
                        <span class="font-bold block text-sm mb-1">No Matching Record Found</span>
                        <span>We could not find an official credential matching ID <strong class="font-mono">{{ $searchedCertNumber }}</strong>. Please verify the code and re-enter.</span>
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
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">Academy Mission</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $settings['about_heading'] ?? 'Master High-Demand Practical Skills' }}
                    </h2>
                    <div class="text-sm text-slate-600 leading-relaxed space-y-4">
                        {!! nl2br(e($settings['about_content'] ?? 'Our mission is to empower developers and tech enthusiasts through structured, high-definition online video courses. Learn at your own pace, test your knowledge with server-evaluated MCQ assessments, and receive downloadable verified certificates upon successful completion.')) !!}
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
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
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

    <!-- 7. Contact Us Section -->
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
                                <p class="text-xs text-slate-600 mt-0.5">{{ $settings['contact_address'] ?? 'Dhanmondi, Dhaka, Bangladesh' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Support Email</h4>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $settings['contact_email'] ?? 'support@learnhub.com' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-xs">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Direct Hotline</h4>
                                <p class="text-xs text-slate-600 mt-0.5">{{ $settings['contact_phone'] ?? '+880 1700 000000' }}</p>
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

</div>

@endsection
