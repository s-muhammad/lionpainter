@extends('admin.layouts.app')

@section('title', 'مقالات')
@section('subtitle', 'مدیریت مقالات و راهنمای مجله')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-[11px] text-slate-400">در مجموع {{ $posts->total() }} مقاله ثبت شده است.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ route('admin.posts.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجوی عنوان…" class="admin-input w-48 py-2">
                    <button type="submit" class="admin-btn-ghost px-3 py-2">
                        <x-icon name="search" class="h-4 w-4" />
                    </button>
                </form>
                <a href="{{ route('admin.posts.create') }}" class="admin-btn-gold">
                    <x-icon name="plus" class="h-4 w-4" /> افزودن مقاله
                </a>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-right text-[12px]">
                    <thead class="bg-slate-50/70 text-[10px] uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">مقاله</th>
                            <th class="px-4 py-3 font-medium">دسته</th>
                            <th class="px-4 py-3 font-medium">تاریخ انتشار</th>
                            <th class="px-4 py-3 font-medium">زمان مطالعه</th>
                            <th class="px-4 py-3 font-medium">وضعیت</th>
                            <th class="px-6 py-3 font-medium">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($posts as $post)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($post->cover_image)
                                            <img src="{{ asset($post->cover_image) }}" alt="" class="h-11 w-14 shrink-0 rounded-lg border border-slate-200 object-cover">
                                        @else
                                            <span class="flex h-11 w-14 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                                <x-icon name="file" class="h-4 w-4" />
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-slate-700">{{ $post->title }}</p>
                                            <p class="mt-0.5 truncate text-[10px] text-slate-400" dir="ltr">/blog/{{ $post->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-600">{{ $post->category }}</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    {{ $post->published_at ? $post->published_at->locale('fa')->translatedFormat('j F Y') : '—' }}
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $post->reading_time }} دقیقه</td>
                                <td class="px-4 py-4">
                                    @if ($post->is_published)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-medium text-emerald-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> منتشرشده
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-500">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> پیش‌نویس
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('posts.show', $post) }}" target="_blank" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700" title="مشاهده در سایت">
                                            <x-icon name="eye" class="h-4 w-4" />
                                        </a>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-sky-600" title="ویرایش">
                                            <x-icon name="edit" class="h-4 w-4" />
                                        </a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('آیا از حذف این مقاله مطمئن هستید؟')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-red-50 hover:text-red-600" title="حذف">
                                                <x-icon name="trash" class="h-4 w-4" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <p class="text-[12px] text-slate-400">مقاله‌ای یافت نشد.</p>
                                    <a href="{{ route('admin.posts.create') }}" class="mt-3 inline-flex text-[11px] font-medium text-gold-600 hover:text-gold-700">افزودن اولین مقاله</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($posts->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">{{ $posts->links() }}</div>
            @endif
        </div>
    </div>
@endsection
