@php
    $items = [['label' => 'About']];
@endphp

<x-layout
    title="About Lion Painters | Melbourne Painting Studio"
    description="Meet the team behind Lion Painters. Fifteen years of premium residential and commercial painting across Melbourne, built on careful preparation and honest service."
    :breadcrumbs="$items"
>
    <x-page-hero
        eyebrow="Our story"
        title="A Melbourne painting studio built on craft."
        text="Lion Painters began with a simple belief: a paint finish should feel as considered as the furniture in the room. Fifteen years and more than 850 projects later, that belief still guides every brushstroke."
        :items="$items"
        image="images/project-toorak.svg"
        image-alt="A beautifully painted Toorak living room by Lion Painters"
    >
        <div class="mt-9 grid max-w-xl grid-cols-3 gap-6 border-t border-cream-50/10 pt-7">
            <div>
                <p class="font-display text-4xl text-gold-400">{{ setting('years') }}+</p>
                <p class="mt-1 text-[9px] uppercase tracking-[0.18em] text-cream-50/35">Years of craft</p>
            </div>
            <div>
                <p class="font-display text-4xl text-gold-400">{{ setting('projects_count') }}+</p>
                <p class="mt-1 text-[9px] uppercase tracking-[0.18em] text-cream-50/35">Homes painted</p>
            </div>
            <div>
                <p class="font-display text-4xl text-gold-400">{{ setting('suburbs_count') }}+</p>
                <p class="mt-1 text-[9px] uppercase tracking-[0.18em] text-cream-50/35">Suburbs served</p>
            </div>
        </div>
    </x-page-hero>

    <section class="bg-cream-50 py-20 lg:py-28">
        <div class="shell grid gap-14 lg:grid-cols-[0.9fr_1.1fr] lg:gap-20">
            <div class="relative" data-reveal>
                <div class="absolute -left-3 -top-3 h-full w-full border border-gold-500/35"></div>
                <img src="{{ asset('images/hero.svg') }}" alt="A Lion Painters interior project with green walls and a cream sofa" class="relative aspect-[4/5] w-full object-cover" loading="lazy" width="1920" height="1080">
                <div class="absolute -bottom-6 right-5 bg-ink-950 px-6 py-5 text-cream-50">
                    <p class="font-display text-3xl text-gold-400">Est. 2011</p>
                    <p class="mt-1 text-[9px] uppercase tracking-[0.18em] text-cream-50/40">Cremorne, Melbourne</p>
                </div>
            </div>

            <div class="lg:pt-6" data-reveal>
                <p class="eyebrow">The studio</p>
                <h2 class="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl">{{ setting('about_heading') }}</h2>
                <div class="mt-6 space-y-5 text-base leading-[1.9] text-ink-900/80">
                    <p>{{ setting('about_text') }}</p>
                    <p>We are deliberately small. Daniel still visits every site, and the crew who prepare your walls are the same people who apply the final coat. There is no handover to a subcontractor and no volume target to meet — only the standard we set for ourselves.</p>
                    <p>Our work spans heritage terraces in Fitzroy, family homes in Kew, bayside facades in Brighton and boutique commercial spaces in Cremorne. The scale changes; the care does not.</p>
                </div>

                <blockquote class="mt-9 border-l-2 border-gold-500 pl-6">
                    <p class="font-display text-2xl italic leading-snug text-ink-900/80">“A great paint job is 80% preparation and 20% paint. The other 100% is respecting the home you’re working in.”</p>
                    <footer class="mt-4 text-[10px] uppercase tracking-[0.18em] text-ink-900/40">Daniel Rossi — Founder</footer>
                </blockquote>
            </div>
        </div>
    </section>

    <section class="bg-cream-100 py-20 lg:py-24">
        <div class="shell">
            <div class="grid gap-10 border-b border-ink-900/10 pb-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-end">
                <div>
                    <p class="eyebrow">What guides us</p>
                    <h2 class="mt-4 font-display text-4xl leading-tight sm:text-5xl">Three standards,<br><span class="text-gold-600">never compromised.</span></h2>
                </div>
                <p class="max-w-lg text-base leading-relaxed text-ink-900/75 lg:justify-self-end">
                    Our values are practical, not decorative. They shape how we quote, how we protect your home and how we decide when a wall is truly finished.
                </p>
            </div>

            <div class="mt-10 grid gap-px bg-ink-900/15 sm:grid-cols-3">
                @foreach ([
                    ['icon' => 'brush', 'n' => '01', 'title' => 'Craft first', 'text' => 'Hand-cut lines, sanded surfaces and the right product for every substrate. We take the time the finish deserves.'],
                    ['icon' => 'message', 'n' => '02', 'title' => 'Clear communication', 'text' => 'A fixed quote, a written schedule and a direct line to the person running your project. No silence, no surprises.'],
                    ['icon' => 'shield', 'n' => '03', 'title' => 'Genuine care', 'text' => 'Drop sheets, dust control and a clean site every evening. Your home is treated with the respect it deserves.'],
                ] as $value)
                    <div class="bg-cream-100 p-8">
                        <div class="flex items-center justify-between">
                            <x-icon :name="$value['icon']" class="h-6 w-6 text-gold-600" />
                            <span class="font-display text-3xl text-gold-500/40">{{ $value['n'] }}</span>
                        </div>
                        <h3 class="mt-6 font-display text-2xl">{{ $value['title'] }}</h3>
                        <p class="mt-2 text-base leading-relaxed text-ink-900/75">{{ $value['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-cream-50 py-20 lg:py-28">
        <div class="shell">
            <div class="max-w-2xl">
                <p class="eyebrow">Meet the lions</p>
                <h2 class="mt-4 font-display text-4xl leading-tight sm:text-5xl">The people behind the finish.</h2>
                <p class="mt-4 text-base leading-relaxed text-ink-900/75">
                    A compact team of painters, preparers and project managers — supported by a trusted network of colour consultants and joiners.
                </p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['initials' => 'DR', 'name' => 'Daniel Rossi', 'role' => 'Founder & Colour Lead', 'bio' => 'Fifteen years on the tools. Daniel oversees every quote and the final walkthrough.'],
                    ['initials' => 'MC', 'name' => 'Marcus Chen', 'role' => 'Project Manager', 'bio' => 'Keeps schedules honest, sites immaculate and clients informed from day one.'],
                    ['initials' => 'IM', 'name' => 'Isabella Moretti', 'role' => 'Client Care', 'bio' => 'Your first point of contact — from the initial enquiry to the care guide at handover.'],
                    ['initials' => 'NW', 'name' => 'Noah Williams', 'role' => 'Head of Preparation', 'bio' => 'The reason our finishes last. Noah leads repairs, sanding and surface preparation.'],
                ] as $person)
                    <div class="group border border-ink-900/10 bg-white/50 p-7 transition-colors duration-500 hover:border-gold-500/40 hover:bg-white">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full border border-gold-500/30 bg-cream-100 font-display text-2xl text-gold-700">
                            {{ $person['initials'] }}
                        </div>
                        <h3 class="mt-6 font-display text-2xl">{{ $person['name'] }}</h3>
                        <p class="mt-1 text-[9px] font-bold uppercase tracking-[0.18em] text-gold-600">{{ $person['role'] }}</p>
                        <p class="mt-3 text-sm leading-relaxed text-ink-900/70">{{ $person['bio'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-col items-start justify-between gap-5 border-t border-ink-900/10 pt-8 sm:flex-row sm:items-center">
                <p class="text-base text-ink-900/70">We are currently accepting a limited number of projects for the coming season.</p>
                <a href="{{ route('quote') }}" class="btn-dark">Work with us <x-icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
        </div>
    </section>

    <x-quote-cta heading="Let’s talk about your home." text="A free on-site consultation is the best place to start. We’ll listen, measure and advise — with no obligation." />
</x-layout>
