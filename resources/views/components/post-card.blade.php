@props(['post'])

<article class="group flex flex-col">
    <a href="{{ route('posts.show', $post) }}" class="block overflow-hidden bg-ink-900">
        <img
            src="{{ asset($post->cover_image) }}"
            alt="{{ $post->title }}"
            class="aspect-[16/10] w-full object-cover transition-transform duration-[1.2s] group-hover:scale-[1.04]"
            loading="lazy"
            width="1200"
            height="750"
        >
    </a>
    <div class="flex flex-1 flex-col pt-6">
        <div class="flex items-center gap-3 text-[9px] font-semibold uppercase tracking-[0.2em] text-ink-900/40">
            <span class="text-gold-600">{{ $post->category }}</span>
            <span class="h-px w-5 bg-ink-900/20"></span>
            <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j M Y') }}</time>
        </div>
        <h3 class="mt-3 font-display text-[27px] leading-tight text-ink-900 transition-colors group-hover:text-gold-700">
            <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
        </h3>
        <p class="mt-3 flex-1 text-base leading-relaxed text-ink-900/75">{{ $post->excerpt }}</p>
        <div class="mt-5 flex items-center justify-between border-t border-ink-900/10 pt-4">
            <span class="text-[10px] uppercase tracking-[0.16em] text-ink-900/35">{{ $post->reading_time }} min read</span>
            <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center gap-2 text-[9px] font-bold uppercase tracking-[0.22em] text-gold-600">
                Read
                <x-icon name="arrow-right" class="h-3.5 w-3.5 transition-transform duration-300 group-hover:translate-x-1" />
            </a>
        </div>
    </div>
</article>
