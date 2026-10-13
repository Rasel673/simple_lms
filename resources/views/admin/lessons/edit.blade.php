@extends('layouts.admin')

@section('title', 'Edit Lesson - ' . $lesson->title)
@section('page_title', 'Edit Lesson')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Edit Lesson</h1>
            <p class="text-xs text-slate-500 mt-0.5">Course: <span class="font-bold text-slate-800">{{ $course->title }}</span></p>
        </div>
        <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            ← Back to Lessons
        </a>
    </div>

    <form method="POST" action="{{ route('admin.courses.lessons.update', ['course' => $course->id, 'lesson' => $lesson->id]) }}" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Lesson Title *</label>
            <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            @error('title') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">External Video URL</label>
            <input type="url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            @error('video_url') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration</label>
                <input type="text" name="duration" value="{{ old('duration', $lesson->duration) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Sequence Order #</label>
                <input type="number" name="order" value="{{ old('order', $lesson->order) }}" min="1" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Lesson Notes / Description</label>
            <textarea name="description" rows="4" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('description', $lesson->description) }}</textarea>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_preview" value="1" {{ old('is_preview', $lesson->is_preview) ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-slate-700">Allow as Free Preview</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $lesson->is_published) ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-slate-700">Published</span>
            </label>
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                Save Lesson Changes
            </button>
        </div>
    </form>
</div>
@endsection
