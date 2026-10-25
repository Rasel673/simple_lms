@extends('layouts.admin')

@section('title', 'শিক্ষক ও স্টাফ বেতন ব্যবস্থাপনা')
@section('page_title', 'শিক্ষক ও কর্মকর্তা বেতন বিল')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-cyan-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>স্টাফ পে-রোল</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষক ও স্টাফ বেতন প্রদান ও ভাউচার</h1>
            <p class="text-xs text-slate-500 mt-1">মাসিক মূল বেতন, বোনাস ও কর্তন হিসাব করে বেতন ছাড়করণ ও ভাউচার তৈরি</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.school.fees.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                শিক্ষার্থী ফি তালিকায় ফিরুন
            </a>
            <button onclick="document.getElementById('disburseModal').classList.toggle('hidden')" class="px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 shadow-xs">
                বেতন প্রদান করুন
            </button>
        </div>
    </div>

    <!-- Disburse Salary Modal -->
    <div id="disburseModal" class="hidden bg-white p-6 rounded-2xl border-2 border-cyan-400 shadow-lg space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <h2 class="text-sm font-bold text-slate-900">মাসিক বেতন ছাড়করণ ফরম</h2>
            <button type="button" onclick="document.getElementById('disburseModal').classList.add('hidden')" class="text-slate-400">✕</button>
        </div>
        <form action="{{ route('admin.school.fees.salaries.disburse') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শিক্ষক/কর্মকর্তা *</label>
                <select name="staff_profile_id" id="staffSelect" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="">নির্বাচন করুন</option>
                    @foreach($staffList as $stf)
                        <option value="{{ $stf->id }}" data-salary="{{ $stf->monthly_salary }}">{{ $stf->name }} ({{ $stf->designation->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">মাস ও বছর *</label>
                <div class="flex gap-2">
                    <select name="month" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-2">
                        @foreach(['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'] as $mName)
                            <option value="{{ $mName }}" {{ $month === $mName ? 'selected' : '' }}>{{ $mName }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="year" value="2026" class="w-24 text-xs rounded-xl border-slate-200 py-2.5 px-2">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">মূল বেতন (৳) *</label>
                <input type="number" step="0.01" name="basic_salary" id="basicSalaryInput" value="30000.00" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">বোনাস (৳)</label>
                <input type="number" step="0.01" name="bonus" value="0.00" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">কর্তন (Deductions)</label>
                <input type="number" step="0.01" name="deductions" value="0.00" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">পেমেন্ট মেথড</label>
                <select name="payment_method" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
                    <option value="Bank Transfer">ব্যাংক ট্রান্সফার</option>
                    <option value="Cash">নগদ ক্যাশ</option>
                    <option value="bKash/Nagad">মোবাইল ব্যাংকিং</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">প্রদানের তারিখ *</label>
                <input type="date" name="payment_date" value="{{ \Carbon\Carbon::today()->toDateString() }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3">
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-emerald-600 text-white font-semibold text-xs hover:bg-emerald-700 transition-colors shadow-xs">
                    বেতন ছাড়করণ সম্পন্ন করুন
                </button>
            </div>
        </form>
    </div>

    <!-- Salary Records Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h2 class="text-xs font-bold text-slate-700 uppercase">বেতন প্রদানের ইতিহাস ও ভাউচার রেজিস্টার</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">ভাউচার নং</th>
                        <th class="px-4 py-3.5">শিক্ষক/স্টাফের নাম</th>
                        <th class="px-4 py-3.5">পদবি</th>
                        <th class="px-4 py-3.5">মাস ও বছর</th>
                        <th class="px-4 py-3.5">মূল বেতন</th>
                        <th class="px-4 py-3.5">বোনাস</th>
                        <th class="px-4 py-3.5">নিট প্রদেয়</th>
                        <th class="px-4 py-3.5">প্রদানের মাধ্যম ও তারিখ</th>
                        <th class="px-4 py-3.5">স্ট্যাটাস</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($salaryPayments as $sp)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 font-mono font-bold text-slate-800">{{ $sp->voucher_number }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-slate-900">{{ $sp->staff->name ?? '—' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $sp->staff->employee_id ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3.5">{{ $sp->staff->designation->name ?? 'স্টাফ' }}</td>
                            <td class="px-4 py-3.5 font-semibold text-slate-800">{{ $sp->month }} {{ $sp->year }}</td>
                            <td class="px-4 py-3.5">৳{{ number_format($sp->basic_salary, 2) }}</td>
                            <td class="px-4 py-3.5 text-emerald-600 font-semibold">৳{{ number_format($sp->bonus, 2) }}</td>
                            <td class="px-4 py-3.5 font-bold text-slate-900">৳{{ number_format($sp->net_salary, 2) }}</td>
                            <td class="px-4 py-3.5">
                                <span class="font-medium text-slate-800">{{ $sp->payment_method }}</span>
                                <div class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($sp->payment_date)->format('d M, Y') }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                    পরিশোধিত
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-slate-400">কোনো বেতন ছাড়করণের রেকর্ড নেই</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($salaryPayments->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $salaryPayments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
