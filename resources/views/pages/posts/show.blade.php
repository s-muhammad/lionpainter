@php
    $items = [
        ['label' => 'Journal', 'url' => route('posts.index')],
        ['label' => $post->title],
    ];
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $post->title,
        'description' => $post->excerpt,
        'image' => asset($post->cover_image),
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at?->toIso8601String(),
        'articleSection' => $post->category,
        'inLanguage' => 'en-AU',
        'author' => [
            '@type' => 'Person',
            'name' => $post->author,
            'worksFor' => ['@id' => route('home').'#business'],
        ],
        'publisher' => ['@id' => route('home').'#business'],
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('posts.show', $post)],
    ];
@endphp

<x-layout
    :title="$post->meta_title ?: $post->title.' | Lion Painters Journal'"
    :description="$post->meta_description ?: $post->excerpt"
    :image="$post->cover_image ? asset($post->cover_image) : null"
    type="article"
    :breadcrumbs="$items"
    :schema="$schema"
>
    <section class="relative overflow-hidden bg-ink-950 pb-16 pt-9 text-cream-50 lg:pb-24 lg:pt-12">
        <div class="pointer-events-none absolute inset-0 bg-grain opacity-60"></div>
        <div class="shell relative">
            <x-breadcrumbs :items="$items" theme="dark" />
            <div class="mx-auto mt-10 max-w-4xl text-center">
                <p class="eyebrow-light">{{ $post->category }}</p>
                <h1 class="mt-5 font-display text-[40px] leading-[1.05] sm:text-5xl lg:text-[62px]">{{ $post->title }}</h1>
                <p class="mx-auto mt-6 max-w-2xl text-sm leading-relaxed text-cream-50/70">{{ $post->excerpt }}</p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-[10px] uppercase tracking-[0.16em] text-cream-50/35">
                    <span>{{ $post->author }}</span>
                    <span class="h-1 w-1 rounded-full bg-gold-500"></span>
                    <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j F Y') }}</time>
                    <span class="h-1 w-1 rounded-full bg-gold-500"></span>
                    <span>{{ $post->reading_time }} min read</span>
                </div>
            </div>
        </div>
    </section>

    <div class="bg-cream-50">
        <div class="shell -mt-10 lg:-mt-14">
            <div class="relative mx-auto max-w-5xl">
                <div class="absolute -left-3 -top-3 h-full w-full border border-gold-500/40"></div>
                <img src="{{ asset($post->cover_image) }}" alt="{{ $post->title }}" class="relative aspect-[16/9] w-full object-cover" width="1200" height="675">
            </div>
        </div>
    </div>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell grid gap-12 lg:grid-cols-[0.95fr_0.3fr] lg:gap-16">
            <article class="mx-auto w-full max-w-3xl">
                <div class="prose-editorial">
                    {!! $post->body !!}
                </div>

                <div class="mt-12 flex flex-wrap items-center justify-between gap-6 border-y border-ink-900/10 py-6">
                    <div class="flex items-center gap-4">
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-ink-950 font-display text-lg text-gold-400">LP</span>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em]">{{ $post->author }}</p>
                            <p class="mt-0.5 text-[10px] uppercase tracking-[0.14em] text-ink-900/40">Lion Painters Studio</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[9px] uppercase tracking-[0.16em] text-ink-900/35">Share</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('posts.show', $post)) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook" class="flex h-9 w-9 items-center justify-center border border-ink-900/15 text-ink-900/50 transition-colors hover:border-gold-500 hover:text-gold-700">
                            <x-icon name="facebook" class="h-4 w-4" />
                        </a>
                        <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode(route('posts.show', $post)) }}" aria-label="Share by email" class="flex h-9 w-9 items-center justify-center border border-ink-900/15 text-ink-900/50 transition-colors hover:border-gold-500 hover:text-gold-700">
                            <x-icon name="mail" class="h-4 w-4" />
                        </a>
                    </div>
                </div>

                <div class="mt-10 bg-cream-100 p-7 sm:p-9">
                    <div class="flex flex-col items-start justify-between gap-5 sm:flex-row sm:items-center">
                        <div>
                            <p class="eyebrow">Planning a project?</p>
                            <p class="mt-2 font-display text-2xl">We’re happy to talk through the details.</p>
                        </div>
                        <a href="{{ route('quote') }}" class="btn-gold shrink-0">Request a quote</a>
                    </div>
                </div>
            </article>

            <aside class="lg:sticky lg:top-32 lg:self-start">
                @if ($related->isNotEmpty())
                    <p class="eyebrow">Keep reading</p>
                    <div class="mt-5 space-y-4">
                        @foreach ($related as $item)
                            <a href="{{ route('posts.show', $item) }}" class="group block border-b border-ink-900/10 pb-4">
                                <span class="text-[9px] font-bold uppercase tracking-[0.18em] text-gold-600">{{ $item->category }}</span>
                                <h2 class="mt-1.5 font-display text-xl leading-snug transition-colors group-hover:text-gold-700">{{ $item->title }}</h2>
                                <span class="mt-2 block text-[10px] uppercase tracking-[0.14em] text-ink-900/35">{{ $item->reading_time }} min read</span>
                            </a>
                        @endforeach
                    </div>
                @endif
                <div class="mt-8 bg-ink-950 p-6 text-cream-50">
                    <p class="font-display text-2xl">Get the studio notes.</p>
                    <p class="mt-2 text-[12px] leading-relaxed text-cream-50/65">Colour ideas and practical care advice, a few times a year.</p>
                    <a href="{{ route('contact') }}" class="mt-5 inline-flex items-center gap-2 text-[9px] font-bold uppercase tracking-[0.2em] text-gold-400 hover:text-gold-300">
                        Get in touch <x-icon name="arrow-up-right" class="h-3.5 w-3.5" />
                    </a>
                </div>
            </aside>
        </div>
    </section>

    <x-quote-cta heading="Ready to put it into practice?" text="We can help you choose the right colours, products and finish for your home." />
</x-layout>
