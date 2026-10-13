@extends('layouts.admin')

@section('title', 'Exam Management')
@section('page_title', 'MCQ Assessments')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">MCQ Examination Management</h1>
            <p class="text-xs text-slate-500 mt-0.5">Create topic-based multiple choice exams, set passing scores, and configure retake rules.</p>
        </div>
        <a href="{{ route('admin.exams.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition-colors shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create New Exam
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Exam Title</th>
                        <th class="p-4">Assigned Course</th>
                        <th class="p-4">Passing Score</th>
                        <th class="p-4">Questions</th>
                        <th class="p-4">Duration</th>
                        <th class="p-4">Max Attempts</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($exams as $exam)
                        <tr class="hover:bg-slate-50/60">
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $exam->title }}</span>
                                @if($exam->description)
                                    <span class="text-[11px] text-slate-400 line-clamp-1">{{ $exam->description }}</span>
                                @endif
                            </td>

                            <td class="p-4 font-semibold text-slate-800">
                                {{ $exam->course->title }}
                            </td>

                            <td class="p-4 font-bold text-amber-700">
                                {{ $exam->passing_percentage }}%
                            </td>

                            <td class="p-4">
                                <a href="{{ route('admin.exams.questions', $exam->id) }}" class="inline-flex items-center gap-1 font-bold text-indigo-600 hover:underline">
                                    {{ $exam->questions->count() }} Questions →
                                </a>
                            </td>

                            <td class="p-4">{{ $exam->duration_minutes }} Mins</td>

                            <td class="p-4">
                                {{ $exam->max_attempts > 0 ? $exam->max_attempts . ' Attempts' : 'Unlimited' }}
                            </td>

                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.exams.questions', $exam->id) }}" class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-indigo-600 transition-colors" title="Manage Questions">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </a>
                                    <a href="{{ route('admin.exams.edit', $exam->id) }}" class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 transition-colors" title="Edit Exam">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.exams.destroy', $exam->id) }}" class="inline-block" onsubmit="return confirm('Delete this exam?');">
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
                            <td colspan="7" class="p-8 text-center text-slate-400">No exams configured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $exams->links() }}
        </div>
    </div>
</div>
@endsection
