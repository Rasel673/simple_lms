@extends('layouts.app')

@section('title', 'Privacy Policy - ' . config('app.name', 'LearnHub LMS'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 sm:p-12 shadow-xs space-y-6">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Privacy Policy</h1>
        <p class="text-xs text-slate-400">Last updated: {{ date('F Y') }}</p>

        <div class="prose prose-slate prose-sm text-xs text-slate-600 leading-relaxed space-y-4">
            {!! nl2br(e($privacyPolicy)) !!}
        </div>
    </div>
</div>
@endsection
