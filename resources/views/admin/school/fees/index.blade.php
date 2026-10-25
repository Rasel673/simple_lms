@extends('layouts.admin')

@section('title', 'ফি ও বেতন ব্যবস্থাপনা')
@section('page_title', 'ফি সংগ্রহ ও বকেয়া রিপোর্ট')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-cyan-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>আর্থিক ব্যবস্থাপনা</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষার্থী ফি ও বকেয়া ব্যবস্থাপনা</h1>
            <p class="text-xs text-slate-500 mt-1">ভর্তি ফি, মাসিক বেতন, পরীক্ষা ফি ইনভয়েস তৈরি ও রসিদ প্রিন্ট</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.school.fees.salaries') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-cyan-200 bg-cyan-50/60 text-cyan-800 font-semibold text-xs hover:bg-cyan-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                শিক্ষক ও স্টাফ বেতন
            </a>
            <button onclick="document.getElementById('generateModal').classList.toggle('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700 transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                নতুন ফি ইনভয়েস তৈরি
            </button>
        </div>
    </div>

    <!-- Quick Financial Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <span class="text-xs text-slate-500 font-medium">মোট ইনভয়েসকৃত ফি</span>
            <p class="text-2xl font-bold text-slate-900 mt-1">৳{{ number_format($stats['total_invoiced'], 2) }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-emerald-100 bg-emerald-50/20 shadow-xs">
            <span class="text-xs text-emerald-700 font-medium">মোট সংগৃহীত ফি</span>
            <p class="text-2xl font-bold text-emerald-600 mt-1">৳{{ number_format($stats['total_collected'], 2) }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-rose-100 bg-rose-50/20 shadow-xs">
            <span class="text-xs text-rose-700 font-medium">মোট বকেয়া ফি (Dues)</span>
            <p class="text-2xl font-bold text-rose-600 mt-1">৳{{ number_format($stats['total_due'], 2) }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-blue-100 bg-blue-50/20 shadow-xs">
            <span class="text-xs text-blue-700 font-medium">পরিশোধিত বনাম বকেয়া ইনভয়েস</span>
            <p class="text-xl font-bold text-blue-800 mt-1">{{ $stats['paid_count'] }} পরিশোধিত / {{ $stats['unpaid_count'] }} বাকি</p>
        </div>
    </div>

    <!-- Generate Invoices Modal / Panel -->
    <div id="generateModal" class="hidden bg-white p-6 rounded-2xl border-2 border-amber-400 shadow-lg space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-sm font-bold text-slate-900">শ্রেণিভিত্তিক ফি ইনভয়েস তৈরি করুন</h2>
            <button type="button" onclick="document.getElementById('generateModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>
        <form action="{{ route('admin.school.fees.generate') }}" method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শ্রেণি *</label>
                <select name="academic_class_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="">শ্রেণি নির্বাচন করুন</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ফি টাইপ *</label>
                <select name="fee_type_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    @foreach($feeTypes as $ft)
                        <option value="{{ $ft->id }}">{{ $ft->name }} (৳{{ $ft->default_amount }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ইনভয়েসের নাম *</label>
                <input type="text" name="title" value="এপ্রিল ২০২৬ মাসের বেতন" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">টাকার পরিমাণ (৳) *</label>
                <input type="number" step="0.01" name="amount" value="1200.00" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">পরিশোধের শেষ তারিখ *</label>
                <input type="date" name="due_date" value="{{ \Carbon\Carbon::now()->addDays(15)->toDateString() }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div class="md:col-span-5 flex justify-end gap-2 pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700">
                    ইনভয়েস তৈরি করুন
                </button>
            </div>
        </form>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.school.fees.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="ইনভয়েস নং বা শিক্ষার্থীর নাম দিয়ে খুঁজুন..." class="w-full text-xs rounded-lg border-slate-200 py-2 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-2 px-3 focus:border-amber-500 focus:ring-amber-500">
                <option value="">সকল স্ট্যাটাস</option>
                <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>পরিশোধিত (Paid)</option>
                <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>বকেয়া (Unpaid)</option>
                <option value="partial" {{ $status === 'partial' ? 'selected' : '' }}>আংশিক (Partial)</option>
            </select>

            <select name="class_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-2 px-3 focus:border-amber-500 focus:ring-amber-500">
                <option value="">সকল শ্রেণি</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors">
                ফিল্টার
            </button>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">ইনভয়েস নং</th>
                        <th class="px-4 py-3.5">শিক্ষার্থীর নাম ও রোল</th>
                        <th class="px-4 py-3.5">শ্রেণি</th>
                        <th class="px-4 py-3.5">ফি টাইপ ও বিবরণ</th>
                        <th class="px-4 py-3.5">মোট পরিমাণ</th>
                        <th class="px-4 py-3.5">পরিশোধিত</th>
                        <th class="px-4 py-3.5">বকেয়া</th>
                        <th class="px-4 py-3.5">স্ট্যাটাস</th>
                        <th class="px-4 py-3.5 text-right">পদক্ষেপ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $inv)
                        @php $due = max(0, $inv->amount - $inv->paid_amount); @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 font-mono font-bold text-slate-800">{{ $inv->invoice_number }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-slate-900">{{ $inv->student->name ?? '—' }}</div>
                                <div class="text-[10px] text-slate-400">রোল: #{{ $inv->student->roll_number ?? '' }} | {{ $inv->student->admission_number ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3.5">{{ $inv->student->academicClass->name ?? '' }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-800">{{ $inv->feeType->name ?? '' }}</div>
                                <div class="text-[10px] text-slate-500">{{ $inv->title }}</div>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-900">৳{{ number_format($inv->amount, 2) }}</td>
                            <td class="px-4 py-3.5 font-bold text-emerald-600">৳{{ number_format($inv->paid_amount, 2) }}</td>
                            <td class="px-4 py-3.5 font-bold text-rose-600">৳{{ number_format($due, 2) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $inv->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($inv->status === 'partial' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $inv->status === 'paid' ? 'পরিশোধিত' : ($inv->status === 'partial' ? 'আংশিক' : 'বকেয়া') }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    @if($inv->status !== 'paid')
                                        <!-- Collect Fee Action Form Modal Trigger -->
                                        <button type="button" onclick="document.getElementById('collectModal_{{ $inv->id }}').classList.toggle('hidden')" class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white text-[10px] font-semibold hover:bg-emerald-700 shadow-xs">
                                            ফি গ্রহণ
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.school.fees.receipt', $inv) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-slate-200 text-slate-700 text-[10px] font-semibold hover:bg-slate-50">
                                        <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        রসিদ
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Quick Collect Modal for this row -->
                        <div id="collectModal_{{ $inv->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
                            <div class="bg-white rounded-2xl border border-slate-200 max-w-md w-full p-6 shadow-2xl space-y-4">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                    <h3 class="text-sm font-bold text-slate-900">ফি পেমেন্ট সংগ্রহ ({{ $inv->student->name ?? '' }})</h3>
                                    <button type="button" onclick="document.getElementById('collectModal_{{ $inv->id }}').classList.add('hidden')" class="text-slate-400">✕</button>
                                </div>
                                <form action="{{ route('admin.school.fees.collect', $inv) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">গ্রহণের পরিমাণ (৳) * (বকেয়া: ৳{{ $due }})</label>
                                        <input type="number" step="0.01" name="paid_amount" value="{{ $due }}" max="{{ $due }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">পেমেন্ট মেথড *</label>
                                        <select name="payment_method" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                                            <option value="Cash">নগদ ক্যাশ (Cash)</option>
                                            <option value="bKash">বিকাশ (bKash)</option>
                                            <option value="Nagad">নগদ (Nagad)</option>
                                            <option value="Bank Transfer">ব্যাংক ট্রান্সফার</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">তারিখ *</label>
                                        <input type="date" name="payment_date" value="{{ \Carbon\Carbon::today()->toDateString() }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">ট্রানজ্যাকশন আইডি / নোট</label>
                                        <input type="text" name="transaction_id" placeholder="TRX..." class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                                    </div>
                                    <div class="flex justify-end gap-2 pt-2">
                                        <button type="button" onclick="document.getElementById('collectModal_{{ $inv->id }}').classList.add('hidden')" class="px-4 py-2 text-xs text-slate-500">বাতিল</button>
                                        <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">পেমেন্ট নিশ্চিত করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-slate-400">কোনো ফি ইনভয়েস পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
