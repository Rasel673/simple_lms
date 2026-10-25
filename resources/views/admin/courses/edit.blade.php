@extends('layouts.admin')

@section('title', 'Edit Course - ' . $course->title)
@section('page_title', 'Edit Course')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Edit Course</h1>
            <p class="text-xs text-slate-500 mt-0.5">Update course information, pricing, outcomes, and settings.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.courses.lessons.index', $course->id) }}" class="px-3.5 py-2 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-semibold hover:bg-indigo-100 transition-colors">
                Manage Lessons ({{ $course->lessons->count() }}) →
            </a>
            <a href="{{ route('admin.courses.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Back to Courses
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.courses.update', $course->id) }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Course Title *</label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('title') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">URL Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $course->slug) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('slug') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Discipline / Category *</label>
                <select name="category_id" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Price -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Standard Price (BDT) *</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $course->price) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('price') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Discount Price -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Discounted Sale Price (BDT)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price', $course->discount_price) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('discount_price') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration</label>
                <input type="text" name="duration" value="{{ old('duration', $course->duration) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>

            <!-- Passing Score -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Required Passing Score (%) *</label>
                <input type="number" name="passing_score" value="{{ old('passing_score', $course->passing_score) }}" min="1" max="100" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>

            <!-- Current & New Thumbnail -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Course Thumbnail</label>
                @if($course->thumbnail)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}" class="w-24 h-16 object-cover rounded-xl border border-slate-200">
                        <span class="text-xs text-slate-500">Current thumbnail</span>
                    </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3">
            </div>

            <!-- Short Description -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Short Overview *</label>
                <textarea name="short_description" rows="2" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('short_description', $course->short_description) }}</textarea>
            </div>

            <!-- Learning Outcomes -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Learning Outcomes (One per line)</label>
                @php
                    $outcomesText = is_array($course->learning_outcomes) ? implode("\n", $course->learning_outcomes) : $course->learning_outcomes;
                @endphp
                <textarea name="learning_outcomes" rows="4" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('learning_outcomes', $outcomesText) }}</textarea>
            </div>

            <!-- Full Description -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Full Course Description</label>
                <textarea name="description" rows="6" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('description', $course->description) }}</textarea>
            </div>

            <!-- Publication Status -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Publication Status *</label>
                <select name="status" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>Published (Live)</option>
                    <option value="draft" {{ old('status', $course->status) === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                </select>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.courses.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                Update Course Changes
            </button>
        </div>
    </form>
</div>
@endsection
