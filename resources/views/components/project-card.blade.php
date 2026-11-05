@props(['project', 'filterable' => false])

<article
    @if ($filterable)
        x-show="matches(@js($project->category))"
        x-transition:enter="transition duration-500 ease-out"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
    @endif
    class="group"
>
    <a href="{{ route('projects.show', $project) }}" class="block">
        <div class="relative overflow-hidden bg-ink-900">
            <img
                src="{{ asset($project->cover_image) }}"
                alt="{{ $project->title }} — {{ $project->category }} painting project in {{ $project->suburb }}"
                class="aspect-[4/5] w-full object-cover transition-transform duration-[1.2s] ease-out group-hover:scale-[1.04]"
                loading="lazy"
                width="1200"
                height="1500"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-ink-950/85 via-ink-950/10 to-transparent"></div>
            <div class="absolute left-0 top-0 flex items-center gap-2 bg-cream-50/95 px-4 py-2 text-[9px] font-bold uppercase tracking-[0.2em] text-ink-900">
                {{ $project->category }}
            </div>
            <div class="absolute inset-x-0 bottom-0 p-6">
                <p class="text-[9px] font-semibold uppercase tracking-[0.24em] text-gold-300">
                    {{ $project->suburb }}{{ $project->year ? ' · '.$project->year : '' }}
                </p>
                <h3 class="mt-2 font-display text-[26px] leading-tight text-cream-50">{{ $project->title }}</h3>
                <span class="mt-4 inline-flex items-center gap-2 text-[9px] font-bold uppercase tracking-[0.22em] text-cream-50/70 transition-colors group-hover:text-gold-300">
                    View project
                    <x-icon name="arrow-up-right" class="h-3.5 w-3.5 transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                </span>
            </div>
        </div>
    </a>
</article>
