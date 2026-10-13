@extends('layouts.student')

@section('title', 'My Enrolled Courses')
@section('page_title', 'My Enrolled Courses')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">My Enrolled Courses</h1>
            <p class="text-xs text-slate-500 mt-0.5">Select a course to resume your video lessons and exam assessments.</p>
        </div>
        <a href="{{ route('courses.index') }}" class="px-3.5 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition-colors shadow-xs">
            + Explore More Courses
        </a>
    </div>

    @if($enrollments->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-md mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">No active course enrollments</h3>
            <p class="text-xs text-slate-500 mt-1">You have not enrolled in any courses yet. Browse our catalog and start learning.</p>
            <a href="{{ route('courses.index') }}" class="inline-block mt-4 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold shadow-xs">
                Browse Course Catalog
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrollments as $enrollment)
                @php
                    $course = $enrollment->course;
                    $progress = $enrollment->progressData;
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
                    <div>
                        <!-- Thumbnail -->
                        <div class="relative h-40 bg-slate-900 overflow-hidden">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-slate-900 to-indigo-950 text-white text-xs font-bold p-4 text-center">
                                    {{ $course->title }}
                                </div>
                            @endif

                            <span class="absolute top-2.5 left-2.5 bg-white/90 backdrop-blur-md text-slate-800 text-[10px] font-bold px-2 py-0.5 rounded-md shadow-xs">
                                {{ $course->category->name }}
                            </span>

                            @if($progress['is_completed'])
                                <span class="absolute top-2.5 right-2.5 bg-emerald-500 text-white text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md shadow-xs flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    Passed
                                </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-5">
                            <h3 class="text-sm font-bold text-slate-900 line-clamp-2 mb-3">
                                <a href="{{ route('student.learning', ['course' => $course->id]) }}" class="hover:text-indigo-600 transition-colors">
                                    {{ $course->title }}
                                </a>
                            </h3>

                            <!-- Progress Bar -->
                            <div class="space-y-1.5 mb-2">
                                <div class="flex items-center justify-between text-[11px]">
                                    <span class="text-slate-500">{{ $progress['completed_lessons'] }} of {{ $progress['total_lessons'] }} lessons</span>
                                    <span class="font-bold text-indigo-600">{{ $progress['progress_percentage'] }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress['progress_percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="p-5 pt-0 border-t border-slate-100 flex items-center justify-between mt-2">
                        <span class="text-[11px] text-slate-400">
                            Enrolled {{ $enrollment->enrolled_at->format('M d, Y') }}
                        </span>
                        <a href="{{ route('student.learning', ['course' => $course->id]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-indigo-600 hover:bg-indigo-700 text-white shadow-xs transition-colors">
                            Continue
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $enrollments->links() }}
        </div>
    @endif
</div>
@endsection
