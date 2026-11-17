<div class="space-y-4" x-data="{ importOpen: {{ session()->has('import_error') || $errors->has('file') ? 'true' : 'false' }} }">
    <div class="flex flex-col justify-between gap-3 xl:flex-row xl:items-center">
        <div>
            <h2 class="text-[13px] font-bold text-slate-800">چک‌لیست کارها</h2>
            <p class="mt-0.5 text-[10px] text-slate-400">کارهای سئو را بر اساس مرحله مرتب کنید و پیشرفت را علامت بزنید.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.seo.tasks.export') }}" class="admin-btn-ghost py-2 text-[11px]">
                <x-icon name="file" class="h-3.5 w-3.5" />
                خروجی CSV
            </a>
            <button
                type="button"
                @click="importOpen = !importOpen"
                :aria-expanded="importOpen.toString()"
                aria-controls="task-import-panel"
                class="admin-btn-ghost py-2 text-[11px]"
            >
                <x-icon name="arrow-up-right" class="h-3.5 w-3.5" />
                ورود CSV
            </button>
            <button type="button" @click="$dispatch('add-task')" class="admin-btn-gold py-2 text-[11px]">
                <x-icon name="plus" class="h-3.5 w-3.5" />
                افزودن کار
            </button>
        </div>
    </div>

    @include('admin.seo.partials.import-result')

    <div x-show="importOpen" x-cloak id="task-import-panel" class="admin-card p-5">
        <form method="POST" action="{{ route('admin.seo.tasks.import') }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-[1fr_1.2fr] lg:items-end">
            @csrf
            <div>
                <label for="task-csv-file" class="admin-label">فایل CSV کارها</label>
                <input
                    id="task-csv-file"
                    name="file"
                    type="file"
                    accept=".csv,text/csv,text/plain"
                    required
                    class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-[11px] text-slate-600 file:me-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-[11px] file:font-medium file:text-slate-600"
                    @if ($errors->has('file')) aria-invalid="true" aria-describedby="task-csv-file-error" @endif
                >
                @error('file')
                    <p id="task-csv-file-error" class="mt-1.5 text-[10px] text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" class="admin-btn-primary mt-3 py-2 text-[11px]">
                    <x-icon name="arrow-up-right" class="h-3.5 w-3.5" />
                    وارد کردن فایل
                </button>
            </div>
            <div class="rounded-xl bg-slate-50 px-4 py-3 text-[10px] leading-relaxed text-slate-500">
                <p class="font-semibold text-slate-600">قواعد ورود</p>
                <p class="mt-1">همه ردیف‌ها به‌عنوان کار جدید ثبت می‌شوند. اگر ستون <span dir="ltr">keyword</span> با یک کلیدواژه موجود مطابق باشد، کار به آن متصل می‌شود.</p>
                <p class="mt-1.5 text-slate-400" dir="ltr">title, category, priority, status, phase, due_date, keyword, notes, sort_order, completed_at</p>
            </div>
        </form>
    </div>

    @include('admin.seo.partials.tasks.quick-add')
    @include('admin.seo.partials.tasks.filters')
    @include('admin.seo.partials.tasks.list')

    @include('admin.seo.partials.tasks.modal')
</div>
