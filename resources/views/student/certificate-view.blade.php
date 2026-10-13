@extends('layouts.student')

@section('title', 'Certificate - ' . $certificate->certificate_number)
@section('page_title', 'Certificate Preview')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('student.certificates.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Certificates
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('certificate.verify', ['number' => $certificate->certificate_number]) }}" target="_blank" class="px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold">
                Verify Publicly ↗
            </a>
            <a href="{{ route('student.certificates.download', $certificate->id) }}" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-xs">
                Download PDF Certificate
            </a>
        </div>
    </div>

    <!-- On-screen Certificate Card -->
    <div class="bg-white rounded-3xl border-8 border-slate-900 p-8 sm:p-14 shadow-2xl relative overflow-hidden text-center space-y-6">
        <!-- Gold seal & badge -->
        <div class="flex items-center justify-center">
            <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-amber-400 to-amber-600 border-4 border-amber-200 text-white flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
            </div>
        </div>

        <div class="space-y-1">
            <span class="text-xs font-bold tracking-widest uppercase text-amber-700">Certificate of Completion</span>
            <p class="text-[11px] text-slate-400 uppercase tracking-widest font-semibold">{{ config('app.name', 'LearnHub LMS') }} Academic Council</p>
        </div>

        <p class="text-xs text-slate-500 italic">This is proudly presented to</p>
        <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 underline decoration-indigo-200 decoration-wavy underline-offset-8">
            {{ $certificate->user->name }}
        </h2>

        <p class="text-xs text-slate-600 max-w-lg mx-auto leading-relaxed pt-2">
            for successfully satisfying all comprehensive curriculum milestones, video modules, and passing the required certification examination for
        </p>

        <h3 class="text-lg sm:text-2xl font-bold text-indigo-700">
            {{ $certificate->course->title }}
        </h3>

        <!-- Certificate Footer -->
        <div class="pt-8 mt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs text-slate-500">
            <div class="text-left">
                <span class="block text-[11px] text-slate-400">Issue Date</span>
                <span class="font-bold text-slate-800">{{ $certificate->issue_date->format('F d, Y') }}</span>
                <span class="block text-[10px] text-slate-400 font-mono mt-1">ID: {{ $certificate->certificate_number }}</span>
            </div>

            <div class="text-right">
                <span class="block text-[11px] text-slate-400">Authorized Signature</span>
                <span class="font-serif italic font-bold text-slate-900 text-sm">Academic Director</span>
                <span class="block text-[10px] text-emerald-600 font-bold mt-1">✓ Digitally Signed & Verified</span>
            </div>
        </div>
    </div>
</div>
@endsection
