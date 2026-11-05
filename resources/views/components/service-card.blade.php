@props(['service'])

<a href="{{ route('services.show', $service) }}" class="group relative flex flex-col overflow-hidden border border-ink-900/10 bg-white/50 p-7 transition-all duration-500 hover:border-gold-500/50 hover:bg-white hover:shadow-[0_30px_70px_-45px_rgba(7,20,31,0.45)] sm:p-8">
    <x-icon :name="$service->icon" class="pointer-events-none absolute -right-5 -top-5 h-28 w-28 text-ink-900/[0.035] transition-transform duration-700 group-hover:rotate-6 group-hover:scale-110" stroke-width="0.6" />

    <span class="flex h-12 w-12 items-center justify-center border border-gold-500/30 text-gold-600 transition-colors duration-300 group-hover:bg-ink-900 group-hover:text-gold-400">
        <x-icon :name="$service->icon" class="h-5 w-5" />
    </span>

    <h3 class="mt-7 font-display text-[26px] leading-tight text-ink-900">{{ $service->title }}</h3>
    <p class="mt-3 flex-1 text-base leading-relaxed text-ink-900/75">{{ $service->excerpt }}</p>

    <span class="mt-7 inline-flex items-center gap-2 text-[9px] font-bold uppercase tracking-[0.24em] text-gold-600">
        Explore service
        <x-icon name="arrow-right" class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1" />
    </span>
</a>
