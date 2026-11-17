@php
    $cards = [
        [
            'label' => 'کل کلیدواژه‌ها',
            'value' => number_format($stats['keywords_total']),
            'hint' => 'در دفترچه ثبت شده',
            'icon' => 'search',
            'tone' => 'bg-gold-500/15 text-gold-700',
        ],
        [
            'label' => 'کلیدواژه‌های منتشرشده',
            'value' => number_format($stats['keywords_published']),
            'hint' => 'در سایت منتشر شده‌اند',
            'icon' => 'check-circle',
            'tone' => 'bg-emerald-50 text-emerald-600',
        ],
        [
            'label' => 'کارهای انجام‌شده',
            'value' => number_format($stats['tasks_done']).' / '.number_format($stats['tasks_total']),
            'hint' => 'از کل کارهای سئو',
            'icon' => 'clock-check',
            'tone' => 'bg-sky-50 text-sky-600',
        ],
        [
            'label' => 'کارهای عقب‌افتاده',
            'value' => number_format($stats['tasks_overdue']),
            'hint' => $stats['tasks_overdue'] > 0 ? 'نیازمند رسیدگی' : 'همه کارها به‌موقع‌اند',
            'icon' => 'clock',
            'tone' => $stats['tasks_overdue'] > 0 ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-500',
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
