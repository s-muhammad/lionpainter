@extends('admin.layouts.app')

@section('title', 'پیام از ' . $message->name)
@section('subtitle', 'جزئیات پیام دریافتی از فرم تماس')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center gap-2 text-[11px] text-slate-500 hover:text-slate-800">
                <x-icon name="arrow-right" class="h-3.5 w-3.5" /> بازگشت به پیام‌ها
            </a>
            <div class="flex flex-wrap gap-2">
                <form action="{{ route('admin.messages.read', $message) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="admin-btn-ghost">
                        <x-icon name="check-circle" class="h-4 w-4" />
                        {{ $message->is_read ? 'خوانده‌نشده' : 'خوانده‌شده' }}
                    </button>
                </form>
                <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: '.($message->subject ?: 'Your enquiry')) }}" class="admin-btn-gold">
                    <x-icon name="mail" class="h-4 w-4" /> پاسخ با ایمیل
                </a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.6fr_1fr]">
            <section class="admin-card overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50/60 px-6 py-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-[15px] font-bold text-slate-800">{{ $message->subject ?: 'پیام عمومی' }}</h2>
                        @unless ($message->is_read)
                            <span class="rounded-full bg-gold-500/15 px-2.5 py-0.5 text-[9px] font-medium text-gold-700">جدید</span>
                        @endunless
                    </div>
                    <p class="mt-1.5 text-[11px] text-slate-400">
                        {{ $message->name }} · {{ $message->created_at->locale('fa')->translatedFormat('j F Y، ساعت H:i') }}
                    </p>
                </div>
                <div class="px-6 py-6">
                    <p class="whitespace-pre-line text-[13px] leading-8 text-slate-600">{{ $message->message }}</p>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                    <a href="mailto:{{ $message->email }}" class="inline-flex items-center gap-2 text-[11px] font-medium text-gold-700 hover:text-gold-600">
                        <x-icon name="mail" class="h-4 w-4" /> پاسخ به این پیام
                    </a>
                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('آیا از حذف این پیام مطمئن هستید؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 text-[11px] text-slate-400 transition-colors hover:text-red-600">
                            <x-icon name="trash" class="h-3.5 w-3.5" /> حذف پیام
                        </button>
                    </form>
                </div>
            </section>

            <div class="space-y-4">
                <section class="admin-card p-6">
                    <h3 class="text-[12px] font-bold text-slate-800">اطلاعات فرستنده</h3>
                    <dl class="mt-4 space-y-4 text-[12px]">
                        <div>
                            <dt class="text-[10px] text-slate-400">نام</dt>
                            <dd class="mt-1 font-medium text-slate-700">{{ $message->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] text-slate-400">ایمیل</dt>
                            <dd class="mt-1">
                                <a href="mailto:{{ $message->email }}" class="font-medium text-slate-700 hover:text-gold-700" dir="ltr">{{ $message->email }}</a>
                            </dd>
                        </div>
                        @if ($message->phone)
                            <div>
                                <dt class="text-[10px] text-slate-400">تلفن</dt>
                                <dd class="mt-1">
                                    <a href="tel:{{ $message->phone }}" class="font-medium text-slate-700 hover:text-gold-700" dir="ltr">{{ $message->phone }}</a>
                                </dd>
                            </div>
                        @endif
                        @if ($message->suburb)
                            <div>
                                <dt class="text-[10px] text-slate-400">منطقه</dt>
                                <dd class="mt-1 font-medium text-slate-700">{{ $message->suburb }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-[10px] text-slate-400">تاریخ دریافت</dt>
                            <dd class="mt-1 font-medium text-slate-700">{{ $message->created_at->locale('fa')->translatedFormat('j F Y، H:i') }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="rounded-2xl bg-ink-950 p-6 text-slate-300">
                    <p class="text-[10px] font-medium uppercase tracking-[0.18em] text-gold-400">پاسخ سریع</p>
                    <p class="mt-3 text-[11px] leading-relaxed">
                        پاسخگویی در کمتر از یک روز کاری، شانس تبدیل این پیام به پروژه را به‌طور چشمگیری افزایش می‌دهد.
                    </p>
                    <a href="mailto:{{ $message->email }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-gold-500 px-3.5 py-2 text-[11px] font-bold text-ink-950 transition-colors hover:bg-gold-400">
                        <x-icon name="mail" class="h-3.5 w-3.5" /> ارسال ایمیل
                    </a>
                </section>
            </div>
        </div>
    </div>
@endsection
