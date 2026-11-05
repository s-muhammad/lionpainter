@extends('admin.layouts.app')

@section('title', 'پروژه‌ها')
@section('subtitle', 'مدیریت نمونه‌کارهای سایت')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
            <div>
                <p class="text-[11px] text-slate-400">در مجموع {{ $projects->total() }} پروژه ثبت شده است.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ route('admin.projects.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجوی عنوان…" class="admin-input w-44 py-2">
                    <select name="category" class="admin-input w-36 py-2">
                        <option value="">همه دسته‌ها</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="admin-btn-ghost px-3 py-2">
                        <x-icon name="search" class="h-4 w-4" />
                    </button>
                </form>
                <a href="{{ route('admin.projects.create') }}" class="admin-btn-gold">
                    <x-icon name="plus" class="h-4 w-4" /> افزودن پروژه
                </a>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-right text-[12px]">
                    <thead class="bg-slate-50/70 text-[10px] uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">پروژه</th>
                            <th class="px-4 py-3 font-medium">دسته</th>
                            <th class="px-4 py-3 font-medium">منطقه / سال</th>
                            <th class="px-4 py-3 font-medium">ترتیب</th>
                            <th class="px-4 py-3 font-medium">وضعیت</th>
                            <th class="px-6 py-3 font-medium">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($projects as $project)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($project->cover_image)
                                            <img src="{{ asset($project->cover_image) }}" alt="" class="h-11 w-14 shrink-0 rounded-lg border border-slate-200 object-cover">
                                        @else
                                            <span class="flex h-11 w-14 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                                <x-icon name="image" class="h-4 w-4" />
                                            </span>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="flex items-center gap-2 font-semibold text-slate-700">
                                                {{ $project->title }}
                                                @if ($project->is_featured)
                                                    <span class="rounded-full bg-gold-500/15 px-2 py-0.5 text-[9px] font-medium text-gold-700">ویژه</span>
                                                @endif
                                            </p>
                                            <p class="mt-0.5 truncate text-[10px] text-slate-400" dir="ltr">/portfolio/{{ $project->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-600">{{ $project->category }}</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500">
                                    <p>{{ $project->suburb ?: '—' }}</p>
                                    <p class="mt-0.5 text-[10px] text-slate-400">{{ $project->year ?: 'بدون سال' }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $project->sort_order }}</td>
                                <td class="px-4 py-4">
                                    @if ($project->is_published)
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
                                        <a href="{{ route('projects.show', $project) }}" target="_blank" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700" title="مشاهده در سایت">
                                            <x-icon name="eye" class="h-4 w-4" />
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-sky-600" title="ویرایش">
                                            <x-icon name="edit" class="h-4 w-4" />
                                        </a>
                                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('آیا از حذف این پروژه مطمئن هستید؟')">
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
                                    <p class="text-[12px] text-slate-400">پروژه‌ای یافت نشد.</p>
                                    <a href="{{ route('admin.projects.create') }}" class="mt-3 inline-flex text-[11px] font-medium text-gold-600 hover:text-gold-700">افزودن اولین پروژه</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($projects->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">{{ $projects->links() }}</div>
            @endif
        </div>
    </div>
@endsection
