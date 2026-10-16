<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sultaf Restaurant — {{ __('Authentic Pakistani & Yemeni Flavors') }}</title>
    <meta name="description" content="{{ __('Experience the rich culinary heritage of Pakistan and Yemen in Condongcatur, Yogyakarta. Savor rich Chicken Karahi, tender Yemeni Fasolya, crispy Samosas, and freshly baked Kunafa.') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/sultaf_logo.jpg') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,500&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .hero-video-container {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        .hero-video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(74, 14, 20, 0.88) 0%, rgba(122, 22, 32, 0.78) 50%, rgba(43, 27, 27, 0.85) 100%);
            z-index: 1;
        }
        .text-glow {
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body class="bg-sultaf-cream text-sultaf-ink font-sans antialiased selection:bg-sultaf-maroon selection:text-white">

    {{-- ===================== NAVBAR ===================== --}}
    <header id="main-header" class="fixed top-0 left-0 right-0 z-40 transition-all duration-300 bg-sultaf-maroon/95 backdrop-blur-md border-b border-sultaf-gold/20 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                {{-- Brand Logo --}}
                <a href="#home" class="flex items-center gap-3 group">
                    <img src="{{ asset('assets/sultaf_logo.jpg') }}" alt="Sultaf Logo" class="w-12 h-12 rounded-xl object-cover border-2 border-sultaf-gold/60 shadow-sm group-hover:scale-105 transition-transform">
                    <div>
                        <div class="font-serif text-xl sm:text-2xl font-bold tracking-wide text-white flex items-center gap-2">
                            <span>SULTAF</span>
                            <span class="text-xs bg-sultaf-gold/30 text-sultaf-gold-light border border-sultaf-gold/40 px-2 py-0.5 rounded-full font-sans font-medium tracking-normal hidden sm:inline-block">مطعم</span>
                        </div>
                        <div class="text-[11px] sm:text-xs text-sultaf-gold-light tracking-wider uppercase font-semibold">{{ __('Pakistani & Yemeni Cuisine') }}</div>
                    </div>
                </a>

                {{-- Desktop Navigation Links --}}
                <nav class="hidden lg:flex items-center space-x-1 xl:space-x-2">
                    <a href="#home" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-colors">{{ __('Home') }}</a>
                    <a href="#about" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-colors">{{ __('About') }}</a>
                    <a href="#menu" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-colors">{{ __('Menu') }}</a>
                    <a href="#gallery" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-colors">{{ __('Gallery') }}</a>
                    <a href="#catering" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-colors">{{ __('Catering') }}</a>
                    <a href="#location" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-colors">{{ __('Location') }}</a>
                    <a href="#contact" class="nav-link px-3 py-2 rounded-lg text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 transition-colors">{{ __('Contact') }}</a>
                </nav>

                {{-- Action CTAs & Language Switcher (Cleaned of header login elements) --}}
                <div class="flex items-center gap-2.5 sm:gap-3">
                    
                    {{-- Language Switcher --}}
                    @include('components.lang-switch')

                    {{-- Cart Button with Live Counter --}}
                    <a href="{{ route('cart.index') }}" title="{{ __('Shopping Cart') }}" class="relative p-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors border border-white/10">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
                            <path d="M1 1h4l2.4 12.4a2 2 0 0 0 2 1.6h8.6a2 2 0 0 0 2-1.6L23 6H6"/>
                        </svg>
                        @if (session('cart') && count(session('cart')))
                            <span class="absolute -top-1 -right-1 bg-sultaf-gold text-sultaf-maroon-dark font-extrabold text-[11px] w-5 h-5 rounded-full flex items-center justify-center shadow-md">
                                {{ array_sum(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    {{-- WhatsApp Quick Order Trigger --}}
                    <button onclick="openWhatsAppModal()" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-sultaf-gold-light hover:text-white border border-sultaf-gold/40 text-xs sm:text-sm font-bold shadow-sm transition-all hover:scale-105">
                        <svg class="w-4 h-4 text-sultaf-gold-light" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/>
                        </svg>
                        <span>WA Order</span>
                    </button>

                    {{-- Mobile Hamburger Toggle --}}
                    <button onclick="toggleMobileMenu()" class="lg:hidden p-2 rounded-xl bg-white/10 text-white hover:bg-white/20 transition-colors" aria-label="Toggle menu">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Drawer Menu --}}
        <div id="mobile-menu" class="lg:hidden hidden bg-sultaf-maroon-dark border-t border-white/10 px-4 pt-3 pb-6 space-y-2">
            <a href="#home" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/10">{{ __('Home') }}</a>
            <a href="#about" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/10">{{ __('About Us') }}</a>
            <a href="#menu" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/10">{{ __('Our Menu') }}</a>
            <a href="#gallery" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/10">{{ __('Gallery') }}</a>
            <a href="#catering" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/10">{{ __('Catering Packages') }}</a>
            <a href="#location" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/10">{{ __('Location & Hours') }}</a>
            <a href="#contact" onclick="toggleMobileMenu()" class="block px-3 py-2 rounded-lg text-base font-medium text-white hover:bg-white/10">{{ __('Contact') }}</a>
            <a href="{{ route('menu.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-base font-bold text-sultaf-gold-light hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>{{ __('Full Online Order Catalog') }}</span>
            </a>
            <div class="pt-3 border-t border-white/10 flex gap-2">
                <button onclick="openWhatsAppModal(); toggleMobileMenu();" class="flex-1 py-3 rounded-xl bg-sultaf-gold hover:bg-sultaf-gold-dark text-sultaf-maroon-dark font-extrabold text-sm text-center shadow-md transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/></svg>
                    <span>{{ __('Order via WhatsApp') }}</span>
                </button>
            </div>
        </div>
    </header>

    {{-- ===================== HERO SECTION WITH VIDEO ===================== --}}
    <section id="home" class="relative min-h-[92vh] sm:min-h-screen flex items-center justify-center pt-24 pb-16 px-4 overflow-hidden">
        
        {{-- Background Video Container --}}
        <div class="hero-video-container">
            <video id="heroBgVideo" autoplay loop muted playsinline preload="none" poster="{{ asset('assets/hero_feast.jpg') }}" class="w-full h-full object-cover">
                <source data-src="{{ asset('assets/sultaf_hero_video.mp4') }}" type="video/mp4">
            </video>
            <div class="hero-overlay"></div>
        </div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-4xl mx-auto text-center text-white pt-6">
            
            {{-- Badge Group --}}
            <div class="inline-flex items-center gap-3 bg-black/40 backdrop-blur-md border border-white/20 px-4 py-1.5 rounded-full mb-6">
                <span class="flex items-center gap-1.5 text-xs font-bold text-amber-300">
                    <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Google 4.9 (150+ Reviews)
                </span>
                <span class="text-white/40">|</span>
                <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-400">
                    <svg class="w-4 h-4 fill-emerald-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    GoFood 4.5
                </span>
            </div>

            {{-- Main Title --}}
            <h1 class="font-serif text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-glow leading-tight mb-6">
                {{ __('Authentic Pakistani & Yemeni Flavors') }}
            </h1>

            <p class="text-base sm:text-xl text-sultaf-cream-soft max-w-2xl mx-auto leading-relaxed mb-10 text-glow font-medium">
                {{ __('Experience authentic wok-cooked Chicken Karahi, savory Yemeni Fasolya Hamra, fragrant Mandi rice, and freshly baked Kunafa in Condongcatur, Yogyakarta.') }}
            </p>

            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-12">
                <a href="{{ route('menu.index') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-sultaf-gold hover:bg-sultaf-gold-dark text-sultaf-maroon-dark font-extrabold text-base shadow-xl hover:scale-105 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>{{ __('Order Online (Cart & Delivery)') }}</span>
                </a>

                <button onclick="openWhatsAppModal()" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-sultaf-maroon/90 hover:bg-sultaf-maroon text-white border-2 border-sultaf-gold/60 font-extrabold text-base shadow-xl hover:scale-105 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-sultaf-gold-light" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/></svg>
                    <span>{{ __('Order via WhatsApp') }}</span>
                </button>

                <a href="#menu" class="w-full sm:w-auto px-6 py-4 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-white font-bold text-base border border-white/30 transition-all flex items-center justify-center gap-2">
                    <span>{{ __('Explore Menu') }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>

            {{-- Amenities Bar with Clean SVGs --}}
            <div class="inline-flex flex-wrap items-center justify-center gap-4 sm:gap-8 bg-black/40 backdrop-blur-md border border-white/20 px-6 py-3 rounded-2xl text-xs sm:text-sm text-sultaf-cream-soft">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sultaf-gold-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0 z"/></svg>
                    <span>{{ __('Outdoor Seating') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sultaf-gold-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                    <span>{{ __('High-Speed Wi-Fi') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sultaf-gold-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>{{ __('High Chairs Available') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sultaf-gold-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>{{ __('Prayer Area (Musholla)') }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="geometric-divider"></div>

    {{-- ===================== ABOUT SECTION ===================== --}}
    <section id="about" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            {{-- Image Stack --}}
            <div class="lg:col-span-6 relative">
                <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl border-4 border-white">
                    <img src="{{ asset('assets/ambiance.jpg') }}" alt="Sultaf Restaurant Dining Ambiance" class="w-full h-[380px] sm:h-[440px] object-cover hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
                </div>
                <div class="absolute -bottom-8 -right-4 sm:-right-8 z-20 w-3/5 rounded-2xl overflow-hidden shadow-2xl border-4 border-white hidden sm:block">
                    <img src="{{ asset('assets/chicken_karahi.jpg') }}" alt="Authentic Chicken Karahi" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-700" loading="lazy" decoding="async">
                </div>
                <div class="absolute -top-6 -left-6 w-24 h-24 bg-sultaf-gold/20 rounded-full blur-2xl -z-0"></div>
            </div>

            {{-- Text & Pillar Cards --}}
            <div class="lg:col-span-6">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-sultaf-maroon/10 text-sultaf-maroon font-bold text-xs uppercase tracking-wider mb-4">
                    <svg class="w-3.5 h-3.5 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>{{ __('Our Heritage & Story') }}</span>
                </span>
                <h2 class="font-serif text-3xl sm:text-4xl font-extrabold text-sultaf-ink leading-tight mb-6">
                    {{ __('A Passion for Authentic Pakistani & Yemeni Traditions in Yogyakarta') }}
                </h2>
                <p class="text-sultaf-muted text-base sm:text-lg leading-relaxed mb-4">
                    {{ __('Welcome to') }} <strong class="text-sultaf-maroon font-bold">Sultaf Restaurant</strong>, {{ __('your culinary home for authentic Pakistani wok karahi, aromatic basmati rice, and rich Yemeni stews in Sanggrahan, Condongcatur.') }}
                </p>
                <p class="text-sultaf-muted text-base leading-relaxed mb-8">
                    {{ __('Every dish is crafted from scratch using traditional whole spices, uncompromised halal ingredients, and slow-cooking methods perfected over generations.') }}
                </p>

                {{-- 4 Quality Pillars Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:border-sultaf-gold transition-colors">
                        <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                        </div>
                        <h4 class="font-serif font-bold text-sultaf-ink text-base mb-1">{{ __('Authentic Wok Cooking') }}</h4>
                        <p class="text-xs text-sultaf-muted leading-relaxed">{{ __('Karahi curries simmered in traditional copper woks over roaring flames for profound depth.') }}</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:border-sultaf-gold transition-colors">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h4 class="font-serif font-bold text-sultaf-ink text-base mb-1">{{ __('100% Halal Certified') }}</h4>
                        <p class="text-xs text-sultaf-muted leading-relaxed">{{ __('Strictly premium halal chicken, meats, spices, and dairy from certified suppliers.') }}</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:border-sultaf-gold transition-colors">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <h4 class="font-serif font-bold text-sultaf-ink text-base mb-1">{{ __('Cozy Ambiance') }}</h4>
                        <p class="text-xs text-sultaf-muted leading-relaxed">{{ __('Warm lighting, family-friendly tables, tranquil outdoor seating & fast Wi-Fi.') }}</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:border-sultaf-gold transition-colors">
                        <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                        <h4 class="font-serif font-bold text-sultaf-ink text-base mb-1">{{ __('Middle Eastern Sweets') }}</h4>
                        <p class="text-xs text-sultaf-muted leading-relaxed">{{ __('Warm sweet cheese Kunafa, saffron-infused Kheer, and creamy mango Lassi.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="geometric-divider"></div>

    {{-- ===================== MENU SHOWCASE SECTION ===================== --}}
    <section id="menu" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-sultaf-maroon/10 text-sultaf-maroon font-bold text-xs uppercase tracking-wider mb-3">
                <svg class="w-4 h-4 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span>{{ __('Our Culinary Masterpieces') }}</span>
            </span>
            <h2 class="font-serif text-3xl sm:text-5xl font-extrabold text-sultaf-ink mb-4">
                {{ __('Signature Menu Dishes') }}
            </h2>
            <p class="text-sultaf-muted text-base sm:text-lg">
                {{ __('Freshly prepared with whole ground spices, fragrant rice, and passion. Click any dish to add to your order!') }}
            </p>
            
            {{-- Category Filter Pills --}}
            <div class="flex flex-wrap items-center justify-center gap-2 mt-8">
                <button onclick="filterCategory('all')" class="category-btn active px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all bg-sultaf-maroon text-white shadow-sm" data-category="all">
                    {{ __('All Dishes') }}
                </button>
                @foreach ($categories as $cat)
                    <button onclick="filterCategory('{{ $cat->slug }}')" class="category-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all bg-white text-sultaf-ink border border-sultaf-border hover:border-sultaf-maroon shadow-sm" data-category="{{ $cat->slug }}">
                        {{ __($cat->name) }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Dynamic Dishes Grid from Database --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($categories as $cat)
                @foreach ($cat->menus as $menu)
                    <div class="menu-card bg-white rounded-3xl overflow-hidden border border-sultaf-border/80 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group" data-category="{{ $cat->slug }}">
                        
                        {{-- Image & Badges --}}
                        <div class="relative h-52 bg-sultaf-cream-dark overflow-hidden">
                            @if ($menu->foto_makanan)
                                <img src="{{ asset('storage/'.$menu->foto_makanan) }}" alt="{{ $menu->nama_makanan }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-sultaf-gold">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                            @endif

                            {{-- Rating & Halal Tags --}}
                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-md px-2.5 py-1 rounded-full text-xs font-bold text-sultaf-ink flex items-center gap-1 shadow-sm">
                                <svg class="w-3.5 h-3.5 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span>{{ number_format($menu->rating, 1) }}</span>
                            </div>

                            @if ($menu->is_halal)
                                <div class="absolute top-3 right-3 bg-emerald-600/90 text-white backdrop-blur-md px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wide shadow-sm flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>{{ __('Halal') }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-serif text-lg sm:text-xl font-bold text-sultaf-ink mb-1 group-hover:text-sultaf-maroon transition-colors">
                                    {{ __($menu->nama_makanan) }}
                                </h3>
                                <p class="text-xs sm:text-sm text-sultaf-muted line-clamp-2 leading-relaxed mb-4">
                                    {{ __($menu->deskripsi ?? '') }}
                                </p>
                            </div>

                            <div>
                                {{-- Diet Type Badge & Price --}}
                                <div class="flex items-center justify-between pb-4 mb-4 border-b border-sultaf-border/60">
                                    <div>
                                        @if (strtolower($menu->diet_type ?? '') === 'vegan')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                                <span>{{ __('Vegan') }}</span>
                                            </span>
                                        @elseif (strtolower($menu->diet_type ?? '') === 'vegetarian')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="currentColor"/></svg>
                                                <span>{{ __('Vegetarian') }}</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                <svg class="w-3.5 h-3.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                <span>{{ __('Non-Veg') }}</span>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="font-serif text-lg font-extrabold text-sultaf-maroon">
                                        {{ $menu->harga_format }}
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="grid grid-cols-2 gap-2">
                                    <button 
                                        onclick="openCartModal({{ $menu->id }}, '{{ addslashes(__($menu->nama_makanan)) }}', '{{ addslashes(__($menu->deskripsi ?? '')) }}', {{ $menu->harga_makanan }}, '{{ route('cart.add', $menu) }}')"
                                        class="py-2.5 px-3 rounded-xl bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>{{ __('Add to Cart') }}</span>
                                    </button>

                                    <button 
                                        onclick="quickWhatsAppOrder('{{ addslashes(__($menu->nama_makanan)) }}')"
                                        class="py-2.5 px-3 rounded-xl border border-sultaf-border bg-sultaf-cream hover:bg-sultaf-cream-dark hover:border-sultaf-maroon text-sultaf-maroon font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition-all">
                                        <svg class="w-4 h-4 text-sultaf-maroon" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/></svg>
                                        <span>WA</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        {{-- View Full Catalog CTA --}}
        <div class="mt-14 text-center">
            <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-extrabold text-base shadow-xl hover:scale-105 transition-all">
                <span>{{ __('View Full Dedicated Online Ordering Portal') }}</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </section>

    <div class="geometric-divider"></div>

    {{-- ===================== PHOTO GALLERY & LIGHTBOX ===================== --}}
    <section id="gallery" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-sultaf-maroon/10 text-sultaf-maroon font-bold text-xs uppercase tracking-wider mb-3">
                <svg class="w-4 h-4 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __('Visual Experience') }}</span>
            </span>
            <h2 class="font-serif text-3xl sm:text-5xl font-extrabold text-sultaf-ink mb-4">
                {{ __('Photo Gallery') }}
            </h2>
            <p class="text-sultaf-muted text-base sm:text-lg">
                {{ __('Take a glimpse into our freshly prepared Pakistani & Yemeni culinary creations and ambient dining space.') }}
            </p>

            {{-- Gallery Filter Tabs --}}
            <div class="flex items-center justify-center gap-2 mt-8">
                <button onclick="filterGallery('All')" class="gallery-btn active px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-all bg-sultaf-maroon text-white" data-gallery="All">{{ __('All') }}</button>
                <button onclick="filterGallery('Food')" class="gallery-btn px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-all bg-white text-sultaf-ink border border-sultaf-border hover:border-sultaf-maroon" data-gallery="Food">{{ __('Food & Drinks') }}</button>
                <button onclick="filterGallery('Interior')" class="gallery-btn px-5 py-2 rounded-full text-xs sm:text-sm font-bold transition-all bg-white text-sultaf-ink border border-sultaf-border hover:border-sultaf-maroon" data-gallery="Interior">{{ __('Interior & Ambiance') }}</button>
            </div>
        </div>

        {{-- Gallery Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($galleryPhotos as $photo)
                <div class="gallery-item relative h-64 rounded-2xl overflow-hidden cursor-pointer group shadow-sm border border-sultaf-border" data-gallery-type="{{ $photo['category'] }}" onclick="openLightbox('{{ asset($photo['image']) }}', '{{ addslashes(__($photo['title'])) }}', '{{ addslashes(__($photo['category'])) }}')">
                    <img src="{{ asset($photo['image']) }}" alt="{{ $photo['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" decoding="async">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-white">
                        <div class="font-serif font-bold text-sm leading-tight">{{ __($photo['title']) }}</div>
                        <span class="text-[11px] text-sultaf-gold-light">{{ __($photo['category']) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="geometric-divider"></div>

    {{-- ===================== CATERING & EVENTS ===================== --}}
    <section id="catering" class="py-20 px-4 sm:px-6 lg:px-8 bg-sultaf-maroon text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
            
            <div class="lg:col-span-7">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/10 text-sultaf-gold-light font-bold text-xs uppercase tracking-wider mb-4">
                    <svg class="w-4 h-4 text-sultaf-gold-light" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ __('Gatherings & Celebrations') }}</span>
                </span>
                <h2 class="font-serif text-3xl sm:text-5xl font-extrabold leading-tight mb-6">
                    {{ __('Catering Packages & Party Platter Orders') }}
                </h2>
                <p class="text-sultaf-cream-soft text-base sm:text-lg leading-relaxed mb-8">
                    {{ __('Planning a wedding, family feast, birthday party, office luncheon, or community gathering in Yogyakarta? Elevate your event with authentic Pakistani & Yemeni feasts delivered hot and fresh.') }}
                </p>

                <ul class="space-y-3.5 mb-8">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-sultaf-gold text-sultaf-maroon font-extrabold text-xs flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-sm sm:text-base">{{ __('Custom Karahi wok stations & Yemeni Rice Platters tailored to your guest count') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-sultaf-gold text-sultaf-maroon font-extrabold text-xs flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-sm sm:text-base">{{ __('Live tandoori oven station & buffet setups available on demand') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-sultaf-gold text-sultaf-maroon font-extrabold text-xs flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-sm sm:text-base">{{ __('Freshly baked flatbreads, sweet cheese Kunafa, and spiced Karak Chai') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-sultaf-gold text-sultaf-maroon font-extrabold text-xs flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-sm sm:text-base">{{ __('100% Halal certified, premium hygienic packaging & punctual venue setup') }}</span>
                    </li>
                </ul>

                <a href="https://wa.me/{{ $info['whatsappNumber'] }}?text=Halo%20Sultaf%20Restaurant,%20saya%20ingin%20konsultasi%20paket%20catering%20acara..." target="_blank" class="inline-flex items-center gap-2.5 px-8 py-4 rounded-xl bg-sultaf-gold hover:bg-sultaf-gold-dark text-sultaf-maroon-dark font-extrabold text-base shadow-xl hover:scale-105 transition-all">
                    <svg class="w-5 h-5 text-sultaf-maroon-dark" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/></svg>
                    <span>{{ __('Inquire Catering via WhatsApp') }}</span>
                </a>
            </div>

            <div class="lg:col-span-5 bg-sultaf-maroon-dark/80 backdrop-blur-md p-8 rounded-3xl border border-white/10 shadow-2xl">
                <h3 class="font-serif text-2xl font-bold text-sultaf-gold-light mb-6">
                    {{ __('Events We Cater') }}
                </h3>
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                        <div class="w-8 h-8 rounded-lg bg-white/10 text-sultaf-gold-light flex items-center justify-center mb-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div class="font-bold text-sm text-white">{{ __('Family Feasts') }}</div>
                        <div class="text-xs text-white/70">{{ __('10 to 50+ guests') }}</div>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                        <div class="w-8 h-8 rounded-lg bg-white/10 text-sultaf-gold-light flex items-center justify-center mb-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div class="font-bold text-sm text-white">{{ __('Weddings & Arisan') }}</div>
                        <div class="text-xs text-white/70">{{ __('Customized buffet') }}</div>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                        <div class="w-8 h-8 rounded-lg bg-white/10 text-sultaf-gold-light flex items-center justify-center mb-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="font-bold text-sm text-white">{{ __('Office Lunches') }}</div>
                        <div class="text-xs text-white/70">{{ __('Meal box delivery') }}</div>
                    </div>
                    <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                        <div class="w-8 h-8 rounded-lg bg-white/10 text-sultaf-gold-light flex items-center justify-center mb-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                        <div class="font-bold text-sm text-white">{{ __('VIP Gatherings') }}</div>
                        <div class="text-xs text-white/70">{{ __('Curated menu') }}</div>
                    </div>
                </div>
                <p class="text-xs text-sultaf-cream-soft/80 italic text-center">
                    {{ __('* Custom dietary options and portion sizing tailored according to your budget.') }}
                </p>
            </div>
        </div>
    </section>

    <div class="geometric-divider"></div>

    {{-- ===================== TESTIMONIALS SECTION ===================== --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-sultaf-maroon/10 text-sultaf-maroon font-bold text-xs uppercase tracking-wider mb-3">
                <svg class="w-4 h-4 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span>{{ __('Guest Impressions') }}</span>
            </span>
            <h2 class="font-serif text-3xl sm:text-5xl font-extrabold text-sultaf-ink mb-4">
                {{ __('Loved by Food Lovers in Yogyakarta') }}
            </h2>
            <p class="text-sultaf-muted text-base sm:text-lg">
                {{ __('Read real feedback from our verified Google & GoFood diners.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($reviews as $rev)
                <div class="bg-white p-6 rounded-3xl border border-sultaf-border shadow-md flex flex-col justify-between hover:shadow-lg transition-shadow">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex text-amber-400">
                                @for ($i = 0; $i < $rev['rating']; $i++)
                                    <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-xs text-sultaf-muted font-medium">{{ $rev['source'] }}</span>
                        </div>
                        <p class="text-sultaf-ink text-sm leading-relaxed italic mb-6">
                            "{{ $rev['text'] }}"
                        </p>
                    </div>
                    <div class="pt-4 border-t border-sultaf-border/60 flex items-center justify-between">
                        <span class="font-bold text-xs text-sultaf-maroon">{{ $rev['name'] }}</span>
                        <span class="text-[11px] text-sultaf-muted">{{ $rev['date'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="geometric-divider"></div>

    {{-- ===================== LOCATION & OPERATING HOURS ===================== --}}
    <section id="location" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-sultaf-maroon/10 text-sultaf-maroon font-bold text-xs uppercase tracking-wider mb-3">
                <svg class="w-4 h-4 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __('Dine In With Us') }}</span>
            </span>
            <h2 class="font-serif text-3xl sm:text-5xl font-extrabold text-sultaf-ink mb-4">
                {{ __('Location & Operating Hours') }}
            </h2>
            <p class="text-sultaf-muted text-base sm:text-lg">
                {{ __('Conveniently situated in Sanggrahan, Condongcatur, Sleman, Yogyakarta.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            {{-- Info Details Card --}}
            <div class="lg:col-span-5 bg-white p-8 rounded-3xl border border-sultaf-border shadow-md flex flex-col justify-between">
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="p-3 rounded-2xl bg-sultaf-maroon/10 text-sultaf-maroon shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-serif font-bold text-sultaf-ink text-base mb-1">{{ __('Restaurant Address') }}</h4>
                            <p class="text-xs sm:text-sm text-sultaf-muted leading-relaxed">{{ $info['address'] }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="p-3 rounded-2xl bg-sultaf-maroon/10 text-sultaf-maroon shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="w-full">
                            <h4 class="font-serif font-bold text-sultaf-ink text-base mb-1">{{ __('Opening Hours') }}</h4>
                            <div class="flex justify-between text-xs sm:text-sm text-sultaf-muted py-1 border-b border-sultaf-border/60">
                                <span class="font-semibold">{{ __('Monday – Sunday') }}</span>
                                <span class="font-bold text-sultaf-maroon">{{ $info['hours'] }}</span>
                            </div>
                            <div class="flex justify-between text-xs py-1 text-emerald-600 font-bold">
                                <span>{{ __('Status') }}</span>
                                <span>{{ __('Open Daily') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="p-3 rounded-2xl bg-sultaf-maroon/10 text-sultaf-maroon shrink-0">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-serif font-bold text-sultaf-ink text-base mb-1">{{ __('Phone & Inquiries') }}</h4>
                            <p class="text-sm font-bold text-sultaf-ink">{{ $info['phone'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-6 border-t border-sultaf-border">
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($info['name'].' '.$info['address']) }}" target="_blank" class="w-full py-3.5 px-6 rounded-xl bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-bold text-sm flex items-center justify-center gap-2 shadow-md transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>{{ __('Open in Google Maps Navigation') }}</span>
                    </a>
                </div>
            </div>

            {{-- Google Map Embed --}}
            <div class="lg:col-span-7 rounded-3xl overflow-hidden shadow-md border-4 border-white min-h-[350px]">
                <iframe src="{{ $info['mapEmbedUrl'] }}" width="100%" height="100%" style="border:0; min-height: 380px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <div class="geometric-divider"></div>

    {{-- ===================== CONTACT & SOCIALS ===================== --}}
    <section id="contact" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-sultaf-maroon/10 text-sultaf-maroon font-bold text-xs uppercase tracking-wider mb-3">
                <svg class="w-4 h-4 text-sultaf-maroon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>{{ __('Connect With Us') }}</span>
            </span>
            <h2 class="font-serif text-3xl sm:text-5xl font-extrabold text-sultaf-ink mb-4">
                {{ __('Official Channels & Socials') }}
            </h2>
            <p class="text-sultaf-muted text-base sm:text-lg">
                {{ __('Reach out directly for orders, reservations, catering inquiries, or follow our updates.') }}
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            
            {{-- WhatsApp --}}
            <a href="https://wa.me/{{ $info['whatsappNumber'] }}" target="_blank" class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:shadow-md hover:border-sultaf-maroon transition-all text-center group">
                <div class="w-12 h-12 rounded-xl bg-sultaf-maroon/10 text-sultaf-maroon flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/></svg>
                </div>
                <h4 class="font-serif font-bold text-sm text-sultaf-ink mb-0.5">WhatsApp</h4>
                <p class="text-[11px] text-sultaf-muted">{{ __('Instant Chat') }}</p>
            </a>

            {{-- Phone --}}
            <a href="tel:{{ preg_replace('/[^0-9]/', '', $info['phone']) }}" class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:shadow-md hover:border-sultaf-maroon transition-all text-center group">
                <div class="w-12 h-12 rounded-xl bg-sultaf-maroon/10 text-sultaf-maroon flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <h4 class="font-serif font-bold text-sm text-sultaf-ink mb-0.5">{{ __('Phone Call') }}</h4>
                <p class="text-[11px] text-sultaf-muted">{{ __('Direct Line') }}</p>
            </a>

            {{-- GoFood --}}
            <a href="{{ $info['socials']['gofood'] }}" target="_blank" class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:shadow-md hover:border-emerald-600 transition-all text-center group">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h4 class="font-serif font-bold text-sm text-sultaf-ink mb-0.5">GoFood</h4>
                <p class="text-[11px] text-sultaf-muted">{{ __('Order Delivery') }}</p>
            </a>

            {{-- Instagram --}}
            <a href="{{ $info['socials']['instagram'] }}" target="_blank" class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:shadow-md hover:border-pink-500 transition-all text-center group">
                <div class="w-12 h-12 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </div>
                <h4 class="font-serif font-bold text-sm text-sultaf-ink mb-0.5">Instagram</h4>
                <p class="text-[11px] text-sultaf-muted">@sultafrestaurant</p>
            </a>

            {{-- TikTok --}}
            <a href="{{ $info['socials']['tiktok'] }}" target="_blank" class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:shadow-md hover:border-black transition-all text-center group">
                <div class="w-12 h-12 rounded-xl bg-gray-100 text-black flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298-.002.595.042.88.13V9.4a6.33 6.33 0 0 0-1-.08A6.34 6.34 0 0 0 3 15.66a6.34 6.34 0 0 0 10.82 4.47V11.2a8.28 8.28 0 0 0 5.77 2.27V10a4.84 4.84 0 0 1-3.77-3.31z"/></svg>
                </div>
                <h4 class="font-serif font-bold text-sm text-sultaf-ink mb-0.5">TikTok</h4>
                <p class="text-[11px] text-sultaf-muted">@sultaf.restaurant</p>
            </a>

            {{-- Linktree --}}
            <a href="{{ $info['socials']['linktree'] }}" target="_blank" class="p-5 rounded-2xl bg-white border border-sultaf-border shadow-sm hover:shadow-md hover:border-emerald-500 transition-all text-center group">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#43e660] flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                </div>
                <h4 class="font-serif font-bold text-sm text-sultaf-ink mb-0.5">Linktree</h4>
                <p class="text-[11px] text-sultaf-muted">{{ __('All Links') }}</p>
            </a>
        </div>
    </section>

    {{-- ===================== FOOTER WITH DIRECT LOGIN ===================== --}}
    <footer id="footer" class="bg-sultaf-maroon-dark text-white border-t border-sultaf-gold/20 pt-16 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
            
            {{-- Col 1: Brand Info --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/sultaf_logo.jpg') }}" alt="Sultaf Logo" class="w-12 h-12 rounded-xl object-cover border border-sultaf-gold/40">
                    <div>
                        <h3 class="font-serif text-2xl font-bold tracking-wide">SULTAF RESTAURANT</h3>
                        <p class="text-xs text-sultaf-gold-light">{{ __('Pakistani & Yemeni Cuisine') }}</p>
                    </div>
                </div>
                <p class="text-xs sm:text-sm text-sultaf-cream-soft/80 leading-relaxed max-w-sm">
                    {{ __('Immerse in the aromatic culinary heritage of Pakistan and Yemen. Authentic wok-cooked Chicken Karahi, rich Fasolya Hamra, and sweet Kunafa.') }}
                </p>
            </div>

            {{-- Col 2: Quick Links --}}
            <div>
                <h4 class="font-serif font-bold text-base text-sultaf-gold-light mb-4">{{ __('Quick Links') }}</h4>
                <ul class="space-y-2 text-xs sm:text-sm text-sultaf-cream-soft/80">
                    <li><a href="#home" class="hover:text-white transition-colors">{{ __('Home') }}</a></li>
                    <li><a href="#about" class="hover:text-white transition-colors">{{ __('About Us') }}</a></li>
                    <li><a href="#menu" class="hover:text-white transition-colors">{{ __('Our Menu') }}</a></li>
                    <li><a href="{{ route('menu.index') }}" class="hover:text-white transition-colors">{{ __('Online Ordering') }}</a></li>
                    <li><a href="#gallery" class="hover:text-white transition-colors">{{ __('Photo Gallery') }}</a></li>
                    <li><a href="#catering" class="hover:text-white transition-colors">{{ __('Catering Packages') }}</a></li>
                </ul>
            </div>

            {{-- Col 3: Hours --}}
            <div>
                <h4 class="font-serif font-bold text-base text-sultaf-gold-light mb-4">{{ __('Opening Hours') }}</h4>
                <div class="text-xs sm:text-sm text-sultaf-cream-soft/80 space-y-2">
                    <p><strong>{{ __('Every Day:') }}</strong></p>
                    <p class="text-sultaf-gold font-semibold">{{ $info['hours'] }}</p>
                    <p class="text-xs text-emerald-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ __('Open for Dine-In, Takeaway & Delivery') }}</span>
                    </p>
                </div>
            </div>

            {{-- Col 4: Visit Us --}}
            <div>
                <h4 class="font-serif font-bold text-base text-sultaf-gold-light mb-4">{{ __('Contact & Location') }}</h4>
                <div class="text-xs sm:text-sm text-sultaf-cream-soft/80 space-y-2">
                    <p>{{ $info['address'] }}</p>
                    <p class="font-bold text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-sultaf-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>{{ $info['phone'] }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-sultaf-cream-soft/60 gap-4">
            <p>© {{ date('Y') }} Sultaf Restaurant. {{ __('All rights reserved.') }}</p>
            <div class="flex items-center gap-4">
                {{-- DIRECT LOGIN LINK IN FOOTER --}}
                <a href="{{ route('login') }}" class="bg-sultaf-gold/10 border border-sultaf-gold/40 text-sultaf-gold-light hover:bg-sultaf-gold hover:text-sultaf-maroon-dark px-4 py-2 rounded-xl transition-all font-bold flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>{{ __('Staff & Admin Login Portal') }}</span>
                </a>
            </div>
        </div>
    </footer>

    {{-- ===================== STICKY FLOATING WHATSAPP BUTTON ===================== --}}
    <div class="fixed bottom-6 right-6 z-40">
        <button onclick="openWhatsAppModal()" class="flex items-center gap-2 px-5 py-3.5 rounded-full bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-sultaf-gold-light border-2 border-sultaf-gold/50 shadow-2xl hover:scale-105 transition-all text-sm font-bold">
            <svg class="w-5 h-5 fill-current text-sultaf-gold-light" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/></svg>
            <span class="hidden sm:inline">{{ __('Order via WhatsApp') }}</span>
        </button>
    </div>

    {{-- ===================== WHATSAPP ORDER MODAL ===================== --}}
    <div id="whatsAppModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-sultaf-border relative animate-in fade-in zoom-in duration-200">
            <button onclick="closeWhatsAppModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-sultaf-cream-dark flex items-center justify-center text-sultaf-muted hover:text-sultaf-ink">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <div class="flex items-center gap-2 mb-4">
                <div class="p-2.5 rounded-xl bg-sultaf-maroon/10 text-sultaf-maroon">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/></svg>
                </div>
                <h3 class="font-serif text-xl font-bold text-sultaf-ink">{{ __('Quick WhatsApp Order') }}</h3>
            </div>

            <p class="text-xs text-sultaf-muted mb-6 leading-relaxed">
                {{ __('Select your desired dish and quantities. Clicking send will open WhatsApp with your pre-formatted order message!') }}
            </p>

            <form onsubmit="submitWhatsAppOrder(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-sultaf-muted mb-1">{{ __('Select Dish') }}</label>
                    <select id="waItemSelect" class="w-full border border-sultaf-border bg-sultaf-cream/40 rounded-xl px-4 py-3 text-sm font-medium text-sultaf-ink focus:ring-2 focus:ring-sultaf-maroon/30 focus:outline-none">
                        @foreach ($categories as $cat)
                            <optgroup label="{{ __($cat->name) }}">
                                @foreach ($cat->menus as $m)
                                    <option value="{{ __($m->nama_makanan) }}">{{ __($m->nama_makanan) }} ({{ $m->harga_format }})</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-sultaf-muted mb-1">{{ __('Quantity') }}</label>
                    <div class="flex items-center gap-4">
                        <button type="button" onclick="adjustWaQty(-1)" class="w-10 h-10 rounded-xl border border-sultaf-border bg-sultaf-cream font-bold text-lg text-sultaf-ink hover:bg-sultaf-cream-dark transition-colors">−</button>
                        <span id="waQtyDisplay" class="font-serif font-bold text-xl w-8 text-center text-sultaf-ink">1</span>
                        <button type="button" onclick="adjustWaQty(1)" class="w-10 h-10 rounded-xl border border-sultaf-maroon bg-sultaf-maroon font-bold text-lg text-white hover:bg-sultaf-maroon-dark transition-colors">+</button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-sultaf-muted mb-1">{{ __('Your Name (Optional)') }}</label>
                    <input type="text" id="waCustomerName" placeholder="e.g. Ahmad / Sarah" class="w-full border border-sultaf-border bg-sultaf-cream/40 rounded-xl px-4 py-2.5 text-sm text-sultaf-ink focus:ring-2 focus:ring-sultaf-maroon/30 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-sultaf-muted mb-1">{{ __('Special Notes / Requests (Optional)') }}</label>
                    <textarea id="waNotes" rows="2" placeholder="e.g. Extra mint chutney, less spicy Karahi, or dine-in time..." class="w-full border border-sultaf-border bg-sultaf-cream/40 rounded-xl px-4 py-2.5 text-sm text-sultaf-ink focus:ring-2 focus:ring-sultaf-maroon/30 focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex gap-3">
                    <button type="button" onclick="closeWhatsAppModal()" class="flex-1 py-3.5 rounded-xl border border-sultaf-border text-sultaf-ink font-bold text-sm hover:bg-sultaf-cream transition-colors">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="flex-2 py-3.5 px-6 rounded-xl bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-bold text-sm shadow-md transition-colors flex items-center justify-center gap-2 border border-sultaf-gold/40">
                        <svg class="w-4 h-4 text-sultaf-gold-light" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2z"/></svg>
                        <span>{{ __('Send to WhatsApp') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===================== ADD TO CART MODAL ===================== --}}
    <div id="cartModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-sultaf-border relative animate-in fade-in zoom-in duration-200">
            <button onclick="closeCartModal()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-sultaf-cream-dark flex items-center justify-center text-sultaf-muted hover:text-sultaf-ink">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <p class="text-xs uppercase tracking-wide font-bold text-sultaf-muted mb-1">{{ __('Add to Cart') }}</p>
            <h3 id="modalCartName" class="font-serif text-2xl font-bold text-sultaf-ink mb-1"></h3>
            <p id="modalCartDesc" class="text-xs text-sultaf-muted mb-4"></p>
            <p id="modalCartPrice" class="font-serif text-2xl font-bold text-sultaf-maroon mb-6"></p>

            <div class="flex items-center justify-center gap-6 mb-6">
                <button onclick="adjustCartQty(-1)" class="w-12 h-12 rounded-full border border-sultaf-border flex items-center justify-center text-xl font-bold hover:border-sultaf-maroon text-sultaf-ink">−</button>
                <span id="cartQtyDisplay" class="font-serif text-3xl font-bold text-sultaf-ink w-10 text-center">1</span>
                <button onclick="adjustCartQty(1)" class="w-12 h-12 rounded-full bg-sultaf-maroon text-white flex items-center justify-center text-xl font-bold hover:bg-sultaf-maroon-dark transition-colors">+</button>
            </div>

            <div class="flex justify-between items-center bg-sultaf-cream rounded-2xl px-4 py-3 mb-6">
                <span class="text-xs text-sultaf-muted font-bold uppercase">{{ __('Subtotal') }}</span>
                <span id="modalCartSubtotal" class="font-bold text-sultaf-ink text-base"></span>
            </div>

            <form id="modalCartForm" method="POST" class="grid grid-cols-2 gap-3">
                @csrf
                <input type="hidden" name="qty" id="cartQtyInput" value="1">
                <button type="button" onclick="closeCartModal()" class="py-3.5 rounded-xl border border-sultaf-border text-sultaf-ink font-bold text-sm hover:bg-sultaf-cream transition-colors">
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="py-3.5 rounded-xl bg-sultaf-maroon hover:bg-sultaf-maroon-dark text-white font-bold text-sm transition-colors shadow-md">
                    {{ __('Add to Cart') }}
                </button>
            </form>
        </div>
    </div>

    {{-- ===================== LIGHTBOX MODAL ===================== --}}
    <div id="lightboxModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/90 backdrop-blur-md" onclick="closeLightbox()">
        <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center" onclick="event.stopPropagation()">
            <button onclick="closeLightbox()" class="absolute -top-12 right-0 text-white hover:text-sultaf-gold transition-colors">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <img id="lightboxImg" src="" alt="" class="max-h-[75vh] max-w-full rounded-2xl object-contain shadow-2xl border-2 border-white/20">
            <div id="lightboxCaption" class="mt-4 text-center text-white font-serif text-lg font-bold"></div>
            <div id="lightboxCategory" class="text-xs text-sultaf-gold-light uppercase tracking-wider"></div>
        </div>
    </div>

    {{-- ===================== CLIENT SCRIPTS ===================== --}}
    <script>
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        window.scrollTo(0, 0);

        window.addEventListener('pageshow', function () {
            window.scrollTo(0, 0);
            if (window.location.hash) {
                history.replaceState(null, null, window.location.pathname);
            }
        });

        window.addEventListener('beforeunload', function () {
            window.scrollTo(0, 0);
        });

        document.addEventListener('DOMContentLoaded', function () {
            window.scrollTo(0, 0);
        });

        window.addEventListener('load', function () {
            const video = document.getElementById('heroBgVideo');
            if (video) {
                const source = video.querySelector('source[data-src]');
                if (source && source.dataset.src) {
                    source.src = source.dataset.src;
                    video.load();
                    video.play().catch(function () {});
                }
            }
        });

        const WA_PHONE = '{{ $info['whatsappNumber'] }}';

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        function filterCategory(catSlug) {
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('bg-sultaf-maroon', 'text-white');
                btn.classList.add('bg-white', 'text-sultaf-ink');
                if (btn.dataset.category === catSlug) {
                    btn.classList.add('bg-sultaf-maroon', 'text-white');
                    btn.classList.remove('bg-white', 'text-sultaf-ink');
                }
            });

            document.querySelectorAll('.menu-card').forEach(card => {
                if (catSlug === 'all' || card.dataset.category === catSlug) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function filterGallery(category) {
            document.querySelectorAll('.gallery-btn').forEach(btn => {
                btn.classList.remove('bg-sultaf-maroon', 'text-white');
                btn.classList.add('bg-white', 'text-sultaf-ink');
                if (btn.dataset.gallery === category) {
                    btn.classList.add('bg-sultaf-maroon', 'text-white');
                    btn.classList.remove('bg-white', 'text-sultaf-ink');
                }
            });

            document.querySelectorAll('.gallery-item').forEach(item => {
                if (category === 'All' || item.dataset.galleryType === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function openLightbox(imgSrc, title, category) {
            document.getElementById('lightboxImg').src = imgSrc;
            document.getElementById('lightboxCaption').textContent = title;
            document.getElementById('lightboxCategory').textContent = category;
            document.getElementById('lightboxModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightboxModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        let waQty = 1;
        function openWhatsAppModal() {
            document.getElementById('whatsAppModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeWhatsAppModal() {
            document.getElementById('whatsAppModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function adjustWaQty(delta) {
            waQty = Math.max(1, waQty + delta);
            document.getElementById('waQtyDisplay').textContent = waQty;
        }

        function quickWhatsAppOrder(dishName) {
            const select = document.getElementById('waItemSelect');
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === dishName) {
                    select.selectedIndex = i;
                    break;
                }
            }
            openWhatsAppModal();
        }

        function submitWhatsAppOrder(e) {
            e.preventDefault();
            const dish = document.getElementById('waItemSelect').value;
            const name = document.getElementById('waCustomerName').value.trim();
            const notes = document.getElementById('waNotes').value.trim();

            const nameStr = name ? `Nama: ${name}\n` : '';
            const notesStr = notes ? `Catatan: ${notes}\n` : '';
            const message = `Halo Sultaf Restaurant!\n\nSaya ingin memesan:\n* ${waQty}x ${dish}\n${nameStr}${notesStr}\nMohon konfirmasi pesanan dan ketersediaan. Terima kasih!`;
            
            const waUrl = `https://wa.me/${WA_PHONE}?text=${encodeURIComponent(message)}`;
            window.open(waUrl, '_blank');
            closeWhatsAppModal();
        }

        let cartQty = 1;
        let cartUnitPrice = 0;

        function openCartModal(id, name, desc, price, route) {
            cartQty = 1;
            cartUnitPrice = price;
            document.getElementById('modalCartName').textContent = name;
            document.getElementById('modalCartDesc').textContent = desc;
            document.getElementById('modalCartPrice').textContent = formatRp(price);
            document.getElementById('cartQtyDisplay').textContent = 1;
            document.getElementById('cartQtyInput').value = 1;
            document.getElementById('modalCartSubtotal').textContent = formatRp(price);
            document.getElementById('modalCartForm').action = route;

            document.getElementById('cartModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCartModal() {
            document.getElementById('cartModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function adjustCartQty(delta) {
            cartQty = Math.max(1, cartQty + delta);
            document.getElementById('cartQtyDisplay').textContent = cartQty;
            document.getElementById('cartQtyInput').value = cartQty;
            document.getElementById('modalCartSubtotal').textContent = formatRp(cartUnitPrice * cartQty);
        }

        function formatRp(amount) {
            return 'Rp ' + Math.round(amount).toLocaleString('id-ID');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
                closeWhatsAppModal();
                closeCartModal();
            }
        });
    </script>
</body>
</html>