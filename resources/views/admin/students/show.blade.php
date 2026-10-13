@extends('layouts.admin')

@section('title', 'Student Profile - ' . $student->name)
@section('page_title', 'Student Profile')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Students Directory
        </a>
    </div>

    <!-- Student Header Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white font-extrabold text-2xl flex items-center justify-center shrink-0 shadow-md shadow-indigo-200">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">{{ $student->name }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ $student->email }} • {{ $student->phone ?: 'No phone provided' }}</p>
                <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700">
                    Joined {{ $student->created_at->format('M d, Y') }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-center p-3 rounded-2xl bg-slate-50 border border-slate-100 min-w-[90px]">
                <span class="text-xs text-slate-500 block">Courses</span>
                <span class="text-lg font-bold text-slate-900">{{ $student->enrollments->count() }}</span>
            </div>
            <div class="text-center p-3 rounded-2xl bg-emerald-50 border border-emerald-100 min-w-[90px]">
                <span class="text-xs text-emerald-700 block">Certificates</span>
                <span class="text-lg font-bold text-emerald-800">{{ $student->certificates->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Enrolled Courses -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Purchased Courses & Progress</h3>

        @if($student->enrollments->isEmpty())
            <p class="text-xs text-slate-400 py-4 text-center">No purchased courses for this student.</p>
        @else
            <div class="space-y-3">
                @foreach($student->enrollments as $enrollment)
                    @php $c = $enrollment->course; @endphp
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-slate-50">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">{{ $c->title }}</h4>
                            <span class="text-[11px] text-slate-500">Enrolled on {{ $enrollment->enrolled_at->format('M d, Y') }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $enrollment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $enrollment->status }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Exam Attempts -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">MCQ Exam Attempts History</h3>

        @if($student->examAttempts->isEmpty())
            <p class="text-xs text-slate-400 py-4 text-center">No exams attempted yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-semibold text-[10px]">
                        <tr>
                            <th class="p-3">Exam</th>
                            <th class="p-3">Attempt #</th>
                            <th class="p-3">Score</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($student->examAttempts as $attempt)
                            <tr>
                                <td class="p-3 font-semibold text-slate-900">{{ $attempt->exam->title }}</td>
                                <td class="p-3">#{{ $attempt->attempt_number }}</td>
                                <td class="p-3 font-bold">{{ $attempt->score_percentage }}% ({{ $attempt->correct_answers }}/{{ $attempt->total_questions }})</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $attempt->is_passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $attempt->is_passed ? 'PASSED' : 'FAILED' }}
                                    </span>
                                </td>
                                <td class="p-3 text-slate-500">{{ $attempt->started_at->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Certificates -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Earned Certificates</h3>

        @if($student->certificates->isEmpty())
            <p class="text-xs text-slate-400 py-4 text-center">No certificates earned yet.</p>
        @else
            <div class="space-y-3">
                @foreach($student->certificates as $cert)
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-slate-50">
                        <div>
                            <h4 class="text-xs font-bold text-slate-900">{{ $cert->course->title }}</h4>
                            <span class="text-[11px] font-mono text-slate-500">Number: {{ $cert->certificate_number }}</span>
                        </div>
                        <a href="{{ route('certificate.verify', ['number' => $cert->certificate_number]) }}" target="_blank" class="text-indigo-600 font-bold text-xs hover:underline">
                            Verify ↗
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
