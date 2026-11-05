@props(['items' => [], 'theme' => 'light'])

@php
    $base = $theme === 'dark' ? 'text-cream-50/45' : 'text-ink-900/40';
    $link = $theme === 'dark' ? 'hover:text-gold-300' : 'hover:text-gold-700';
    $current = $theme === 'dark' ? 'text-cream-50/80' : 'text-ink-900/65';
    $sep = $theme === 'dark' ? 'text-cream-50/25' : 'text-ink-900/25';
@endphp

<nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-[10px] uppercase tracking-[0.16em] {{ $base }}">
    <a href="{{ route('home') }}" class="transition-colors {{ $link }}">Home</a>
    @foreach ($items as $item)
        <x-icon name="chevron-right" class="h-3 w-3 {{ $sep }}" />
        @if (! empty($item['url']) && ! $loop->last)
            <a href="{{ $item['url'] }}" class="transition-colors {{ $link }}">{{ $item['label'] }}</a>
        @else
            <span class="{{ $current }}" aria-current="page">{{ $item['label'] }}</span>
        @endif
    @endforeach
</nav>
