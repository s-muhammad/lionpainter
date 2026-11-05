@extends('admin.layouts.app')

@section('title', $post->exists ? 'ویرایش مقاله' : 'افزودن مقاله')
@section('subtitle', 'محتوای مقاله، تصویر شاخص و تنظیمات سئو')

@section('content')
    <form action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($post->exists)
            @method('PUT')
        @endif

        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-2 text-[11px] text-slate-500 hover:text-slate-800">
                <x-icon name="arrow-right" class="h-3.5 w-3.5" /> بازگشت به مقالات
            </a>
            <div class="flex gap-2">
                @if ($post->exists)
                    <a href="{{ route('posts.show', $post) }}" target="_blank" class="admin-btn-ghost">مشاهده در سایت</a>
                @endif
                <button type="submit" class="admin-btn-gold">
                    <x-icon name="check" class="h-4 w-4" /> ذخیره تغییرات
                </button>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.6fr_1fr]">
            <div class="space-y-6">
                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">محتوای مقاله</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="title" class="admin-label">عنوان مقاله <span class="text-red-500">*</span></label>
                            <input id="title" name="title" type="text" value="{{ old('title', $post->title) }}" required class="admin-input" placeholder="مثلاً چطور رنگ درست را برای اتاق خواب انتخاب کنیم">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="slug" class="admin-label">نامک (آدرس)</label>
                                <input id="slug" name="slug" type="text" value="{{ old('slug', $post->slug) }}" class="admin-input text-left" dir="ltr" placeholder="choosing-bedroom-colours">
                                <p class="mt-1 text-[10px] text-slate-400">خالی بگذارید تا خودکار ساخته شود.</p>
                            </div>
                            <div>
                                <label for="category" class="admin-label">دسته‌بندی <span class="text-red-500">*</span></label>
                                <input id="category" name="category" type="text" value="{{ old('category', $post->category) }}" required class="admin-input" placeholder="Advice" list="post-categories">
                                <datalist id="post-categories">
                                    @foreach (['Advice', 'Colour', 'Process', 'Trends', 'Maintenance'] as $option)
                                        <option value="{{ $option }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div>
                            <label for="excerpt" class="admin-label">خلاصه <span class="text-red-500">*</span></label>
                            <textarea id="excerpt" name="excerpt" rows="3" required class="admin-input" placeholder="خلاصه‌ای کوتاه برای کارت مقاله و نتایج جستجو">{{ old('excerpt', $post->excerpt) }}</textarea>
                        </div>
                        <div>
                            <label for="body" class="admin-label">متن مقاله (HTML مجاز)</label>
                            <textarea id="body" name="body" rows="18" required class="admin-input font-mono text-[12px] leading-relaxed" dir="ltr" placeholder="<p>متن مقاله…</p>">{{ old('body', $post->body) }}</textarea>
                            <p class="mt-1 text-[10px] text-slate-400">از تگ‌های p، h2، h3، ul، li، blockquote و strong استفاده کنید.</p>
                        </div>
                    </div>
                </section>

                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">تنظیمات سئو</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="meta_title" class="admin-label">عنوان سئو</label>
                            <input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title', $post->meta_title) }}" class="admin-input" placeholder="خالی بگذارید تا عنوان مقاله استفاده شود">
                        </div>
                        <div>
                            <label for="meta_description" class="admin-label">توضیحات سئو</label>
                            <textarea id="meta_description" name="meta_description" rows="3" class="admin-input">{{ old('meta_description', $post->meta_description) }}</textarea>
                            <p class="mt-1 text-[10px] text-slate-400">حداکثر ۱۵۵ کاراکتر پیشنهاد می‌شود.</p>
                        </div>
                    </div>
                </section>
            </div>

            <div class="space-y-6">
                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">انتشار</h2>
                    <div class="mt-5 space-y-3">
                        <label class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 text-[12px]">
                            <span>
                                <span class="block font-medium text-slate-700">منتشر شود</span>
                                <span class="mt-0.5 block text-[10px] text-slate-400">نمایش در مجله سایت</span>
                            </span>
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published)) class="h-4 w-4 rounded accent-[#c6a45f]">
                        </label>
                        <div class="rounded-xl border border-slate-200 px-4 py-3">
                            <label for="published_at" class="text-[12px] font-medium text-slate-700">تاریخ و ساعت انتشار</label>
                            <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="admin-input mt-2 py-1.5 text-[12px]">
                            <p class="mt-1 text-[10px] text-slate-400">خالی بگذارید تا زمان ذخیره ثبت شود.</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 px-4 py-3">
                            <label for="author" class="text-[12px] font-medium text-slate-700">نویسنده</label>
                            <input id="author" name="author" type="text" value="{{ old('author', $post->author) }}" required class="admin-input mt-2 py-1.5 text-[12px]">
                        </div>
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3">
                            <span class="text-[12px] font-medium text-slate-700">زمان مطالعه (دقیقه)</span>
                            <input type="number" name="reading_time" value="{{ old('reading_time', $post->reading_time) }}" min="1" max="60" class="admin-input w-20 py-1.5 text-center">
                        </div>
                    </div>
                </section>

                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">تصویر شاخص</h2>
                    <div class="mt-5">
                        @if ($post->cover_image)
                            <img src="{{ asset($post->cover_image) }}" alt="" class="mb-3 h-36 w-full rounded-xl border border-slate-200 object-cover">
                        @endif
                        <input id="cover_image" name="cover_image" type="file" accept="image/*" class="admin-input file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-[11px]">
                        <p class="mt-1 text-[10px] text-slate-400">JPG، PNG یا WebP — حداکثر ۴ مگابایت.</p>
                    </div>
                </section>

                <div class="flex flex-col gap-2">
                    <button type="submit" class="admin-btn-gold w-full py-3">ذخیره مقاله</button>
                    <a href="{{ route('admin.posts.index') }}" class="admin-btn-ghost w-full py-3">انصراف</a>
                </div>
            </div>
        </div>
    </form>
@endsection
