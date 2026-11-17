<div class="space-y-4" x-data="{ importOpen: {{ session()->has('import_error') || $errors->has('file') ? 'true' : 'false' }} }">
    <div class="flex flex-col justify-between gap-3 xl:flex-row xl:items-center">
        <div>
            <h2 class="text-[13px] font-bold text-slate-800">{{ $viewMode === 'plan' ? 'برنامه محتوا' : 'فهرست کلیدواژه‌ها' }}</h2>
            <p class="mt-0.5 text-[10px] text-slate-400">
                {{ $viewMode === 'plan' ? 'کلیدواژه‌های برنامه‌ریزی‌شده و در حال انجام، مرتب بر اساس اولویت و تاریخ هدف.' : 'جستجو، فیلتر و به‌روزرسانی کلیدواژه‌های این سایت.' }}
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if ($viewMode === 'plan')
                <a href="{{ route('admin.seo.index', array_merge(request()->except(['k_view', 'k_page']), ['tab' => 'keywords'])) }}" class="admin-btn-ghost py-2 text-[11px]">
                    <x-icon name="grid" class="h-3.5 w-3.5" />
                    نمای جدول
                </a>
            @else
                <a href="{{ route('admin.seo.index', array_merge(request()->except('k_page'), ['tab' => 'keywords', 'k_view' => 'plan'])) }}" class="admin-btn-ghost py-2 text-[11px]">
                    <x-icon name="layers" class="h-3.5 w-3.5" />
                    نمای برنامه
                </a>
            @endif
            <a href="{{ route('admin.seo.keywords.export') }}" class="admin-btn-ghost py-2 text-[11px]">
                <x-icon name="file" class="h-3.5 w-3.5" />
                خروجی CSV
            </a>
            <button
                type="button"
                @click="importOpen = !importOpen"
                :aria-expanded="importOpen.toString()"
                aria-controls="keyword-import-panel"
                class="admin-btn-ghost py-2 text-[11px]"
            >
                <x-icon name="arrow-up-right" class="h-3.5 w-3.5" />
                ورود CSV
            </button>
            <button type="button" @click="$dispatch('add-keyword')" class="admin-btn-gold py-2 text-[11px]">
                <x-icon name="plus" class="h-3.5 w-3.5" />
                افزودن کلیدواژه
            </button>
        </div>
    </div>

    @include('admin.seo.partials.import-result')

    <div x-show="importOpen" x-cloak id="keyword-import-panel" class="admin-card p-5">
        <form method="POST" action="{{ route('admin.seo.keywords.import') }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-[1fr_1.2fr] lg:items-end">
            @csrf
            <div>
                <label for="keyword-csv-file" class="admin-label">فایل CSV کلیدواژه‌ها</label>
                <input
                    id="keyword-csv-file"
                    name="file"
                    type="file"
                    accept=".csv,text/csv,text/plain"
                    required
                    class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-[11px] file:font-medium file:text-slate-600"
                    @if ($errors->has('file')) aria-invalid="true" aria-describedby="keyword-csv-file-error" @endif
                >
                @error('file')
                    <p id="keyword-csv-file-error" class="mt-1.5 text-[10px] text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" class="admin-btn-primary mt-3 py-2 text-[11px]">
                    <x-icon name="arrow-up-right" class="h-3.5 w-3.5" />
                    وارد کردن فایل
                </button>
            </div>
            <div class="rounded-xl bg-slate-50 px-4 py-3 text-[10px] leading-relaxed text-slate-500">
                <p class="font-semibold text-slate-600">قواعد ورود</p>
                <p class="mt-1">کلیدواژه تکراری به‌روزرسانی می‌شود. ردیف‌های نامعتبر نادیده گرفته می‌شوند و نتیجه در بالای صفحه نمایش داده می‌شود.</p>
                <p class="mt-1.5 text-slate-400" dir="ltr">keyword, cluster, intent, content_type, target_url, priority, status, difficulty, search_volume, current_position, target_date, notes</p>
            </div>
        </form>
    </div>

    @if ($viewMode === 'plan')
        @include('admin.seo.partials.keywords.plan')
    @else
        @include('admin.seo.partials.keywords.filters')
        @include('admin.seo.partials.keywords.table')
    @endif

    @include('admin.seo.partials.keywords.modal')
</div>
