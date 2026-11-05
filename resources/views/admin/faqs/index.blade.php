@extends('admin.layouts.app')

@section('title', 'سؤالات متداول')
@section('subtitle', 'مدیریت پرسش‌های صفحه پرسش و پاسخ')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[1.7fr_1fr]">
        <div class="space-y-5">
            <div class="flex items-center justify-between">
                <p class="text-[11px] text-slate-400">در مجموع {{ $faqs->total() }} پرسش ثبت شده است.</p>
                <a href="{{ route('faqs') }}" target="_blank" class="inline-flex items-center gap-1.5 text-[10px] font-medium text-slate-500 hover:text-gold-600">
                    <x-icon name="eye" class="h-3.5 w-3.5" /> مشاهده صفحه پرسش‌ها
                </a>
            </div>

            <div class="admin-card divide-y divide-slate-100">
                @forelse ($faqs as $item)
                    <article class="p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-[11px] font-bold text-slate-500">{{ $loop->iteration + ($faqs->currentPage() - 1) * $faqs->perPage() }}</span>
                                <div>
                                    <h3 class="text-[13px] font-semibold leading-relaxed text-slate-700">{{ $item->question }}</h3>
                                    <p class="mt-2 text-[11px] leading-relaxed text-slate-500">{{ $item->answer }}</p>
                                </div>
                            </div>
                            <div class="flex shrink-0 flex-col items-end gap-2">
                                @if ($item->is_published)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-medium text-emerald-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> منتشرشده
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-500">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> غیرفعال
                                    </span>
                                @endif
                                <span class="text-[10px] text-slate-400">ترتیب: {{ $item->sort_order }}</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-1">
                            <a href="{{ route('admin.faqs.edit', $item) }}" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-[10px] font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-sky-600">
                                <x-icon name="edit" class="h-3.5 w-3.5" /> ویرایش
                            </a>
                            <form action="{{ route('admin.faqs.destroy', $item) }}" method="POST" onsubmit="return confirm('آیا از حذف این پرسش مطمئن هستید؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-[10px] font-medium text-slate-500 transition-colors hover:bg-red-50 hover:text-red-600">
                                    <x-icon name="trash" class="h-3.5 w-3.5" /> حذف
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="px-6 py-16 text-center">
                        <p class="text-[12px] text-slate-400">هنوز پرسشی ثبت نشده است. از فرم کنار صفحه اولین پرسش را اضافه کنید.</p>
                    </div>
                @endforelse
            </div>

            @if ($faqs->hasPages())
                <div>{{ $faqs->links() }}</div>
            @endif
        </div>

        <div>
            <section class="admin-card sticky top-24 p-6">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                        <x-icon name="plus" class="h-4 w-4" />
                    </span>
                    <h2 class="text-[13px] font-bold text-slate-800">افزودن پرسش جدید</h2>
                </div>
                <p class="mt-2 text-[10px] leading-relaxed text-slate-400">پرسش‌ها به ترتیب در صفحه سؤالات متداول نمایش داده می‌شوند.</p>

                <form action="{{ route('admin.faqs.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label for="question" class="admin-label">پرسش <span class="text-red-500">*</span></label>
                        <textarea id="question" name="question" rows="2" required class="admin-input" placeholder="مثلاً آیا قبل از رنگ‌آمیزی باید خانه را تخلیه کنیم؟">{{ old('question') }}</textarea>
                    </div>
                    <div>
                        <label for="answer" class="admin-label">پاسخ <span class="text-red-500">*</span></label>
                        <textarea id="answer" name="answer" rows="6" required class="admin-input" placeholder="پاسخ روشن و کوتاه…">{{ old('answer') }}</textarea>
                    </div>
                    <div>
                        <label for="sort_order" class="admin-label">ترتیب نمایش</label>
                        <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', 0) }}" min="0" max="999" class="admin-input">
                    </div>
                    <label class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 text-[12px]">
                        <span class="font-medium text-slate-700">انتشار در سایت</span>
                        <span class="flex items-center gap-2">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', true)) class="h-4 w-4 rounded accent-[#c6a45f]">
                        </span>
                    </label>
                    <button type="submit" class="admin-btn-gold w-full py-3">
                        <x-icon name="plus" class="h-4 w-4" /> افزودن پرسش
                    </button>
                </form>
            </section>
        </div>
    </div>
@endsection
