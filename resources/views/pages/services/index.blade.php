@php
    $items = [['label' => 'Services']];
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Painting services in Melbourne',
        'itemListElement' => $services->values()->map(fn ($service, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $service->title,
            'url' => route('services.show', $service),
        ])->all(),
    ];
@endphp

<x-layout
    title="Painting Services in Melbourne | Lion Painters"
    description="Explore Lion Painters' complete painting services: interior and exterior painting, surface preparation, deck restoration, repairs and commercial projects across Melbourne."
    :breadcrumbs="$items"
    :schema="$schema"
>
    <x-page-hero
        eyebrow="Our services"
        title="Complete painting services, considered in every detail."
        text="From a single feature wall to a full exterior restoration, each service is delivered end-to-end by our own skilled team — never subcontracted."
        :items="$items"
        image="images/about.svg"
        image-alt="A paint roller applying a fresh coat to a wall"
    >
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('quote') }}" class="btn-gold">Request a quote <x-icon name="arrow-right" class="h-4 w-4" /></a>
            <a href="tel:{{ setting('phone_href') }}" class="btn-outline"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>
        </div>
    </x-page-hero>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell">
            <div class="mx-auto max-w-3xl text-center">
                <p class="eyebrow">What’s included</p>
                <h2 class="mt-4 font-display text-4xl leading-tight sm:text-5xl">A complete scope, start to finish.</h2>
                <p class="mt-4 text-base leading-relaxed text-ink-900/75">
                    Every project begins with a thorough site assessment. We document the preparation, products and finishes required, so you know exactly what you are paying for.
                </p>
            </div>

            <div class="mt-14 space-y-14 lg:space-y-20">
                @foreach ($services as $service)
                    <article class="group grid items-center gap-8 border-b border-ink-900/10 pb-14 last:border-0 last:pb-0 lg:grid-cols-2 lg:gap-16 lg:pb-20" data-reveal>
                        <div class="{{ $loop->even ? 'lg:order-2' : '' }}">
                            <div class="relative">
                                <div class="absolute -left-3 -top-3 h-full w-full border border-gold-500/30"></div>
                                <img
                                    src="{{ asset($service->image ?: 'images/about.svg') }}"
                                    alt="{{ $service->title }} in Melbourne by Lion Painters"
                                    class="relative aspect-[4/3] w-full object-cover"
                                    loading="lazy"
                                    width="1200"
                                    height="900"
                                >
                                <span class="absolute -bottom-5 right-6 font-display text-6xl text-cream-50 drop-shadow-lg">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center border border-gold-500/30 text-gold-600">
                                    <x-icon :name="$service->icon" class="h-4 w-4" />
                                </span>
                                <span class="text-[9px] font-bold uppercase tracking-[0.24em] text-gold-600">{{ $service->tagline }}</span>
                            </div>
                            <h3 class="mt-5 font-display text-3xl leading-tight sm:text-4xl">{{ $service->title }}</h3>
                            <p class="mt-4 text-base leading-relaxed text-ink-900/75">{{ $service->excerpt }}</p>

                            @if ($service->features)
                                <ul class="mt-6 grid gap-x-6 gap-y-2.5 sm:grid-cols-2">
                                    @foreach (array_slice($service->features, 0, 6) as $feature)
                                        <li class="flex items-start gap-2.5 text-sm leading-relaxed text-ink-900/75">
                                            <x-icon name="check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gold-600" stroke-width="2.2" />
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ route('services.show', $service) }}" class="link-underline mt-7">
                                Explore {{ $service->title }}
                                <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <x-cta-band heading="Not sure where to start? We’ll guide you." text="Book a free on-site consultation and we’ll help you define the scope, products and palette." />
</x-layout>
