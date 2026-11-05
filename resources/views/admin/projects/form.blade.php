@extends('admin.layouts.app')

@section('title', $project->exists ? 'ویرایش پروژه' : 'افزودن پروژه')
@section('subtitle', 'اطلاعات پروژه، گالری تصاویر و تنظیمات انتشار')

@section('content')
    <form action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($project->exists)
            @method('PUT')
        @endif

        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center gap-2 text-[11px] text-slate-500 hover:text-slate-800">
                <x-icon name="arrow-right" class="h-3.5 w-3.5" /> بازگشت به پروژه‌ها
            </a>
            <div class="flex gap-2">
                @if ($project->exists)
                    <a href="{{ route('projects.show', $project) }}" target="_blank" class="admin-btn-ghost">مشاهده در سایت</a>
                @endif
                <button type="submit" class="admin-btn-gold">
                    <x-icon name="check" class="h-4 w-4" /> ذخیره تغییرات
                </button>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.6fr_1fr]">
            <div class="space-y-6">
                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">مشخصات پروژه</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="title" class="admin-label">عنوان پروژه <span class="text-red-500">*</span></label>
                            <input id="title" name="title" type="text" value="{{ old('title', $project->title) }}" required class="admin-input" placeholder="مثلاً بازسازی خانه‌ای در تووراک">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="slug" class="admin-label">نامک (آدرس)</label>
                                <input id="slug" name="slug" type="text" value="{{ old('slug', $project->slug) }}" class="admin-input text-left" dir="ltr" placeholder="hawthorn-interior-refresh">
                                <p class="mt-1 text-[10px] text-slate-400">خالی بگذارید تا خودکار ساخته شود.</p>
                            </div>
                            <div>
                                <label for="category" class="admin-label">دسته‌بندی <span class="text-red-500">*</span></label>
                                <input id="category" name="category" type="text" value="{{ old('category', $project->category) }}" required class="admin-input" placeholder="Residential" list="project-categories">
                                <datalist id="project-categories">
                                    @foreach (['Residential', 'Commercial', 'Exterior', 'Interior', 'Restoration'] as $option)
                                        <option value="{{ $option }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label for="suburb" class="admin-label">منطقه</label>
                                <input id="suburb" name="suburb" type="text" value="{{ old('suburb', $project->suburb) }}" class="admin-input" placeholder="Hawthorn">
                            </div>
                            <div>
                                <label for="year" class="admin-label">سال انجام</label>
                                <input id="year" name="year" type="text" value="{{ old('year', $project->year) }}" class="admin-input text-center" dir="ltr" inputmode="numeric" maxlength="4" placeholder="2025">
                            </div>
                            <div>
                                <label for="colour" class="admin-label">رنگ‌بندی</label>
                                <input id="colour" name="colour" type="text" value="{{ old('colour', $project->colour) }}" class="admin-input" placeholder="Warm whites">
                            </div>
                        </div>
                        <div>
                            <label for="summary" class="admin-label">خلاصه <span class="text-red-500">*</span></label>
                            <textarea id="summary" name="summary" rows="3" required class="admin-input" placeholder="یک تا دو جمله برای کارت پروژه">{{ old('summary', $project->summary) }}</textarea>
                        </div>
                        <div>
                            <label for="description" class="admin-label">شرح کامل (HTML مجاز)</label>
                            <textarea id="description" name="description" rows="12" required class="admin-input font-mono text-[12px] leading-relaxed" dir="ltr" placeholder="<p>شرح پروژه…</p>">{{ old('description', $project->description) }}</textarea>
                            <p class="mt-1 text-[10px] text-slate-400">می‌توانید از تگ‌های p، h2، h3، ul، li و strong استفاده کنید.</p>
                        </div>
                    </div>
                </section>

                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">تصاویر</h2>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="cover_image" class="admin-label">تصویر شاخص</label>
                            @if ($project->cover_image)
                                <img src="{{ asset($project->cover_image) }}" alt="" class="mb-3 h-40 w-full rounded-xl border border-slate-200 object-cover">
                            @endif
                            <input id="cover_image" name="cover_image" type="file" accept="image/*" class="admin-input file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-[11px]">
                            <p class="mt-1 text-[10px] text-slate-400">JPG، PNG یا WebP — حداکثر ۴ مگابایت.</p>
                        </div>
                        <div>
                            <label for="gallery" class="admin-label">گالری تصاویر (هر خط یک مسیر)</label>
                            <textarea id="gallery" name="gallery" rows="5" class="admin-input font-mono text-[11px]" dir="ltr" placeholder="images/projects/living-room.svg&#10;images/projects/kitchen.svg">{{ old('gallery', $project->gallery ? implode("\n", $project->gallery) : '') }}</textarea>
                            <p class="mt-1 text-[10px] text-slate-400">مسیرها نسبت به پوشه public هستند.</p>
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
                                <span class="mt-0.5 block text-[10px] text-slate-400">نمایش در صفحه نمونه‌کارها</span>
                            </span>
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $project->is_published)) class="h-4 w-4 rounded accent-[#c6a45f]">
                        </label>
                        <label class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 text-[12px]">
                            <span>
                                <span class="block font-medium text-slate-700">پروژه ویژه</span>
                                <span class="mt-0.5 block text-[10px] text-slate-400">نمایش در بخش منتخب صفحه اصلی</span>
                            </span>
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured)) class="h-4 w-4 rounded accent-[#c6a45f]">
                        </label>
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3">
                            <span class="text-[12px] font-medium text-slate-700">ترتیب نمایش</span>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}" min="0" max="999" class="admin-input w-20 py-1.5 text-center">
                        </div>
                    </div>
                </section>

                <section class="admin-card p-6">
                    <h2 class="text-[13px] font-bold text-slate-800">راهنما</h2>
                    <ul class="mt-4 space-y-3 text-[11px] leading-relaxed text-slate-500">
                        <li class="flex gap-2">
                            <x-icon name="check-circle" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gold-500" />
                            خلاصه در کارت‌های نمونه‌کار و نتایج جستجو دیده می‌شود؛ کوتاه و توصیفی بنویسید.
                        </li>
                        <li class="flex gap-2">
                            <x-icon name="check-circle" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gold-500" />
                            سال و منطقه به اعتماد مشتری کمک می‌کند؛ در صورت نبود اطلاعات خالی بگذارید.
                        </li>
                        <li class="flex gap-2">
                            <x-icon name="check-circle" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gold-500" />
                            برای گالری می‌توانید از تصاویر موجود در پوشه public/images استفاده کنید.
                        </li>
                    </ul>
                </section>

                <div class="flex flex-col gap-2">
                    <button type="submit" class="admin-btn-gold w-full py-3">ذخیره پروژه</button>
                    <a href="{{ route('admin.projects.index') }}" class="admin-btn-ghost w-full py-3">انصراف</a>
                </div>
            </div>
        </div>
    </form>
@endsection
