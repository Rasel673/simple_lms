@extends('layouts.admin')

@section('title', 'নতুন শিক্ষার্থী ভর্তি ফরম')
@section('page_title', 'নতুন শিক্ষার্থী ভর্তি')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">শিক্ষার্থী ভর্তি ফরম (Admission Form)</h1>
            <p class="text-xs text-slate-500 mt-1">শিক্ষার্থীর প্রয়োজনীয় ব্যক্তিগত ও অ্যাকাডেমিক তথ্য পূরণ করুন</p>
        </div>
        <a href="{{ route('admin.school.students.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
            তালিকায় ফিরে যান
        </a>
    </div>

    <form action="{{ route('admin.school.students.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-6">
        @csrf

        <div class="border-b border-slate-100 pb-3">
            <h2 class="text-sm font-bold text-slate-800">অ্যাকাডেমিক তথ্য</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ভর্তি নম্বর (Admission No.) *</label>
                <input type="text" name="admission_number" value="{{ old('admission_number', 'ADM-2026-' . rand(100, 999)) }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                @error('admission_number') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শ্রেণি (Class) *</label>
                <select name="academic_class_id" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">শ্রেণি নির্বাচন করুন</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ old('academic_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('academic_class_id') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">রোল নম্বর (Roll No.) *</label>
                <input type="number" name="roll_number" value="{{ old('roll_number') }}" required placeholder="e.g. 1" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                @error('roll_number') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">গ্রুপ (Group) *</label>
                <select name="group" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="General" {{ old('group') == 'General' ? 'selected' : '' }}>সাধারণ (General)</option>
                    <option value="Science" {{ old('group') == 'Science' ? 'selected' : '' }}>বিজ্ঞান (Science)</option>
                    <option value="Commerce" {{ old('group') == 'Commerce' ? 'selected' : '' }}>ব্যবসায় শিক্ষা (Commerce)</option>
                    <option value="Humanities" {{ old('group') == 'Humanities' ? 'selected' : '' }}>মানবিক (Humanities)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">শিক্ষাবর্ষ (Session Year) *</label>
                <input type="number" name="session_year" value="{{ old('session_year', 2026) }}" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>
        </div>

        <div class="border-b border-slate-100 pb-3 pt-2">
            <h2 class="text-sm font-bold text-slate-800">ব্যক্তিগত ও অভিভাবকের তথ্য</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">শিক্ষার্থীর পূর্ণ নাম *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="শিক্ষার্থীর নাম লিখুন" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                @error('name') <span class="text-[10px] text-rose-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">লিঙ্গ (Gender) *</label>
                <select name="gender" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ছাত্র (Male)</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>ছাত্রী (Female)</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>অন্যান্য (Other)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">জন্ম তারিখ</label>
                <input type="date" name="dob" value="{{ old('dob') }}" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">রক্তের গ্রুপ</label>
                <select name="blood_group" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
                    <option value="">নির্বাচন করুন</option>
                    <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                    <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                    <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">অভিভাবকের নাম</label>
                <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" placeholder="পিতা/মাতার নাম" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">অভিভাবকের মোবাইল নম্বর</label>
                <input type="text" name="guardian_phone" value="{{ old('guardian_phone') }}" placeholder="+880 17..." class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">বর্তমান ঠিকানা</label>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="গ্রাম/রাস্তা, পোস্ট অফিস, থানা, জেলা" class="w-full text-xs rounded-xl border-slate-200 py-2.5 px-3 focus:border-amber-500 focus:ring-amber-500">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.school.students.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50">বাতিল</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 text-white text-xs font-semibold hover:bg-amber-700 transition-colors shadow-xs">
                ভর্তি সম্পন্ন করুন
            </button>
        </div>
    </form>
</div>
@endsection
