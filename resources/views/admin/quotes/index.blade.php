@extends('admin.layouts.app')

@section('title', 'درخواست‌های قیمت')
@section('subtitle', 'پیگیری درخواست‌های دریافت قیمت از سایت')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col justify-between gap-4 xl:flex-row xl:items-center">
            <div class="flex flex-wrap items-center gap-1 rounded-xl border border-slate-200 bg-white p-1">
                <a href="{{ route('admin.quotes.index') }}"
                   class="rounded-lg px-3.5 py-2 text-[11px] font-medium transition-colors {{ request('status') ? 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' : 'bg-ink-900 text-white' }}">
                    همه
                </a>
                @foreach ($statuses as $value => $label)
                    <a href="{{ route('admin.quotes.index', ['status' => $value]) }}"
                       class="rounded-lg px-3.5 py-2 text-[11px] font-medium transition-colors {{ request('status') === $value ? 'bg-ink-900 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            <form action="{{ route('admin.quotes.index') }}" method="GET" class="flex items-center gap-2">
                @if (request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="search" name="q" value="{{ request('q') }}" placeholder="نام، ایمیل، تلفن یا منطقه…" class="admin-input w-60 py-2">
                <button type="submit" class="admin-btn-ghost px-3 py-2">
                    <x-icon name="search" class="h-4 w-4" />
                </button>
            </form>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-right text-[12px]">
                    <thead class="bg-slate-50/70 text-[10px] uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">مشتری</th>
                            <th class="px-4 py-3 font-medium">خدمت</th>
                            <th class="px-4 py-3 font-medium">ملک / منطقه</th>
                            <th class="px-4 py-3 font-medium">زمان‌بندی</th>
                            <th class="px-4 py-3 font-medium">تاریخ</th>
                            <th class="px-4 py-3 font-medium">وضعیت</th>
                            <th class="px-6 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($quotes as $quote)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-700">{{ $quote->name }}</p>
                                    <p class="mt-0.5 text-[10px] text-slate-400" dir="ltr">{{ $quote->phone }} · {{ $quote->email }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-600">{{ $quote->service }}</td>
                                <td class="px-4 py-4 text-slate-500">
                                    <p>{{ $quote->property_type ?: '—' }}</p>
                                    <p class="mt-0.5 text-[10px] text-slate-400">{{ $quote->suburb }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $quote->timeline ?: '—' }}</td>
                                <td class="px-4 py-4 text-slate-400">
                                    <p>{{ $quote->created_at->locale('fa')->translatedFormat('j M Y') }}</p>
                                    <p class="mt-0.5 text-[10px]">{{ $quote->created_at->locale('fa')->diffForHumans() }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-medium
                                        {{ $quote->status === 'new' ? 'bg-gold-500/15 text-gold-700' : '' }}
                                        {{ $quote->status === 'contacted' ? 'bg-sky-50 text-sky-600' : '' }}
                                        {{ $quote->status === 'quoted' ? 'bg-violet-50 text-violet-600' : '' }}
                                        {{ $quote->status === 'won' ? 'bg-emerald-50 text-emerald-600' : '' }}
                                        {{ $quote->status === 'closed' ? 'bg-slate-100 text-slate-500' : '' }}">
                                        {{ $quote->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <a href="{{ route('admin.quotes.show', $quote) }}" class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-[10px] font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-gold-700">
                                        جزئیات <x-icon name="chevron-left" class="h-3.5 w-3.5" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <x-icon name="file" class="mx-auto h-7 w-7 text-slate-300" />
                                    <p class="mt-3 text-[12px] text-slate-400">درخواستی با این مشخصات یافت نشد.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($quotes->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">{{ $quotes->links() }}</div>
            @endif
        </div>
    </div>
@endsection
