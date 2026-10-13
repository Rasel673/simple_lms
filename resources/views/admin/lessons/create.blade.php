@extends('layouts.admin')

@section('title', 'Add Lesson - ' . $course->title)
@section('page_title', 'Add Lesson')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Add New Lesson</h1>
            <p class="text-xs text-slate-500 mt-0.5">Course: <span class="font-bold text-slate-800">{{ $course->title }}</span></p>
        </div>
        <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            ← Back to Lessons
        </a>
    </div>

    <form method="POST" action="{{ route('admin.courses.lessons.store', $course->id) }}" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Lesson Title *</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Introduction to Routing and Controllers" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            @error('title') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">External Video URL (YouTube, Vimeo, direct MP4)</label>
            <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            <p class="text-[11px] text-slate-400 mt-1">Supports standard YouTube links, unlisted YouTube videos, Vimeo video URLs, and direct embeddable links.</p>
            @error('video_url') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration</label>
                <input type="text" name="duration" value="{{ old('duration', '15 mins') }}" placeholder="e.g. 25 mins" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Sequence Order #</label>
                <input type="number" name="order" value="{{ old('order', $nextOrder) }}" min="1" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Lesson Notes / Description</label>
            <textarea name="description" rows="4" placeholder="Summary of topics covered in this lesson..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_preview" value="1" {{ old('is_preview') ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-slate-700">Allow as Free Preview Lesson</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-slate-700">Published (Visible in playlist)</span>
            </label>
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                Add Lesson to Syllabus
            </button>
        </div>
    </form>
</div>
@endsection
