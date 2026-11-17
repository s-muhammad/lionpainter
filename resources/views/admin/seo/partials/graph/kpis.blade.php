@php
    $cards = [
        [
            'label' => 'کلمات در گراف',
            'value' => number_format($graphStats['keywords']),
            'hint' => 'گره در این نمایش',
            'icon' => 'network',
            'tone' => 'bg-gold-500/15 text-gold-700',
        ],
        [
            'label' => 'ارتباط متنی',
            'value' => number_format($graphStats['links']),
            'hint' => 'پیوند بین کلمات',
            'icon' => 'layers',
            'tone' => 'bg-violet-50 text-violet-600',
        ],
        [
            'label' => 'اولویت بالا',
            'value' => number_format($graphStats['priority']),
            'hint' => 'از '.number_format($graphStats['keywords']).' کلمه',
            'icon' => 'star',
            'tone' => 'bg-red-50 text-red-600',
        ],
        [
            'label' => 'منتشرشده',
            'value' => number_format($graphStats['published']),
            'hint' => 'در سایت منتشر شده‌اند',
            'icon' => 'check-circle',
            'tone' => 'bg-emerald-50 text-emerald-600',
        ],
    ];
@endphp

<div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
    @foreach ($cards as $card)
        <article class="admin-card flex items-center gap-4 p-4">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $card['tone'] }}">
                <x-icon :name="$card['icon']" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <p class="text-[10px] font-medium text-slate-400">{{ $card['label'] }}</p>
                <p class="mt-0.5 text-[20px] font-bold leading-tight text-slate-800">{{ $card['value'] }}</p>
                <p class="mt-0.5 text-[10px] text-slate-400">{{ $card['hint'] }}</p>
            </div>
        </article>
    @endforeach
</div>
