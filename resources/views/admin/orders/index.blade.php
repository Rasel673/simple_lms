@extends('layouts.admin')

@section('title', 'Orders & Payments')
@section('page_title', 'Orders & Payments')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Orders & Payment Transactions</h1>
            <p class="text-xs text-slate-500 mt-0.5">Track student purchases, payment gateways, and verified order logs.</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <div class="md:col-span-6 relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search by order #, transaction ID, student name..." class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 pl-9 pr-3 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <div class="md:col-span-4">
                <select name="status" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Payment Statuses</option>
                    <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid & Verified</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full px-4 py-2.5 bg-slate-800 text-white text-xs font-semibold rounded-xl hover:bg-slate-700 transition-colors">Filter</button>
                @if($search || $status)
                    <a href="{{ route('admin.orders.index') }}" class="px-3 py-2.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-200 transition-colors">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="p-4">Order #</th>
                        <th class="p-4">Student</th>
                        <th class="p-4">Purchased Course</th>
                        <th class="p-4">Amount</th>
                        <th class="p-4">Gateway</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Date</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/60">
                            <td class="p-4 font-mono font-bold text-slate-900">{{ $order->order_number }}</td>

                            <td class="p-4">
                                <span class="font-bold text-slate-900 block">{{ $order->user->name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $order->user->email }}</span>
                            </td>

                            <td class="p-4 font-medium text-slate-800 truncate max-w-xs">
                                {{ $order->items->first()?->course?->title ?? 'N/A' }}
                            </td>

                            <td class="p-4 font-bold text-slate-900">৳{{ number_format($order->total_amount, 2) }}</td>

                            <td class="p-4 uppercase text-[10px] font-bold text-slate-500">
                                {{ $order->payment_method }}
                            </td>

                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($order->payment_status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>

                            <td class="p-4 text-slate-500">{{ $order->created_at->format('M d, Y h:i A') }}</td>

                            <td class="p-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 font-bold hover:underline">
                                    View Audit →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400">No orders matching criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
