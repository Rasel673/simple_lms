@extends('layouts.admin')

@section('title', 'Students Directory')
@section('page_title', 'Students Directory')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Registered Students</h1>
            <p class="text-xs text-slate-500 mt-0.5">View registered learners, enrolled courses, and exam performance.</p>
        </div>
    </div>

    <!-- Search filter -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.students.index') }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search by name, email, or phone..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 pl-9 pr-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white text-xs font-semibold rounded-xl hover:bg-slate-700 transition-colors">Search</button>
            @if($search)
                <a href="{{ route('admin.students.index') }}" class="px-3 py-2.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-200 transition-colors">Clear</a>
            @endif
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Student</th>
                        <th class="p-4">Contact</th>
                        <th class="p-4">Purchased Courses</th>
                        <th class="p-4">Exam Attempts</th>
                        <th class="p-4">Certificates</th>
                        <th class="p-4">Registered Date</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50/60">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block">{{ $student->name }}</span>
                                        <span class="text-[11px] text-slate-400 font-mono">ID: #{{ $student->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4">
                                <span class="text-slate-800 block">{{ $student->email }}</span>
                                <span class="text-[11px] text-slate-400">{{ $student->phone ?: 'No phone provided' }}</span>
                            </td>

                            <td class="p-4 font-bold text-indigo-600">
                                {{ $student->enrollments_count }} Courses
                            </td>

                            <td class="p-4 font-semibold text-slate-700">
                                {{ $student->exam_attempts_count }} Attempts
                            </td>

                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $student->certificates_count > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $student->certificates_count }} Earned
                                </span>
                            </td>

                            <td class="p-4 text-slate-500">{{ $student->created_at->format('M d, Y') }}</td>

                            <td class="p-4 text-right">
                                <a href="{{ route('admin.students.show', $student->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-indigo-600 font-semibold text-xs transition-colors">
                                    View Academic Profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-400">No students registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
