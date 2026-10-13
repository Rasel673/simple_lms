@extends('layouts.admin')

@section('title', 'View Message')
@section('page_title', 'View Message')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Messages
        </a>

        <form method="POST" action="{{ route('admin.messages.destroy', $message->id) }}" onsubmit="return confirm('Delete message?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-3.5 py-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 font-semibold text-xs transition-colors">
                Delete Message
            </button>
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
        <div class="flex items-start justify-between border-b border-slate-100 pb-4">
            <div>
                <h1 class="text-lg font-bold text-slate-900">{{ $message->subject }}</h1>
                <p class="text-xs text-slate-500 mt-1">From: <strong class="text-slate-800">{{ $message->name }}</strong> &lt;{{ $message->email }}&gt;</p>
            </div>
            <span class="text-xs text-slate-400">{{ $message->created_at->format('F d, Y h:i A') }}</span>
        </div>

        <div class="text-xs text-slate-700 leading-relaxed whitespace-pre-line p-4 rounded-2xl bg-slate-50 border border-slate-100">
            {{ $message->message }}
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Reply via Email Client
            </a>
        </div>
    </div>
</div>
@endsection
