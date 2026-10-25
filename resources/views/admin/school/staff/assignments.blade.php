@extends('layouts.admin')

@section('title', 'শিক্ষক ক্লাস ও বিষয় বরাদ্দ')
@section('page_title', 'শ্রেণি ও বিষয়ভিত্তিক শিক্ষক বণ্টন')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span>দায়িত্ব ও ক্লাস বণ্টন</span>
            </div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষক ক্লাস ও বিষয় বরাদ্দ ম্যাট্রিক্স</h1>
            <p class="text-xs text-slate-500 mt-1">কোন শিক্ষক কোন শ্রেণির কোন বিষয় পড়াবেন তা নির্ধারণ করুন</p>
        </div>

        <a href="{{ route('admin.school.staff.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            শিক্ষক তালিকায় ফিরুন
        </a>
    </div>

    <!-- Allocation Form -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <h2 class="text-sm font-bold text-slate-800 mb-4">নতুন ক্লাস ও বিষয় বরাদ্দ করুন</h2>
        <form action="{{ route('admin.school.staff.assignments.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শিক্ষক নির্বাচন করুন *</label>
                <select name="staff_profile_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">শিক্ষক বেছে নিন</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->designation->name ?? '' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শ্রেণি *</label>
                <select name="academic_class_id" required id="classSelect" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">শ্রেণি বেছে নিন</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">বিষয় *</label>
                <select name="subject_id" required id="subjectSelect" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">বিষয় বেছে নিন</option>
                    @foreach($classes as $c)
                        @foreach($c->subjects as $sub)
                            <option value="{{ $sub->id }}" data-class="{{ $c->id }}">{{ $c->name }} - {{ $sub->name }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-amber-600 text-white font-semibold text-xs hover:bg-amber-700 transition-colors shadow-xs">
                    বরাদ্দ নিশ্চিত করুন
                </button>
            </div>
        </form>
    </div>

    <!-- Current Assignments Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h2 class="text-xs font-bold text-slate-700 uppercase">বর্তমানে বরাদ্দকৃত শ্রেণি ও বিষয়সমূহ</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">শিক্ষকের নাম</th>
                        <th class="px-4 py-3">পদবি</th>
                        <th class="px-4 py-3">শ্রেণি</th>
                        <th class="px-4 py-3">শাখা</th>
                        <th class="px-4 py-3">বিষয়</th>
                        <th class="px-4 py-3 text-right">পদক্ষেপ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($assignments as $asgn)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-4 py-3.5 font-bold text-slate-900">{{ $asgn->staffProfile->name ?? '' }}</td>
                            <td class="px-4 py-3.5 text-slate-600">{{ $asgn->staffProfile->designation->name ?? '' }}</td>
                            <td class="px-4 py-3.5 font-medium text-slate-800">{{ $asgn->academicClass->name ?? '' }}</td>
                            <td class="px-4 py-3.5">{{ $asgn->section->name ?? 'সকল শাখা' }}</td>
                            <td class="px-4 py-3.5 font-semibold text-amber-700">{{ $asgn->subject->name ?? '' }}</td>
                            <td class="px-4 py-3.5 text-right">
                                <form action="{{ route('admin.school.staff.assignments.destroy', $asgn) }}" method="POST" class="inline-block" onsubmit="return confirm('বরাদ্দ বাতিল করতে চান?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50" title="বাতিল করুন">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-400">এখনো কোনো ক্লাস বরাদ্দ দেওয়া হয়নি</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
