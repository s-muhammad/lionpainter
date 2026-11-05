@php
    use App\Models\ContactMessage;
    use App\Models\QuoteRequest;

    $newQuotes = QuoteRequest::new()->count();
    $unreadMessages = ContactMessage::unread()->count();

    $navigation = [
        ['label' => 'داشبورد', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'grid'],
        ['label' => 'خدمات', 'route' => 'admin.services.index', 'active' => 'admin.services.*', 'icon' => 'brush'],
        ['label' => 'پروژه‌ها', 'route' => 'admin.projects.index', 'active' => 'admin.projects.*', 'icon' => 'image'],
        ['label' => 'مقالات', 'route' => 'admin.posts.index', 'active' => 'admin.posts.*', 'icon' => 'file'],
        ['label' => 'نظرات مشتریان', 'route' => 'admin.testimonials.index', 'active' => 'admin.testimonials.*', 'icon' => 'quote'],
        ['label' => 'سؤالات متداول', 'route' => 'admin.faqs.index', 'active' => 'admin.faqs.*', 'icon' => 'message'],
        ['label' => 'درخواست‌های قیمت', 'route' => 'admin.quotes.index', 'active' => 'admin.quotes.*', 'icon' => 'file', 'badge' => $newQuotes],
        ['label' => 'پیام‌ها', 'route' => 'admin.messages.index', 'active' => 'admin.messages.*', 'icon' => 'mail', 'badge' => $unreadMessages],
        ['label' => 'تنظیمات سایت', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*', 'icon' => 'settings'],
    ];
@endphp

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#0c1e2e">
    <title>@yield('title', 'پنل مدیریت') | Lion Painters</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-dvh overflow-hidden bg-slate-100 font-fa text-slate-800" x-data="{ sidebar: false }">
    <div class="h-full">
        {{-- Sidebar --}}
        <div x-show="sidebar" x-cloak class="fixed inset-0 z-40 bg-ink-950/60 backdrop-blur-sm lg:hidden" @click="sidebar = false"></div>

        <aside
            class="admin-sidebar fixed inset-y-0 right-0 z-50 flex h-dvh w-72 flex-col overflow-hidden bg-ink-950 text-slate-300 transition-transform duration-300 lg:translate-x-0"
            :class="sidebar ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
        >
            <div class="flex h-16 shrink-0 items-center justify-between border-b border-white/10 px-4">
                <x-logo theme="dark" compact />
                <button type="button" class="text-slate-400 hover:text-white lg:hidden" @click="sidebar = false" aria-label="بستن منو">
                    <x-icon name="close" class="h-5 w-5" />
                </button>
            </div>
            <div class="shrink-0 px-4 pt-3">
                <p class="text-[9px] font-medium tracking-wide text-slate-500">پنل مدیریت سایت</p>
                <p class="mt-0.5 text-[12px] font-semibold text-slate-200">خوش آمدید، {{ auth()->user()->name }}</p>
            </div>

            <nav class="mt-2 min-h-0 flex-1 space-y-0.5 overflow-y-auto overscroll-contain px-2 pb-3" aria-label="منوی مدیریت">
                @foreach ($navigation as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-[12px] transition-colors {{ request()->routeIs($item['active']) ? 'bg-white/10 font-semibold text-white' : 'text-slate-400 hover:bg-white/5 hover:text-slate-200' }}"
                    >
                        <span class="flex h-7 w-7 items-center justify-center rounded-md {{ request()->routeIs($item['active']) ? 'bg-gold-500/15 text-gold-400' : 'text-slate-500' }}">
                            <x-icon :name="$item['icon']" class="h-3.5 w-3.5" />
                        </span>
                        <span class="flex-1">{{ $item['label'] }}</span>
                        @if (! empty($item['badge']))
                            <span class="rounded-full bg-gold-500 px-2 py-0.5 text-[10px] font-bold text-ink-950">{{ $item['badge'] }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="shrink-0 border-t border-white/10 p-2">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-[11px] text-slate-400 transition-colors hover:bg-white/5 hover:text-slate-200">
                    <x-icon name="arrow-up-right" class="h-3.5 w-3.5" />
                    مشاهده سایت
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-[11px] text-slate-400 transition-colors hover:bg-red-500/10 hover:text-red-300">
                        <x-icon name="logout" class="h-3.5 w-3.5" />
                        خروج از حساب
                    </button>
                </form>
                <p class="px-2.5 pt-1.5 text-[8px] text-slate-600">Lion Painters · نسخه مدیریت</p>
            </div>
        </aside>

        {{-- Content --}}
        <div class="flex h-full flex-col lg:mr-72">
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between gap-4 border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 lg:hidden" @click="sidebar = true" aria-label="باز کردن منو">
                        <x-icon name="menu" class="h-4 w-4" />
                    </button>
                    <div>
                        <h1 class="text-[15px] font-bold text-slate-800">@yield('title', 'داشبورد')</h1>
                        <p class="hidden text-[10px] text-slate-400 sm:block">@yield('subtitle', 'مدیریت محتوای سایت لاین پینترز')</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" target="_blank" class="hidden items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-[11px] text-slate-500 transition-colors hover:border-slate-300 hover:text-slate-700 sm:flex">
                        <x-icon name="eye" class="h-3.5 w-3.5" />
                        مشاهده سایت
                    </a>
                    <div class="flex items-center gap-2.5 rounded-full border border-slate-200 py-1 pl-3 pr-1">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-ink-900 text-[10px] font-bold text-gold-400">{{ mb_substr(auth()->user()->name, 0, 2) }}</span>
                        <span class="text-[11px] font-medium text-slate-600">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>

            <main class="min-h-0 flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @if (session('status'))
                    <div class="mb-5 flex items-center justify-between gap-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-[13px] text-emerald-700">
                        <span class="flex items-center gap-2">
                            <x-icon name="check-circle" class="h-4 w-4" />
                            {{ session('status') }}
                        </span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-[12px] text-red-700">
                        <p class="font-semibold">لطفاً خطاهای زیر را بررسی کنید:</p>
                        <ul class="mt-1 list-inside list-disc space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
