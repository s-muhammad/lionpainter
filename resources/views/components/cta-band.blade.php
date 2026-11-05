@props(['eyebrow' => 'Ready when you are', 'heading' => null, 'text' => null, 'label' => 'Request a quote'])

<section class="bg-cream-100">
    <div class="shell py-14 lg:py-20">
        <div class="relative overflow-hidden bg-ink-900 px-7 py-12 text-cream-50 sm:px-12 lg:px-16">
            <div class="pointer-events-none absolute inset-0 bg-grain opacity-50"></div>
            <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full border border-gold-500/20"></div>
            <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full border border-gold-500/15"></div>
            <div class="relative flex flex-col items-start justify-between gap-8 lg:flex-row lg:items-center">
                <div>
                    <p class="eyebrow-light">{{ $eyebrow }}</p>
                    <h2 class="mt-4 max-w-xl font-display text-4xl leading-tight sm:text-5xl">{{ $heading ?? 'A beautiful finish starts with a conversation.' }}</h2>
                    @if ($text)
                        <p class="mt-4 max-w-lg text-sm leading-relaxed text-cream-50/70">{{ $text }}</p>
                    @endif
                </div>
                <div class="flex shrink-0 flex-col gap-3 sm:flex-row">
                    <a href="{{ route('quote') }}" class="btn-gold">{{ $label }} <x-icon name="arrow-right" class="h-4 w-4" /></a>
                    <a href="tel:{{ setting('phone_href') }}" class="btn-outline"><x-icon name="phone" class="h-4 w-4" /> {{ setting('phone') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>
