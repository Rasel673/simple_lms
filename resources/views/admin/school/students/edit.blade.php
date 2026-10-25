@extends('layouts.admin')

@section('title', 'শিক্ষার্থীর তথ্য সম্পাদনা')
@section('page_title', 'শিক্ষার্থী তথ্য আপডেট')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষার্থীর তথ্য সম্পাদনা (Edit Profile)</h1>
            <p class="text-xs text-slate-500 mt-1">{{ $student->name }} - {{ $student->admission_number }}</p>
        </div>
        <a href="{{ route('admin.school.students.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            তালিকায় ফিরে যান
        </a>
    </div>

    <form action="{{ route('admin.school.students.update', $student) }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-slate-100 pb-3">
            <h2 class="text-sm font-bold text-slate-800">অ্যাকাডেমিক তথ্য</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ভর্তি নম্বর (Admission No.) *</label>
                <input type="text" name="admission_number" value="{{ old('admission_number', $student->admission_number) }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শ্রেণি (Class) *</label>
                <select name="academic_class_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ old('academic_class_id', $student->academic_class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">রোল নম্বর (Roll No.) *</label>
                <input type="number" name="roll_number" value="{{ old('roll_number', $student->roll_number) }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শাখা (Section)</label>
                <select name="section_id" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">শাখা নেই</option>
                    @foreach($sections as $s)
                        <option value="{{ $s->id }}" {{ old('section_id', $student->section_id) == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">গ্রুপ (Group) *</label>
                <select name="group" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="General" {{ old('group', $student->group) == 'General' ? 'selected' : '' }}>সাধারণ (General)</option>
                    <option value="Science" {{ old('group', $student->group) == 'Science' ? 'selected' : '' }}>বিজ্ঞান (Science)</option>
                    <option value="Commerce" {{ old('group', $student->group) == 'Commerce' ? 'selected' : '' }}>ব্যবসায় শিক্ষা (Commerce)</option>
                    <option value="Humanities" {{ old('group', $student->group) == 'Humanities' ? 'selected' : '' }}>মানবিক (Humanities)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">স্ট্যাটাস (Status) *</label>
                <select name="status" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="active" {{ old('status', $student->status) == 'active' ? 'selected' : '' }}>নিয়মিত (Active)</option>
                    <option value="promoted" {{ old('status', $student->status) == 'promoted' ? 'selected' : '' }}>প্রমোটেড (Promoted)</option>
                    <option value="transferred" {{ old('status', $student->status) == 'transferred' ? 'selected' : '' }}>ছাড়পত্রপ্রাপ্ত (Transferred)</option>
                </select>
            </div>
        </div>

        <div class="border-b border-slate-100 pb-3 pt-2">
            <h2 class="text-sm font-bold text-slate-800">ব্যক্তিগত ও অভিভাবকের তথ্য</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">শিক্ষার্থীর পূর্ণ নাম *</label>
                <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">লিঙ্গ (Gender) *</label>
                <select name="gender" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>ছাত্র (Male)</option>
                    <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>ছাত্রী (Female)</option>
                    <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>অন্যান্য (Other)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">জন্ম তারিখ</label>
                <input type="date" name="dob" value="{{ old('dob', $student->dob) }}" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">রক্তের গ্রুপ</label>
                <select name="blood_group" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">নির্বাচন করুন</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                        <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">অভিভাবকের নাম</label>
                <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">অভিভাবকের মোবাইল</label>
                <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শিক্ষাবর্ষ</label>
                <input type="number" name="session_year" value="{{ old('session_year', $student->session_year) }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-semibold text-slate-700 mb-1">বর্তমান ঠিকানা</label>
                <input type="text" name="address" value="{{ old('address', $student->address) }}" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.school.students.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50">বাতিল</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-xs">
                তথ্য আপডেট করুন
            </button>
        </div>
    </form>
</div>
@endsection
