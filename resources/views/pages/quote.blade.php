@php
    $items = [['label' => 'Request a quote']];
@endphp

<x-layout
    title="Request a Free Painting Quote in Melbourne | Lion Painters"
    description="Request a free, fixed-price painting quote from Lion Painters. Tell us about your interior, exterior or commercial project and we’ll reply within one business day."
    :breadcrumbs="$items"
>
    <x-page-hero
        eyebrow="Start your project"
        title="Request a fixed-price quote."
        text="A few details are all we need to prepare an accurate proposal. No obligation, no pressure — just clear advice from a team that has painted more than 850 Melbourne homes."
        :items="$items"
    >
        <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-4 text-[10px] uppercase tracking-[0.16em] text-cream-50/40">
            <span class="flex items-center gap-2"><x-icon name="clock" class="h-4 w-4 text-gold-400" /> Reply within 1 business day</span>
            <span class="flex items-center gap-2"><x-icon name="check-circle" class="h-4 w-4 text-gold-400" /> Fixed-price proposal</span>
            <span class="flex items-center gap-2"><x-icon name="palette" class="h-4 w-4 text-gold-400" /> Free colour consultation</span>
        </div>
    </x-page-hero>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell grid gap-10 lg:grid-cols-[1.35fr_0.65fr] lg:gap-14">
            <div>
                @if (session('quote_success'))
                    <div class="border border-gold-500/40 bg-white p-8 text-center sm:p-12">
                        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gold-500/15 text-gold-700">
                            <x-icon name="check" class="h-8 w-8" stroke-width="2" />
                        </span>
                        <p class="eyebrow mt-6">Request received</p>
                        <h1 class="mt-3 font-display text-4xl">Thank you — we’ve got it.</h1>
                        <p class="mx-auto mt-4 max-w-md text-base leading-relaxed text-ink-900/75">
                            Daniel or Isabella will review your project details and be in touch within one business day. If it’s urgent, call us on
                            <a href="tel:{{ setting('phone_href') }}" class="font-medium text-gold-700 underline decoration-gold-500/40 underline-offset-4">{{ setting('phone') }}</a>.
                        </p>
                        <div class="mx-auto mt-9 grid max-w-lg gap-px bg-ink-900/10 sm:grid-cols-3">
                            @foreach ([
                                ['n' => '01', 't' => 'Review', 'd' => 'We look at your scope and suburb.'],
                                ['n' => '02', 't' => 'Call', 'd' => 'A short conversation about details.'],
                                ['n' => '03', 't' => 'Quote', 'd' => 'A fixed-price proposal in writing.'],
                            ] as $step)
                                <div class="bg-white p-5">
                                    <span class="text-[9px] font-bold uppercase tracking-[0.18em] text-gold-600">{{ $step['n'] }}</span>
                                    <p class="mt-2 font-display text-xl">{{ $step['t'] }}</p>
                                    <p class="mt-1 text-sm leading-relaxed text-ink-900/65">{{ $step['d'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-9 flex flex-wrap justify-center gap-3">
                            <a href="{{ route('projects.index') }}" class="btn-dark">Browse our work</a>
                            <a href="{{ route('home') }}" class="btn-outline-dark">Back to home</a>
                        </div>
                    </div>
                @else
                    <div x-data="quoteWizard({{ $errors->any() ? 3 : 1 }})" x-ref="wizard" class="border border-ink-900/10 bg-white">
                        <div class="border-b border-ink-900/10 px-6 py-6 sm:px-9">
                            <div class="flex items-center justify-between">
                                <p class="eyebrow">Your brief</p>
                                <p class="text-[10px] uppercase tracking-[0.16em] text-ink-900/35">Step <span x-text="step"></span> of 3</p>
                            </div>
                            <div class="mt-4 h-1 w-full bg-ink-900/10">
                                <div class="h-full bg-gold-500 transition-all duration-500" :style="`width: ${progress}%`"></div>
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-2 text-[9px] font-bold uppercase tracking-[0.14em]">
                                <span :class="step >= 1 ? 'text-ink-900' : 'text-ink-900/30'">01 · Project</span>
                                <span class="text-center" :class="step >= 2 ? 'text-ink-900' : 'text-ink-900/30'">02 · Timing</span>
                                <span class="text-right" :class="step >= 3 ? 'text-ink-900' : 'text-ink-900/30'">03 · Contact</span>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="mx-6 mt-6 border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700 sm:mx-9">
                                <p class="font-semibold">Almost there — please review the details below.</p>
                                <ul class="mt-1 list-inside list-disc">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form x-ref="form" action="{{ route('quote.store') }}" method="POST" @submit="submit($event)" class="px-6 py-8 sm:px-9">
                            @csrf
                            <div class="hidden" aria-hidden="true">
                                <label for="quote-website">Website</label>
                                <input type="text" id="quote-website" name="website" tabindex="-1" autocomplete="off">
                            </div>

                            {{-- Step 1 --}}
                            <div x-show="step === 1" x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                <h2 class="font-display text-3xl">What would you like painted?</h2>
                                <p class="mt-2 text-base text-ink-900/70">Choose the service closest to your project. We can refine the scope together.</p>

                                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                    @foreach ($services as $service)
                                        <label class="group flex cursor-pointer items-start gap-3 border border-ink-900/15 p-4 transition-all has-[:checked]:border-gold-500 has-[:checked]:bg-gold-500/[0.06] hover:border-gold-500/60">
                                            <input type="radio" name="service" value="{{ $service->title }}" @checked(old('service') === $service->title) class="mt-1 h-3.5 w-3.5 accent-[#c6a45f]">
                                            <span>
                                                <span class="flex items-center gap-2 text-[12px] font-semibold text-ink-900">
                                                    <x-icon :name="$service->icon" class="h-4 w-4 text-gold-600" />
                                                    {{ $service->title }}
                                                </span>
                                                <span class="mt-1 block text-sm leading-relaxed text-ink-900/65">{{ $service->tagline }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-[11px] text-red-600" x-show="errors.service" x-text="errors.service"></p>

                                <div class="mt-7 grid gap-5 sm:grid-cols-2">
                                    <div>
                                        <label for="property_type" class="label">Property type</label>
                                        <select id="property_type" name="property_type" x-ref="property_type" class="field">
                                            <option value="">Select property type</option>
                                            @foreach (['House', 'Townhouse', 'Apartment', 'Heritage home', 'Commercial space'] as $type)
                                                <option value="{{ $type }}" @selected(old('property_type') === $type)>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-[11px] text-red-600" x-show="errors.property_type" x-text="errors.property_type"></p>
                                    </div>
                                    <div>
                                        <label for="bedrooms" class="label">Bedrooms <span class="normal-case tracking-normal text-ink-900/30">(optional)</span></label>
                                        <select id="bedrooms" name="bedrooms" class="field">
                                            <option value="">Select</option>
                                            @foreach (['1', '2', '3', '4', '5+'] as $bedroom)
                                                <option value="{{ $bedroom }}" @selected(old('bedrooms') === $bedroom)>{{ $bedroom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <label for="suburb" class="label">Suburb</label>
                                    <input id="suburb" name="suburb" type="text" x-ref="suburb" value="{{ old('suburb') }}" autocomplete="address-level2" class="field" placeholder="e.g. Toorak">
                                    <p class="mt-1 text-[11px] text-red-600" x-show="errors.suburb" x-text="errors.suburb"></p>
                                </div>
                            </div>

                            {{-- Step 2 --}}
                            <div x-show="step === 2" x-cloak x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                <h2 class="font-display text-3xl">When are you hoping to start?</h2>
                                <p class="mt-2 text-base text-ink-900/70">A rough timeline helps us plan realistically. Things can always change.</p>

                                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                    @foreach ([
                                        ['value' => 'As soon as possible', 'text' => 'We have availability in the coming weeks.'],
                                        ['value' => 'Within 1–3 months', 'text' => 'Planning and comparing quotes.'],
                                        ['value' => 'In 3–6 months', 'text' => 'A larger renovation or future move.'],
                                        ['value' => 'Just exploring', 'text' => 'Gathering ideas and budget guidance.'],
                                    ] as $timeline)
                                        <label class="flex cursor-pointer flex-col border border-ink-900/15 p-4 transition-all has-[:checked]:border-gold-500 has-[:checked]:bg-gold-500/[0.06] hover:border-gold-500/60">
                                            <span class="flex items-center gap-2 text-[12px] font-semibold text-ink-900">
                                                <input type="radio" name="timeline" value="{{ $timeline['value'] }}" @checked(old('timeline') === $timeline['value']) class="h-3.5 w-3.5 accent-[#c6a45f]">
                                                {{ $timeline['value'] }}
                                            </span>
                                            <span class="mt-1 pl-5 text-sm leading-relaxed text-ink-900/65">{{ $timeline['text'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-[11px] text-red-600" x-show="errors.timeline" x-text="errors.timeline"></p>

                                <div class="mt-7">
                                    <label for="details" class="label">Anything else we should know? <span class="normal-case tracking-normal text-ink-900/30">(optional)</span></label>
                                    <textarea id="details" name="details" rows="5" class="field" placeholder="Number of rooms, ceiling heights, current condition, access, colour ideas…">{{ old('details') }}</textarea>
                                </div>

                                <div class="mt-6 flex items-start gap-3 bg-cream-100 p-4">
                                    <x-icon name="palette" class="mt-0.5 h-5 w-5 shrink-0 text-gold-600" />
                                    <p class="text-sm leading-relaxed text-ink-900/70">Not sure about colour? Every quote includes a complimentary colour consultation with our founder, Daniel.</p>
                                </div>
                            </div>

                            {{-- Step 3 --}}
                            <div x-show="step === 3" x-cloak x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                <h2 class="font-display text-3xl">Where should we send your quote?</h2>
                                <p class="mt-2 text-base text-ink-900/70">We’ll be in touch within one business day. Your details stay with our studio.</p>

                                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                                    <div>
                                        <label for="name" class="label">Full name</label>
                                        <input id="name" name="name" type="text" x-ref="name" value="{{ old('name') }}" autocomplete="name" class="field" placeholder="Jane Smith">
                                        <p class="mt-1 text-[11px] text-red-600" x-show="errors.name" x-text="errors.name"></p>
                                    </div>
                                    <div>
                                        <label for="phone" class="label">Phone</label>
                                        <input id="phone" name="phone" type="tel" x-ref="phone" value="{{ old('phone') }}" autocomplete="tel" class="field" placeholder="0400 000 000">
                                        <p class="mt-1 text-[11px] text-red-600" x-show="errors.phone" x-text="errors.phone"></p>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <label for="email" class="label">Email</label>
                                    <input id="email" name="email" type="email" x-ref="email" value="{{ old('email') }}" autocomplete="email" class="field" placeholder="jane@email.com">
                                    <p class="mt-1 text-[11px] text-red-600" x-show="errors.email" x-text="errors.email"></p>
                                </div>

                                <label class="mt-5 flex items-start gap-3 text-sm leading-relaxed text-ink-900/70">
                                    <input type="checkbox" name="consent" required class="mt-0.5 h-3.5 w-3.5 accent-[#c6a45f]">
                                    I agree to Lion Painters contacting me about this enquiry and to the <a href="{{ route('privacy') }}" class="text-gold-700 underline decoration-gold-500/40 underline-offset-2">privacy policy</a>.
                                </label>
                            </div>

                            <div class="mt-8 flex items-center justify-between border-t border-ink-900/10 pt-6">
                                <button type="button" x-show="step > 1" x-cloak @click="previous()" class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.18em] text-ink-900/50 transition-colors hover:text-ink-900">
                                    <x-icon name="arrow-left" class="h-4 w-4" /> Back
                                </button>
                                <span x-show="step === 1" class="text-[10px] uppercase tracking-[0.16em] text-ink-900/30">Takes about 2 minutes</span>

                                <button type="button" x-show="step < 3" @click="next()" class="btn-dark">
                                    Continue <x-icon name="arrow-right" class="h-4 w-4" />
                                </button>
                                <button type="submit" x-show="step === 3" x-cloak class="btn-gold">
                                    Send my request <x-icon name="arrow-right" class="h-4 w-4" />
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="bg-ink-950 p-7 text-cream-50">
                    <p class="eyebrow-light">What happens next</p>
                    <h2 class="mt-3 font-display text-3xl">From brief to brushstroke.</h2>
                    <ol class="mt-6 space-y-5">
                        @foreach ([
                            ['n' => '01', 'title' => 'We review your brief', 'text' => 'Daniel reads every enquiry personally.'],
                            ['n' => '02', 'title' => 'A short phone call', 'text' => 'We clarify scope, access and timing.'],
                            ['n' => '03', 'title' => 'On-site inspection', 'text' => 'We measure, assess surfaces and discuss colour.'],
                            ['n' => '04', 'title' => 'Fixed-price quote', 'text' => 'A clear, written proposal — no hidden extras.'],
                        ] as $step)
                            <li class="flex gap-4">
                                <span class="font-display text-xl text-gold-500/70">{{ $step['n'] }}</span>
                                <div class="border-b border-cream-50/10 pb-4">
                                    <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-cream-50/85">{{ $step['title'] }}</p>
                                    <p class="mt-1 text-sm leading-relaxed text-cream-50/70">{{ $step['text'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="border border-ink-900/10 bg-white/60 p-7">
                    <p class="eyebrow">Rather talk it through?</p>
                    <p class="mt-3 text-base leading-relaxed text-ink-900/75">Call the studio and speak with a painter — not a call centre.</p>
                    <a href="tel:{{ setting('phone_href') }}" class="mt-4 flex items-center gap-3 text-lg font-semibold text-ink-900 hover:text-gold-700">
                        <x-icon name="phone" class="h-5 w-5 text-gold-600" /> {{ setting('phone') }}
                    </a>
                    <p class="mt-2 text-[10px] uppercase tracking-[0.14em] text-ink-900/35">{{ setting('hours') }}</p>
                </div>

                <div class="flex items-center gap-4 border border-gold-500/30 bg-gold-500/5 p-5">
                    <x-icon name="shield" class="h-8 w-8 shrink-0 text-gold-600" />
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.12em]">No pressure, no obligation</p>
                        <p class="mt-1 text-sm leading-relaxed text-ink-900/65">Quotes are free and remain valid for 30 days.</p>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</x-layout>
