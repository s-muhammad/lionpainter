@php
    $items = [
        ['label' => 'Portfolio', 'url' => route('projects.index')],
        ['label' => $project->title],
    ];
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => $project->title,
        'description' => $project->summary,
        'image' => asset($project->cover_image),
        'dateCreated' => $project->year,
        'locationCreated' => ['@type' => 'Place', 'name' => $project->suburb.', Melbourne'],
        'creator' => ['@id' => route('home').'#business'],
    ];
@endphp

<x-layout
    :title="$project->title.' — '.$project->category.' Painting in '.$project->suburb.' | Lion Painters'"
    :description="$project->summary"
    :image="$project->cover_image ? asset($project->cover_image) : null"
    type="article"
    :breadcrumbs="$items"
    :schema="$schema"
>
    <section class="relative overflow-hidden bg-ink-950 pb-14 pt-9 text-cream-50 lg:pb-20 lg:pt-12">
        <div class="pointer-events-none absolute inset-0 bg-grain opacity-60"></div>
        <div class="shell relative">
            <x-breadcrumbs :items="$items" theme="dark" />

            <div class="mt-10 grid gap-10 lg:grid-cols-[1.4fr_0.6fr] lg:items-end">
                <div>
                    <p class="eyebrow-light">{{ $project->category }} · {{ $project->suburb }}</p>
                    <h1 class="mt-4 max-w-3xl font-display text-[42px] leading-[1.02] sm:text-5xl lg:text-6xl">{{ $project->title }}</h1>
                    <p class="mt-5 max-w-2xl text-sm leading-relaxed text-cream-50/70">{{ $project->summary }}</p>
                </div>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-5 border-t border-cream-50/10 pt-6 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
                    @foreach ([
                        ['label' => 'Location', 'value' => $project->suburb],
                        ['label' => 'Year', 'value' => $project->year],
                        ['label' => 'Category', 'value' => $project->category],
                        ['label' => 'Palette', 'value' => $project->colour],
                    ] as $meta)
                        @if ($meta['value'])
                            <div>
                                <dt class="text-[9px] uppercase tracking-[0.18em] text-cream-50/35">{{ $meta['label'] }}</dt>
                                <dd class="mt-1 text-sm text-cream-50/80">{{ $meta['value'] }}</dd>
                            </div>
                        @endif
                    @endforeach
                </dl>
            </div>
        </div>
    </section>

    <div class="bg-cream-50">
        <div class="shell -mt-8 lg:-mt-10">
            <div class="relative">
                <div class="absolute -left-3 -top-3 h-full w-full border border-gold-500/40"></div>
                <img
                    src="{{ asset($project->cover_image) }}"
                    alt="{{ $project->title }} — {{ $project->category }} project in {{ $project->suburb }} by Lion Painters"
                    class="relative aspect-[16/9] w-full object-cover"
                    width="1600"
                    height="900"
                    fetchpriority="high"
                >
            </div>
        </div>
    </div>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell grid gap-12 lg:grid-cols-[1.5fr_0.65fr] lg:gap-20">
            <div>
                <div class="prose-editorial max-w-3xl">
                    {!! $project->description !!}
                </div>

                @if ($project->gallery)
                    <div class="mt-12 grid gap-4 sm:grid-cols-2">
                        @foreach ($project->gallery as $image)
                            <img
                                src="{{ asset($image) }}"
                                alt="{{ $project->title }} painting detail {{ $loop->iteration }}"
                                class="aspect-[4/3] w-full object-cover"
                                loading="lazy"
                                width="1200"
                                height="900"
                            >
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="lg:sticky lg:top-32 lg:self-start">
                <div class="border border-ink-900/10 bg-white/60 p-7">
                    <p class="eyebrow">Project notes</p>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex justify-between gap-4 border-b border-ink-900/10 pb-3">
                            <span class="text-ink-900/45">Service</span>
                            <span class="text-right font-medium text-ink-900">{{ $project->category }} painting</span>
                        </div>
                        <div class="flex justify-between gap-4 border-b border-ink-900/10 pb-3">
                            <span class="text-ink-900/45">Location</span>
                            <span class="text-right font-medium text-ink-900">{{ $project->suburb }}</span>
                        </div>
                        @if ($project->year)
                            <div class="flex justify-between gap-4 border-b border-ink-900/10 pb-3">
                                <span class="text-ink-900/45">Completed</span>
                                <span class="text-right font-medium text-ink-900">{{ $project->year }}</span>
                            </div>
                        @endif
                        @if ($project->colour)
                            <div class="flex justify-between gap-4">
                                <span class="text-ink-900/45">Palette</span>
                                <span class="text-right font-medium text-ink-900">{{ $project->colour }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6 bg-ink-950 p-7 text-cream-50">
                    <p class="eyebrow-light">Inspired by this project?</p>
                    <p class="mt-3 font-display text-2xl leading-snug">Let’s create something just as considered for your home.</p>
                    <a href="{{ route('quote') }}" class="btn-gold mt-5 w-full">Request a quote</a>
                </div>
            </aside>
        </div>
    </section>

    @if ($next)
        <section class="border-t border-ink-900/10 bg-cream-100">
            <a href="{{ route('projects.show', $next) }}" class="group block">
                <div class="shell flex flex-col items-start justify-between gap-6 py-12 sm:flex-row sm:items-center">
                    <div>
                        <p class="eyebrow">Next project</p>
                        <h2 class="mt-3 font-display text-3xl transition-colors group-hover:text-gold-700 sm:text-4xl">{{ $next->title }}</h2>
                        <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-ink-900/40">{{ $next->suburb }} · {{ $next->category }}</p>
                    </div>
                    <span class="flex h-12 w-12 items-center justify-center border border-ink-900/15 text-gold-600 transition-all duration-300 group-hover:border-gold-500 group-hover:bg-gold-500 group-hover:text-ink-950">
                        <x-icon name="arrow-right" class="h-5 w-5" />
                    </span>
                </div>
            </a>
        </section>
    @endif
</x-layout>
