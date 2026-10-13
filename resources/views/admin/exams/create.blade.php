@extends('layouts.admin')

@section('title', 'Create MCQ Exam')
@section('page_title', 'Create Exam')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Create New MCQ Exam</h1>
            <p class="text-xs text-slate-500 mt-0.5">Define exam guidelines, pass thresholds, and timing.</p>
        </div>
        <a href="{{ route('admin.exams.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            ← Back to Exams
        </a>
    </div>

    <form method="POST" action="{{ route('admin.exams.store') }}" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Assign to Course *</label>
            <select name="course_id" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                <option value="">Select Course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
            @error('course_id') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Exam Title *</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Laravel Final Certification Assessment" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            @error('title') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Exam Instructions / Description</label>
            <textarea name="description" rows="3" placeholder="Brief rules and instructions for students..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Passing Score (%) *</label>
                <input type="number" name="passing_percentage" value="{{ old('passing_percentage', '70') }}" min="1" max="100" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('passing_percentage') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration (Minutes) *</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', '30') }}" min="1" max="180" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('duration_minutes') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Max Attempts (0 = unlimited) *</label>
                <input type="number" name="max_attempts" value="{{ old('max_attempts', '3') }}" min="0" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('max_attempts') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-6 pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_required" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-slate-700">Course Completion Requires Passing this Exam</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                <span class="text-xs font-semibold text-slate-700">Published</span>
            </label>
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.exams.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                Create & Add Questions →
            </button>
        </div>
    </form>
</div>
@endsection
