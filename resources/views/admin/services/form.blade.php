@extends('admin.layouts.app')

@section('title', $service->exists ? 'ویرایش خدمت' : 'افزودن خدمت')
@section('subtitle', 'اطلاعات خدمت، محتوا و تنظیمات سئو')

@section('content')
    <form action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($service->exists)
            @method('PUT')
        @endif

        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-2 text-[11px] text-slate-500 hover:text-slate-800">
                <x-icon name="arrow-right" class="h-3.5 w-3.5" /> بازگشت به خدمات
            </a>
            <div class="flex gap-2">
                @if ($service->exists)
                    <a href="{{ route('services.show', $service) }}" target="_blank" class="admin-btn-ghost">مشاهده در سایت</a>
                @endif
                <button type="submit" class="admin-btn-gold">
                    <x-icon name="check" class="h-4 w-4" /> ذخیره تغییرات
                </button>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.6fr_1fr]">
            <div class="space-y-6">
                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">محتوای اصلی</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="title" class="admin-label">عنوان خدمت <span class="text-red-500">*</span></label>
                            <input id="title" name="title" type="text" value="{{ old('title', $service->title) }}" required class="admin-input" placeholder="مثلاً نقاشی داخلی">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="slug" class="admin-label">نامک (آدرس)</label>
                                <input id="slug" name="slug" type="text" value="{{ old('slug', $service->slug) }}" class="admin-input text-left" dir="ltr" placeholder="interior-painting">
                                <p class="mt-1 text-[10px] text-slate-400">خالی بگذارید تا خودکار ساخته شود.</p>
                            </div>
                            <div>
                                <label for="tagline" class="admin-label">شعار کوتاه</label>
                                <input id="tagline" name="tagline" type="text" value="{{ old('tagline', $service->tagline) }}" class="admin-input" placeholder="مثلاً: کامل و بی‌نقص">
                            </div>
                        </div>
                        <div>
                            <label for="excerpt" class="admin-label">خلاصه (برای کارت‌ها) <span class="text-red-500">*</span></label>
                            <textarea id="excerpt" name="excerpt" rows="3" required class="admin-input" placeholder="توضیح کوتاه یک تا دو جمله‌ای">{{ old('excerpt', $service->excerpt) }}</textarea>
                        </div>
                        <div>
                            <label for="description" class="admin-label">توضیحات کامل (HTML مجاز)</label>
                            <textarea id="description" name="description" rows="14" required class="admin-input font-mono text-[12px] leading-relaxed" dir="ltr" placeholder="<p>توضیحات…</p>">{{ old('description', $service->description) }}</textarea>
                            <p class="mt-1 text-[10px] text-slate-400">می‌توانید از تگ‌های p، h2، h3، ul، li و strong استفاده کنید.</p>
                        </div>
                        <div>
                            <label for="features" class="admin-label">محدوده کار (هر خط یک مورد)</label>
                            <textarea id="features" name="features" rows="7" class="admin-input" placeholder="نقاشی دیوارها و سقف‌ها&#10;پوشش درها و چهارچوب‌ها">{{ old('features', $service->features ? implode("\n", $service->features) : '') }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">تنظیمات سئو</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="meta_title" class="admin-label">عنوان سئو</label>
                            <input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title', $service->meta_title) }}" class="admin-input" placeholder="خالی بگذارید تا عنوان اصلی استفاده شود">
                        </div>
                        <div>
                            <label for="meta_description" class="admin-label">توضیحات سئو</label>
                            <textarea id="meta_description" name="meta_description" rows="3" class="admin-input">{{ old('meta_description', $service->meta_description) }}</textarea>
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
                                <span class="block font-medium text-slate-700">فعال باشد</span>
                                <span class="mt-0.5 block text-[10px] text-slate-400">نمایش در منو و صفحات سایت</span>
                            </span>
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active)) class="h-4 w-4 rounded accent-[#c6a45f]">
                        </label>
                        <label class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 text-[12px]">
                            <span>
                                <span class="block font-medium text-slate-700">نمایش در صفحه اصلی</span>
                                <span class="mt-0.5 block text-[10px] text-slate-400">در بخش خدمات ویژه</span>
                            </span>
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $service->is_featured)) class="h-4 w-4 rounded accent-[#c6a45f]">
                        </label>
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3">
                            <span class="text-[12px] font-medium text-slate-700">ترتیب نمایش</span>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}" min="0" max="999" class="admin-input w-20 py-1.5 text-center">
                        </div>
                    </div>
                </section>

                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">آیکون و تصویر</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="icon" class="admin-label">آیکون</label>
                            <select id="icon" name="icon" class="admin-input">
                                @foreach (['brush' => 'قلم‌مو', 'roller' => 'غلتک', 'home' => 'خانه', 'building' => 'ساختمان', 'shield' => 'سپر', 'sparkle' => 'درخشش', 'leaf' => 'برگ', 'drop' => 'قطره', 'ruler' => 'خط‌کش', 'layers' => 'لایه‌ها', 'sun' => 'خورشید', 'wind' => 'باد', 'palette' => 'پالت رنگ', 'award' => 'مدال'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('icon', $service->icon) === $value)>{{ $label }} ({{ $value }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="image" class="admin-label">تصویر خدمت</label>
                            @if ($service->image)
                                <img src="{{ asset($service->image) }}" alt="" class="mb-3 h-32 w-full rounded-xl border border-slate-200 object-cover">
                            @endif
                            <input id="image" name="image" type="file" accept="image/*" class="admin-input file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-[11px]">
                            <p class="mt-1 text-[10px] text-slate-400">JPG، PNG یا WebP — حداکثر ۴ مگابایت.</p>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col gap-2">
                    <button type="submit" class="admin-btn-gold w-full py-3">ذخیره خدمت</button>
                    <a href="{{ route('admin.services.index') }}" class="admin-btn-ghost w-full py-3">انصراف</a>
                </div>
            </div>
        </div>
    </form>
@endsection
