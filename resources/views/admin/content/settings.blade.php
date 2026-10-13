@extends('layouts.admin')

@section('title', 'Website Content Settings')
@section('page_title', 'Website Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Website Content & Settings</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage branding, About Us section, contact channels, SEO metadata, and legal terms.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.content.settings.update') }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-xs space-y-8">
        @csrf

        <!-- General Branding -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Branding & Identity</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Platform Brand Name</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>
            </div>
        </div>

        <!-- Contact Channels -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Contact Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Support Email</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Hotline Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Address / Campus</label>
                    <input type="text" name="contact_address" value="{{ old('contact_address', $settings['contact_address']) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
                </div>
            </div>
        </div>

        <!-- About Us Section -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Homepage About Us Content</h3>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">About Section Heading</label>
                <input type="text" name="about_heading" value="{{ old('about_heading', $settings['about_heading']) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">About Section Body</label>
                <textarea name="about_content" rows="4" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('about_content', $settings['about_content']) }}</textarea>
            </div>
        </div>

        <!-- SEO Metadata -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Global SEO Defaults</h3>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Default Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $settings['meta_title']) }}" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Default Meta Description</label>
                <textarea name="meta_description" rows="2" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('meta_description', $settings['meta_description']) }}</textarea>
            </div>
        </div>

        <!-- Legal Content -->
        <div class="space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Privacy Policy & Terms</h3>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Privacy Policy Content</label>
                <textarea name="privacy_policy" rows="5" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('privacy_policy', $settings['privacy_policy']) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Terms and Conditions Content</label>
                <textarea name="terms_conditions" rows="5" class="w-full text-xs rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 py-2.5 px-3">{{ old('terms_conditions', $settings['terms_conditions']) }}</textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-md shadow-indigo-200 transition-all">
                Save Website Settings
            </button>
        </div>
    </form>
</div>
@endsection
