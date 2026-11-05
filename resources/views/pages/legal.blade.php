@php
    $isPrivacy = $page === 'privacy';
    $title = $isPrivacy ? 'Privacy Policy' : 'Terms & Conditions';
    $items = [['label' => $title]];
    $updated = 'November 2026';

    $privacy = [
        ['h' => 'What we collect', 'p' => 'We collect the information you choose to share with us when you request a quote, send a message or call the studio. This typically includes your name, email address, phone number, suburb and details about your painting project.'],
        ['h' => 'Why we collect it', 'p' => 'Your information is used to respond to your enquiry, prepare a quote, schedule work and keep you informed about your project. We may occasionally send studio notes if you have opted in — you can unsubscribe at any time.'],
        ['h' => 'How we store it', 'p' => 'Enquiries are stored securely in our website database and email system, accessible only to authorised Lion Painters staff. We keep project records for the period required to honour our workmanship guarantee and meet our legal obligations.'],
        ['h' => 'Sharing', 'p' => 'We never sell or rent your personal information. We only share details with trusted suppliers where it is necessary to deliver your project — for example, a paint supplier or a specialist tradesperson — and only to the extent required.'],
        ['h' => 'Cookies & analytics', 'p' => 'This website uses essential cookies to operate forms and sessions. If analytics are enabled, they are configured to collect aggregated, non-identifying information about site usage. You can disable cookies in your browser settings.'],
        ['h' => 'Your rights', 'p' => 'You may request access to, correction of, or deletion of the personal information we hold about you. To make a request, email us at '.setting('email').' and we will respond within a reasonable timeframe.'],
    ];

    $terms = [
        ['h' => 'Quotes & acceptance', 'p' => 'Quotes are prepared following an inspection of the site and are valid for 30 days unless stated otherwise. A quote may be adjusted if the scope changes, previously hidden damage is discovered, or access conditions differ from those agreed.'],
        ['h' => 'Deposits & payment', 'p' => 'A deposit may be required to secure your booking and order materials. Progress payments are scheduled according to the size of the project, with the balance due on completion. Payment terms are set out in your quote and invoice.'],
        ['h' => 'Scope of work', 'p' => 'The scope of work, products and number of coats are documented in your quote. Any additional work requested during the project will be quoted separately and agreed in writing before it proceeds.'],
        ['h' => 'Access & site conditions', 'p' => 'We ask that reasonable access is provided, valuables are secured, and furniture is cleared where agreed. We protect floors and furnishings as part of our service. Delays caused by weather, unsafe access or other trades may affect the schedule.'],
        ['h' => 'Workmanship guarantee', 'p' => 'We provide a 10-year workmanship guarantee on our preparation and application, subject to normal wear, correct cleaning and maintenance. Paint manufacturer warranties apply separately to the products used.'],
        ['h' => 'Variations & colour', 'p' => 'Colour and sheen selections are confirmed in writing before work begins. Slight variation between batches, screens and physical samples is normal. We recommend reviewing a physical sample in the actual light of your space.'],
        ['h' => 'Cancellations', 'p' => 'Please give us as much notice as possible if your plans change. Cancellations within 48 hours of a scheduled start may incur a fee to cover materials and reserved crew time.'],
    ];

    $sections = $isPrivacy ? $privacy : $terms;
@endphp

<x-layout
    :title="$title.' | Lion Painters'"
    :description="$isPrivacy ? 'How Lion Painters collects, uses and protects your personal information.' : 'The terms under which Lion Painters provides painting services and quotes in Melbourne.'"
    :breadcrumbs="$items"
    robots="noindex,follow"
>
    <x-page-hero
        eyebrow="Legal"
        :title="$title"
        :text="$isPrivacy ? 'Your trust matters to us. This policy explains what we collect, why we collect it and how we keep it safe.' : 'Clear expectations, in writing. These terms apply to painting services provided by Lion Painters in Melbourne.'"
        :items="$items"
    />

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell grid gap-12 lg:grid-cols-[0.35fr_1fr] lg:gap-20">
            <aside>
                <p class="text-[10px] uppercase tracking-[0.18em] text-ink-900/40">Last updated</p>
                <p class="mt-2 text-sm font-medium">{{ $updated }}</p>
                <div class="mt-6 border-t border-ink-900/10 pt-6">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-ink-900/40">Questions?</p>
                    <p class="mt-2 text-base leading-relaxed text-ink-900/75">Write to us at <a href="mailto:{{ setting('email') }}" class="text-gold-700 underline decoration-gold-500/40 underline-offset-4">{{ setting('email') }}</a>.</p>
                </div>
                <div class="mt-6 flex gap-4 text-[11px]">
                    <a href="{{ route('privacy') }}" class="{{ $isPrivacy ? 'font-semibold text-ink-900' : 'text-ink-900/45 hover:text-gold-700' }}">Privacy</a>
                    <a href="{{ route('terms') }}" class="{{ ! $isPrivacy ? 'font-semibold text-ink-900' : 'text-ink-900/45 hover:text-gold-700' }}">Terms</a>
                </div>
            </aside>

            <div class="max-w-3xl">
                @foreach ($sections as $section)
                    <div class="mb-10 border-b border-ink-900/10 pb-8 last:border-0">
                        <h2 class="font-display text-3xl">{{ $section['h'] }}</h2>
                        <p class="mt-3 text-base leading-[1.9] text-ink-900/80">{{ $section['p'] }}</p>
                    </div>
                @endforeach
                <p class="text-[11px] leading-relaxed text-ink-900/35">
                    This information is general in nature and does not constitute legal advice. For matters specific to your project, please contact us directly.
                </p>
            </div>
        </div>
    </section>
</x-layout>
