@extends('layouts.admin')

@section('title', 'Course Management')
@section('page_title', 'Courses Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Courses Directory</h1>
            <p class="text-xs text-slate-500 mt-0.5">Create, edit, organize lessons, and publish online courses.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Publish New Course
            </a>
        </div>
    </div>

    <!-- Search filter -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search course by title or slug..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 pl-9 pr-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-xs font-semibold rounded-xl hover:bg-slate-700 transition-colors">Search</button>
            @if($search)
                <a href="{{ route('admin.courses.index') }}" class="px-3 py-2.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-200 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    <!-- Courses Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Course</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Price / Discount</th>
                        <th class="p-4">Lessons</th>
                        <th class="p-4">Pass %</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $course)
                        <tr class="hover:bg-slate-50/60">
                            <!-- Course Thumbnail & Title -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-10 rounded-lg bg-slate-900 overflow-hidden shrink-0">
                                        @if($course->thumbnail)
                                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-indigo-900 flex items-center justify-center text-white text-[10px] font-bold">LMS</div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('courses.show', $course->slug) }}" target="_blank" class="font-bold text-slate-900 hover:text-indigo-600 transition-colors line-clamp-1">
                                            {{ $course->title }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $course->slug }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $course->category->name }}
                                </span>
                            </td>

                            <td class="p-4 font-semibold text-slate-900">
                                ৳{{ number_format($course->effective_price, 2) }}
                                @if($course->has_discount)
                                    <span class="text-[10px] text-slate-400 line-through block font-normal">
                                        ৳{{ number_format($course->price, 2) }}
                                    </span>
                                @endif
                            </td>

                            <td class="p-4">
                                <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="inline-flex items-center gap-1 font-bold text-indigo-600 hover:underline">
                                    {{ $course->lessons->count() }} Lessons →
                                </a>
                            </td>

                            <td class="p-4 font-semibold">{{ $course->passing_score }}%</td>

                            <td class="p-4">
                                <form method="POST" action="{{ route('admin.courses.publish', $course->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition-colors {{ $course->status === 'published' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }}">
                                        {{ $course->status }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 transition-colors" title="Manage Lessons">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-indigo-600 transition-colors" title="Edit Course">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.courses.destroy', $course->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this course? All associated lessons and exam associations will be removed.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg border border-slate-200 hover:bg-rose-50 text-rose-600 transition-colors" title="Delete">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">No courses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection
