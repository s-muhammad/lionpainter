@if (session('import_result'))
    @php
        $result = session('import_result');
    @endphp
    <div class="admin-card border-emerald-200 bg-emerald-50/40 p-4" role="status" aria-live="polite">
        <div class="flex flex-wrap items-center gap-2">
            <x-icon name="check-circle" class="h-4 w-4 text-emerald-600" />
            <p class="text-[12px] font-semibold text-slate-700">نتیجه ورود CSV</p>
            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-medium text-emerald-700">ایجادشده: {{ number_format($result['created']) }}</span>
            <span class="rounded-full bg-sky-100 px-2.5 py-1 text-[10px] font-medium text-sky-700">به‌روزرسانی: {{ number_format($result['updated']) }}</span>
            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-medium text-amber-700">ردشده: {{ number_format($result['skipped']) }}</span>
        </div>

        @if (! empty($result['skipped_rows']) || ! empty($result['warnings']))
            <details class="mt-3">
                <summary class="cursor-pointer text-[11px] font-medium text-slate-500 hover:text-slate-700">جزئیات ردیف‌ها</summary>
                <ul class="mt-2 space-y-1 text-[11px] leading-relaxed">
                    @foreach ($result['skipped_rows'] as $row)
                        <li class="rounded-lg bg-white/70 px-3 py-1.5 text-red-600">ردیف {{ $row['row'] }}: {{ $row['reason'] }}</li>
                    @endforeach
                    @foreach ($result['warnings'] as $row)
                        <li class="rounded-lg bg-white/70 px-3 py-1.5 text-amber-700">هشدار ردیف {{ $row['row'] }}: {{ $row['reason'] }}</li>
                    @endforeach
                </ul>
            </details>
        @endif
    </div>
@endif

@if (session('import_error'))
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-[12px] text-red-700" role="alert">
        <p class="font-semibold">ورود CSV انجام نشد.</p>
        <p class="mt-1">{{ session('import_error') }}</p>
    </div>
@endif
