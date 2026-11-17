<nav class="flex flex-wrap items-center gap-1 rounded-xl border border-slate-200 bg-white p-1" aria-label="بخش‌های دفترچه سئو">
    <a
        href="{{ route('admin.seo.index', array_merge(request()->except('tab'), ['tab' => 'keywords'])) }}"
        @if ($tab === 'keywords') aria-current="page" @endif
        class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-[11px] font-medium transition-colors {{ $tab === 'keywords' ? 'bg-ink-900 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}"
    >
        <x-icon name="search" class="h-3.5 w-3.5" />
        کلیدواژه‌ها
        <span class="rounded-full px-1.5 py-0.5 text-[9px] {{ $tab === 'keywords' ? 'bg-white/15 text-gold-300' : 'bg-slate-100 text-slate-500' }}">{{ number_format($stats['keywords_total']) }}</span>
    </a>
    <a
        href="{{ route('admin.seo.index', array_merge(request()->except('tab'), ['tab' => 'tasks'])) }}"
        @if ($tab === 'tasks') aria-current="page" @endif
        class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-[11px] font-medium transition-colors {{ $tab === 'tasks' ? 'bg-ink-900 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}"
    >
        <x-icon name="check-circle" class="h-3.5 w-3.5" />
        کارها
        <span class="rounded-full px-1.5 py-0.5 text-[9px] {{ $tab === 'tasks' ? 'bg-white/15 text-gold-300' : 'bg-slate-100 text-slate-500' }}">{{ number_format($stats['tasks_total']) }}</span>
    </a>
    <a
        href="{{ route('admin.seo.index', array_merge(request()->except('tab'), ['tab' => 'graph'])) }}"
        @if ($tab === 'graph') aria-current="page" @endif
        class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-[11px] font-medium transition-colors {{ $tab === 'graph' ? 'bg-ink-900 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}"
    >
        <x-icon name="network" class="h-3.5 w-3.5" />
        گراف
        <span class="rounded-full px-1.5 py-0.5 text-[9px] {{ $tab === 'graph' ? 'bg-white/15 text-gold-300' : 'bg-slate-100 text-slate-500' }}">{{ number_format($stats['keywords_total']) }}</span>
    </a>
</nav>
