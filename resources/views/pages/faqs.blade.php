@php
    $items = [['label' => 'FAQs']];
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq->question,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq->answer],
        ])->all(),
    ];
@endphp

<x-layout
    title="Painting FAQs | Quotes, Timelines & Guarantees | Lion Painters"
    description="Answers to common questions about painting quotes, preparation, timelines, paint brands, weather, guarantees and working with Lion Painters in Melbourne."
    :breadcrumbs="$items"
    :schema="$schema"
>
    <x-page-hero
        eyebrow="Good to know"
        title="Questions, answered."
        text="Everything you might want to know before inviting a painter into your home — from how we quote to how we protect your floors."
        :items="$items"
    >
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('quote') }}" class="btn-gold">Request a quote</a>
            <a href="tel:{{ setting('phone_href') }}" class="btn-outline"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>
        </div>
    </x-page-hero>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell grid gap-12 lg:grid-cols-[1.3fr_0.7fr] lg:gap-20">
            <div>
                <div x-data="faqAccordion(0)" class="divide-y divide-ink-900/10 border-y border-ink-900/10">
                    @foreach ($faqs as $index => $faq)
                        <div>
                            <h2>
                                <button
                                    type="button"
                                    @click="toggle({{ $index }})"
                                    class="flex w-full items-center justify-between gap-6 py-6 text-left"
                                    :aria-expanded="open === {{ $index }}"
                                >
                                    <span class="font-display text-xl leading-snug sm:text-2xl">{{ $faq->question }}</span>
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center border border-ink-900/15 text-gold-600">
                                        <x-icon name="plus" class="h-4 w-4 transition-transform duration-300" x-bind:class="open === {{ $index }} ? 'rotate-45' : ''" />
                                    </span>
                                </button>
                            </h2>
                            <div x-show="open === {{ $index }}" x-cloak x-transition class="max-w-2xl pb-6 pr-12 text-base leading-[1.85] text-ink-900/75">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 bg-cream-100 p-7 sm:p-9">
                    <div class="flex flex-col items-start justify-between gap-5 sm:flex-row sm:items-center">
                        <div>
                            <p class="eyebrow">Still wondering?</p>
                            <p class="mt-2 font-display text-2xl">Ask us anything. A real painter will answer.</p>
                        </div>
                        <a href="{{ route('contact') }}" class="btn-dark shrink-0">Contact the studio</a>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-ink-950 p-7 text-cream-50">
                    <p class="eyebrow-light">At a glance</p>
                    <ul class="mt-6 space-y-5 text-[12px]">
                        @foreach ([
                            ['icon' => 'calendar', 'title' => 'Typical lead time', 'text' => '2–4 weeks for interior projects'],
                            ['icon' => 'clock', 'title' => 'Quote turnaround', 'text' => 'Within one business day'],
                            ['icon' => 'shield', 'title' => 'Workmanship guarantee', 'text' => '10 years, in writing'],
                            ['icon' => 'leaf', 'title' => 'Paints we use', 'text' => 'Dulux & Haymes low-VOC'],
                        ] as $item)
                            <li class="flex gap-3">
                                <x-icon :name="$item['icon']" class="mt-0.5 h-4 w-4 shrink-0 text-gold-500" />
                                <div>
                                    <p class="font-semibold text-cream-50/85">{{ $item['title'] }}</p>
                                    <p class="mt-0.5 text-cream-50/45">{{ $item['text'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('quote') }}" class="btn-gold mt-7 w-full">Request a quote</a>
                </div>

                <div class="border border-ink-900/10 bg-white/60 p-7">
                    <p class="eyebrow">Service areas</p>
                    <p class="mt-3 text-sm leading-relaxed text-ink-900/70">Proudly painting across {{ setting('service_area') }}.</p>
                    <ul class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-[11px] text-ink-900/60">
                        @foreach (array_slice(config('site.suburbs'), 0, 6) as $suburb)
                            <li class="flex items-center gap-2"><span class="h-px w-2 bg-gold-500/60"></span>{{ $suburb }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('service-areas') }}" class="link-underline mt-5">All service areas</a>
                </div>
            </aside>
        </div>
    </section>

    <x-quote-cta />
</x-layout>
