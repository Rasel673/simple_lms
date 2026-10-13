@extends('layouts.admin')

@section('title', 'Hero Sliders')
@section('page_title', 'Hero Sliders')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Homepage Hero Sliders</h1>
            <p class="text-xs text-slate-500 mt-0.5">Add, arrange, and manage slides featured on the public homepage header.</p>
        </div>
    </div>

    <!-- Add Slide Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Slide
        </h3>

        <form method="POST" action="{{ route('admin.content.sliders.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Slide Heading *</label>
                    <input type="text" name="heading" required placeholder="e.g. Master In-Demand Web Development Skills" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Short Description</label>
                    <textarea name="description" rows="2" placeholder="Brief overview of featured programs..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Call to Action Button Text</label>
                    <input type="text" name="button_text" placeholder="e.g. Browse Courses" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Button URL Link</label>
                    <input type="text" name="button_url" placeholder="#courses-section or /courses" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Background / Feature Image (Optional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2 px-3">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Display Order #</label>
                    <input type="number" name="order" value="{{ ($sliders->max('order') ?? 0) + 1 }}" min="1" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                    Create Slide
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Slides Table -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Existing Hero Slides ({{ $sliders->count() }})</h3>

        @if($sliders->isEmpty())
            <p class="text-xs text-slate-400 py-4 text-center">No slides configured yet.</p>
        @else
            <div class="space-y-4">
                @foreach($sliders as $slide)
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-slate-50 gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <span class="w-7 h-7 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center shrink-0">
                                {{ $slide->order }}
                            </span>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 truncate">{{ $slide->heading }}</h4>
                                <p class="text-[11px] text-slate-500 line-clamp-1">{{ $slide->description }}</p>
                                @if($slide->button_url)
                                    <span class="text-[10px] text-indigo-600 font-mono">{{ $slide->button_text }} → {{ $slide->button_url }}</span>
                                @endif
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.content.sliders.destroy', $slide->id) }}" onsubmit="return confirm('Delete this slide?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg border border-slate-200 hover:bg-rose-50 text-rose-600 transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
