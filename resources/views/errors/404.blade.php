@php
    $items = [['label' => 'Page not found']];
@endphp

<x-layout
    title="Page Not Found | Lion Painters"
    description="The page you were looking for could not be found. Explore our painting services or contact Lion Painters."
    robots="noindex,nofollow"
    :breadcrumbs="$items"
>
    <section class="relative flex min-h-[65vh] items-center overflow-hidden bg-ink-950 text-cream-50">
        <div class="pointer-events-none absolute inset-0 bg-grain opacity-50"></div>
        <div class="pointer-events-none absolute -right-20 top-0 select-none font-display text-[30rem] leading-none text-cream-50/[0.03]">404</div>
        <div class="shell relative py-20 text-center">
            <p class="eyebrow-light">404</p>
            <h1 class="mt-4 font-display text-5xl sm:text-6xl">This wall hasn’t been painted yet.</h1>
            <p class="mx-auto mt-5 max-w-md text-sm leading-relaxed text-cream-50/55">
                The page you’re looking for may have moved, or the link might be incorrect. Let’s get you back to something beautiful.
            </p>
            <div class="mt-9 flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}" class="btn-gold">Back to home</a>
                <a href="{{ route('services.index') }}" class="btn-outline">Our services</a>
            </div>
        </div>
    </section>
</x-layout>
