<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'LearnHub LMS'))</title>
    <meta name="description" content="@yield('meta_description', 'Discover modern video courses, pass MCQ assessments, and earn verified certificates.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta property="og:title" content="@yield('title', config('app.name', 'LearnHub LMS'))">
    <meta property="og:description" content="@yield('meta_description', 'Discover modern video courses, pass MCQ assessments, and earn verified certificates.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Header & Navigation -->
    <header x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-700 to-violet-600 flex items-center justify-center text-white shadow-md shadow-indigo-200 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold tracking-tight text-slate-900 group-hover:text-indigo-600 transition-colors">
                                {{ \App\Models\WebsiteSetting::get('site_name', 'LearnHub') }}
                            </span>
                            <span class="text-[10px] uppercase tracking-widest text-indigo-600 font-semibold -mt-1">Online Academy</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-8 text-sm font-medium text-slate-600">
                    <a href="{{ route('home') }}#hero-section" class="hover:text-indigo-600 transition-colors">Home</a>
                    <a href="{{ route('home') }}#courses-section" class="hover:text-indigo-600 transition-colors">Courses</a>
                    <a href="{{ route('home') }}#verify-section" class="hover:text-indigo-600 transition-colors">Verify Certificate</a>
                    <a href="{{ route('home') }}#about-section" class="hover:text-indigo-600 transition-colors">About Us</a>
                    <a href="{{ route('home') }}#contact-section" class="hover:text-indigo-600 transition-colors">Contact Us</a>
                </nav>

                <!-- Auth Buttons / User Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <div class="relative" @click.away="userDropdownOpen = false">
                            <button @click="userDropdownOpen = !userDropdownOpen" type="button" class="flex items-center gap-2.5 p-1.5 pr-3 rounded-full hover:bg-slate-100 transition-colors border border-slate-200">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-semibold text-xs flex items-center justify-center">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="text-xs font-semibold text-slate-700 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="userDropdownOpen" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 text-xs text-slate-700 z-50">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                    <span class="inline-block mt-1 px-1.5 py-0.5 text-[10px] font-medium rounded-sm {{ auth()->user()->isAdmin() ? 'bg-amber-100 text-amber-800' : 'bg-indigo-100 text-indigo-800' }}">
                                        {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                </div>

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-slate-700 font-medium">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                        Admin Dashboard
                                    </a>
                                @endif

                                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-slate-700 font-medium">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z"></path></svg>
                                    Student Dashboard
                                </a>
                                <a href="{{ route('student.my-courses') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-slate-700 font-medium">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    My Enrolled Courses
                                </a>
                                <a href="{{ route('student.certificates.index') }}" class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-slate-700 font-medium">
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                    My Certificates
                                </a>

                                <div class="border-t border-slate-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 hover:bg-rose-50 text-rose-600 font-medium">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors px-3 py-2">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-all">
                            Get Started
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-6 space-y-3">
            <a href="{{ route('home') }}#hero-section" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-slate-700 rounded-lg hover:bg-slate-50">Home</a>
            <a href="{{ route('home') }}#courses-section" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-slate-700 rounded-lg hover:bg-slate-50">Courses</a>
            <a href="{{ route('home') }}#verify-section" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-slate-700 rounded-lg hover:bg-slate-50">Verify Certificate</a>
            <a href="{{ route('home') }}#about-section" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-slate-700 rounded-lg hover:bg-slate-50">About Us</a>
            <a href="{{ route('home') }}#contact-section" @click="mobileMenuOpen = false" class="block px-3 py-2 text-base font-medium text-slate-700 rounded-lg hover:bg-slate-50">Contact Us</a>

            <div class="border-t border-slate-100 pt-3 space-y-2">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-base font-medium text-amber-700 rounded-lg bg-amber-50">Admin Dashboard</a>
                    @endif
                    <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 text-base font-medium text-indigo-700 rounded-lg bg-indigo-50">Student Dashboard</a>
                    <a href="{{ route('student.my-courses') }}" class="block px-3 py-2 text-base font-medium text-slate-700 rounded-lg hover:bg-slate-50">My Courses</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-base font-medium text-rose-600 rounded-lg hover:bg-rose-50">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2.5 text-base font-semibold text-slate-700 border border-slate-200 rounded-lg">Log in</a>
                    <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2.5 text-base font-semibold text-white bg-indigo-600 rounded-lg shadow-sm">Get Started</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Toast / Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('info') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <!-- Col 1: About & Logo -->
                <div class="md:col-span-1 space-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">{{ \App\Models\WebsiteSetting::get('site_name', 'LearnHub LMS') }}</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        {{ \App\Models\WebsiteSetting::get('site_tagline', 'Learn practical high-demand tech skills, pass MCQ exams, and earn certified credentials.') }}
                    </p>
                    <div class="pt-2 text-xs text-slate-400">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-emerald-400 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            SSLCommerz Secured Payments
                        </span>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Explore</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="{{ route('home') }}#hero-section" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('home') }}#courses-section" class="hover:text-white transition-colors">All Courses</a></li>
                        <li><a href="{{ route('home') }}#verify-section" class="hover:text-white transition-colors">Verify Certificate</a></li>
                        <li><a href="{{ route('home') }}#about-section" class="hover:text-white transition-colors">About Academy</a></li>
                        <li><a href="{{ route('home') }}#contact-section" class="hover:text-white transition-colors">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Col 3: Student Area & Policies -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Student & Legal</h4>
                    <ul class="space-y-2.5 text-xs">
                        @auth
                            <li><a href="{{ route('student.dashboard') }}" class="hover:text-white transition-colors">Student Dashboard</a></li>
                            <li><a href="{{ route('student.my-courses') }}" class="hover:text-white transition-colors">My Courses</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Student Login</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Register Account</a></li>
                        @endauth
                        <li><a href="{{ route('legal.privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="{{ route('legal.terms') }}" class="hover:text-white transition-colors">Terms & Conditions</a></li>
                        <li><a href="{{ route('sitemap') }}" class="hover:text-white transition-colors">XML Sitemap</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact Info -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Contact</h4>
                    <ul class="space-y-3 text-xs text-slate-400">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>{{ \App\Models\WebsiteSetting::get('contact_address', 'Dhanmondi, Dhaka, Bangladesh') }}</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>{{ \App\Models\WebsiteSetting::get('contact_email', 'support@learnhub.com') }}</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>{{ \App\Models\WebsiteSetting::get('contact_phone', '+880 1700 000000') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ \App\Models\WebsiteSetting::get('site_name', 'LearnHub LMS') }}. All rights reserved.</p>
                <p class="mt-2 sm:mt-0 text-[11px]">Powered by Laravel Clean Service Architecture & Tailwind CSS.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
