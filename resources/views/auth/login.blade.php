@extends('layouts.app')

@section('title', 'Log In - ' . config('app.name', 'LearnHub LMS'))

@section('content')
<div class="min-h-[75vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xl space-y-6">
            <div class="text-center">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Sign In to Your Account</h1>
                <p class="text-xs text-slate-500 mt-1">Access your purchased courses, tests, and certificates.</p>
            </div>

            <!-- Demo helper card -->
            <div x-data="{
                fill(email) {
                    $refs.emailInput.value = email;
                    $refs.passwordInput.value = 'password';
                }
            }" class="p-3 bg-indigo-50/60 rounded-xl border border-indigo-100 text-xs">
                <span class="font-bold text-indigo-900 block mb-1.5 text-[11px] uppercase tracking-wider">Quick Fill Demo Credentials:</span>
                <div class="flex gap-2">
                    <button type="button" @click="fill('admin@lms.test')" class="px-2.5 py-1 bg-white border border-indigo-200 rounded-lg text-indigo-700 font-semibold hover:bg-indigo-50 transition-colors text-[11px]">
                        Admin Login
                    </button>
                    <button type="button" @click="fill('student@lms.test')" class="px-2.5 py-1 bg-white border border-indigo-200 rounded-lg text-indigo-700 font-semibold hover:bg-indigo-50 transition-colors text-[11px]">
                        Student Login
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" x-ref="emailInput" value="{{ old('email') }}" required autofocus class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('email') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-slate-700">Password</label>
                    </div>
                    <input type="password" name="password" x-ref="passwordInput" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('password') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span class="text-slate-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-md shadow-indigo-200 transition-all">
                    Sign In
                </button>
            </form>

            <div class="text-center border-t border-slate-100 pt-4 text-xs text-slate-500">
                Don't have an account yet?
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:underline ml-1">Create Student Account</a>
            </div>
        </div>
    </div>
</div>
@endsection
