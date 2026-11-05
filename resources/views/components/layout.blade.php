@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'robots' => 'index,follow,max-image-preview:large',
    'schema' => null,
    'breadcrumbs' => [],
])

<!DOCTYPE html>
<html lang="en-AU" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo
        :title="$title"
        :description="$description"
        :image="$image"
        :type="$type"
        :robots="$robots"
        :schema="$schema"
        :breadcrumbs="$breadcrumbs"
    />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-cream-50 font-sans text-ink-900">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:bg-gold-500 focus:px-4 focus:py-2 focus:text-ink-950">
        Skip to content
    </a>

    <header x-data="siteHeader" class="sticky top-0 z-50">
        {{-- Utility bar --}}
        <div class="hidden bg-ink-950 text-cream-50/65 lg:block">
            <div class="shell flex h-10 items-center justify-between text-[10px] font-medium tracking-[0.14em]">
                <div class="flex items-center gap-7">
                    <a href="tel:{{ setting('phone_href') }}" class="flex items-center gap-2 transition-colors hover:text-gold-300">
                        <x-icon name="phone" class="h-3.5 w-3.5 text-gold-500" />
                        {{ setting('phone') }}
                    </a>
                    <a href="mailto:{{ setting('email') }}" class="flex items-center gap-2 transition-colors hover:text-gold-300">
                        <x-icon name="mail" class="h-3.5 w-3.5 text-gold-500" />
                        {{ setting('email') }}
                    </a>
                </div>
                <div class="flex items-center gap-6">
                    <span class="flex items-center gap-2 normal-case tracking-normal text-cream-50/65">
                        <x-icon name="pin" class="h-3.5 w-3.5 text-gold-500" />
                        {{ setting('service_area') }}
                    </span>
                    <span class="h-3 w-px bg-cream-50/15"></span>
                    <div class="flex items-center gap-3">
                        @if (setting('instagram'))
                            <a href="{{ setting('instagram') }}" target="_blank" rel="noopener noreferrer" aria-label="Lion Painters on Instagram" class="transition-colors hover:text-gold-300">
                                <x-icon name="instagram" class="h-3.5 w-3.5" />
                            </a>
                        @endif
                        @if (setting('facebook'))
                            <a href="{{ setting('facebook') }}" target="_blank" rel="noopener noreferrer" aria-label="Lion Painters on Facebook" class="transition-colors hover:text-gold-300">
                                <x-icon name="facebook" class="h-3.5 w-3.5" />
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Main navigation --}}
        <div
            class="border-b border-ink-900/10 bg-cream-50/95 backdrop-blur-md transition-shadow duration-300"
            :class="scrolled ? 'shadow-[0_10px_40px_-24px_rgba(7,20,31,0.45)]' : ''"
        >
            <div class="shell flex h-20 items-center justify-between gap-6 lg:h-24">
                <img src="/images/1102.png" alt="" width="200px">

                <nav class="hidden items-center gap-7 lg:flex" aria-label="Primary">
                    <a href="{{ route('home') }}" class="nav-link" @if (request()->routeIs('home')) aria-current="page" @endif>Home</a>

                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button
                            type="button"
                            class="nav-link flex items-center gap-1.5"
                            @click="open = !open"
                            :aria-expanded="open"
                            aria-haspopup="true"
                        >
                            Services
                            <x-icon name="chevron-down" class="h-3 w-3 transition-transform duration-300" x-bind:class="open ? 'rotate-180' : ''" />
                        </button>
                        <div
                            x-show="open"
                            x-cloak
                            x-transition:enter="transition duration-200 ease-out"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition duration-150 ease-in"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="absolute left-1/2 top-full w-[340px] -translate-x-1/2 pt-7"
                        >
                            <div class="border border-ink-900/10 bg-white p-3 shadow-[0_30px_70px_-30px_rgba(7,20,31,0.4)]">
                                @foreach ($navServices as $navService)
                                    <a href="{{ route('services.show', $navService) }}" class="group flex items-start gap-3 px-3 py-3 transition-colors hover:bg-cream-100">
                                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center border border-gold-500/30 text-gold-600">
                                            <x-icon :name="$navService->icon" class="h-4 w-4" />
                                        </span>
                                        <span>
                                            <span class="block text-[12px] font-semibold text-ink-900 group-hover:text-gold-700">{{ $navService->title }}</span>
                                            <span class="mt-0.5 block text-[11px] leading-relaxed text-ink-900/50">{{ $navService->tagline }}</span>
                                        </span>
                                    </a>
                                @endforeach
                                <a href="{{ route('services.index') }}" class="mt-1 flex items-center justify-between border-t border-ink-900/10 px-3 pb-1 pt-3 text-[10px] font-bold uppercase tracking-[0.2em] text-gold-600 hover:text-gold-700">
                                    All services
                                    <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('projects.index') }}" class="nav-link" @if (request()->routeIs('projects.*')) aria-current="page" @endif>Portfolio</a>
                    <a href="{{ route('about') }}" class="nav-link" @if (request()->routeIs('about')) aria-current="page" @endif>About</a>
                    <a href="{{ route('posts.index') }}" class="nav-link" @if (request()->routeIs('posts.*')) aria-current="page" @endif>Journal</a>
                    <a href="{{ route('contact') }}" class="nav-link" @if (request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
                </nav>

                <div class="flex items-center gap-3">
                    <a href="{{ route('quote') }}" class="hidden border border-ink-900/20 px-6 py-3 text-[10px] font-bold uppercase tracking-[0.2em] text-ink-900 transition-all duration-300 hover:border-gold-500 hover:bg-gold-500 hover:text-ink-950 sm:inline-flex">
                        Request a quote
                    </a>
                    <button
                        type="button"
                        class="flex h-11 w-11 items-center justify-center border border-ink-900/15 text-ink-900 lg:hidden"
                        @click="open = true"
                        aria-label="Open menu"
                        :aria-expanded="open"
                    >
                        <x-icon name="menu" class="h-5 w-5" />
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="fixed inset-0 z-[60] flex flex-col bg-ink-950 text-cream-50 lg:hidden"
            x-effect="document.body.classList.toggle('overflow-hidden', open)"
            role="dialog"
            aria-modal="true"
            aria-label="Site menu"
        >
            <div class="flex h-20 items-center justify-between px-5">
                <x-logo theme="dark" compact />
                <button type="button" class="flex h-11 w-11 items-center justify-center border border-cream-50/20" @click="open = false" aria-label="Close menu">
                    <x-icon name="close" class="h-5 w-5" />
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto px-5 pb-8 pt-4" aria-label="Mobile">
                <div class="space-y-1">
                    @foreach ([
                        ['label' => 'Home', 'route' => 'home'],
                        ['label' => 'Services', 'route' => 'services.index'],
                        ['label' => 'Portfolio', 'route' => 'projects.index'],
                        ['label' => 'About', 'route' => 'about'],
                        ['label' => 'Journal', 'route' => 'posts.index'],
                        ['label' => 'Contact', 'route' => 'contact'],
                    ] as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center justify-between border-b border-cream-50/10 py-4 font-display text-3xl" @click="open = false">
                            {{ $item['label'] }}
                            <x-icon name="arrow-up-right" class="h-4 w-4 text-gold-500" />
                        </a>
                    @endforeach
                </div>
                <div class="mt-8 grid gap-2">
                    <a href="{{ route('quote') }}" class="btn-gold w-full" @click="open = false">Request a free quote</a>
                    <a href="tel:{{ setting('phone_href') }}" class="btn-outline w-full">
                        <x-icon name="phone" class="h-4 w-4" />
                        {{ setting('phone') }}
                    </a>
                </div>
                <p class="mt-8 text-xs leading-relaxed text-cream-50/45">
                    {{ setting('address') }}<br>
                    {{ setting('hours') }}
                </p>
            </nav>
        </div>
    </header>

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-cloak class="border-b border-sage-600/20 bg-sage-500/10">
            <div class="shell flex items-start justify-between gap-4 py-4">
                <p class="flex items-start gap-3 text-sm text-sage-600">
                    <x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0" />
                    {{ session('success') }}
                </p>
                <button type="button" @click="show = false" class="text-sage-600/60 hover:text-sage-600" aria-label="Dismiss message">
                    <x-icon name="close" class="h-4 w-4" />
                </button>
            </div>
        </div>
    @endif

    <main id="main-content">
        {{ $slot }}
    </main>

    <x-site-footer />
</body>
</html>
