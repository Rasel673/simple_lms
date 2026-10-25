@extends('layouts.admin')

@section('title', 'শিক্ষক/স্টাফ তথ্য সম্পাদনা')
@section('page_title', 'শিক্ষক/স্টাফ সম্পাদনা')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষক ও কর্মকর্তা সম্পাদনা</h1>
            <p class="text-xs text-slate-500 mt-1">{{ $staff->name }} ({{ $staff->employee_id }})</p>
        </div>
        <a href="{{ route('admin.school.staff.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            তালিকায় ফিরুন
        </a>
    </div>

    <form action="{{ route('admin.school.staff.update', $staff) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">এমপ্লয়ি আইডি *</label>
                <input type="text" name="employee_id" value="{{ old('employee_id', $staff->employee_id) }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">পদবি (Designation) *</label>
                <select name="designation_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    @foreach($designations as $d)
                        <option value="{{ $d->id }}" {{ old('designation_id', $staff->designation_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">পূর্ণ নাম *</label>
                <input type="text" name="name" value="{{ old('name', $staff->name) }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">মোবাইল নম্বর</label>
                <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ইমেইল ঠিকানা</label>
                <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শিক্ষাগত যোগ্যতা</label>
                <input type="text" name="qualification" value="{{ old('qualification', $staff->qualification) }}" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">যোগদানের তারিখ</label>
                <input type="date" name="joining_date" value="{{ old('joining_date', $staff->joining_date) }}" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">মাসিক মূল বেতন (৳) *</label>
                <input type="number" step="0.01" name="monthly_salary" value="{{ old('monthly_salary', $staff->monthly_salary) }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">স্ট্যাটাস</label>
                <select name="status" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="active" {{ old('status', $staff->status) == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                    <option value="inactive" {{ old('status', $staff->status) == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.school.staff.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50">বাতিল</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-xs">
                আপডেট করুন
            </button>
        </div>
    </form>
</div>
@endsection
