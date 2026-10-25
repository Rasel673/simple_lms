@extends('layouts.app')

@section('title', 'লগইন - ' . config('app.name', 'সহজ এলএমএস'))

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xl space-y-6">
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">আপনার অ্যাকাউন্টে লগইন করুন</h1>
                <p class="text-xs text-slate-500 mt-1">আপনার কোর্স, পরীক্ষা এবং সার্টিফিকেট দেখতে প্রবেশ করুন।</p>
            </div>

            <!-- Demo helper card -->
            <div x-data="{
                fill(email) {
                    $refs.emailInput.value = email;
                    $refs.passwordInput.value = 'password';
                }
            }" class="p-3.5 bg-indigo-50/70 rounded-2xl border border-indigo-100 text-xs">
                <span class="font-bold text-indigo-900 block mb-2 text-[11px] uppercase tracking-wider">টেস্টিং ডেমো একাউন্ট:</span>
                <div class="flex gap-2">
                    <button type="button" @click="fill('admin@lms.test')" class="px-3 py-1.5 bg-white border border-indigo-200 rounded-xl text-indigo-700 font-semibold hover:bg-indigo-50 transition-colors text-xs">
                        এডমিন একাউন্ট
                    </button>
                    <button type="button" @click="fill('student@lms.test')" class="px-3 py-1.5 bg-white border border-indigo-200 rounded-xl text-indigo-700 font-semibold hover:bg-indigo-50 transition-colors text-xs">
                        শিক্ষার্থী একাউন্ট
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">ইমেইল অ্যাড্রেস *</label>
                    <input type="email" name="email" x-ref="emailInput" value="{{ old('email') }}" required autofocus placeholder="name@example.com" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('email') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-slate-700">পাসওয়ার্ড *</label>
                    </div>
                    <input type="password" name="password" x-ref="passwordInput" required placeholder="••••••••" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('password') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="text-slate-600 font-medium">আমাকে মনে রাখুন</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-200 transition-all">
                    লগইন করুন
                </button>
            </form>

            <div class="text-center border-t border-slate-100 pt-4 text-xs text-slate-500">
                এখনও কোনো একাউন্ট নেই?
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:underline ml-1">নতুন একাউন্ট খুলুন</a>
            </div>
        </div>
    </div>
</div>
@endsection
