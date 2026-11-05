@props([
    'eyebrow' => null,
    'title' => null,
    'text' => null,
    'items' => [],
    'image' => null,
    'imageAlt' => null,
])

<section class="relative overflow-hidden bg-ink-950 text-cream-50">
    <div class="pointer-events-none absolute inset-0 bg-grain opacity-60"></div>
    <div class="pointer-events-none absolute -right-16 -top-24 select-none font-display text-[20rem] leading-none text-cream-50/[0.03]">L</div>
    <div class="pointer-events-none absolute left-1/3 top-0 h-64 w-64 rounded-full bg-gold-500/10 blur-[100px]"></div>

    <div class="shell relative pb-14 pt-9 lg:pb-20 lg:pt-12">
        <x-breadcrumbs :items="$items" theme="dark" />

        <div class="mt-9 grid items-center gap-10 {{ $image ? 'lg:grid-cols-[1.15fr_0.85fr]' : '' }}">
            <div class="{{ $image ? '' : 'max-w-3xl' }}">
                @if ($eyebrow)
                    <p class="eyebrow-light">{{ $eyebrow }}</p>
                @endif
                @if ($title)
                    <h1 class="mt-4 font-display text-[42px] leading-[1.02] sm:text-5xl lg:text-6xl">{{ $title }}</h1>
                @endif
                @if ($text)
                    <p class="mt-5 max-w-2xl text-base leading-relaxed text-cream-50/70">{{ $text }}</p>
                @endif
                {{ $slot }}
            </div>

            @if ($image)
                <div class="relative hidden lg:block">
                    <div class="absolute -right-3 -top-3 h-full w-full border border-gold-500/30"></div>
                    <img src="{{ asset($image) }}" alt="{{ $imageAlt ?? $title }}" class="relative aspect-[4/3] w-full object-cover" width="1200" height="900">
                </div>
            @endif
        </div>
    </div>
</section>
