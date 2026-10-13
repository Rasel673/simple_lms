@extends('layouts.admin')

@section('title', 'Manage Lessons - ' . $course->title)
@section('page_title', 'Course Lessons')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">Courses</a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold text-indigo-600 truncate max-w-sm">{{ $course->title }}</span>
            </div>
            <h1 class="text-xl font-extrabold text-slate-900">Manage Course Curriculum</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.courses.lessons.create', $course->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add New Lesson
            </a>
            <a href="{{ route('admin.courses.index') }}" class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                Back to Courses
            </a>
        </div>
    </div>

    <!-- Lessons Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4 w-12 text-center">#</th>
                        <th class="p-4">Lesson Title</th>
                        <th class="p-4">External Video URL</th>
                        <th class="p-4">Duration</th>
                        <th class="p-4">Free Preview</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lessons as $lesson)
                        <tr class="hover:bg-slate-50/60">
                            <td class="p-4 text-center font-bold text-slate-800">{{ $lesson->order }}</td>

                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $lesson->title }}</span>
                                @if($lesson->description)
                                    <span class="text-[11px] text-slate-400 line-clamp-1">{{ $lesson->description }}</span>
                                @endif
                            </td>

                            <td class="p-4 font-mono text-[11px] text-slate-500 max-w-xs truncate">
                                @if($lesson->video_url)
                                    <a href="{{ $lesson->video_url }}" target="_blank" class="text-indigo-600 hover:underline flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        {{ $lesson->video_url }}
                                    </a>
                                @else
                                    <span class="text-slate-400">No URL</span>
                                @endif
                            </td>

                            <td class="p-4 text-slate-700">{{ $lesson->duration ?: '-' }}</td>

                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $lesson->is_preview ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $lesson->is_preview ? 'Free Preview' : 'Locked (Paid)' }}
                                </span>
                            </td>

                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.courses.lessons.edit', ['course' => $course->id, 'lesson' => $lesson->id]) }}" class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-indigo-600 transition-colors" title="Edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.courses.lessons.destroy', ['course' => $course->id, 'lesson' => $lesson->id]) }}" class="inline-block" onsubmit="return confirm('Delete this lesson?');">
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
                            <td colspan="6" class="p-8 text-center text-slate-400">No lessons created yet. Click "Add New Lesson" above.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
