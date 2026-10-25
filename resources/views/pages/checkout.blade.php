@extends('layouts.app')

@section('title', 'ভর্তি ও পেমেন্ট - ' . $course->title . ' - ' . config('app.name', 'সহজ এলএমএস'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-8">
        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">নিরাপদ ভর্তি প্রক্রিয়া</span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">কোর্সে ভর্তি সম্পন্ন করুন</h1>
        <p class="text-xs text-slate-500 mt-1">আপনার তথ্য যাচাই করুন এবং নিরাপদ পেমেন্টের মাধ্যমে কোর্সে যোগ দিন।</p>
    </div>

    <form method="POST" action="{{ route('checkout.process') }}" class="grid grid-cols-1 md:grid-cols-12 gap-8">
        @csrf
        <input type="hidden" name="course_id" value="{{ $course->id }}">

        <!-- Billing Info Col -->
        <div class="md:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">শিক্ষার্থী ও বিলিং তথ্য</h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">শিক্ষার্থীর পূর্ণ নাম *</label>
                    <input type="text" name="billing_name" value="{{ old('billing_name', $user->name) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('billing_name') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">ইমেইল অ্যাড্রেস *</label>
                    <input type="email" name="billing_email" value="{{ old('billing_email', $user->email) }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                    @error('billing_email') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">মোবাইল নম্বর *</label>
                    <input type="text" name="billing_phone" value="{{ old('billing_phone', $user->phone ?: '01700000000') }}" required class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3" placeholder="017XXXXXXXX">
                    @error('billing_phone') <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">বিশেষ নোট (ঐচ্ছিক)</label>
                    <textarea name="notes" rows="2" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3" placeholder="কোনো তথ্য বা জিজ্ঞাসা থাকলে লিখতে পারেন">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Payment Gateway Selector -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">পেমেন্ট মাধ্যম বেছে নিন</h3>

                <div class="space-y-3">
                    <!-- SSLCommerz -->
                    <label class="flex items-start gap-3 p-4 rounded-xl border border-indigo-200 bg-indigo-50/40 cursor-pointer hover:border-indigo-400 transition-colors">
                        <input type="radio" name="payment_method" value="sslcommerz" checked class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900">SSLCommerz (বিকাশ, নগদ, রকেট, ভিসা/মাস্টারকার্ড ও নেট ব্যাংকিং)</span>
                                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-sm">অফিসিয়াল</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">
                                মোবাইল ওয়ালেট (বিকাশ, নগদ, রকেট) বা ভিসা/মাস্টারকার্ড ও ইন্টারনেট ব্যাংকিং দিয়ে নিরাপদে পে করুন।
                            </p>
                        </div>
                    </label>

                    <!-- Test Gateway simulator -->
                    <label class="flex items-start gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer hover:border-slate-300 transition-colors">
                        <input type="radio" name="payment_method" value="test_gateway" class="mt-0.5 text-indigo-600 focus:ring-indigo-500">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800">তাৎক্ষণিক টেস্ট পেমেন্ট (ডেমো / স্যান্ডবক্স মোড)</span>
                                <span class="text-[10px] font-bold text-slate-700 bg-slate-200 px-2 py-0.5 rounded-sm">ডেমো</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1">
                                কোনো রিয়েল কার্ড বা ব্যালেন্স ছাড়াই তাত্ক্ষণিকভাবে কোর্সে ভর্তি পরীক্ষা করার জন্য।
                            </p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Order Summary Col -->
        <div class="md:col-span-5 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-md space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">অর্ডার বিবরণী</h3>

                <div class="flex gap-3">
                    <div class="w-16 h-14 rounded-lg bg-slate-900 overflow-hidden shrink-0">
                        @if($course->thumbnail)
                            <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-indigo-900 flex items-center justify-center text-white text-[10px] font-bold">LMS</div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-slate-900 line-clamp-2">{{ $course->title }}</h4>
                        <span class="text-[11px] text-indigo-600 font-medium">{{ $course->category->name }}</span>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-3 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>কোর্স মূল্য</span>
                        <span>৳{{ number_format($course->price, 2) }}</span>
                    </div>

                    @if($course->has_discount)
                        <div class="flex justify-between text-emerald-600 font-medium">
                            <span>বিশেষ ছাড়</span>
                            <span>-৳{{ number_format($course->price - $course->effective_price, 2) }}</span>
                        </div>
                    @endif

                    <div class="border-t border-slate-200 pt-2 flex justify-between text-sm font-extrabold text-slate-900">
                        <span>সর্বমোট প্রদেয়</span>
                        <span class="text-indigo-600">৳{{ number_format($course->effective_price, 2) }}</span>
                    </div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                    পেমেন্ট সম্পন্ন করুন
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>

                <div class="text-[11px] text-slate-400 space-y-1 text-center pt-2">
                    <p class="flex items-center justify-center gap-1 font-medium">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        পেমেন্ট সম্পন্ন হলে সাথে সাথেই কোর্স চালু হবে
                    </p>
                    <p>SSLCommerz এর মাধ্যমে সুরক্ষিত লেনদেন</p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
