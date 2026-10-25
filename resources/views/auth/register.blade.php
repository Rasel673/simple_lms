@extends('layouts.app')

@section('title', 'রেজিস্ট্রেশন - ' . config('app.name', 'সহজ এলএমএস'))

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xl space-y-6">
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">নতুন একাউন্ট তৈরি করুন</h1>
                <p class="text-xs text-slate-500 mt-1">অনলাইন কোর্সে ভর্তি হতে ও সার্টিফিকেট অর্জন করতে রেজিস্ট্রেশন করুন।</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">আপনার পূর্ণ নাম *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="যেমন: মো: আরিফুল ইসলাম" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('name') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">ইমেইল অ্যাড্রেস *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('email') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">মোবাইল নম্বর (ঐচ্ছিক)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="017XXXXXXXX" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('phone') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">পাসওয়ার্ড *</label>
                    <input type="password" name="password" required placeholder="কমপক্ষে ৮ অক্ষরের পাসওয়ার্ড" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('password') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">পাসওয়ার্ড নিশ্চিত করুন *</label>
                    <input type="password" name="password_confirmation" required placeholder="পাসওয়ার্ড পুনরায় লিখুন" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-200 transition-all">
                    রেজিস্ট্রেশন সম্পন্ন করুন
                </button>
            </form>

            <div class="text-center border-t border-slate-100 pt-4 text-xs text-slate-500">
                ইতোমধ্যে একটি একাউন্ট আছে?
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline ml-1">লগইন করুন</a>
            </div>
        </div>
    </div>
</div>
@endsection
