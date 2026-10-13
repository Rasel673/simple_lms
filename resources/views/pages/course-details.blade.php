@extends('layouts.app')

@section('title', ($course->meta_title ?: $course->title) . ' - ' . config('app.name', 'LearnHub LMS'))
@section('meta_description', $course->meta_description ?: $course->short_description)

@push('styles')
<!-- Schema.org Structured Data for SEO -->
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Course',
    'name' => $course->title,
    'description' => $course->short_description,
    'provider' => [
        '@type' => 'Organization',
        'name' => config('app.name', 'LearnHub LMS'),
        'sameAs' => url('/'),
    ],
    'offers' => [
        '@type' => 'Offer',
        'category' => $course->category->name,
        'price' => (string) $course->effective_price,
        'priceCurrency' => 'BDT',
        'availability' => 'https://schema.org/InStock',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<!-- Header Banner -->
<div class="bg-slate-900 text-white py-12 sm:py-16 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-8 space-y-4">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        {{ $course->category->name }}
                    </span>
                    <span class="text-xs text-slate-400">
                        Updated {{ $course->updated_at->format('M Y') }}
                    </span>
                </div>

                <h1 class="text-2xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    {{ $course->title }}
                </h1>

                <p class="text-sm sm:text-base text-slate-300 max-w-2xl leading-relaxed">
                    {{ $course->short_description }}
                </p>

                <div class="flex flex-wrap items-center gap-6 pt-2 text-xs text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ $course->duration ?: 'Self-Paced' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span>{{ $course->publishedLessons->count() }} Lessons Included</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                        <span>Verifiable Certificate</span>
                    </div>
                </div>
            </div>

            <!-- Header Quick Purchase Card on Desktop -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-2xl p-6 text-slate-800 shadow-xl border border-slate-200">
                    <div class="relative h-44 rounded-xl overflow-hidden mb-5 bg-slate-900">
                        @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-indigo-950 to-slate-900 text-white p-4 text-center text-xs font-semibold">
                                {{ $course->title }}
                            </div>
                        @endif
                    </div>

                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-3xl font-extrabold text-slate-900">
                            ৳{{ number_format($course->effective_price, 2) }}
                        </span>
                        @if($course->has_discount)
                            <span class="text-sm text-slate-400 line-through">
                                ৳{{ number_format($course->price, 2) }}
                            </span>
                            <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">
                                Save ৳{{ number_format($course->price - $course->effective_price, 2) }}
                            </span>
                        @endif
                    </div>

                    @if($isEnrolled)
                        <div class="space-y-3">
                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
                                <span class="text-xs font-bold text-emerald-800">✓ You are enrolled in this course</span>
                            </div>
                            <a href="{{ route('student.learning', ['course' => $course->id]) }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-700 text-white shadow-md transition-all">
                                Continue Learning
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    @else
                        <a href="{{ route('checkout.show', $course->slug) }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                            Buy Course Now
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <p class="text-[11px] text-slate-400 text-center mt-3">SSLCommerz secure encrypted checkout</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Course Body Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main details -->
        <div class="lg:col-span-8 space-y-10">
            <!-- Learning Outcomes -->
            @if(!empty($course->learning_outcomes) && is_array($course->learning_outcomes))
                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        What You Will Learn
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-700">
                        @foreach($course->learning_outcomes as $outcome)
                            <div class="flex items-start gap-2.5 p-2 rounded-lg bg-slate-50">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                <span>{{ $outcome }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Curriculum Topics -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Course Curriculum</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $course->publishedLessons->count() }} Structured Video Lessons</p>
                    </div>
                    @if($course->finalExam)
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            Assessment Exam Required ({{ $course->finalExam->passing_percentage }}% to pass)
                        </span>
                    @endif
                </div>

                <div class="space-y-2.5">
                    @forelse($course->publishedLessons as $index => $lesson)
                        <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-100 bg-slate-50 hover:bg-slate-100/80 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 rounded-md bg-white border border-slate-200 text-slate-700 font-bold text-[11px] flex items-center justify-center shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-800">{{ $lesson->title }}</h4>
                                    @if($lesson->description)
                                        <p class="text-[11px] text-slate-500 line-clamp-1">{{ $lesson->description }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3 shrink-0">
                                @if($lesson->duration)
                                    <span class="text-[11px] text-slate-500">{{ $lesson->duration }}</span>
                                @endif

                                @if($lesson->is_preview)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Preview</span>
                                @elseif(!$isEnrolled)
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-4 text-center">Lessons are currently being prepared for this course.</p>
                    @endforelse
                </div>
            </div>

            <!-- Course Description -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Course Description</h2>
                <div class="prose prose-slate prose-sm max-w-none text-xs text-slate-700 leading-relaxed space-y-3">
                    {!! $course->description !!}
                </div>
            </div>
        </div>

        <!-- Sidebar / Details -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Course Inclusions</h3>
                <ul class="space-y-3 text-xs text-slate-600">
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span>Full HD Video Lessons on demand</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                        <span>Verified MCQ Examination with 70% pass requirement</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Downloadable PDF Completion Certificate</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Lifetime Access from Desktop & Mobile</span>
                    </li>
                </ul>
            </div>

            <!-- Share & Verification Info -->
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 text-xs text-indigo-900 space-y-2">
                <h4 class="font-bold">Credential Verification</h4>
                <p class="text-indigo-700 leading-relaxed">
                    Certificates issued for this course are uniquely identified and can be verified publicly by future employers anytime on our verification portal.
                </p>
                <a href="{{ route('certificate.verify') }}" class="inline-block text-indigo-600 font-bold hover:underline pt-1">
                    Try Verification Search →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
