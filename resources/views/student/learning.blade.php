<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $currentLesson->title }} - {{ $course->title }}</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 flex flex-col h-full antialiased overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- Top Navigation Bar -->
    <header class="h-16 bg-slate-900 border-b border-slate-800 px-4 sm:px-6 flex items-center justify-between shrink-0 z-30">
        <div class="flex items-center gap-4 min-w-0">
            <a href="{{ route('student.my-courses') }}" class="flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="hidden sm:inline">My Courses</span>
            </a>
            <span class="text-slate-700 hidden sm:inline">|</span>
            <div class="min-w-0">
                <h1 class="text-xs sm:text-sm font-bold text-white truncate max-w-md">{{ $course->title }}</h1>
                <span class="text-[10px] text-slate-400 truncate block">Lesson: {{ $currentLesson->title }}</span>
            </div>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            <!-- Progress indicator -->
            <div class="hidden md:flex items-center gap-2 text-xs">
                <div class="w-28 bg-slate-800 rounded-full h-2 overflow-hidden">
                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-300" style="width: {{ $progress['progress_percentage'] }}%"></div>
                </div>
                <span class="text-[11px] font-bold text-slate-300">{{ $progress['progress_percentage'] }}%</span>
            </div>

            <!-- Toggle playlist button -->
            <button @click="sidebarOpen = !sidebarOpen" type="button" class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-300 flex items-center gap-1.5 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                <span class="hidden sm:inline" x-text="sidebarOpen ? 'Hide Playlist' : 'Show Playlist'">Playlist</span>
            </button>
        </div>
    </header>

    <!-- Main Learning Container -->
    <div class="flex-1 flex overflow-hidden relative">

        <!-- Video & Controls Main Area -->
        <main class="flex-1 overflow-y-auto flex flex-col p-4 sm:p-6 lg:p-8 min-w-0 bg-slate-950">
            <div class="max-w-5xl mx-auto w-full space-y-6">

                <!-- Video Player Container -->
                <div class="rounded-2xl overflow-hidden bg-black shadow-2xl border border-slate-800 aspect-video relative flex items-center justify-center">
                    @if($currentLesson->embed_url)
                        <iframe src="{{ $currentLesson->embed_url }}"
                                title="{{ $currentLesson->title }}"
                                class="w-full h-full border-0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                    @else
                        <!-- No embed url provided or plain text note -->
                        <div class="text-center p-8 max-w-md">
                            <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-white mb-1">Lesson Material Available</h3>
                            <p class="text-xs text-slate-400">Review the lesson notes below. The video for this module will be added shortly.</p>
                        </div>
                    @endif
                </div>

                <!-- Lesson Header & Actions Bar -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-400 block mb-0.5">Current Module</span>
                        <h2 class="text-base sm:text-lg font-bold text-white">{{ $currentLesson->title }}</h2>
                        @if($currentLesson->duration)
                            <span class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $currentLesson->duration }}
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                        <!-- Mark Complete Form -->
                        <form method="POST" action="{{ route('student.learning.toggle', ['course' => $course->id, 'lesson' => $currentLesson->id]) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 rounded-xl text-xs font-semibold flex items-center gap-2 transition-all {{ $isCurrentCompleted ? 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/40 hover:bg-emerald-600/30' : 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-md' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ $isCurrentCompleted ? 'Completed (Click to Revert)' : 'Mark as Completed' }}</span>
                            </button>
                        </form>

                        <!-- Prev & Next Lesson Buttons -->
                        <div class="flex items-center gap-2">
                            @if($prevLesson)
                                <a href="{{ route('student.learning', ['course' => $course->id, 'lesson' => $prevLesson->id]) }}" class="px-3.5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 flex items-center gap-1.5 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                    <span>Prev</span>
                                </a>
                            @else
                                <span class="px-3.5 py-2.5 rounded-xl bg-slate-900 text-xs font-semibold text-slate-600 border border-slate-800 cursor-not-allowed">
                                    Prev
                                </span>
                            @endif

                            @if($nextLesson)
                                <a href="{{ route('student.learning', ['course' => $course->id, 'lesson' => $nextLesson->id]) }}" class="px-3.5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-semibold text-white flex items-center gap-1.5 transition-colors shadow-xs">
                                    <span>Next</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            @elseif($progress['final_exam'])
                                <a href="{{ route('student.exam.show', $progress['final_exam']->id) }}" class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-xs font-bold text-slate-950 flex items-center gap-1.5 transition-colors shadow-xs">
                                    <span>Take Exam</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Exam / Completion Notice Banner -->
                @if($progress['is_all_lessons_completed'])
                    @if($progress['has_required_exam'] && !$progress['has_passed_exam'])
                        <div class="bg-gradient-to-r from-amber-950/80 to-slate-900 border border-amber-500/40 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Step 2: Certification Examination</span>
                                <h3 class="text-base font-bold text-white">All lessons completed! Now take the Certification Exam.</h3>
                                <p class="text-xs text-slate-300">Pass with at least {{ $progress['final_exam']->passing_percentage }}% to receive your verified PDF certificate.</p>
                            </div>
                            <a href="{{ route('student.exam.show', $progress['final_exam']->id) }}" class="px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shrink-0 shadow-lg transition-all">
                                Start Assessment Exam →
                            </a>
                        </div>
                    @elseif($progress['is_completed'])
                        <div class="bg-gradient-to-r from-emerald-950/80 to-slate-900 border border-emerald-500/40 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Course Fully Completed!</span>
                                <h3 class="text-base font-bold text-white">Congratulations! You have fulfilled all completion requirements.</h3>
                                <p class="text-xs text-slate-300">Your verified certificate of completion has been automatically generated.</p>
                            </div>
                            <a href="{{ route('student.certificates.index') }}" class="px-6 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs shrink-0 shadow-lg transition-all">
                                View & Download Certificate →
                            </a>
                        </div>
                    @endif
                @endif

                <!-- Lesson Description & Text -->
                @if($currentLesson->description)
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 space-y-3">
                        <h3 class="text-sm font-bold text-white">Lesson Overview & Notes</h3>
                        <p class="text-xs text-slate-300 leading-relaxed whitespace-pre-line">{{ $currentLesson->description }}</p>
                    </div>
                @endif
            </div>
        </main>

        <!-- Right Sidebar: Lesson Playlist -->
        <aside x-show="sidebarOpen" x-cloak
               class="w-80 bg-slate-900 border-l border-slate-800 flex flex-col shrink-0 overflow-y-auto">
            <div class="p-4 border-b border-slate-800 flex items-center justify-between bg-slate-900/90 sticky top-0 z-10 backdrop-blur-md">
                <div>
                    <h3 class="text-xs font-bold text-white">Curriculum Playlist</h3>
                    <p class="text-[10px] text-slate-400">{{ $progress['completed_lessons'] }}/{{ $progress['total_lessons'] }} Lessons Completed</p>
                </div>
            </div>

            <!-- Lessons List -->
            <div class="p-3 space-y-1.5 flex-1">
                @foreach($lessons as $index => $l)
                    @php $isDone = $l->isCompletedBy(auth()->id()); @endphp
                    <a href="{{ route('student.learning', ['course' => $course->id, 'lesson' => $l->id]) }}"
                       class="flex items-center justify-between p-3 rounded-xl text-xs transition-all {{ $l->id === $currentLesson->id ? 'bg-indigo-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <!-- Checkmark circle -->
                            <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 {{ $isDone ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'border border-slate-700 text-slate-500' }}">
                                @if($isDone)
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                @else
                                    <span class="text-[9px]">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <span class="truncate">{{ $l->title }}</span>
                        </div>

                        @if($l->duration)
                            <span class="text-[10px] text-slate-400 shrink-0 ml-2">{{ $l->duration }}</span>
                        @endif
                    </a>
                @endforeach

                <!-- Exam in Playlist -->
                @if($progress['final_exam'])
                    <div class="pt-3">
                        <a href="{{ route('student.exam.show', $progress['final_exam']->id) }}" class="flex items-center justify-between p-3 rounded-xl border border-amber-500/30 bg-amber-950/20 text-amber-300 hover:bg-amber-950/40 transition-colors">
                            <div class="flex items-center gap-2 min-w-0">
                                <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                                <span class="text-xs font-bold truncate">Certification Exam</span>
                            </div>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $progress['has_passed_exam'] ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-300' }}">
                                {{ $progress['has_passed_exam'] ? 'Passed' : 'Required' }}
                            </span>
                        </a>
                    </div>
                @endif
            </div>
        </aside>
    </div>
</body>
</html>
