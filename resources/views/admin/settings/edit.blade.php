@extends('admin.layouts.app')

@section('title', 'تنظیمات سایت')
@section('subtitle', 'اطلاعات تماس، محتوای صفحه اصلی و تنظیمات سئو')

@section('content')
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <p class="text-[11px] leading-relaxed text-slate-400">
                تغییرات بلافاصله در سایت اعمال می‌شوند. فیلدهای خالی به مقدار پیش‌فرض بازمی‌گردند.
            </p>
            <div class="flex gap-2">
                <button type="reset" class="admin-btn-ghost">بازنشانی فرم</button>
                <button type="submit" class="admin-btn-gold">
                    <x-icon name="check" class="h-4 w-4" /> ذخیره تنظیمات
                </button>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
            <aside class="hidden lg:block">
                <nav class="admin-card sticky top-24 p-3" aria-label="بخش‌های تنظیمات">
                    @foreach ([
                        ['id' => 'general', 'label' => 'اطلاعات برند', 'icon' => 'award'],
                        ['id' => 'contact', 'label' => 'اطلاعات تماس', 'icon' => 'phone'],
                        ['id' => 'homepage', 'label' => 'محتوای صفحه اصلی', 'icon' => 'home'],
                        ['id' => 'about', 'label' => 'درباره ما و آمار', 'icon' => 'users'],
                        ['id' => 'seo', 'label' => 'تنظیمات سئو', 'icon' => 'search'],
                    ] as $section)
                        <a href="#{{ $section['id'] }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 text-[11px] font-medium text-slate-500 transition-colors hover:bg-slate-50 hover:text-slate-800">
                            <x-icon :name="$section['icon']" class="h-3.5 w-3.5 text-slate-400" />
                            {{ $section['label'] }}
                        </a>
                    @endforeach
                </nav>
            </aside>

            <div class="space-y-6">
                <section id="general" class="admin-card scroll-mt-24 p-6">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                            <x-icon name="award" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="text-[13px] font-bold text-slate-800">اطلاعات برند</h2>
                            <p class="mt-0.5 text-[10px] text-slate-400">نام و شعار اصلی سایت</p>
                        </div>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="brand" class="admin-label">نام برند <span class="text-red-500">*</span></label>
                            <input id="brand" name="brand" type="text" value="{{ old('brand', setting('brand')) }}" required class="admin-input" dir="ltr">
                        </div>
                        <div>
                            <label for="tagline" class="admin-label">شعار برند</label>
                            <input id="tagline" name="tagline" type="text" value="{{ old('tagline', setting('tagline')) }}" class="admin-input" dir="ltr">
                        </div>
                    </div>
                </section>

                <section id="contact" class="admin-card scroll-mt-24 p-6">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                            <x-icon name="phone" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="text-[13px] font-bold text-slate-800">اطلاعات تماس</h2>
                            <p class="mt-0.5 text-[10px] text-slate-400">در هدر، فوتر و صفحه تماس نمایش داده می‌شود</p>
                        </div>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="phone" class="admin-label">تلفن ثابت</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone', setting('phone')) }}" class="admin-input" dir="ltr" placeholder="03 9123 4567">
                        </div>
                        <div>
                            <label for="phone_href" class="admin-label">شماره قابل تماس (لینک)</label>
                            <input id="phone_href" name="phone_href" type="text" value="{{ old('phone_href', setting('phone_href')) }}" class="admin-input" dir="ltr" placeholder="+61391234567">
                            <p class="mt-1 text-[10px] text-slate-400">با کد کشور و بدون فاصله.</p>
                        </div>
                        <div>
                            <label for="mobile" class="admin-label">موبایل</label>
                            <input id="mobile" name="mobile" type="text" value="{{ old('mobile', setting('mobile')) }}" class="admin-input" dir="ltr" placeholder="0412 345 678">
                        </div>
                        <div>
                            <label for="email" class="admin-label">ایمیل</label>
                            <input id="email" name="email" type="email" value="{{ old('email', setting('email')) }}" class="admin-input" dir="ltr" placeholder="hello@lionpainters.com.au">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="address" class="admin-label">آدرس</label>
                            <input id="address" name="address" type="text" value="{{ old('address', setting('address')) }}" class="admin-input" dir="ltr">
                        </div>
                        <div>
                            <label for="service_area" class="admin-label">محدوده خدمات</label>
                            <input id="service_area" name="service_area" type="text" value="{{ old('service_area', setting('service_area')) }}" class="admin-input" dir="ltr">
                        </div>
                        <div>
                            <label for="hours" class="admin-label">ساعات کاری</label>
                            <input id="hours" name="hours" type="text" value="{{ old('hours', setting('hours')) }}" class="admin-input" dir="ltr">
                        </div>
                        <div>
                            <label for="abn" class="admin-label">شماره ABN</label>
                            <input id="abn" name="abn" type="text" value="{{ old('abn', setting('abn')) }}" class="admin-input" dir="ltr">
                        </div>
                        <div>
                            <label for="instagram" class="admin-label">اینستاگرام</label>
                            <input id="instagram" name="instagram" type="url" value="{{ old('instagram', setting('instagram')) }}" class="admin-input" dir="ltr" placeholder="https://instagram.com/…">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="facebook" class="admin-label">فیسبوک</label>
                            <input id="facebook" name="facebook" type="url" value="{{ old('facebook', setting('facebook')) }}" class="admin-input" dir="ltr" placeholder="https://facebook.com/…">
                        </div>
                    </div>
                </section>

                <section id="homepage" class="admin-card scroll-mt-24 p-6">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                            <x-icon name="home" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="text-[13px] font-bold text-slate-800">محتوای صفحه اصلی</h2>
                            <p class="mt-0.5 text-[10px] text-slate-400">تیتر اصلی، معرفی خدمات و بخش‌های دعوت به اقدام</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-5">
                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                            <p class="text-[11px] font-semibold text-slate-600">بخش هیرو</p>
                            <div class="mt-3 space-y-4">
                                <div>
                                    <label for="hero_eyebrow" class="admin-label">متن کوچک بالای عنوان</label>
                                    <input id="hero_eyebrow" name="hero_eyebrow" type="text" value="{{ old('hero_eyebrow', setting('hero_eyebrow')) }}" class="admin-input" dir="ltr">
                                </div>
                                <div>
                                    <label for="hero_title" class="admin-label">عنوان اصلی</label>
                                    <input id="hero_title" name="hero_title" type="text" value="{{ old('hero_title', setting('hero_title')) }}" class="admin-input" dir="ltr">
                                </div>
                                <div>
                                    <label for="hero_text" class="admin-label">توضیح هیرو</label>
                                    <textarea id="hero_text" name="hero_text" rows="2" class="admin-input" dir="ltr">{{ old('hero_text', setting('hero_text')) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                            <p class="text-[11px] font-semibold text-slate-600">معرفی خدمات</p>
                            <div class="mt-3 space-y-4">
                                <div>
                                    <label for="intro_heading" class="admin-label">عنوان بخش</label>
                                    <input id="intro_heading" name="intro_heading" type="text" value="{{ old('intro_heading', setting('intro_heading')) }}" class="admin-input" dir="ltr">
                                </div>
                                <div>
                                    <label for="intro_text" class="admin-label">توضیح بخش</label>
                                    <textarea id="intro_text" name="intro_text" rows="2" class="admin-input" dir="ltr">{{ old('intro_text', setting('intro_text')) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                            <p class="text-[11px] font-semibold text-slate-600">چرا ما را انتخاب کنید</p>
                            <div class="mt-3 space-y-4">
                                <div>
                                    <label for="why_heading" class="admin-label">عنوان بخش</label>
                                    <input id="why_heading" name="why_heading" type="text" value="{{ old('why_heading', setting('why_heading')) }}" class="admin-input" dir="ltr">
                                </div>
                                <div>
                                    <label for="why_text" class="admin-label">توضیح بخش</label>
                                    <textarea id="why_text" name="why_text" rows="2" class="admin-input" dir="ltr">{{ old('why_text', setting('why_text')) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                            <p class="text-[11px] font-semibold text-slate-600">دعوت به اقدام پایانی</p>
                            <div class="mt-3 space-y-4">
                                <div>
                                    <label for="cta_heading" class="admin-label">عنوان</label>
                                    <input id="cta_heading" name="cta_heading" type="text" value="{{ old('cta_heading', setting('cta_heading')) }}" class="admin-input" dir="ltr">
                                </div>
                                <div>
                                    <label for="cta_text" class="admin-label">توضیح</label>
                                    <textarea id="cta_text" name="cta_text" rows="2" class="admin-input" dir="ltr">{{ old('cta_text', setting('cta_text')) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="about" class="admin-card scroll-mt-24 p-6">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                            <x-icon name="users" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="text-[13px] font-bold text-slate-800">درباره ما و آمار</h2>
                            <p class="mt-0.5 text-[10px] text-slate-400">متن معرفی و اعداد نمایش‌داده‌شده در سایت</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="about_heading" class="admin-label">عنوان درباره ما</label>
                            <input id="about_heading" name="about_heading" type="text" value="{{ old('about_heading', setting('about_heading')) }}" class="admin-input" dir="ltr">
                        </div>
                        <div>
                            <label for="about_text" class="admin-label">متن درباره ما</label>
                            <textarea id="about_text" name="about_text" rows="4" class="admin-input" dir="ltr">{{ old('about_text', setting('about_text')) }}</textarea>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            <div>
                                <label for="years" class="admin-label">سال تجربه</label>
                                <input id="years" name="years" type="text" value="{{ old('years', setting('years')) }}" class="admin-input text-center" dir="ltr">
                            </div>
                            <div>
                                <label for="projects_count" class="admin-label">تعداد پروژه</label>
                                <input id="projects_count" name="projects_count" type="text" value="{{ old('projects_count', setting('projects_count')) }}" class="admin-input text-center" dir="ltr">
                            </div>
                            <div>
                                <label for="suburbs_count" class="admin-label">تعداد مناطق</label>
                                <input id="suburbs_count" name="suburbs_count" type="text" value="{{ old('suburbs_count', setting('suburbs_count')) }}" class="admin-input text-center" dir="ltr">
                            </div>
                            <div>
                                <label for="review_count" class="admin-label">تعداد نظرات</label>
                                <input id="review_count" name="review_count" type="text" value="{{ old('review_count', setting('review_count')) }}" class="admin-input text-center" dir="ltr">
                            </div>
                        </div>
                        <div class="sm:max-w-[220px]">
                            <label for="rating" class="admin-label">میانگین امتیاز</label>
                            <input id="rating" name="rating" type="text" value="{{ old('rating', setting('rating')) }}" class="admin-input text-center" dir="ltr" placeholder="4.9">
                        </div>
                    </div>
                </section>

                <section id="seo" class="admin-card scroll-mt-24 p-6">
                    <div class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                            <x-icon name="search" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="text-[13px] font-bold text-slate-800">تنظیمات سئو</h2>
                            <p class="mt-0.5 text-[10px] text-slate-400">عنوان و توضیحات پیش‌فرض صفحه اصلی</p>
                        </div>
                    </div>
                    <div class="mt-5 space-y-4">
                        <div>
                            <label for="meta_title" class="admin-label">عنوان سئو</label>
                            <input id="meta_title" name="meta_title" type="text" value="{{ old('meta_title', setting('meta_title')) }}" class="admin-input" dir="ltr">
                            <p class="mt-1 text-[10px] text-slate-400">حدود ۶۰ کاراکتر برای نمایش کامل در گوگل مناسب است.</p>
                        </div>
                        <div>
                            <label for="meta_description" class="admin-label">توضیحات سئو</label>
                            <textarea id="meta_description" name="meta_description" rows="3" class="admin-input" dir="ltr">{{ old('meta_description', setting('meta_description')) }}</textarea>
                            <p class="mt-1 text-[10px] text-slate-400">حداکثر ۱۵۵ کاراکتر پیشنهاد می‌شود.</p>
                        </div>
                    </div>
                </section>

                <div class="flex flex-col gap-2 sm:flex-row-reverse">
                    <button type="submit" class="admin-btn-gold py-3 sm:flex-1">
                        <x-icon name="check" class="h-4 w-4" /> ذخیره تنظیمات
                    </button>
                    <a href="{{ route('home') }}" target="_blank" class="admin-btn-ghost py-3 sm:flex-1">
                        <x-icon name="eye" class="h-4 w-4" /> مشاهده سایت
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection
