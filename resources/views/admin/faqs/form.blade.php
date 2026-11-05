@extends('admin.layouts.app')

@section('title', 'ویرایش پرسش متداول')
@section('subtitle', 'ویرایش متن پرسش، پاسخ و ترتیب نمایش')

@section('content')
    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST" class="mx-auto max-w-2xl space-y-6">
        @csrf
        @method('PUT')

        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('admin.faqs.index') }}" class="inline-flex items-center gap-2 text-[11px] text-slate-500 hover:text-slate-800">
                <x-icon name="arrow-right" class="h-3.5 w-3.5" /> بازگشت به پرسش‌ها
            </a>
            <button type="submit" class="admin-btn-gold">
                <x-icon name="check" class="h-4 w-4" /> ذخیره تغییرات
            </button>
        </div>

        <section class="admin-card p-6">
            <div class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                    <x-icon name="message" class="h-4 w-4" />
                </span>
                <h2 class="text-[13px] font-bold text-slate-800">محتوا</h2>
            </div>

            <div class="mt-5 space-y-4">
                <div>
                    <label for="question" class="admin-label">پرسش <span class="text-red-500">*</span></label>
                    <textarea id="question" name="question" rows="2" required class="admin-input">{{ old('question', $faq->question) }}</textarea>
                </div>
                <div>
                    <label for="answer" class="admin-label">پاسخ <span class="text-red-500">*</span></label>
                    <textarea id="answer" name="answer" rows="7" required class="admin-input">{{ old('answer', $faq->answer) }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="sort_order" class="admin-label">ترتیب نمایش</label>
                        <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0" max="999" class="admin-input">
                    </div>
                    <label class="flex items-center justify-between gap-3 self-end rounded-xl border border-slate-200 px-4 py-2.5 text-[12px]">
                        <span class="font-medium text-slate-700">انتشار در سایت</span>
                        <span class="flex items-center gap-2">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $faq->is_published)) class="h-4 w-4 rounded accent-[#c6a45f]">
                        </span>
                    </label>
                </div>
            </div>
        </section>

        <div class="flex flex-col gap-2 sm:flex-row-reverse">
            <button type="submit" class="admin-btn-gold py-3 sm:flex-1">ذخیره تغییرات</button>
            <a href="{{ route('admin.faqs.index') }}" class="admin-btn-ghost py-3 sm:flex-1">انصراف</a>
        </div>
    </form>
@endsection
