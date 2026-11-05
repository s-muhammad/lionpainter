@extends('admin.layouts.app')

@section('title', 'داشبورد')
@section('subtitle', 'نمای کلی سایت و فعالیت‌های اخیر')

@section('content')
    <div class="space-y-6">
        <section class="relative overflow-hidden rounded-2xl bg-ink-950 p-6 text-white sm:p-8">
            <div class="pointer-events-none absolute inset-0 bg-grain opacity-50"></div>
            <div class="pointer-events-none absolute -left-10 -top-16 font-display text-[14rem] leading-none text-white/[0.03]">L</div>
            <div class="relative flex flex-col justify-between gap-6 lg:flex-row lg:items-center">
                <div>
                    <p class="text-[10px] font-medium uppercase tracking-[0.18em] text-gold-400">نمای کلی</p>
                    <h2 class="mt-3 text-2xl font-bold sm:text-3xl">سلام {{ auth()->user()->name }}، خوش آمدید.</h2>
                    <p class="mt-2 max-w-xl text-[12px] leading-relaxed text-slate-400">
                        {{ $stats['new_quotes'] }} درخواست قیمت جدید و {{ $stats['unread_messages'] }} پیام خوانده‌نشده در انتظار بررسی است.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-gold-500 px-4 py-2.5 text-[12px] font-bold text-ink-950 transition-colors hover:bg-gold-400">
                        <x-icon name="plus" class="h-4 w-4" /> پروژه جدید
                    </a>
                    <a href="{{ route('admin.quotes.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/15 px-4 py-2.5 text-[12px] text-slate-300 transition-colors hover:border-gold-500 hover:text-white">
                        <x-icon name="file" class="h-4 w-4" /> درخواست‌ها
                    </a>
                    <a href="{{ route('admin.settings.edit') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/15 px-4 py-2.5 text-[12px] text-slate-300 transition-colors hover:border-gold-500 hover:text-white">
                        <x-icon name="settings" class="h-4 w-4" /> تنظیمات
                    </a>
                </div>
            </div>
        </section>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'درخواست قیمت جدید', 'value' => $stats['new_quotes'], 'sub' => 'از مجموع '.$stats['quotes_total'].' درخواست', 'icon' => 'file', 'href' => route('admin.quotes.index', ['status' => 'new']), 'tone' => 'gold'],
                ['label' => 'پیام خوانده‌نشده', 'value' => $stats['unread_messages'], 'sub' => 'پیام‌های فرم تماس', 'icon' => 'mail', 'href' => route('admin.messages.index', ['status' => 'unread']), 'tone' => 'blue'],
                ['label' => 'پروژه منتشرشده', 'value' => $stats['projects'], 'sub' => 'در نمونه‌کارهای سایت', 'icon' => 'image', 'href' => route('admin.projects.index'), 'tone' => 'green'],
                ['label' => 'خدمات فعال', 'value' => $stats['services'], 'sub' => $stats['posts'].' مقاله و '.$stats['testimonials'].' نظر منتشرشده', 'icon' => 'brush', 'href' => route('admin.services.index'), 'tone' => 'purple'],
            ] as $card)
                <a href="{{ $card['href'] }}" class="admin-card group p-5 transition-shadow hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl
                            {{ $card['tone'] === 'gold' ? 'bg-gold-500/15 text-gold-600' : '' }}
                            {{ $card['tone'] === 'blue' ? 'bg-sky-50 text-sky-600' : '' }}
                            {{ $card['tone'] === 'green' ? 'bg-emerald-50 text-emerald-600' : '' }}
                            {{ $card['tone'] === 'purple' ? 'bg-violet-50 text-violet-600' : '' }}">
                            <x-icon :name="$card['icon']" class="h-5 w-5" />
                        </span>
                        <x-icon name="arrow-up-right" class="h-4 w-4 text-slate-300 transition-colors group-hover:text-gold-500" />
                    </div>
                    <p class="mt-4 text-2xl font-bold text-slate-800">{{ $card['value'] }}</p>
                    <p class="mt-1 text-[12px] font-medium text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-2 text-[10px] text-slate-400">{{ $card['sub'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_0.6fr]">
            <section class="admin-card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-[14px] font-bold text-slate-800">درخواست‌های قیمت هفته</h2>
                        <p class="mt-1 text-[10px] text-slate-400">هفت روز گذشته</p>
                    </div>
                    <span class="rounded-lg bg-slate-50 px-2.5 py-1 text-[10px] text-slate-500">{{ $stats['quotes_this_month'] }} در این ماه</span>
                </div>
                <div class="mt-8 flex h-44 items-end justify-between gap-2 sm:gap-4">
                    @foreach ($chart as $point)
                        <div class="flex h-full flex-1 flex-col items-center justify-end gap-2">
                            <span class="text-[10px] font-bold text-slate-500">{{ $point['value'] }}</span>
                            <div class="w-full max-w-12 rounded-t-lg bg-ink-900/90 transition-all hover:bg-gold-500" style="height: {{ max(4, $point['percent']) }}%"></div>
                            <span class="text-[10px] text-slate-400">{{ $point['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="admin-card p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-[14px] font-bold text-slate-800">آخرین پیام‌ها</h2>
                    <a href="{{ route('admin.messages.index') }}" class="text-[10px] font-medium text-gold-600 hover:text-gold-700">همه</a>
                </div>
                <div class="mt-4 divide-y divide-slate-100">
                    @forelse ($latestMessages as $message)
                        <a href="{{ route('admin.messages.show', $message) }}" class="group block py-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-[12px] font-semibold text-slate-700 group-hover:text-gold-700">{{ $message->name }}</p>
                                    <p class="mt-0.5 truncate text-[10px] text-slate-400">{{ $message->subject ?: 'پیام عمومی' }} · {{ $message->created_at->locale('fa')->diffForHumans() }}</p>
                                </div>
                                @unless ($message->is_read)
                                    <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-gold-500"></span>
                                @endunless
                            </div>
                        </a>
                    @empty
                        <p class="py-8 text-center text-[11px] text-slate-400">پیامی دریافت نشده است.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <section class="admin-card overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-[14px] font-bold text-slate-800">آخرین درخواست‌های قیمت</h2>
                    <p class="mt-1 text-[10px] text-slate-400">پنج مورد اخیر</p>
                </div>
                <a href="{{ route('admin.quotes.index') }}" class="text-[10px] font-medium text-gold-600 hover:text-gold-700">مشاهده همه</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-right text-[12px]">
                    <thead class="bg-slate-50/70 text-[10px] uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">مشتری</th>
                            <th class="px-4 py-3 font-medium">خدمت</th>
                            <th class="px-4 py-3 font-medium">منطقه</th>
                            <th class="px-4 py-3 font-medium">تاریخ</th>
                            <th class="px-4 py-3 font-medium">وضعیت</th>
                            <th class="px-6 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($latestQuotes as $quote)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-6 py-3.5">
                                    <p class="font-semibold text-slate-700">{{ $quote->name }}</p>
                                    <p class="mt-0.5 text-[10px] text-slate-400" dir="ltr">{{ $quote->phone }}</p>
                                </td>
                                <td class="px-4 py-3.5 text-slate-500">{{ $quote->service }}</td>
                                <td class="px-4 py-3.5 text-slate-500">{{ $quote->suburb }}</td>
                                <td class="px-4 py-3.5 text-slate-400">{{ $quote->created_at->locale('fa')->translatedFormat('j M') }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-medium
                                        {{ $quote->status === 'new' ? 'bg-gold-500/15 text-gold-700' : '' }}
                                        {{ $quote->status === 'contacted' ? 'bg-sky-50 text-sky-600' : '' }}
                                        {{ $quote->status === 'quoted' ? 'bg-violet-50 text-violet-600' : '' }}
                                        {{ $quote->status === 'won' ? 'bg-emerald-50 text-emerald-600' : '' }}
                                        {{ $quote->status === 'closed' ? 'bg-slate-100 text-slate-500' : '' }}">
                                        {{ $quote->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-left">
                                    <a href="{{ route('admin.quotes.show', $quote) }}" class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-400 hover:text-gold-600">
                                        جزئیات <x-icon name="chevron-left" class="h-3.5 w-3.5" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-10 text-center text-[11px] text-slate-400">هنوز درخواستی ثبت نشده است.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
