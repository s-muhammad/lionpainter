<footer class="relative overflow-hidden bg-ink-950 text-cream-50">
    <div class="h-px w-full bg-gradient-to-r from-transparent via-gold-500/60 to-transparent"></div>
    <div class="pointer-events-none absolute -right-20 top-10 select-none font-display text-[22rem] leading-none text-cream-50/[0.025]">LION</div>

    <div class="shell relative py-16 lg:py-20">
        <div class="grid gap-12 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <x-logo theme="dark" />
                <p class="mt-6 max-w-sm text-sm leading-relaxed text-cream-50/70">
                    Premium interior and exterior painting for Melbourne homes and commercial spaces. Considered preparation, beautiful materials, and a finish made to last.
                </p>
                <div class="mt-6 flex items-center gap-3">
                    @if (setting('instagram'))
                        <a href="{{ setting('instagram') }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="flex h-10 w-10 items-center justify-center border border-cream-50/15 text-cream-50/70 transition-colors hover:border-gold-500 hover:text-gold-400">
                            <x-icon name="instagram" class="h-4 w-4" />
                        </a>
                    @endif
                    @if (setting('facebook'))
                        <a href="{{ setting('facebook') }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="flex h-10 w-10 items-center justify-center border border-cream-50/15 text-cream-50/70 transition-colors hover:border-gold-500 hover:text-gold-400">
                            <x-icon name="facebook" class="h-4 w-4" />
                        </a>
                    @endif
                    <a href="mailto:{{ setting('email') }}" aria-label="Email" class="flex h-10 w-10 items-center justify-center border border-cream-50/15 text-cream-50/70 transition-colors hover:border-gold-500 hover:text-gold-400">
                        <x-icon name="mail" class="h-4 w-4" />
                    </a>
                </div>
            </div>

            <div class="lg:col-span-2">
                <h2 class="text-[10px] font-semibold uppercase tracking-[0.28em] text-gold-400">Services</h2>
                <ul class="mt-5 space-y-3 text-sm text-cream-50/70">
                    @foreach ($navServices->take(6) as $service)
                        <li>
                            <a href="{{ route('services.show', $service) }}" class="transition-colors hover:text-cream-50">{{ $service->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h2 class="text-[10px] font-semibold uppercase tracking-[0.28em] text-gold-400">Studio</h2>
                <ul class="mt-5 space-y-3 text-sm text-cream-50/70">
                    <li><a href="{{ route('about') }}" class="transition-colors hover:text-cream-50">About us</a></li>
                    <li><a href="{{ route('projects.index') }}" class="transition-colors hover:text-cream-50">Portfolio</a></li>
                    <li><a href="{{ route('service-areas') }}" class="transition-colors hover:text-cream-50">Service areas</a></li>
                    <li><a href="{{ route('posts.index') }}" class="transition-colors hover:text-cream-50">Journal</a></li>
                    <li><a href="{{ route('faqs') }}" class="transition-colors hover:text-cream-50">FAQs</a></li>
                    <li><a href="{{ route('contact') }}" class="transition-colors hover:text-cream-50">Contact</a></li>
                    <li><a href="{{ route('quote') }}" class="transition-colors hover:text-cream-50">Request a quote</a></li>
                </ul>
            </div>

            <div class="lg:col-span-4">
                <h2 class="text-[10px] font-semibold uppercase tracking-[0.28em] text-gold-400">Get in touch</h2>
                <ul class="mt-5 space-y-4 text-sm text-cream-50/70">
                    <li class="flex items-start gap-3">
                        <x-icon name="phone" class="mt-0.5 h-4 w-4 shrink-0 text-gold-500" />
                        <a href="tel:{{ setting('phone_href') }}" class="transition-colors hover:text-cream-50">{{ setting('phone') }}</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-icon name="mail" class="mt-0.5 h-4 w-4 shrink-0 text-gold-500" />
                        <a href="mailto:{{ setting('email') }}" class="break-all transition-colors hover:text-cream-50">{{ setting('email') }}</a>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-icon name="pin" class="mt-0.5 h-4 w-4 shrink-0 text-gold-500" />
                        <span>{{ setting('address') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-icon name="clock" class="mt-0.5 h-4 w-4 shrink-0 text-gold-500" />
                        <span>{{ setting('hours') }}</span>
                    </li>
                </ul>
                <a href="{{ route('quote') }}" class="mt-7 inline-flex items-center gap-2 border-b border-gold-500/40 pb-1 text-[10px] font-bold uppercase tracking-[0.2em] text-gold-400 transition-colors hover:border-gold-400 hover:text-gold-300">
                    Start your project
                    <x-icon name="arrow-up-right" class="h-3.5 w-3.5" />
                </a>
            </div>
        </div>
    </div>

    <div class="border-t border-cream-50/10">
        <div class="shell flex flex-col items-start justify-between gap-4 py-6 text-[10px] tracking-[0.08em] text-cream-50/35 sm:flex-row sm:items-center">
            <p>&copy; {{ date('Y') }} {{ setting('brand') }}. All rights reserved. {{ setting('abn') }}</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('privacy') }}" class="transition-colors hover:text-cream-50/70">Privacy</a>
                <a href="{{ route('terms') }}" class="transition-colors hover:text-cream-50/70">Terms</a>
                <a href="{{ route('sitemap') }}" class="transition-colors hover:text-cream-50/70">Sitemap</a>
                <span class="hidden items-center gap-1.5 text-cream-50/25 sm:flex">Made in Melbourne <span class="text-gold-500/60">·</span></span>
            </div>
        </div>
    </div>
</footer>
