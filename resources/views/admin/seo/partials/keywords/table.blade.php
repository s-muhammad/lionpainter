@php
    $keywordIds = $keywords->getCollection()->pluck('id')->map(fn ($id) => (string) $id)->values()->all();
    $pageCount = $keywords->count();

    $sortLink = function (string $column) use ($filters) {
        $direction = $filters['sort'] === $column && $filters['direction'] === 'asc' ? 'desc' : 'asc';

        return route('admin.seo.index', array_merge(request()->except('k_page'), [
            'tab' => 'keywords',
            'k_sort' => $column,
            'k_dir' => $direction,
        ]));
    };

    $ariaSort = fn (string $column) => $filters['sort'] === $column
        ? ($filters['direction'] === 'asc' ? 'ascending' : 'descending')
        : 'none';

    $sortIndicator = fn (string $column) => $filters['sort'] === $column
        ? ($filters['direction'] === 'asc' ? '↑' : '↓')
        : '';
@endphp

<div x-data="{ selected: [] }" class="space-y-3">
    <form
        id="keyword-bulk-form"
        method="POST"
        action="{{ route('admin.seo.keywords.bulk-status') }}"
        x-show="selected.length > 0"
        x-cloak
        class="flex flex-wrap items-center gap-2 rounded-xl border border-gold-500/30 bg-gold-500/10 px-4 py-2.5"
    >
        @csrf
        @method('PATCH')
        <p class="text-[11px] font-medium text-slate-700"><span x-text="selected.length"></span> کلیدواژه انتخاب شده</p>
        <label for="keyword-bulk-status" class="sr-only">وضعیت جدید</label>
        <select id="keyword-bulk-status" name="status" required class="admin-input w-auto py-1.5 text-[11px]">
            <option value="">تغییر وضعیت…</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="admin-btn-gold py-1.5 text-[11px]">اعمال</button>
    </form>

    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1180px] text-right text-[11px]">
                <caption class="sr-only">فهرست کلیدواژه‌های سئو</caption>
                <thead class="bg-slate-50/70 text-[10px] text-slate-400">
                    <tr>
                        <th scope="col" class="w-10 px-4 py-3">
                            <input
                                type="checkbox"
                                aria-label="انتخاب همه کلیدواژه‌های این صفحه"
                                :checked="selected.length === {{ $pageCount }} && {{ $pageCount }} > 0"
                                @change="$event.target.checked ? selected = {{ Js::from($keywordIds) }} : selected = []"
                                class="h-3.5 w-3.5 rounded border-slate-300 accent-[#c6a45f]"
                            >
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium" aria-sort="{{ $ariaSort('keyword') }}">
                            <a href="{{ $sortLink('keyword') }}" class="inline-flex items-center gap-1 transition-colors hover:text-slate-700">کلیدواژه <span aria-hidden="true" class="text-gold-600">{{ $sortIndicator('keyword') }}</span></a>
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium" aria-sort="{{ $ariaSort('cluster') }}">
                            <a href="{{ $sortLink('cluster') }}" class="inline-flex items-center gap-1 transition-colors hover:text-slate-700">خوشه <span aria-hidden="true" class="text-gold-600">{{ $sortIndicator('cluster') }}</span></a>
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium" aria-sort="{{ $ariaSort('intent') }}">
                            <a href="{{ $sortLink('intent') }}" class="inline-flex items-center gap-1 transition-colors hover:text-slate-700">قصد <span aria-hidden="true" class="text-gold-600">{{ $sortIndicator('intent') }}</span></a>
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium" aria-sort="{{ $ariaSort('content_type') }}">
                            <a href="{{ $sortLink('content_type') }}" class="inline-flex items-center gap-1 transition-colors hover:text-slate-700">نوع محتوا <span aria-hidden="true" class="text-gold-600">{{ $sortIndicator('content_type') }}</span></a>
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium" aria-sort="{{ $ariaSort('target_url') }}">
                            <a href="{{ $sortLink('target_url') }}" class="inline-flex items-center gap-1 transition-colors hover:text-slate-700">آدرس هدف <span aria-hidden="true" class="text-gold-600">{{ $sortIndicator('target_url') }}</span></a>
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium" aria-sort="{{ $ariaSort('priority') }}">
                            <a href="{{ $sortLink('priority') }}" class="inline-flex items-center gap-1 transition-colors hover:text-slate-700">اولویت <span aria-hidden="true" class="text-gold-600">{{ $sortIndicator('priority') }}</span></a>
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium" aria-sort="{{ $ariaSort('status') }}">
                            <a href="{{ $sortLink('status') }}" class="inline-flex items-center gap-1 transition-colors hover:text-slate-700">وضعیت <span aria-hidden="true" class="text-gold-600">{{ $sortIndicator('status') }}</span></a>
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium" aria-sort="{{ $ariaSort('current_position') }}">
                            <a href="{{ $sortLink('current_position') }}" class="inline-flex items-center gap-1 transition-colors hover:text-slate-700">جایگاه <span aria-hidden="true" class="text-gold-600">{{ $sortIndicator('current_position') }}</span></a>
                        </th>
                        <th scope="col" class="px-4 py-3 font-medium" aria-sort="{{ $ariaSort('target_date') }}">
                            <a href="{{ $sortLink('target_date') }}" class="inline-flex items-center gap-1 transition-colors hover:text-slate-700">تاریخ هدف <span aria-hidden="true" class="text-gold-600">{{ $sortIndicator('target_date') }}</span></a>
                        </th>
                        <th scope="col" class="px-4 py-3"><span class="sr-only">عملیات</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($keywords as $keyword)
                        @php
                            $url = $keyword->target_url;
                            $href = $url
                                ? (\Illuminate\Support\Str::startsWith($url, ['http://', 'https://']) ? $url : url($url))
                                : null;
                        @endphp
                        <tr class="transition-colors hover:bg-slate-50/60">
                            <td class="px-4 py-3">
                                <label class="inline-flex cursor-pointer items-center">
                                    <span class="sr-only">انتخاب {{ $keyword->keyword }}</span>
                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $keyword->id }}"
                                        form="keyword-bulk-form"
                                        x-model="selected"
                                        class="h-3.5 w-3.5 rounded border-slate-300 accent-[#c6a45f]"
                                    >
                                </label>
                            </td>
                            <td class="max-w-[240px] px-4 py-3">
                                <p class="font-semibold text-slate-700">{{ $keyword->keyword }}</p>
                                @if (filled($keyword->notes))
                                    <p class="mt-0.5 truncate text-[9px] text-slate-400" title="{{ $keyword->notes }}">{{ $keyword->notes }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if (filled($keyword->cluster))
                                    <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-medium text-slate-600">{{ $keyword->cluster }}</span>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $keyword->intent->label() }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $keyword->content_type->label() }}</td>
                            <td class="px-4 py-3">
                                @if ($href)
                                    <a href="{{ $href }}" target="_blank" rel="noopener" class="inline-block max-w-[160px] truncate text-[10px] text-slate-500 underline decoration-slate-200 underline-offset-2 hover:text-gold-700" dir="ltr" title="{{ $url }}">{{ $url }}</a>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @include('admin.seo.partials.priority-badge', ['priority' => $keyword->priority])
                            </td>
                            <td class="w-36 px-4 py-3">
                                @include('admin.seo.partials.keywords.status-form', ['keyword' => $keyword])
                            </td>
                            <td class="px-4 py-3 text-slate-500" dir="ltr">
                                {{ $keyword->current_position ? '#'.$keyword->current_position : '—' }}
                            </td>
                            <td class="px-4 py-3 text-slate-500">
                                {{ $keyword->target_date?->locale('fa')->translatedFormat('j M Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button
                                        type="button"
                                        x-on:click="$dispatch('edit-keyword', {{ Js::from($keyword->toFormArray()) }})"
                                        class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[9px] font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-sky-600"
                                        aria-label="ویرایش {{ $keyword->keyword }}"
                                    >
                                        <x-icon name="edit" class="h-3 w-3" />
                                        ویرایش
                                    </button>
                                    <form method="POST" action="{{ route('admin.seo.keywords.destroy', $keyword) }}" onsubmit="return confirm('آیا از حذف این کلیدواژه مطمئن هستید؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-[9px] font-medium text-slate-500 transition-colors hover:bg-red-50 hover:text-red-600" aria-label="حذف {{ $keyword->keyword }}">
                                            <x-icon name="trash" class="h-3 w-3" />
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-16 text-center">
                                <x-icon name="search" class="mx-auto h-7 w-7 text-slate-300" />
                                <p class="mt-3 text-[12px] text-slate-500">کلیدواژه‌ای با این مشخصات پیدا نشد.</p>
                                <p class="mt-1 text-[10px] text-slate-400">فیلترها را پاک کنید یا اولین کلیدواژه را اضافه کنید.</p>
                                <button type="button" @click="$dispatch('add-keyword')" class="admin-btn-gold mt-4 py-2 text-[11px]">
                                    <x-icon name="plus" class="h-3.5 w-3.5" />
                                    افزودن کلیدواژه
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($keywords->hasPages())
            <div class="border-t border-slate-100 px-4 py-4 sm:px-6">{{ $keywords->links() }}</div>
        @endif
    </div>
</div>
