@extends('layouts.admin')

@section('title', 'Create New Course')
@section('page_title', 'Create Course')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Publish New Course</h1>
            <p class="text-xs text-slate-500 mt-0.5">Fill in the course details, pricing, outcomes, and syllabus settings.</p>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            ← Back to Courses
        </a>
    </div>

    <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-xs space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Course Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Complete Laravel & Modern PHP Masterclass" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('title') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Slug -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">URL Slug (Optional, auto-generated if blank)</label>
                <input type="text" name="slug" value="{{ old('slug') }}" placeholder="complete-laravel-masterclass" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('slug') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Category -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Discipline / Category *</label>
                <select name="category_id" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Price -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Standard Price (BDT) *</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', '2500.00') }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('price') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Discount Price -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Discounted Sale Price (BDT, Optional)</label>
                <input type="number" step="0.01" name="discount_price" value="{{ old('discount_price') }}" placeholder="Leave blank if no discount" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('discount_price') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration (e.g. 14 Hours)</label>
                <input type="text" name="duration" value="{{ old('duration', '12 Hours') }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('duration') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Passing Score -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Required Passing Score (%) *</label>
                <input type="number" name="passing_score" value="{{ old('passing_score', '70') }}" min="1" max="100" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                @error('passing_score') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Thumbnail Upload -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Course Thumbnail Image (JPEG, PNG, WEBP)</label>
                <input type="file" name="thumbnail" accept="image/*" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3">
                @error('thumbnail') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Short Description -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Short Overview (1-2 sentences for course cards) *</label>
                <textarea name="short_description" rows="2" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('short_description') }}</textarea>
                @error('short_description') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Learning Outcomes -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Learning Outcomes (Enter one outcome per line)</label>
                <textarea name="learning_outcomes" rows="4" placeholder="Architect production-grade web applications&#10;Master Eloquent ORM relationships&#10;Integrate SSLCommerz payment gateways" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('learning_outcomes') }}</textarea>
                @error('learning_outcomes') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Full Description (HTML/Text) -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Full Course Description & Syllabus Overview</label>
                <textarea name="description" rows="6" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('description') }}</textarea>
                @error('description') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Publication Status -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Publication Status *</label>
                <select name="status" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published (Visible to students)</option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                </select>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.courses.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                Save & Proceed to Lessons
            </button>
        </div>
    </form>
</div>
@endsection
