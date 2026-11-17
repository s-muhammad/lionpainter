@php
    use App\Enums\KeywordStatus;

    $groups = [
        KeywordStatus::Planned->value => [
            'label' => KeywordStatus::Planned->label(),
            'dot' => 'bg-sky-500',
            'empty' => 'هنوز کلیدواژه‌ای برای برنامه‌ریزی انتخاب نشده است.',
        ],
        KeywordStatus::InProgress->value => [
            'label' => KeywordStatus::InProgress->label(),
            'dot' => 'bg-violet-500',
            'empty' => 'هیچ کلیدواژه‌ای در حال انجام نیست.',
        ],
    ];
@endphp

<div class="space-y-4">
    <p class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-[10px] leading-relaxed text-slate-400">
        این نما فقط کلیدواژه‌های <span class="font-medium text-sky-600">برنامه‌ریزی‌شده</span> و <span class="font-medium text-violet-600">در حال انجام</span> را نشان می‌دهد؛ ابتدا اولویت و سپس تاریخ هدف.
    </p>

    @foreach ($groups as $statusValue => $groupMeta)
        @php
            $group = $planGroups[$statusValue] ?? collect();
        @endphp
        <section class="admin-card overflow-hidden">
            <header class="flex items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-3">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full {{ $groupMeta['dot'] }}"></span>
                    <h3 class="text-[12px] font-bold text-slate-700">{{ $groupMeta['label'] }}</h3>
                </div>
                <span class="rounded-full bg-white px-2.5 py-1 text-[9px] font-medium text-slate-500 ring-1 ring-inset ring-slate-200">{{ number_format($group->count()) }} کلیدواژه</span>
            </header>

            @forelse ($group as $keyword)
                @php
                    $url = $keyword->target_url;
                    $href = $url
                        ? (\Illuminate\Support\Str::startsWith($url, ['http://', 'https://']) ? $url : url($url))
                        : null;
                @endphp
                <article class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 last:border-0 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="text-[12px] font-semibold text-slate-700">{{ $keyword->keyword }}</h4>
                            @include('admin.seo.partials.priority-badge', ['priority' => $keyword->priority])
                        </div>
                        <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-[10px] text-slate-400">
                            @if (filled($keyword->cluster))
                                <span>خوشه: {{ $keyword->cluster }}</span>
                            @endif
                            <span>قصد: {{ $keyword->intent->label() }}</span>
                            <span>محتوا: {{ $keyword->content_type->label() }}</span>
                            <span>تاریخ هدف: {{ $keyword->target_date?->locale('fa')->translatedFormat('j M Y') ?? '—' }}</span>
                            @if ($keyword->current_position)
                                <span>جایگاه: <span dir="ltr">#{{ $keyword->current_position }}</span></span>
                            @endif
                        </div>
                        @if ($href)
                            <a href="{{ $href }}" target="_blank" rel="noopener" class="mt-1.5 inline-block max-w-full truncate text-[10px] text-slate-500 underline decoration-slate-200 underline-offset-2 hover:text-gold-700" dir="ltr" title="{{ $url }}">{{ $url }}</a>
                        @endif
                        @if (filled($keyword->notes))
                            <p class="mt-1.5 max-w-2xl text-[10px] leading-relaxed text-slate-400">{{ \Illuminate\Support\Str::limit($keyword->notes, 180) }}</p>
                        @endif
                    </div>

                    <div class="flex shrink-0 items-center gap-2 sm:pt-0.5">
                        <div class="w-36">
                            @include('admin.seo.partials.keywords.status-form', ['keyword' => $keyword])
                        </div>
                        <button
                            type="button"
                            x-on:click="$dispatch('edit-keyword', {{ Js::from($keyword->toFormArray()) }})"
                            class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-[10px] font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-sky-600"
                            aria-label="ویرایش {{ $keyword->keyword }}"
                        >
                            <x-icon name="edit" class="h-3.5 w-3.5" />
                            ویرایش
                        </button>
                    </div>
                </article>
            @empty
                <div class="px-5 py-10 text-center">
                    <x-icon name="layers" class="mx-auto h-6 w-6 text-slate-300" />
                    <p class="mt-2 text-[11px] text-slate-400">{{ $groupMeta['empty'] }}</p>
                </div>
            @endforelse
        </section>
    @endforeach
</div>
