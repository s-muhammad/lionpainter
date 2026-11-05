@php
    $items = [
        ['label' => 'Services', 'url' => route('services.index')],
        ['label' => $service->title],
    ];
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service->title,
        'description' => $service->meta_description ?: $service->excerpt,
        'serviceType' => $service->title,
        'url' => route('services.show', $service),
        'image' => asset($service->image ?: 'images/hero.svg'),
        'areaServed' => ['@type' => 'City', 'name' => 'Melbourne'],
        'provider' => ['@id' => route('home').'#business'],
    ];
@endphp

<x-layout
    :title="$service->meta_title ?: $service->title.' in Melbourne | Lion Painters'"
    :description="$service->meta_description ?: $service->excerpt"
    :image="$service->image ? asset($service->image) : null"
    :breadcrumbs="$items"
    :schema="$schema"
>
    <x-page-hero
        :eyebrow="$service->tagline ?: 'Painting service'"
        :title="$service->title"
        :text="$service->excerpt"
        :items="$items"
        :image="$service->image"
        :image-alt="$service->title.' by Lion Painters in Melbourne'"
    >
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('quote') }}" class="btn-gold">Get a fixed-price quote <x-icon name="arrow-right" class="h-4 w-4" /></a>
            <a href="tel:{{ setting('phone_href') }}" class="btn-outline"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>
        </div>
    </x-page-hero>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell grid gap-12 lg:grid-cols-[1.5fr_0.8fr] lg:gap-16">
            <div>
                <div class="prose-editorial max-w-3xl">
                    {!! $service->description !!}
                </div>

                @if ($service->features)
                    <div class="mt-12 border-t border-ink-900/10 pt-10">
                        <p class="eyebrow">Scope of work</p>
                        <h2 class="mt-3 font-display text-3xl sm:text-4xl">What this service includes</h2>
                        <ul class="mt-7 grid gap-3 sm:grid-cols-2">
                            @foreach ($service->features as $feature)
                                <li class="flex items-start gap-3 border border-ink-900/10 bg-white/60 p-4 text-sm leading-relaxed text-ink-900/65">
                                    <x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0 text-gold-600" />
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-12 bg-cream-100 p-7 sm:p-9">
                    <div class="flex flex-wrap items-center justify-between gap-5">
                        <div>
                            <p class="eyebrow">Our promise</p>
                            <p class="mt-2 max-w-md font-display text-2xl leading-snug">A fixed price, a clear timeline and a finish we are proud to sign.</p>
                        </div>
                        <a href="{{ route('about') }}" class="link-underline shrink-0">Why Lion Painters</a>
                    </div>
                    <div class="mt-7 grid gap-5 border-t border-ink-900/10 pt-6 sm:grid-cols-3">
                        @foreach ([
                            ['icon' => 'shield', 'title' => 'Fully insured', 'text' => '$20m liability cover'],
                            ['icon' => 'award', 'title' => '10-year guarantee', 'text' => 'Workmanship backed'],
                            ['icon' => 'leaf', 'title' => 'Low-VOC', 'text' => 'Safer for your family'],
                        ] as $point)
                            <div class="flex items-center gap-3">
                                <x-icon :name="$point['icon']" class="h-5 w-5 text-gold-600" />
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.12em] text-ink-900">{{ $point['title'] }}</p>
                                    <p class="mt-0.5 text-[10px] text-ink-900/45">{{ $point['text'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <aside class="space-y-6 lg:sticky lg:top-32 lg:self-start">
                <div class="bg-ink-950 p-7 text-cream-50">
                    <p class="eyebrow-light">Free consultation</p>
                    <h2 class="mt-3 font-display text-3xl">Ready to begin?</h2>
                    <p class="mt-3 text-sm leading-relaxed text-cream-50/65">
                        Tell us about your home and we’ll prepare a detailed, fixed-price proposal within one business day.
                    </p>
                    <a href="{{ route('quote') }}" class="btn-gold mt-6 w-full">Request a quote <x-icon name="arrow-right" class="h-4 w-4" /></a>
                    <a href="tel:{{ setting('phone_href') }}" class="mt-4 flex items-center justify-center gap-2 border border-cream-50/15 py-3 text-[10px] font-bold uppercase tracking-[0.18em] text-cream-50/70 transition-colors hover:border-gold-500 hover:text-gold-300">
                        <x-icon name="phone" class="h-3.5 w-3.5" /> {{ setting('phone') }}
                    </a>
                    <p class="mt-5 flex items-center justify-center gap-2 text-[9px] uppercase tracking-[0.16em] text-cream-50/30">
                        <x-icon name="clock" class="h-3 w-3" /> Reply within 1 business day
                    </p>
                </div>

                <div class="border border-ink-900/10 bg-white/60 p-7">
                    <p class="eyebrow">Service areas</p>
                    <p class="mt-3 text-base leading-relaxed text-ink-900/75">{{ setting('service_area') }}, including {{ implode(', ', array_slice(config('site.suburbs'), 0, 4)) }} and more.</p>
                    <a href="{{ route('service-areas') }}" class="link-underline mt-4">All service areas</a>
                </div>

                @if ($related->isNotEmpty())
                    <div class="border border-ink-900/10 bg-white/60 p-7">
                        <p class="eyebrow">Related services</p>
                        <ul class="mt-4 divide-y divide-ink-900/10">
                            @foreach ($related as $item)
                                <li>
                                    <a href="{{ route('services.show', $item) }}" class="group flex items-center justify-between gap-3 py-3">
                                        <span class="flex items-center gap-3 text-sm text-ink-900/70 group-hover:text-gold-700">
                                            <x-icon :name="$item->icon" class="h-4 w-4 text-gold-600" />
                                            {{ $item->title }}
                                        </span>
                                        <x-icon name="arrow-up-right" class="h-3.5 w-3.5 text-ink-900/30 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    <x-quote-cta />
</x-layout>
