@extends('layouts.student')

@section('title', 'My Certificates')
@section('page_title', 'Earned Certificates')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-extrabold text-slate-900">My Certified Credentials</h1>
        <p class="text-xs text-slate-500 mt-0.5">Download your verified PDF completion certificates or share your unique verification ID.</p>
    </div>

    @if($certificates->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-md mx-auto">
            <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800">No certificates earned yet</h3>
            <p class="text-xs text-slate-500 mt-1">Complete all lessons in an enrolled course and pass the final exam assessment to generate your certificate.</p>
            <a href="{{ route('student.my-courses') }}" class="inline-block mt-4 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold shadow-xs">
                Continue Courses
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($certificates as $cert)
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded">
                                {{ $cert->course->category->name }}
                            </span>
                            <span class="text-[11px] font-mono text-slate-400">
                                {{ $cert->certificate_number }}
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-slate-900 line-clamp-2">
                            {{ $cert->course->title }}
                        </h3>

                        <p class="text-[11px] text-slate-500">
                            Issued on: <span class="font-semibold text-slate-700">{{ $cert->issue_date->format('F d, Y') }}</span>
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                        <a href="{{ route('student.certificates.show', $cert->id) }}" class="flex-1 text-center py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors">
                            View
                        </a>
                        <a href="{{ route('student.certificates.download', $cert->id) }}" class="flex-1 text-center py-2 px-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors shadow-xs">
                            Download PDF
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $certificates->links() }}
        </div>
    @endif
</div>
@endsection
