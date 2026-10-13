@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('page_title', 'Inquiries')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-extrabold text-slate-900">Contact Messages & Inquiries</h1>
        <p class="text-xs text-slate-500 mt-0.5">Manage and respond to visitor inquiries submitted via the public contact form.</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Sender</th>
                        <th class="p-4">Subject</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Date</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-slate-50/60 {{ !$msg->is_read ? 'bg-amber-50/30' : '' }}">
                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $msg->name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $msg->email }}</span>
                            </td>

                            <td class="p-4">
                                <span class="font-semibold text-slate-800 block">{{ $msg->subject }}</span>
                                <span class="text-[11px] text-slate-400 truncate max-w-sm block">{{ $msg->message }}</span>
                            </td>

                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $msg->is_read ? 'bg-slate-100 text-slate-600' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $msg->is_read ? 'Read' : 'New Unread' }}
                                </span>
                            </td>

                            <td class="p-4 text-slate-500">{{ $msg->created_at->format('M d, Y h:i A') }}</td>

                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.messages.show', $msg->id) }}" class="text-indigo-600 font-bold hover:underline">
                                        Read →
                                    </a>
                                    <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}" class="inline-block" onsubmit="return confirm('Delete message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded text-slate-400 hover:text-rose-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400">No contact messages received.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
