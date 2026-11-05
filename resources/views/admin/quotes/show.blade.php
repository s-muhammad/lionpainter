@extends('admin.layouts.app')

@section('title', 'درخواست قیمت از ' . $quote->name)
@section('subtitle', 'جزئیات درخواست و پیگیری وضعیت')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('admin.quotes.index') }}" class="inline-flex items-center gap-2 text-[11px] text-slate-500 hover:text-slate-800">
                <x-icon name="arrow-right" class="h-3.5 w-3.5" /> بازگشت به درخواست‌ها
            </a>
            <div class="flex flex-wrap gap-2">
                <a href="tel:{{ $quote->phone }}" class="admin-btn-ghost">
                    <x-icon name="phone" class="h-4 w-4" /> تماس تلفنی
                </a>
                <a href="mailto:{{ $quote->email }}?subject={{ rawurlencode('Your painting quote — Lion Painters') }}" class="admin-btn-gold">
                    <x-icon name="mail" class="h-4 w-4" /> ارسال ایمیل
                </a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.6fr_1fr]">
            <div class="space-y-6">
                <section class="admin-card overflow-hidden">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/60 px-6 py-5">
                        <div>
                            <h2 class="text-[15px] font-bold text-slate-800">{{ $quote->name }}</h2>
                            <p class="mt-1 text-[11px] text-slate-400">
                                ثبت‌شده در {{ $quote->created_at->locale('fa')->translatedFormat('j F Y، ساعت H:i') }}
                            </p>
                        </div>
                        <span class="inline-flex rounded-full px-3 py-1 text-[10px] font-medium
                            {{ $quote->status === 'new' ? 'bg-gold-500/15 text-gold-700' : '' }}
                            {{ $quote->status === 'contacted' ? 'bg-sky-50 text-sky-600' : '' }}
                            {{ $quote->status === 'quoted' ? 'bg-violet-50 text-violet-600' : '' }}
                            {{ $quote->status === 'won' ? 'bg-emerald-50 text-emerald-600' : '' }}
                            {{ $quote->status === 'closed' ? 'bg-slate-100 text-slate-500' : '' }}">
                            {{ $quote->status_label }}
                        </span>
                    </div>

                    <dl class="grid gap-x-6 gap-y-5 px-6 py-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-[10px] text-slate-400">ایمیل</dt>
                            <dd class="mt-1 text-[12px] font-medium text-slate-700" dir="ltr">{{ $quote->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] text-slate-400">تلفن</dt>
                            <dd class="mt-1 text-[12px] font-medium text-slate-700" dir="ltr">{{ $quote->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] text-slate-400">منطقه</dt>
                            <dd class="mt-1 text-[12px] font-medium text-slate-700">{{ $quote->suburb }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] text-slate-400">خدمت درخواستی</dt>
                            <dd class="mt-1 text-[12px] font-medium text-slate-700">{{ $quote->service }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] text-slate-400">نوع ملک</dt>
                            <dd class="mt-1 text-[12px] font-medium text-slate-700">{{ $quote->property_type ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] text-slate-400">تعداد اتاق خواب</dt>
                            <dd class="mt-1 text-[12px] font-medium text-slate-700">{{ $quote->bedrooms ?: '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] text-slate-400">زمان‌بندی مورد نظر</dt>
                            <dd class="mt-1 text-[12px] font-medium text-slate-700">{{ $quote->timeline ?: '—' }}</dd>
                        </div>
                    </dl>

                    @if ($quote->details)
                        <div class="border-t border-slate-100 px-6 py-6">
                            <h3 class="text-[12px] font-bold text-slate-800">توضیحات مشتری</h3>
                            <p class="mt-3 whitespace-pre-line text-[12px] leading-7 text-slate-600">{{ $quote->details }}</p>
                        </div>
                    @endif
                </section>

                <section class="admin-card p-6">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                            <x-icon name="edit" class="h-4 w-4" />
                        </span>
                        <h2 class="text-[13px] font-bold text-slate-800">یادداشت داخلی</h2>
                    </div>
                    <p class="mt-2 text-[10px] text-slate-400">این یادداشت فقط در پنل مدیریت قابل مشاهده است.</p>
                    <form action="{{ route('admin.quotes.update', $quote) }}" method="POST" class="mt-4 space-y-4">
                        @csrf @method('PATCH')
                        <div class="grid gap-4 sm:grid-cols-[220px_1fr]">
                            <div>
                                <label for="status" class="admin-label">وضعیت درخواست</label>
                                <select id="status" name="status" class="admin-input" required>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $quote->status) === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="admin_notes" class="admin-label">یادداشت</label>
                                <textarea id="admin_notes" name="admin_notes" rows="3" class="admin-input" placeholder="مثلاً: بازدید اولیه برای پنجشنبه ساعت ۱۰ صبح هماهنگ شد.">{{ old('admin_notes', $quote->admin_notes) }}</textarea>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="admin-btn-gold">
                                <x-icon name="check" class="h-4 w-4" /> ذخیره تغییرات
                            </button>
                        </div>
                    </form>
                </section>
            </div>

            <div class="space-y-4">
                <section class="admin-card p-6">
                    <h3 class="text-[12px] font-bold text-slate-800">اقدامات پیشنهادی</h3>
                    <ul class="mt-4 space-y-3 text-[11px] leading-relaxed text-slate-500">
                        <li class="flex gap-2">
                            <x-icon name="check-circle" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gold-500" />
                            تماس تلفنی در اولین فرصت؛ درخواست‌های جدید سریع‌تر تبدیل می‌شوند.
                        </li>
                        <li class="flex gap-2">
                            <x-icon name="check-circle" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gold-500" />
                            هماهنگی بازدید و ثبت آن در یادداشت داخلی.
                        </li>
                        <li class="flex gap-2">
                            <x-icon name="check-circle" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gold-500" />
                            پس از ارسال قیمت، وضعیت را روی «قیمت ارسال شده» بگذارید.
                        </li>
                    </ul>
                </section>

                <section class="rounded-2xl border border-red-100 bg-red-50/50 p-6">
                    <h3 class="text-[12px] font-bold text-red-700">حذف درخواست</h3>
                    <p class="mt-2 text-[11px] leading-relaxed text-red-600/80">این عملیات قابل بازگشت نیست. تنها در صورت اسپم بودن درخواست از آن استفاده کنید.</p>
                    <form action="{{ route('admin.quotes.destroy', $quote) }}" method="POST" class="mt-4" onsubmit="return confirm('آیا از حذف این درخواست مطمئن هستید؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-3.5 py-2 text-[11px] font-medium text-red-600 transition-colors hover:bg-red-50">
                            <x-icon name="trash" class="h-3.5 w-3.5" /> حذف درخواست
                        </button>
                    </form>
                </section>
            </div>
        </div>
    </div>
@endsection
