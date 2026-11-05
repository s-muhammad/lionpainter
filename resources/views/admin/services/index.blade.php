@extends('admin.layouts.app')

@section('title', 'خدمات')
@section('subtitle', 'مدیریت خدمات نقاشی سایت')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <p class="text-[11px] text-slate-400">در مجموع {{ $services->total() }} خدمت ثبت شده است.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ route('admin.services.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجوی عنوان…" class="admin-input w-48 py-2">
                    <button type="submit" class="admin-btn-ghost px-3 py-2">
                        <x-icon name="search" class="h-4 w-4" />
                    </button>
                </form>
                <a href="{{ route('admin.services.create') }}" class="admin-btn-gold">
                    <x-icon name="plus" class="h-4 w-4" /> افزودن خدمت
                </a>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-right text-[12px]">
                    <thead class="bg-slate-50/70 text-[10px] uppercase tracking-wide text-slate-400">
                        <tr>
                            <th class="px-6 py-3 font-medium">خدمت</th>
                            <th class="px-4 py-3 font-medium">شعار</th>
                            <th class="px-4 py-3 font-medium">ترتیب</th>
                            <th class="px-4 py-3 font-medium">وضعیت</th>
                            <th class="px-6 py-3 font-medium">عملیات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($services as $service)
                            <tr class="transition-colors hover:bg-slate-50/60">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                            <x-icon :name="$service->icon" class="h-4 w-4" />
                                        </span>
                                        <div>
                                            <p class="font-semibold text-slate-700">{{ $service->title }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400" dir="ltr">/services/{{ $service->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="max-w-[220px] px-4 py-4">
                                    <p class="truncate text-slate-500">{{ $service->tagline ?: '—' }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $service->sort_order }}</td>
                                <td class="px-4 py-4">
                                    @if ($service->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-medium text-emerald-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> فعال
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-medium text-slate-500">
                                            <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> غیرفعال
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('services.show', $service) }}" target="_blank" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700" title="مشاهده در سایت">
                                            <x-icon name="eye" class="h-4 w-4" />
                                        </a>
                                        <a href="{{ route('admin.services.edit', $service) }}" class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-slate-100 hover:text-sky-600" title="ویرایش">
                                            <x-icon name="edit" class="h-4 w-4" />
                                        </a>
                                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('آیا از حذف این خدمت مطمئن هستید؟')">
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
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <p class="text-[12px] text-slate-400">خدمتی یافت نشد.</p>
                                    <a href="{{ route('admin.services.create') }}" class="mt-3 inline-flex text-[11px] font-medium text-gold-600 hover:text-gold-700">افزودن اولین خدمت</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($services->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">{{ $services->links() }}</div>
            @endif
        </div>
    </div>
@endsection
