@php
    $items = [['label' => 'Service areas']];
@endphp

<x-layout
    title="Painting Service Areas Across Melbourne | Lion Painters"
    description="Lion Painters works across Melbourne's inner east, bayside and inner north, including Toorak, South Yarra, Hawthorn, Kew, Brighton and Fitzroy. See the full list of service areas."
    :breadcrumbs="$items"
>
    <x-page-hero
        eyebrow="Service areas"
        title="Painting across Melbourne."
        text="Our studio is in Cremorne, and we travel across the inner east, bayside and inner north for the right project. If your suburb is not listed, ask us — we often travel further."
        :items="$items"
    >
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('quote') }}" class="btn-gold">Request a quote</a>
            <a href="{{ route('contact') }}" class="btn-outline">Ask about your suburb</a>
        </div>
    </x-page-hero>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
            <div>
                <p class="eyebrow">Where we paint</p>
                <h2 class="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl">The suburbs we know by name.</h2>
                <p class="mt-5 text-base leading-relaxed text-ink-900/75">
                    We have painted homes, facades and commercial spaces across {{ setting('suburbs_count') }}+ Melbourne suburbs. Local knowledge matters: heritage overlays, coastal weather, apartment access and the light in a room all shape how we prepare and finish a project.
                </p>
                <p class="mt-4 text-base leading-relaxed text-ink-900/75">
                    The list below covers our most requested areas. If you are just outside it, send us a note — we regularly take on projects across greater Melbourne.
                </p>

                <div class="mt-9 border border-gold-500/30 bg-gold-500/5 p-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-gold-700">Studio base</p>
                    <p class="mt-2 text-base font-medium text-ink-900">{{ setting('address') }}</p>
                    <p class="mt-1 text-sm text-ink-900/65">{{ setting('hours') }}</p>
                </div>
            </div>

            <div>
                <div class="grid gap-px bg-ink-900/15 sm:grid-cols-2">
                    @foreach (config('site.suburbs') as $suburb)
                        <div class="flex items-center gap-3 bg-white/70 px-5 py-4 text-base text-ink-900/80">
                            <span class="h-px w-3 shrink-0 bg-gold-500/70"></span>
                            {{ $suburb }}
                        </div>
                    @endforeach
                </div>
                <p class="mt-5 text-sm text-ink-900/60">
                    Plus surrounding streets and suburbs across greater Melbourne.
                </p>
            </div>
        </div>
    </section>

    <x-quote-cta heading="Painting near you?" text="Tell us where you are and what you have in mind. We will confirm availability and the next sensible step." />
</x-layout>
