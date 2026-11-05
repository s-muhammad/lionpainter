@php
    $items = [['label' => 'Contact']];
@endphp

<x-layout
    title="Contact Lion Painters | Melbourne Painters"
    description="Get in touch with Lion Painters in Cremorne, Melbourne. Call 03 9123 4567, email the studio, or send a message about your interior or exterior painting project."
    :breadcrumbs="$items"
>
    <x-page-hero
        eyebrow="Contact"
        title="Let’s talk about your home."
        text="Whether you have a clear brief or just a feeling for how a room should look, we’d love to hear about it. Expect a thoughtful reply within one business day."
        :items="$items"
    >
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="tel:{{ setting('phone_href') }}" class="btn-gold"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>
            <a href="mailto:{{ setting('email') }}" class="btn-outline"><x-icon name="mail" class="h-4 w-4" /> Email us</a>
        </div>
    </x-page-hero>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell">
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['icon' => 'phone', 'label' => 'Call the studio', 'value' => setting('phone'), 'href' => 'tel:'.setting('phone_href')],
                    ['icon' => 'mail', 'label' => 'Email us', 'value' => setting('email'), 'href' => 'mailto:'.setting('email')],
                    ['icon' => 'pin', 'label' => 'Visit', 'value' => setting('address')],
                    ['icon' => 'clock', 'label' => 'Studio hours', 'value' => setting('hours')],
                ] as $card)
                    <div class="border border-ink-900/10 bg-white/50 p-6">
                        <span class="flex h-10 w-10 items-center justify-center border border-gold-500/30 text-gold-600">
                            <x-icon :name="$card['icon']" class="h-4 w-4" />
                        </span>
                        <p class="mt-5 text-[9px] font-bold uppercase tracking-[0.2em] text-ink-900/40">{{ $card['label'] }}</p>
                        @if (! empty($card['href']))
                            <a href="{{ $card['href'] }}" class="mt-1.5 block text-sm font-medium text-ink-900 transition-colors hover:text-gold-700">{{ $card['value'] }}</a>
                        @else
                            <p class="mt-1.5 text-sm font-medium leading-relaxed text-ink-900">{{ $card['value'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-12 grid gap-10 lg:grid-cols-[1.25fr_0.75fr] lg:gap-16">
                <div class="border border-ink-900/10 bg-white/60 p-7 sm:p-10">
                    <p class="eyebrow">Send a message</p>
                    <h2 class="mt-3 font-display text-3xl sm:text-4xl">Tell us about your project.</h2>
                    <p class="mt-2 text-base text-ink-900/70">If you know your suburb and the service you need, we can reply with useful next steps.</p>

                    @if ($errors->any())
                        <div class="mt-6 border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700">
                            Please check the highlighted fields and try again.
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="mt-7 space-y-5">
                        @csrf
                        <div class="hidden" aria-hidden="true">
                            <label for="contact-website">Website</label>
                            <input type="text" id="contact-website" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="name" class="label">Full name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" class="field @error('name') border-red-400 @enderror" placeholder="Jane Smith">
                                @error('name') <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="label">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="field @error('email') border-red-400 @enderror" placeholder="jane@email.com">
                                @error('email') <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="phone" class="label">Phone <span class="normal-case tracking-normal text-ink-900/30">(optional)</span></label>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="field @error('phone') border-red-400 @enderror" placeholder="0400 000 000">
                            </div>
                            <div>
                                <label for="suburb" class="label">Suburb <span class="normal-case tracking-normal text-ink-900/30">(optional)</span></label>
                                <input id="suburb" name="suburb" type="text" value="{{ old('suburb') }}" autocomplete="address-level2" class="field" placeholder="Toorak">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="label">How can we help?</label>
                            <select id="subject" name="subject" class="field">
                                <option value="">Select a topic</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->title }}" @selected(old('subject') === $service->title)>{{ $service->title }}</option>
                                @endforeach
                                <option value="General enquiry" @selected(old('subject') === 'General enquiry')>General enquiry</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="label">Your message</label>
                            <textarea id="message" name="message" rows="6" required class="field @error('message') border-red-400 @enderror" placeholder="A little about your home, timeline and what you’d like painted…">{{ old('message') }}</textarea>
                            @error('message') <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                            <p class="max-w-xs text-sm leading-relaxed text-ink-900/60">By submitting you agree to our <a href="{{ route('privacy') }}" class="underline decoration-gold-500/40 underline-offset-2 hover:text-gold-700">privacy policy</a>.</p>
                            <button type="submit" class="btn-gold">Send message <x-icon name="arrow-right" class="h-4 w-4" /></button>
                        </div>
                    </form>
                </div>

                <aside class="space-y-6">
                    <div class="bg-ink-950 p-7 text-cream-50">
                        <p class="eyebrow-light">Prefer a conversation?</p>
                        <h2 class="mt-3 font-display text-3xl">We’re on the tools, but always happy to talk.</h2>
                        <p class="mt-3 text-sm leading-relaxed text-cream-50/65">
                            Call the studio between 7am and 5pm, Monday to Friday. If we’re on site, leave a message and we’ll return your call the same day.
                        </p>
                        <a href="tel:{{ setting('phone_href') }}" class="btn-gold mt-6 w-full">
                            <x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}
                        </a>
                        <p class="mt-4 text-center text-[9px] uppercase tracking-[0.16em] text-cream-50/30">{{ setting('hours') }}</p>
                    </div>

                    <div class="border border-ink-900/10 bg-white/60 p-7">
                        <p class="eyebrow">Service areas</p>
                        <p class="mt-3 text-sm leading-relaxed text-ink-900/70">We paint across {{ setting('service_area') }}.</p>
                        <ul class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-[11px] text-ink-900/60">
                            @foreach (array_slice(config('site.suburbs'), 0, 6) as $suburb)
                                <li class="flex items-center gap-2">
                                    <span class="h-px w-2 bg-gold-500/60"></span>{{ $suburb }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('service-areas') }}" class="link-underline mt-5">All service areas</a>
                    </div>

                    <div class="flex items-center gap-4 border border-gold-500/30 bg-gold-500/5 p-5">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gold-500/15 text-gold-700">
                            <x-icon name="award" class="h-5 w-5" />
                        </span>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.12em]">Rated {{ setting('rating') }} / 5</p>
                            <p class="mt-0.5 text-[10px] uppercase tracking-[0.12em] text-ink-900/45">Across {{ setting('review_count') }} Melbourne reviews</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <x-quote-cta heading="Prefer a fixed-price quote?" text="Share a few details through our quote form and we’ll prepare a detailed proposal." />
</x-layout>
