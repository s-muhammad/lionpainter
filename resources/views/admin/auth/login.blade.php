<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#0c1e2e">
    <title>ورود به پنل مدیریت | Lion Painters</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-ink-950 font-fa text-slate-200">
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="relative hidden overflow-hidden lg:block">
            <img src="{{ asset('images/hero.svg') }}" alt="" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-l from-ink-950/20 via-ink-950/70 to-ink-950"></div>
            <div class="relative flex h-full flex-col justify-between p-12">
                <x-logo theme="dark" />
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-gold-400">Lion Painters Studio</p>
                    <h1 class="mt-4 max-w-md font-display text-5xl leading-[1.05] text-white">مدیریت محتوا،<br><span class="text-gold-300">با ظرافت.</span></h1>
                    <p class="mt-5 max-w-sm text-sm leading-relaxed text-slate-400">از این پنل می‌توانید خدمات، پروژه‌ها، مقالات، نظرات و درخواست‌های مشتریان را مدیریت کنید.</p>
                </div>
                <p class="text-[10px] tracking-wide text-slate-500">© {{ date('Y') }} Lion Painters — تمامی حقوق محفوظ است.</p>
            </div>
        </div>

        <div class="flex items-center justify-center px-5 py-12 sm:px-10">
            <div class="w-full max-w-md">
                <div class="mb-8 lg:hidden">
                    <x-logo theme="dark" />
                </div>
                <p class="text-[11px] font-medium uppercase tracking-[0.18em] text-gold-400">پنل مدیریت</p>
                <h2 class="mt-3 text-3xl font-bold text-white">ورود به حساب کاربری</h2>
                <p class="mt-2 text-[13px] leading-relaxed text-slate-400">برای مدیریت سایت، ایمیل و رمز عبور خود را وارد کنید.</p>

                <form action="{{ route('admin.login.store') }}" method="POST" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="mb-2 block text-xs font-medium text-slate-300">ایمیل</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" dir="ltr"
                               class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-left text-sm text-white placeholder:text-slate-500 focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-500/20" placeholder="admin@lionpainters.com.au">
                        @error('email') <p class="mt-1.5 text-[11px] text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="mb-2 block text-xs font-medium text-slate-300">رمز عبور</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password" dir="ltr"
                               class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-left text-sm text-white placeholder:text-slate-500 focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-500/20" placeholder="••••••••">
                        @error('password') <p class="mt-1.5 text-[11px] text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <label class="flex cursor-pointer items-center gap-2 text-[12px] text-slate-400">
                        <input type="checkbox" name="remember" class="h-3.5 w-3.5 rounded border-white/20 bg-white/5 accent-[#c6a45f]">
                        مرا به خاطر بسپار
                    </label>
                    <button type="submit" class="w-full rounded-xl bg-gold-500 py-3 text-sm font-bold text-ink-950 transition-colors hover:bg-gold-400">
                        ورود به پنل
                    </button>
                </form>

                <div class="mt-8 flex items-center justify-between border-t border-white/10 pt-6 text-[11px] text-slate-500">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 transition-colors hover:text-gold-300">
                        <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                        بازگشت به سایت
                    </a>
                    <span>Lion Painters</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
