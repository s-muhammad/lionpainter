@php
    $items = [['label' => 'Journal']];
    $featured = $posts->onFirstPage() ? $posts->first() : null;
@endphp

<x-layout
    title="Painting Journal | Colour, Craft & Home Advice | Lion Painters"
    description="Practical painting advice from Melbourne's Lion Painters: choosing colour palettes, preparing walls, exterior paint durability and caring for your finish."
    :breadcrumbs="$items"
>
    <x-page-hero
        eyebrow="The journal"
        title="Notes on colour, craft and caring for your home."
        text="Practical guidance from the studio — written by the people who prepare, paint and inspect Melbourne homes every week."
        :items="$items"
    >
        <div class="mt-8 flex flex-wrap items-center gap-3">
            @foreach (['Colour', 'Preparation', 'Exteriors', 'Care'] as $tag)
                <span class="border border-cream-50/15 px-4 py-2 text-[9px] font-bold uppercase tracking-[0.18em] text-cream-50/65">{{ $tag }}</span>
            @endforeach
        </div>
    </x-page-hero>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell">
            @if ($featured)
                <article class="group grid gap-8 border-b border-ink-900/10 pb-14 lg:grid-cols-2 lg:items-center lg:gap-14" data-reveal>
                    <a href="{{ route('posts.show', $featured) }}" class="block overflow-hidden bg-ink-900">
                        <img
                            src="{{ asset($featured->cover_image) }}"
                            alt="{{ $featured->title }}"
                            class="aspect-[16/11] w-full object-cover transition-transform duration-[1.2s] group-hover:scale-[1.03]"
                            width="1200"
                            height="825"
                        >
                    </a>
                    <div>
                        <div class="flex items-center gap-3 text-[9px] font-semibold uppercase tracking-[0.2em] text-ink-900/40">
                            <span class="text-gold-600">Featured · {{ $featured->category }}</span>
                            <span class="h-px w-5 bg-ink-900/20"></span>
                            <time datetime="{{ $featured->published_at?->toDateString() }}">{{ $featured->published_at?->format('j M Y') }}</time>
                        </div>
                        <h2 class="mt-4 font-display text-4xl leading-[1.1] sm:text-5xl">
                            <a href="{{ route('posts.show', $featured) }}" class="transition-colors group-hover:text-gold-700">{{ $featured->title }}</a>
                        </h2>
                        <p class="mt-5 max-w-xl text-base leading-relaxed text-ink-900/75">{{ $featured->excerpt }}</p>
                        <div class="mt-7 flex items-center gap-5">
                            <a href="{{ route('posts.show', $featured) }}" class="link-underline">Read the article</a>
                            <span class="text-[10px] uppercase tracking-[0.16em] text-ink-900/35">{{ $featured->reading_time }} min read</span>
                        </div>
                    </div>
                </article>
            @endif

            <div class="mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    @if (! $featured || $post->id !== $featured->id)
                        <x-post-card :post="$post" />
                    @endif
                @endforeach
            </div>

            @if ($posts->hasPages())
                <div class="mt-14 border-t border-ink-900/10 pt-8">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>

    <x-quote-cta heading="Prefer to leave it to the professionals?" text="We’ll help you choose the right products, colours and finish for your home." />
</x-layout>
