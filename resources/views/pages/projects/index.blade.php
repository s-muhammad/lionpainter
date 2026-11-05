@php
    $items = [['label' => 'Portfolio']];
@endphp

<x-layout
    title="Painting Portfolio Melbourne | Lion Painters"
    description="Browse recent interior, exterior, heritage and commercial painting projects by Lion Painters across Toorak, Brighton, Fitzroy, Hawthorn and greater Melbourne."
    :breadcrumbs="$items"
>
    <x-page-hero
        eyebrow="Selected work"
        title="A portfolio of considered finishes."
        text="Every project tells the story of a home — its light, its architecture and the people who live in it. Here is a small selection of our recent work."
        :items="$items"
    >
        <div class="mt-8 flex flex-wrap items-center gap-x-8 gap-y-4 text-[10px] uppercase tracking-[0.18em] text-cream-50/40">
            <span class="flex items-center gap-2"><x-icon name="home" class="h-4 w-4 text-gold-400" /> Residential</span>
            <span class="flex items-center gap-2"><x-icon name="building" class="h-4 w-4 text-gold-400" /> Commercial</span>
            <span class="flex items-center gap-2"><x-icon name="sun" class="h-4 w-4 text-gold-400" /> Exterior</span>
            <span class="flex items-center gap-2"><x-icon name="layers" class="h-4 w-4 text-gold-400" /> Heritage</span>
        </div>
    </x-page-hero>

    <section class="bg-cream-50 py-16 lg:py-24">
        <div class="shell" x-data="portfolioFilter(@js($activeCategory ?: 'All'))">
            <div class="flex flex-wrap gap-2 border-b border-ink-900/10 pb-6">
                @foreach (collect(['All'])->merge($categories) as $category)
                    <button
                        type="button"
                        @click="choose(@js($category))"
                        class="border px-5 py-2.5 text-[10px] font-bold uppercase tracking-[0.16em] transition-all duration-300"
                        :class="active === @js($category) ? 'border-ink-900 bg-ink-900 text-cream-50' : 'border-ink-900/15 text-ink-900/60 hover:border-gold-500 hover:text-gold-700'"
                    >
                        {{ $category }}
                    </button>
                @endforeach
            </div>

            <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    <x-project-card :project="$project" filterable />
                @endforeach
            </div>
        </div>
    </section>

    <x-quote-cta heading="Your home could be next." text="Tell us what you have in mind and we’ll prepare a detailed, fixed-price proposal." />
</x-layout>
