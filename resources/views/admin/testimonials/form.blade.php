@extends('admin.layouts.app')

@section('title', 'ویرایش نظر مشتری')
@section('subtitle', 'ویرایش متن، امتیاز و وضعیت انتشار')

@section('content')
    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" class="mx-auto max-w-2xl space-y-6">
        @csrf
        @method('PUT')

        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center gap-2 text-[11px] text-slate-500 hover:text-slate-800">
                <x-icon name="arrow-right" class="h-3.5 w-3.5" /> بازگشت به نظرات
            </a>
            <button type="submit" class="admin-btn-gold">
                <x-icon name="check" class="h-4 w-4" /> ذخیره تغییرات
            </button>
        </div>

        <section class="admin-card p-6">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-ink-900 text-[12px] font-bold text-gold-400">{{ mb_substr($testimonial->name, 0, 2) }}</span>
                <div>
                    <h2 class="text-[14px] font-bold text-slate-800">{{ $testimonial->name }}</h2>
                    <p class="mt-0.5 text-[10px] text-slate-400">ثبت‌شده در {{ $testimonial->created_at?->locale('fa')->translatedFormat('j F Y') }}</p>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="admin-label">نام مشتری <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $testimonial->name) }}" required class="admin-input">
                    </div>
                    <div>
                        <label for="suburb" class="admin-label">منطقه</label>
                        <input id="suburb" name="suburb" type="text" value="{{ old('suburb', $testimonial->suburb) }}" class="admin-input">
                    </div>
                    <div>
                        <label for="project_type" class="admin-label">نوع پروژه</label>
                        <input id="project_type" name="project_type" type="text" value="{{ old('project_type', $testimonial->project_type) }}" class="admin-input">
                    </div>
                    <div>
                        <label for="rating" class="admin-label">امتیاز <span class="text-red-500">*</span></label>
                        <select id="rating" name="rating" class="admin-input" required>
                            @for ($star = 5; $star >= 1; $star--)
                                <option value="{{ $star }}" @selected((int) old('rating', $testimonial->rating) === $star)>{{ $star }} ستاره</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div>
                    <label for="quote" class="admin-label">متن نظر <span class="text-red-500">*</span></label>
                    <textarea id="quote" name="quote" rows="6" required class="admin-input">{{ old('quote', $testimonial->quote) }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="sort_order" class="admin-label">ترتیب نمایش</label>
                        <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" min="0" max="999" class="admin-input">
                    </div>
                    <label class="flex items-center justify-between gap-3 self-end rounded-xl border border-slate-200 px-4 py-2.5 text-[12px]">
                        <span class="font-medium text-slate-700">انتشار در سایت</span>
                        <span class="flex items-center gap-2">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $testimonial->is_published)) class="h-4 w-4 rounded accent-[#c6a45f]">
                        </span>
                    </label>
                </div>
            </div>
        </section>

        <div class="flex flex-col gap-2 sm:flex-row-reverse">
            <button type="submit" class="admin-btn-gold py-3 sm:flex-1">ذخیره تغییرات</button>
            <a href="{{ route('admin.testimonials.index') }}" class="admin-btn-ghost py-3 sm:flex-1">انصراف</a>
        </div>
    </form>
@endsection
