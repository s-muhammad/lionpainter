@extends('admin.layouts.app')

@section('title', 'نظرات مشتریان')
@section('subtitle', 'مدیریت توصیه‌نامه‌ها و امتیازهای مشتریان')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[1.7fr_1fr]">
        <div class="space-y-5">
            <div class="flex items-center justify-between">
                <p class="text-[11px] text-slate-400">در مجموع {{ $testimonials->total() }} نظر ثبت شده است.</p>
                <span class="rounded-full bg-white px-3 py-1 text-[10px] text-slate-500 shadow-sm">مرتب‌شده بر اساس ترتیب نمایش</span>
            </div>

            <div class="space-y-3">
                @forelse ($testimonials as $item)
                    <article class="admin-card p-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-ink-900 text-[11px] font-bold text-gold-400">{{ mb_substr($item->name, 0, 2) }}</span>
                                <div class="min-w-0">
                                    <p class="flex flex-wrap items-center gap-2 text-[13px] font-semibold text-slate-700">
                                        {{ $item->name }}
                                        @if ($item->suburb)
                                            <span class="text-[10px] font-normal text-slate-400">{{ $item->suburb }}</span>
                                        @endif
                                    </p>
                                    <p class="mt-0.5 text-[10px] text-slate-400">
                                        {{ $item->project_type ?: 'پروژه نقاشی' }}
                                        @if ($item->created_at)
                                            · {{ $item->created_at->locale('fa')->diffForHumans() }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                @for ($star = 1; $star <= 5; $star++)
                                    <x-icon name="star" class="h-3.5 w-3.5 {{ $star <= $item->rating ? 'fill-gold-500 text-gold-500' : 'text-slate-200' }}" />
                                @endfor
                            </div>
                        </div>

                        <blockquote class="mt-4 rounded-xl bg-slate-50 px-4 py-3 text-[12px] leading-relaxed text-slate-600">
                            «{{ $item->quote }}»
                        </blockquote>

                        <div class="mt-4 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
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
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.testimonials.edit', $item) }}" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-[10px] font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-sky-600">
                                    <x-icon name="edit" class="h-3.5 w-3.5" /> ویرایش
                                </a>
                                <form action="{{ route('admin.testimonials.destroy', $item) }}" method="POST" onsubmit="return confirm('آیا از حذف این نظر مطمئن هستید؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-[10px] font-medium text-slate-500 transition-colors hover:bg-red-50 hover:text-red-600">
                                        <x-icon name="trash" class="h-3.5 w-3.5" /> حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="admin-card px-6 py-16 text-center">
                        <p class="text-[12px] text-slate-400">هنوز نظری ثبت نشده است. از فرم کنار صفحه اولین نظر را اضافه کنید.</p>
                    </div>
                @endforelse
            </div>

            @if ($testimonials->hasPages())
                <div>{{ $testimonials->links() }}</div>
            @endif
        </div>

        <div>
            <section class="admin-card sticky top-24 p-6">
                <div class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gold-500/15 text-gold-600">
                        <x-icon name="plus" class="h-4 w-4" />
                    </span>
                    <h2 class="text-[13px] font-bold text-slate-800">افزودن نظر جدید</h2>
                </div>
                <p class="mt-2 text-[10px] leading-relaxed text-slate-400">نظر مشتری بلافاصله در سایت نمایش داده می‌شود.</p>

                <form action="{{ route('admin.testimonials.store') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="admin-label">نام مشتری <span class="text-red-500">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required class="admin-input" placeholder="مثلاً سارا محمدی">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="suburb" class="admin-label">منطقه</label>
                            <input id="suburb" name="suburb" type="text" value="{{ old('suburb') }}" class="admin-input" placeholder="Richmond">
                        </div>
                        <div>
                            <label for="project_type" class="admin-label">نوع پروژه</label>
                            <input id="project_type" name="project_type" type="text" value="{{ old('project_type') }}" class="admin-input" placeholder="نقاشی داخلی">
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="rating" class="admin-label">امتیاز <span class="text-red-500">*</span></label>
                            <select id="rating" name="rating" class="admin-input" required>
                                @for ($star = 5; $star >= 1; $star--)
                                    <option value="{{ $star }}" @selected((int) old('rating', 5) === $star)>{{ $star }} ستاره</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="sort_order" class="admin-label">ترتیب نمایش</label>
                            <input id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', 0) }}" min="0" max="999" class="admin-input">
                        </div>
                    </div>
                    <div>
                        <label for="quote" class="admin-label">متن نظر <span class="text-red-500">*</span></label>
                        <textarea id="quote" name="quote" rows="5" required class="admin-input" placeholder="تجربه مشتری را بنویسید…">{{ old('quote') }}</textarea>
                    </div>
                    <label class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 px-4 py-3 text-[12px]">
                        <span class="font-medium text-slate-700">انتشار در سایت</span>
                        <span class="flex items-center gap-2">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', true)) class="h-4 w-4 rounded accent-[#c6a45f]">
                        </span>
                    </label>
                    <button type="submit" class="admin-btn-gold w-full py-3">
                        <x-icon name="plus" class="h-4 w-4" /> افزودن نظر
                    </button>
                </form>
            </section>
        </div>
    </div>
@endsection
