@extends('layouts.admin')

@section('title', 'Edit Exam - ' . $exam->title)
@section('page_title', 'Edit Exam')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Edit Exam</h1>
            <p class="text-xs text-slate-500 mt-0.5">Course: <span class="font-bold text-slate-800">{{ $exam->course->title }}</span></p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.exams.questions', $exam->id) }}" class="px-3.5 py-2 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-semibold hover:bg-indigo-100 transition-colors">
                Manage Questions ({{ $exam->questions->count() }}) →
            </a>
            <a href="{{ route('admin.exams.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Back to Exams
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.exams.update', $exam->id) }}" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Course *</label>
            <select name="course_id" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id', $exam->course_id) == $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Exam Title *</label>
            <input type="text" name="title" value="{{ old('title', $exam->title) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            @error('title') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Exam Instructions</label>
            <textarea name="description" rows="3" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('description', $exam->description) }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Passing Score (%) *</label>
                <input type="number" name="passing_percentage" value="{{ old('passing_percentage', $exam->passing_percentage) }}" min="1" max="100" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration (Minutes) *</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="1" max="180" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Max Attempts *</label>
                <input type="number" name="max_attempts" value="{{ old('max_attempts', $exam->max_attempts) }}" min="0" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_required" value="1" {{ old('is_required', $exam->is_required) ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-slate-700">Course Completion Requires Passing</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $exam->is_published) ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-slate-700">Published</span>
            </label>
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.exams.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                Save Exam Changes
            </button>
        </div>
    </form>
</div>
@endsection
