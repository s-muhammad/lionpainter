@php
    $heroParts = explode('. ', setting('hero_title'), 2);
    $heroLineOne = $heroParts[0] ?? setting('hero_title');
    $heroLineTwo = $heroParts[1] ?? '';
@endphp

<x-layout :description="setting('meta_description')">
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-ink-950 text-cream-50">
        <img
            src="{{ asset('images/loin.png') }}"
            alt="A richly painted Melbourne living room with an arched window, cream sofa and gold-framed lion artwork"
            class="absolute inset-0 h-full w-full object-cover object-[62%_center]"
            width="1920"
            height="1080"
            fetchpriority="high"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-ink-950 via-ink-950/85 to-ink-950/10"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/80 via-transparent to-ink-950/35"></div>

        <div class="shell relative flex min-h-[660px] flex-col justify-center pb-14 pt-20 sm:min-h-[700px] lg:min-h-[760px] lg:pb-20 lg:pt-24">
            <div class="max-w-[660px]">
                <p class="eyebrow-light flex items-center gap-4 fade-up">
                    <span class="h-px w-10 bg-gold-500"></span>
                    {{ setting('hero_eyebrow') }}
                </p>

                <h1 class="mt-7 font-display text-[52px] font-medium leading-[0.98] tracking-[-0.02em] text-shadow-soft sm:text-[68px] lg:text-[82px] fade-up fade-up-delay-1">
                    {{ $heroLineOne }}.
                    @if ($heroLineTwo)
                        <span class="mt-1 block italic text-gold-300">{{ $heroLineTwo }}</span>
                    @endif
                </h1>

                <p class="mt-7 max-w-lg text-base leading-relaxed text-cream-50/80 fade-up fade-up-delay-2">
                    {{ setting('hero_text') }}
                </p>

                <div class="mt-9 flex flex-col gap-3 sm:flex-row fade-up fade-up-delay-3">
                    <a href="{{ route('services.index') }}" class="btn-gold">
                        Discover our services
                        <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                    <a href="{{ route('projects.index') }}" class="btn-outline">View our portfolio</a>
                </div>

                <div class="mt-12 flex flex-wrap items-center gap-x-8 gap-y-4 border-t border-cream-50/15 pt-7 fade-up fade-up-delay-3">
                    <div class="flex items-center gap-3">
                        <x-stars :rating="5" class="h-3.5 w-3.5" />
                        <span class="text-xs text-cream-50/70">
                            <strong class="font-semibold text-cream-50">{{ setting('rating') }}</strong> from {{ setting('review_count') }} reviews
                        </span>
                    </div>
                    <span class="hidden h-4 w-px bg-cream-50/20 sm:block"></span>
                    <span class="flex items-center gap-2 text-[10px] uppercase tracking-[0.16em] text-cream-50/65">
                        <x-icon name="award" class="h-4 w-4 text-gold-400" />
                        {{ setting('years') }} years of craft
                    </span>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 right-6 hidden flex-col items-center gap-3 lg:flex">
            <span class="text-[9px] uppercase tracking-[0.3em] text-cream-50/35 [writing-mode:vertical-rl]">Melbourne · Est. 2011</span>
            <span class="h-14 w-px bg-gradient-to-b from-gold-500/70 to-transparent"></span>
        </div>
    </section>

    {{-- Trust strip --}}
    <section class="border-b border-ink-900/10 bg-cream-100">
        <div class="shell grid gap-6 py-7 sm:grid-cols-2 lg:grid-cols-4 lg:gap-10">
            @foreach ([
                ['icon' => 'shield', 'title' => 'Fully insured', 'text' => '$20m public liability'],
                ['icon' => 'check-circle', 'title' => 'Fixed-price quotes', 'text' => 'No surprises, ever'],
                ['icon' => 'leaf', 'title' => 'Premium paints', 'text' => 'Low-VOC, Australian made'],
                ['icon' => 'award', 'title' => '10-year guarantee', 'text' => 'On all workmanship'],
            ] as $item)
                <div class="flex items-center gap-4">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center border border-gold-500/30 text-gold-600">
                        <x-icon :name="$item['icon']" class="h-4 w-4" />
                    </span>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-ink-900">{{ $item['title'] }}</p>
                        <p class="mt-0.5 text-sm text-ink-900/65">{{ $item['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Services --}}
    <section class="relative overflow-hidden bg-cream-50 py-20 lg:py-28" id="services">
        <div class="pointer-events-none absolute -right-24 top-12 hidden font-display text-[18rem] leading-none text-ink-900/[0.025] lg:block">01</div>
        <div class="shell relative">
            <div class="flex flex-col justify-between gap-6 border-b border-ink-900/10 pb-10 lg:flex-row lg:items-end">
                <div class="max-w-2xl">
                    <p class="eyebrow">What we do</p>
                    <h2 class="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl lg:text-[58px]">
                        {{ setting('intro_heading') }}
                    </h2>
                    <p class="mt-4 max-w-xl text-base leading-relaxed text-ink-900/75">
                        {{ setting('intro_text') }}
                    </p>
                </div>
                <a href="{{ route('services.index') }}" class="link-underline shrink-0">
                    All services
                    <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                </a>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($services->take(4) as $service)
                    <x-service-card :service="$service" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why us --}}
    <section class="relative overflow-hidden bg-cream-100 py-20 lg:py-28">
        <div class="shell">
            <div class="grid items-center gap-14 lg:grid-cols-2 lg:gap-20">
                <div class="relative" data-reveal>
                    <div class="absolute -left-3 -top-3 h-full w-full border border-gold-500/35"></div>
                    <div class="relative overflow-hidden bg-ink-900">
                        <img
                            src="{{ asset('images/living-room.png') }}"
                            alt="Close-up of a paint roller applying a fresh cream coat to a wall"
                            class="aspect-[4/3.2] w-full object-cover"
                            loading="lazy"
                            width="1200"
                            height="900"
                        >
                    </div>
                    <div class="absolute -bottom-7 right-5 flex items-center gap-4 bg-ink-950 px-6 py-5 text-cream-50 shadow-xl sm:right-10">
                        <span class="font-display text-5xl font-medium leading-none text-gold-400">{{ setting('years') }}</span>
                        <span class="max-w-[90px] text-[9px] uppercase leading-relaxed tracking-[0.18em] text-cream-50/65">Years of masterful finishes</span>
                    </div>
                </div>

                <div data-reveal>
                    <p class="eyebrow">The Lion difference</p>
                    <h2 class="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl lg:text-[54px]">
                        {{ setting('why_heading') }}
                    </h2>
                    <p class="mt-5 max-w-lg text-base leading-relaxed text-ink-900/75">
                        {{ setting('why_text') }}
                    </p>

                    <div class="mt-8 grid gap-x-8 gap-y-5 sm:grid-cols-2">
                        @foreach ([
                            ['title' => 'Professionalism', 'text' => 'Punctual, tidy and respectful of your home.'],
                            ['title' => 'Uncompromising quality', 'text' => 'Every edge cut by hand, every surface prepared.'],
                            ['title' => 'Reliability', 'text' => 'Clear timelines and honest communication.'],
                            ['title' => 'Artistry', 'text' => 'Colour guidance and detailing that elevates a room.'],
                        ] as $feature)
                            <div class="flex gap-3">
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-gold-500/15 text-gold-700">
                                    <x-icon name="check" class="h-3 w-3" stroke-width="2.4" />
                                </span>
                                <div>
                                    <p class="text-[12px] font-bold uppercase tracking-[0.1em] text-ink-900">{{ $feature['title'] }}</p>
                                    <p class="mt-1 text-sm leading-relaxed text-ink-900/70">{{ $feature['text'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-9 flex flex-wrap items-center gap-6">
                        <a href="{{ route('about') }}" class="btn-dark">Meet the team</a>
                        <div class="flex items-center gap-3">
                            <span class="font-display text-2xl font-medium text-ink-900/70">Daniel Rossi</span>
                            <span class="text-[9px] uppercase tracking-[0.18em] text-ink-900/50">Founder</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Before / after --}}
    <section class="relative overflow-hidden bg-ink-950 py-20 text-cream-50 lg:py-28">
        <div class="pointer-events-none absolute inset-0 bg-grain opacity-50"></div>
        <div class="shell relative grid items-center gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
            <div>
                <p class="eyebrow-light">The transformation</p>
                <h2 class="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl lg:text-[56px]">
                    See the difference a<br><span class="text-gold-300">proper finish</span> makes.
                </h2>
                <p class="mt-5 max-w-md text-base leading-relaxed text-cream-50/70">
                    The same Hawthorn living room, reimagined. Careful preparation, a considered palette and three coats of premium low-sheen enamel — the details you only notice when they’re missing.
                </p>
                <dl class="mt-8 grid grid-cols-3 gap-6 border-t border-cream-50/10 pt-7">
                    <div>
                        <dt class="text-[9px] uppercase tracking-[0.18em] text-cream-50/35">Palette</dt>
                        <dd class="mt-1 font-display text-xl font-medium text-cream-50">Dulux Still</dd>
                    </div>
                    <div>
                        <dt class="text-[9px] uppercase tracking-[0.18em] text-cream-50/35">Duration</dt>
                        <dd class="mt-1 font-display text-xl font-medium text-cream-50">6 days</dd>
                    </div>
                    <div>
                        <dt class="text-[9px] uppercase tracking-[0.18em] text-cream-50/35">Finish</dt>
                        <dd class="mt-1 font-display text-xl font-medium text-cream-50">Low sheen</dd>
                    </div>
                </dl>
                <a href="{{ route('projects.index') }}" class="btn-outline mt-9">Explore the portfolio</a>
            </div>

            <div
                x-data="beforeAfter"
                x-on:mousemove.window="move($event)"
                x-on:mouseup.window="stop()"
                x-on:mouseleave.window="stop()"
                x-on:touchmove.prevent="move($event)"
                x-on:touchend="stop()"
                class="relative select-none"
                data-reveal
            >
                <div class="relative overflow-hidden border border-cream-50/10" x-ref="frame">
                    <img src="{{ asset('images/before.jpg') }}" alt="Living room before painting, with tired neutral walls" class="aspect-[4/3] w-full object-cover" loading="lazy" width="1600" height="1000">
                    <div class="absolute inset-0" :style="`clip-path: inset(0 0 0 ${position}%)`">
                        <img src="{{ asset('images/after.jpg') }}" alt="The same living room after a premium green repaint and new styling" class="h-full w-full object-cover" loading="lazy" width="1600" height="1000">
                    </div>

                    <span class="absolute left-4 top-4 bg-ink-950/70 px-3 py-1.5 text-[9px] font-bold uppercase tracking-[0.2em] text-cream-50/80 backdrop-blur">Before</span>
                    <span class="absolute right-4 top-4 bg-gold-500 px-3 py-1.5 text-[9px] font-bold uppercase tracking-[0.2em] text-ink-950">After</span>

                    <div class="absolute inset-y-0 w-px bg-cream-50/90" :style="`left: ${position}%`"></div>
                    <button
                        type="button"
                        class="absolute top-1/2 flex h-11 w-11 -translate-x-1/2 -translate-y-1/2 cursor-ew-resize items-center justify-center rounded-full bg-cream-50 text-ink-950 shadow-lg focus:outline-none"
                        :style="`left: ${position}%`"
                        x-on:mousedown.prevent="start($event)"
                        x-on:touchstart.prevent="start($event)"
                        x-on:keydown="keyboard($event)"
                        aria-label="Drag to compare before and after"
                        aria-valuemin="0"
                        aria-valuemax="100"
                        :aria-valuenow="position"
                        role="slider"
                    >
                        <x-icon name="chevron-left" class="h-4 w-4" />
                        <x-icon name="chevron-right" class="-ml-1 h-4 w-4" />
                    </button>
                </div>
                <p class="mt-4 text-center text-[10px] uppercase tracking-[0.18em] text-cream-50/30">Drag the handle to reveal the transformation</p>
            </div>
        </div>
    </section>

    {{-- Selected work --}}
    <section class="relative overflow-hidden bg-cream-50 py-20 lg:py-28">
        <div class="shell">
            <div class="flex flex-col justify-between gap-6 border-b border-ink-900/10 pb-10 lg:flex-row lg:items-end">
                <div class="max-w-2xl">
                    <p class="eyebrow">Selected work</p>
                    <h2 class="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl lg:text-[56px]">
                        Recent homes, considered finishes.
                    </h2>
                    <p class="mt-4 max-w-xl text-base leading-relaxed text-ink-900/75">
                        A small selection of interiors, exteriors and heritage restorations from across Melbourne — each one prepared by hand and finished to last.
                    </p>
                </div>
                <a href="{{ route('projects.index') }}" class="link-underline shrink-0">
                    View the portfolio
                    <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                </a>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects->take(3) as $project)
                    <x-project-card :project="$project" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section class="bg-cream-100 py-20 lg:py-28">
        <div class="shell grid gap-12 lg:grid-cols-[0.8fr_1.2fr] lg:gap-20">
            <div>
                <p class="eyebrow">Client stories</p>
                <h2 class="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl">Loved by Melbourne homeowners.</h2>
                <div class="mt-7 flex items-center gap-4">
                    <span class="font-display text-5xl font-medium text-ink-900">{{ setting('rating') }}</span>
                    <div>
                        <x-stars :rating="5" class="h-4 w-4" />
                        <p class="mt-1.5 text-[10px] uppercase tracking-[0.16em] text-ink-900/50">Based on {{ setting('review_count') }} verified reviews</p>
                    </div>
                </div>
                <p class="mt-6 max-w-xs text-base leading-relaxed text-ink-900/70">
                    Every project is finished by the same small team who started it. That is why our clients keep coming back.
                </p>
                <a href="{{ route('contact') }}" class="link-underline mt-7">Talk to us about your home</a>
            </div>

            @if ($testimonials->isNotEmpty())
                <div x-data="testimonials({{ $testimonials->count() }})" class="relative border border-ink-900/10 bg-cream-50 p-7 sm:p-10" data-reveal>
                    <x-icon name="quote" class="h-10 w-10 text-gold-500/40" stroke-width="1" />

                    <div class="relative mt-6 min-h-[240px] sm:min-h-[210px]">
                        @foreach ($testimonials as $index => $testimonial)
                            <blockquote
                                x-show="active === {{ $index }}"
                                x-transition:enter="transition duration-500 ease-out"
                                x-transition:enter-start="opacity-0 translate-y-3"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute inset-0"
                            >
                                <p class="font-display text-2xl font-medium leading-[1.35] text-ink-900 sm:text-[30px]">“{{ $testimonial->quote }}”</p>
                                <footer class="mt-6 flex items-center justify-between">
                                    <div>
                                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-ink-900">{{ $testimonial->name }}</p>
                                        <p class="mt-1 text-[10px] uppercase tracking-[0.14em] text-ink-900/50">{{ $testimonial->suburb }}{{ $testimonial->project_type ? ' · '.$testimonial->project_type : '' }}</p>
                                    </div>
                                    <x-stars :rating="$testimonial->rating" class="h-3.5 w-3.5" />
                                </footer>
                            </blockquote>
                        @endforeach
                    </div>

                    <div class="mt-8 flex items-center justify-between border-t border-ink-900/10 pt-5">
                        <div class="flex items-center gap-2">
                            @foreach ($testimonials as $index => $testimonial)
                                <button
                                    type="button"
                                    @click="go({{ $index }})"
                                    class="h-1.5 transition-all duration-300"
                                    :class="active === {{ $index }} ? 'w-8 bg-gold-500' : 'w-3 bg-ink-900/20 hover:bg-ink-900/40'"
                                    aria-label="Show testimonial {{ $index + 1 }}"
                                ></button>
                            @endforeach
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="previous()" class="flex h-9 w-9 items-center justify-center border border-ink-900/15 transition-colors hover:border-gold-500 hover:text-gold-700" aria-label="Previous testimonial">
                                <x-icon name="arrow-left" class="h-4 w-4" />
                            </button>
                            <button type="button" @click="next()" class="flex h-9 w-9 items-center justify-center border border-ink-900/15 transition-colors hover:border-gold-500 hover:text-gold-700" aria-label="Next testimonial">
                                <x-icon name="arrow-right" class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Journal --}}
    <section class="relative overflow-hidden bg-cream-50 py-20 lg:py-28">
        <div class="shell">
            <div class="flex flex-col justify-between gap-6 border-b border-ink-900/10 pb-10 lg:flex-row lg:items-end">
                <div class="max-w-2xl">
                    <p class="eyebrow">The journal</p>
                    <h2 class="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl lg:text-[56px]">
                        Notes from the studio.
                    </h2>
                    <p class="mt-4 max-w-xl text-base leading-relaxed text-ink-900/75">
                        Colour notes, preparation guides and lessons from the homes we paint — written by the people holding the brush.
                    </p>
                </div>
                <a href="{{ route('posts.index') }}" class="link-underline shrink-0">
                    Read the journal
                    <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                </a>
            </div>

            <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ & service areas --}}
    <section class="bg-cream-100 py-20 lg:py-28">
        <div class="shell grid gap-12 lg:grid-cols-[1.15fr_0.85fr] lg:gap-20">
            <div>
                <p class="eyebrow">Good to know</p>
                <h2 class="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl">Questions, answered.</h2>

                <div x-data="faqAccordion(0)" class="mt-9 divide-y divide-ink-900/10 border-y border-ink-900/10">
                    @foreach ($faqs as $index => $faq)
                        <div>
                            <button
                                type="button"
                                @click="toggle({{ $index }})"
                                class="flex w-full items-center justify-between gap-6 py-5 text-left"
                                :aria-expanded="open === {{ $index }}"
                            >
                                <span class="font-display text-xl font-medium leading-snug text-ink-900 sm:text-[22px]">{{ $faq->question }}</span>
                                <span class="flex h-7 w-7 shrink-0 items-center justify-center border border-ink-900/15 text-gold-600">
                                    <x-icon name="plus" class="h-3.5 w-3.5 transition-transform duration-300" x-bind:class="open === {{ $index }} ? 'rotate-45' : ''" />
                                </span>
                            </button>
                            <div x-show="open === {{ $index }}" x-cloak x-transition class="pb-5 pr-10 text-base leading-relaxed text-ink-900/75">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('faqs') }}" class="link-underline mt-8">
                    All frequently asked questions
                    <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                </a>
            </div>

            <aside class="bg-ink-950 p-8 text-cream-50 sm:p-10">
                <p class="eyebrow-light">Where we work</p>
                <h2 class="mt-4 font-display text-4xl font-medium">Across Melbourne.</h2>
                <p class="mt-3 text-base leading-relaxed text-cream-50/70">
                    Based in Cremorne, painting homes and workplaces across the inner east, bayside and beyond.
                </p>
                <ul class="mt-7 grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-cream-50/75">
                    @foreach (array_slice(config('site.suburbs'), 0, 6) as $suburb)
                        <li class="flex items-center gap-2">
                            <span class="h-px w-2 bg-gold-500/60"></span>
                            {{ $suburb }}
                        </li>
                    @endforeach
                </ul>
                <div class="mt-8 border-t border-cream-50/10 pt-6">
                    <p class="text-sm text-cream-50/70">We paint across more than 40 Melbourne suburbs.</p>
                    <a href="{{ route('service-areas') }}" class="mt-4 inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.2em] text-gold-400 hover:text-gold-300">
                        See all service areas
                        <x-icon name="arrow-up-right" class="h-3.5 w-3.5" />
                    </a>
                </div>
            </aside>
        </div>
    </section>

    <x-quote-cta />
</x-layout>
