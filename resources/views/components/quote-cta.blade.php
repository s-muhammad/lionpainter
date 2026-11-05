@props(['heading' => null, 'text' => null, 'compact' => true])

<section class="relative overflow-hidden bg-ink-950 text-cream-50">
    <div class="pointer-events-none absolute inset-0 bg-grain opacity-60"></div>
    <div class="pointer-events-none absolute -left-32 top-0 h-[420px] w-[420px] rounded-full bg-gold-500/10 blur-[120px]"></div>
    <div class="pointer-events-none absolute -right-40 bottom-0 font-display text-[26rem] leading-none text-cream-50/[0.025]">L</div>

    <div class="shell relative grid gap-12 py-16 lg:grid-cols-[1fr_1.05fr] lg:items-center lg:gap-20 lg:py-24">
        <div>
            <p class="eyebrow-light">Begin your project</p>
            <h2 class="mt-5 max-w-lg font-display text-4xl leading-[1.06] sm:text-5xl">
                {{ $heading ?? setting('cta_heading') }}
            </h2>
            <p class="mt-5 max-w-md text-sm leading-relaxed text-cream-50/70">
                {{ $text ?? setting('cta_text') }}
            </p>

            <div class="mt-9 space-y-4">
                <a href="tel:{{ setting('phone_href') }}" class="flex items-center gap-3 text-cream-50/80 transition-colors hover:text-gold-300">
                    <span class="flex h-10 w-10 items-center justify-center border border-gold-500/30 text-gold-400"><x-icon name="phone" class="h-4 w-4" /></span>
                    <span>
                        <span class="block text-[9px] uppercase tracking-[0.2em] text-cream-50/35">Speak with us</span>
                        <span class="mt-0.5 block text-sm font-medium">{{ setting('phone') }}</span>
                    </span>
                </a>
                <a href="mailto:{{ setting('email') }}" class="flex items-center gap-3 text-cream-50/80 transition-colors hover:text-gold-300">
                    <span class="flex h-10 w-10 items-center justify-center border border-gold-500/30 text-gold-400"><x-icon name="mail" class="h-4 w-4" /></span>
                    <span>
                        <span class="block text-[9px] uppercase tracking-[0.2em] text-cream-50/35">Email the studio</span>
                        <span class="mt-0.5 block text-sm font-medium">{{ setting('email') }}</span>
                    </span>
                </a>
            </div>

            <div class="mt-9 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-cream-50/10 pt-6 text-[10px] uppercase tracking-[0.14em] text-cream-50/35">
                <span class="flex items-center gap-2"><x-icon name="clock" class="h-3.5 w-3.5 text-gold-500/70" /> Reply within 1 business day</span>
                <span class="flex items-center gap-2"><x-icon name="check-circle" class="h-3.5 w-3.5 text-gold-500/70" /> Fixed-price quote</span>
                <span class="flex items-center gap-2"><x-icon name="palette" class="h-3.5 w-3.5 text-gold-500/70" /> Free colour consultation</span>
            </div>
        </div>

        <div class="relative">
            <div class="absolute -inset-3 border border-gold-500/20"></div>
            <div class="relative bg-cream-50 p-6 text-ink-900 sm:p-9">
                <h3 class="font-display text-3xl">Request your free quote</h3>
                <p class="mt-2 text-sm leading-relaxed text-ink-900/70">A few details are all we need to prepare an accurate, fixed-price proposal.</p>

                @if ($errors->any())
                    <div class="mt-5 border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700">
                        Please check the highlighted fields and try again.
                    </div>
                @endif

                <form action="{{ route('quote.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div class="hidden" aria-hidden="true">
                        <label for="cta-website">Website</label>
                        <input type="text" id="cta-website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="cta-name" class="label">Full name</label>
                            <input id="cta-name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name" class="field" placeholder="Jane Smith">
                            @error('name') <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="cta-phone" class="label">Phone</label>
                            <input id="cta-phone" name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel" class="field" placeholder="0400 000 000">
                            @error('phone') <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="cta-email" class="label">Email</label>
                            <input id="cta-email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="field" placeholder="jane@email.com">
                            @error('email') <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="cta-suburb" class="label">Suburb</label>
                            <input id="cta-suburb" name="suburb" type="text" value="{{ old('suburb') }}" required autocomplete="address-level2" class="field" placeholder="Toorak">
                            @error('suburb') <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="cta-service" class="label">Service</label>
                        <select id="cta-service" name="service" required class="field">
                            <option value="">Select a service</option>
                            @foreach ($navServices ?? [] as $service)
                                <option value="{{ $service->title }}" @selected(old('service') === $service->title)>{{ $service->title }}</option>
                            @endforeach
                        </select>
                        @error('service') <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="cta-details" class="label">Tell us about the project <span class="normal-case tracking-normal text-ink-900/30">(optional)</span></label>
                        <textarea id="cta-details" name="details" rows="3" class="field" placeholder="Interior repaint of a 3-bedroom home, ready in spring…">{{ old('details') }}</textarea>
                    </div>

                    <button type="submit" class="btn-gold w-full">
                        Send request
                        <x-icon name="arrow-right" class="h-4 w-4" />
                    </button>
                    <p class="text-center text-[10px] leading-relaxed text-ink-900/35">
                        By submitting you agree to our <a href="{{ route('privacy') }}" class="underline decoration-gold-500/40 underline-offset-2 hover:text-gold-700">privacy policy</a>. We never share your details.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
