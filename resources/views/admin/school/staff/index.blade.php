@extends('layouts.admin')

@section('title', 'শিক্ষক ও স্টাফ ব্যবস্থাপনা')
@section('page_title', 'শিক্ষক ও কর্মকর্তা তালিকা')

@section('content')
<div class="space-y-6">
    <!-- Header / Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span>শিক্ষক ও স্টাফ প্রোফাইল</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষক ও কর্মকর্তা ব্যবস্থাপনা</h1>
            <p class="text-xs text-slate-500 mt-1">শিক্ষক ও কর্মচারীদের প্রোফাইল, পদবি, মাসিক বেতন ও ক্লাস বরাদ্দ</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.school.staff.assignments') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50/60 text-emerald-800 text-xs font-semibold hover:bg-emerald-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                ক্লাস ও বিষয় বরাদ্দ
            </a>
            <a href="{{ route('admin.school.staff.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                নতুন শিক্ষক/স্টাফ যুক্ত করুন
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.school.staff.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="নাম, এমপ্লয়ি আইডি বা ফোন দিয়ে খুঁজুন..." class="w-full text-xs rounded-lg border-slate-200 py-2 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <select name="designation_id" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-200 py-2 px-3 focus:border-amber-500 focus:ring-amber-500">
                <option value="">সকল পদবি</option>
                @foreach($designations as $d)
                    <option value="{{ $d->id }}" {{ $designationId == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition-colors">
                অনুসন্ধান
            </button>
            @if($search || $designationId)
                <a href="{{ route('admin.school.staff.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800">রিসেট</a>
            @endif
        </form>
    </div>

    <!-- Staff Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">আইডি</th>
                        <th class="px-4 py-3.5">নাম</th>
                        <th class="px-4 py-3.5">পদবি ও দায়িত্ব</th>
                        <th class="px-4 py-3.5">শিক্ষাগত যোগ্যতা</th>
                        <th class="px-4 py-3.5">যোগাযোগ</th>
                        <th class="px-4 py-3.5">মাসিক বেতন</th>
                        <th class="px-4 py-3.5">বরাদ্দকৃত বিষয়</th>
                        <th class="px-4 py-3.5">স্ট্যাটাস</th>
                        <th class="px-4 py-3.5 text-right">পদক্ষেপ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($staff as $member)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-4 py-3.5 font-bold font-mono text-slate-700">{{ $member->employee_id }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-bold text-slate-900">{{ $member->name }}</div>
                                <div class="text-[10px] text-slate-400">যোগদান: {{ $member->joining_date ? \Carbon\Carbon::parse($member->joining_date)->format('d M, Y') : '—' }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-semibold border border-emerald-200">
                                    {{ $member->designation->name ?? 'স্টাফ' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-slate-700">{{ $member->qualification ?? '—' }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-medium text-slate-800">{{ $member->phone ?? '—' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $member->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-slate-800">৳{{ number_format($member->monthly_salary, 2) }}</td>
                            <td class="px-4 py-3.5">
                                @if($member->assignments->isNotEmpty())
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($member->assignments as $asgn)
                                            <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px]">
                                                {{ $asgn->subject->name ?? '' }} ({{ $asgn->academicClass->name ?? '' }})
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">বরাদ্দ নেই</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $member->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $member->status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.school.staff.edit', $member) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-slate-100 transition-colors" title="সম্পাদনা">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.school.staff.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই শিক্ষক/স্টাফ প্রোফাইল মুছে ফেলতে চান?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="মুছে ফেলুন">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-slate-400">কোনো শিক্ষক/স্টাফের তথ্য পাওয়া যায়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staff->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $staff->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
