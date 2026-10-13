@extends('layouts.admin')

@section('title', 'Add Category')
@section('page_title', 'Add Category')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Add New Category</h1>
            <p class="text-xs text-slate-500 mt-0.5">Define a new academic course category.</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            ← Back to Categories
        </a>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Category Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Cloud Computing & DevOps" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            @error('name') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Slug (Optional, auto-generated if blank)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="cloud-computing" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            @error('slug') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Brief Description</label>
            <textarea name="description" rows="3" placeholder="Category overview..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 mb-1.5">Category Icon / Image (Optional)</label>
            <input type="file" name="image" accept="image/*" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3">
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                Save Category
            </button>
        </div>
    </form>
</div>
@endsection
