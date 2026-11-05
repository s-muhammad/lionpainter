@props(['theme' => 'light', 'compact' => false])

@php
    $wordmark = $theme === 'dark' ? 'text-cream-50' : 'text-ink-900';
    $tagline = $theme === 'dark' ? 'text-cream-50/65' : 'text-ink-900/45';
@endphp

<a href="{{ route('home') }}" class="group inline-flex items-center gap-3" aria-label="Lion Painters — home">
    <svg class="h-12 w-12 shrink-0 text-gold-500 transition-transform duration-500 group-hover:scale-105" viewBox="0 0 120 120" fill="none" aria-hidden="true">
        <circle cx="60" cy="60" r="57" stroke="currentColor" stroke-opacity=".35" stroke-width="1.2" />
        <g stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
            <path d="M60 18c6 .6 8.4 4.8 12.6 6 5.4.6 6.6 6 10.8 9 0 5.4 4.8 8.4 3.6 13.8 4.2 4.8 1.2 10.2-1.2 13.8 1.2 6-3.6 9-7.2 11.4-1.2 6-6.6 7.8-11.4 8.4-3.6 4.2-9.6 2.4-13.2 1.2-5.4 3-9.6-1.2-12.6-4.8-5.4 0-7.8-4.8-9-9-4.8-3.6-3-9.6-3-14.4-3.6-4.2-.6-10.2 2.4-13.2 0-5.4 4.8-8.4 8.4-10.2 1.2-5.4 6.6-6 9.6-7.2 3-5.4 6.6-5.4 9.6-6.6Z" />
            <path d="M60 30c9.6 0 15 7.2 15 16.2 0 11.4-7.2 19.8-15 24-7.8-4.2-15-12.6-15-24C45 37.2 50.4 30 60 30Z" />
            <path d="M47 32l2.4 6M73 32l-2.4 6M59 27l1.2 5.4M66 28.2l-1.8 4.8M53 28.2l1.8 4.8" />
            <path d="M50 47c3-3.6 7.2-3 9-.6M70 47c-3-3.6-7.2-3-9-.6" />
            <path d="M60 52.2 56.4 58h7.2L60 52.2Zm0 5.8v4.2m0 0c-2.4 2.4-5.4 1.2-6.6-.6m6.6.6c2.4 2.4 5.4 1.2 6.6-.6" />
        </g>
    </svg>
    <span class="flex flex-col leading-none">
        <span class="font-display text-[22px] font-semibold uppercase tracking-[0.16em] {{ $wordmark }}">{{ setting('brand', 'Lion Painters') }}</span>
        @unless ($compact)
            <span class="mt-1.5 text-[8px] font-semibold uppercase tracking-[0.32em] {{ $tagline }}">{{ setting('tagline', 'Excellence in every stroke.') }}</span>
        @endunless
    </span>
</a>
