@extends('layouts.admin')

@section('title', 'Manage Questions - ' . $exam->title)
@section('page_title', 'Exam Questions')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.exams.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">MCQ Exams</a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold text-indigo-600 truncate max-w-sm">{{ $exam->title }}</span>
            </div>
            <h1 class="text-xl font-extrabold text-slate-900">Manage Exam Questions</h1>
            <p class="text-xs text-slate-500 mt-0.5">Course: <span class="font-bold text-slate-800">{{ $exam->course->title }}</span> • Passing threshold: {{ $exam->passing_percentage }}%</p>
        </div>
        <a href="{{ route('admin.exams.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            ← Back to Exams
        </a>
    </div>

    <!-- Add Question Card -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Multiple Choice Question
        </h3>

        <form method="POST" action="{{ route('admin.exams.questions.store', $exam->id) }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Question Text *</label>
                <textarea name="question_text" rows="2" required placeholder="e.g. Which HTTP method is used to update existing resources in RESTful APIs?" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('question_text') }}</textarea>
                @error('question_text') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Options with correct radio -->
            <div class="space-y-3">
                <label class="block text-xs font-bold text-slate-700">Answer Options & Correct Answer * (Select radio button for the correct option)</label>

                @for($i = 0; $i < 4; $i++)
                    <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50 focus-within:border-indigo-400 focus-within:bg-white transition-colors">
                        <label class="flex items-center gap-1.5 cursor-pointer shrink-0">
                            <input type="radio" name="correct_option" value="{{ $i }}" {{ old('correct_option', 0) == $i ? 'checked' : '' }} required class="text-emerald-600 focus:ring-emerald-500 h-4 w-4">
                            <span class="text-[11px] font-bold text-slate-600">Option {{ chr(65 + $i) }}</span>
                        </label>
                        <input type="text" name="options[{{ $i }}]" value="{{ old('options.' . $i) }}" required placeholder="Enter option text..." class="flex-1 text-xs border-0 bg-transparent focus:ring-0 p-0 text-slate-800">
                    </div>
                @endfor
                @error('options') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Explanation (Shown after submission, Optional)</label>
                    <textarea name="explanation" rows="2" placeholder="Explain why the chosen answer is correct..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('explanation') }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Marks for Question *</label>
                    <input type="number" name="marks" value="{{ old('marks', '1') }}" min="1" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                    Add Question to Exam
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Questions List -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center justify-between">
            <span>Existing Questions ({{ $exam->questions->count() }})</span>
            <span class="text-xs font-normal text-slate-500">Correct answers are highlighted in green</span>
        </h3>

        @if($exam->questions->isEmpty())
            <p class="text-xs text-slate-400 py-6 text-center">No questions added yet. Use the form above to add questions.</p>
        @else
            <div class="space-y-4">
                @foreach($exam->questions as $index => $q)
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-2.5">
                                <span class="w-6 h-6 rounded-md bg-indigo-100 text-indigo-700 font-extrabold text-xs flex items-center justify-center shrink-0">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">
                                        {{ $q->question_text }}
                                    </h4>
                                    @if($q->explanation)
                                        <p class="text-[11px] text-slate-500 mt-1 italic">
                                            Explanation: {{ $q->explanation }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.exams.questions.destroy', ['exam' => $exam->id, 'question' => $q->id]) }}" onsubmit="return confirm('Delete this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg border border-slate-200 hover:bg-rose-50 text-rose-600 transition-colors" title="Delete Question">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>

                        <!-- Options preview -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2 pl-8">
                            @foreach($q->options as $optIndex => $opt)
                                <div class="p-2.5 rounded-xl border text-xs flex items-center gap-2 {{ $opt->is_correct ? 'border-emerald-300 bg-emerald-50 text-emerald-900 font-bold' : 'border-slate-200 bg-white text-slate-700' }}">
                                    <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] {{ $opt->is_correct ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 text-slate-500' }}">
                                        {{ chr(65 + $optIndex) }}
                                    </span>
                                    <span class="truncate">{{ $opt->option_text }}</span>
                                    @if($opt->is_correct)
                                        <span class="ml-auto text-[10px] uppercase font-bold text-emerald-700">✓ Correct</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
