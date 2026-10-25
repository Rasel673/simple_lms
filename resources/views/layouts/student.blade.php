<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'শিক্ষার্থী পোর্টাল') - {{ config('app.name', 'সহজ এলএমএস') }}</title>
    <!-- Google Fonts: Hind Siliguri (Bangla) & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Hind Siliguri', 'Inter', system-ui, -apple-system, sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body x-data="{ mobileSidebarOpen: false }" class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="mobileSidebarOpen"
         x-cloak
         @click="mobileSidebarOpen = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-950/70 backdrop-blur-xs md:hidden"></div>

    <!-- Student Sidebar -->
    <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
           class="fixed md:static inset-y-0 left-0 z-50 w-72 md:w-64 bg-slate-900 text-slate-200 shrink-0 flex flex-col min-h-screen border-r border-slate-800 shadow-2xl md:shadow-none transition-transform duration-300 ease-in-out">
        <!-- Sidebar Brand -->
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white shadow-md shadow-indigo-900 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-sm text-white tracking-tight line-clamp-1">{{ \App\Models\WebsiteSetting::get('site_name', 'সহজ এলএমএস') }}</span>
                    <span class="text-[11px] text-indigo-400 font-semibold tracking-wide">শিক্ষার্থী পোর্টাল</span>
                </div>
            </a>
            <!-- Mobile Close Button -->
            <button @click="mobileSidebarOpen = false" type="button" class="md:hidden text-slate-400 hover:text-white p-1.5 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Student Info Snippet -->
        <div class="px-5 py-4 border-b border-slate-800 bg-slate-900/50 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-3.5 py-4 flex flex-col gap-1 text-xs font-medium overflow-y-auto">
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.dashboard') ? 'bg-indigo-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>ড্যাশবোর্ড ওভারভিউ</span>
            </a>

            <a href="{{ route('student.my-courses') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.my-courses') || request()->routeIs('student.learning') ? 'bg-indigo-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span>আমার কোর্সসমূহ</span>
            </a>

            <a href="{{ route('student.certificates.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.certificates.*') ? 'bg-indigo-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z"></path></svg>
                <span>সার্টিফিকেটসমূহ</span>
            </a>

            <a href="{{ route('student.payments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('student.payments.*') ? 'bg-indigo-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <span>পেমেন্ট ইতিহাস</span>
            </a>

            <div class="pt-4 pb-1">
                <p class="px-3 text-[10px] uppercase font-bold text-slate-500 tracking-wider">এক্সপ্লোর</p>
            </div>

            <a href="{{ route('courses.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-all">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <span>নতুন কোর্স দেখুন</span>
            </a>

            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-all">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>মূল ওয়েবসাইটে ফিরুন</span>
            </a>
        </nav>

        <!-- Logout Area -->
        <div class="p-3.5 border-t border-slate-800 bg-slate-950/60">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold text-rose-400 hover:bg-rose-950/40 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span>লগআউট</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Body -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Navbar -->
        <header class="h-16 bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-xs sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <!-- Mobile Toggle Button -->
                <button @click="mobileSidebarOpen = true" type="button" class="md:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                    <span class="text-slate-400 hidden sm:inline">শিক্ষার্থী কর্নার</span>
                    <span class="text-slate-300 hidden sm:inline">/</span>
                    <span class="text-slate-900 font-bold text-sm">@yield('page_title', 'ড্যাশবোর্ড')</span>
                </div>
            </div>

            <div class="flex items-center gap-3 text-xs">
                <a href="{{ route('certificate.verify') }}" class="text-indigo-600 hover:underline font-semibold hidden sm:inline">সার্টিফিকেট যাচাই</a>
                <span class="text-slate-200 hidden sm:inline">|</span>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    <span>হোম পেজ</span>
                </a>
            </div>
        </header>

        <!-- Flash alerts -->
        <div class="px-4 sm:px-6 pt-4 pb-0">
            @if(session('success'))
                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-xs mb-4">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center justify-between shadow-xs mb-4">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif
        </div>

        <main class="p-4 sm:p-6 grow">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
