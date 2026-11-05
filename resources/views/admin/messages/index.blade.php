@extends('admin.layouts.app')

@section('title', 'پیام‌ها')
@section('subtitle', 'پیام‌های دریافتی از فرم تماس سایت')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex flex-wrap items-center gap-1 rounded-xl border border-slate-200 bg-white p-1">
                @foreach ([
                    ['value' => '', 'label' => 'همه پیام‌ها'],
                    ['value' => 'unread', 'label' => 'خوانده‌نشده'],
                    ['value' => 'read', 'label' => 'خوانده‌شده'],
                ] as $tab)
                    <a href="{{ route('admin.messages.index', array_filter(['status' => $tab['value']])) }}"
                       class="rounded-lg px-3.5 py-2 text-[11px] font-medium transition-colors {{ request('status', '') === $tab['value'] ? 'bg-ink-900 text-white' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </div>
            <p class="text-[11px] text-slate-400">{{ $messages->total() }} پیام در این فهرست</p>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[820px] text-right text-[12px]">
                    <thead class="bg-slate-50/70 text-[10px] uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">فرستنده</th>
                            <th class="px-4 py-3 font-medium">موضوع</th>
                            <th class="px-4 py-3 font-medium">تماس</th>
                            <th class="px-4 py-3 font-medium">تاریخ</th>
                            <th class="px-4 py-3 font-medium">وضعیت</th>
                            <th class="px-6 py-3 font-medium">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($messages as $message)
                            <tr class="group transition-colors hover:bg-slate-50/60 {{ $message->is_read ? '' : 'bg-gold-500/[0.04]' }}">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="flex items-center gap-3">
                                        <span class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-[10px] font-bold text-slate-500">
                                            {{ mb_substr($message->name, 0, 2) }}
                                            @unless ($message->is_read)
                                                <span class="absolute -left-0.5 -top-0.5 h-2.5 w-2.5 rounded-full border-2 border-white bg-gold-500"></span>
                                            @endunless
                                        </span>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-slate-700 group-hover:text-gold-700">{{ $message->name }}</p>
                                            <p class="mt-0.5 truncate text-[10px] text-slate-400">{{ $message->suburb ?: 'بدون منطقه' }}</p>
                                        </div>
                                    </a>
                                </td>
                                <td class="max-w-[240px] px-4 py-4">
                                    <p class="truncate text-slate-600">{{ $message->subject ?: 'پیام عمومی' }}</p>
                                    <p class="mt-0.5 truncate text-[10px] text-slate-400">{{ \Illuminate\Support\Str::limit($message->message, 70) }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    <a href="mailto:{{ $message->email }}" class="block text-[11px] hover:text-gold-700" dir="ltr">{{ $message->email }}</a>
                                    @if ($message->phone)
                                        <a href="tel:{{ $message->phone }}" class="mt-0.5 block text-[10px] text-slate-400 hover:text-gold-700" dir="ltr">{{ $message->phone }}</a>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-slate-400">
                                    <p>{{ $message->created_at->locale('fa')->translatedFormat('j M Y') }}</p>
                                    <p class="mt-0.5 text-[10px]">{{ $message->created_at->locale('fa')->diffForHumans() }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    @if ($message->is_read)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-500">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> خوانده‌شده
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gold-500/15 px-2.5 py-1 text-[10px] font-medium text-gold-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gold-500"></span> جدید
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.messages.show', $message) }}" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700" title="مشاهده پیام">
                                            <x-icon name="eye" class="h-4 w-4" />
                                        </a>
                                        <form action="{{ route('admin.messages.read', $message) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-emerald-600" title="{{ $message->is_read ? 'علامت‌گذاری به عنوان خوانده‌نشده' : 'علامت‌گذاری به عنوان خوانده‌شده' }}">
                                                <x-icon name="check-circle" class="h-4 w-4" />
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('آیا از حذف این پیام مطمئن هستید؟')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-red-50 hover:text-red-600" title="حذف">
                                                <x-icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <x-icon name="mail" class="mx-auto h-7 w-7 text-slate-300" />
                                    <p class="mt-3 text-[12px] text-slate-400">در این فهرست پیامی وجود ندارد.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($messages->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">{{ $messages->links() }}</div>
            @endif
        </div>
    </div>
@endsection
