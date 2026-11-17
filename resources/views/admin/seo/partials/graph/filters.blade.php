<form method="GET" action="{{ route('admin.seo.index') }}" class="admin-card p-4">
    <input type="hidden" name="tab" value="graph">

    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
        <div class="md:col-span-2">
            <label for="graph-search" class="admin-label">جستجو</label>
            <div class="relative">
                <x-icon name="search" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input
                    id="graph-search"
                    name="g_search"
                    type="search"
                    value="{{ $graphFilters['search'] }}"
                    placeholder="کلمه، صفحه هدف یا عنوان مقاله…"
                    dir="auto"
                    class="admin-input pr-10"
                >
            </div>
        </div>

        <div>
            <label for="graph-status" class="admin-label">وضعیت</label>
            <select id="graph-status" name="g_status" class="admin-input">
                <option value="">همه وضعیت‌ها</option>
                @foreach ($graphStatuses as $status)
                    <option value="{{ $status->value }}" @selected($graphFilters['status'] === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="graph-priority" class="admin-label">اولویت</label>
            <select id="graph-priority" name="g_priority" class="admin-input">
                <option value="">همه اولویت‌ها</option>
                @foreach ($priorityOptions as $value => $label)
                    <option value="{{ $value }}" @selected($graphFilters['priority'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="graph-content-type" class="admin-label">نوع صفحه</label>
            <select id="graph-content-type" name="g_content_type" class="admin-input">
                <option value="">همه انواع</option>
                @foreach ($graphContentTypes as $contentType)
                    <option value="{{ $contentType->value }}" @selected($graphFilters['content_type'] === $contentType->value)>{{ $contentType->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2">
            <label for="graph-target" class="admin-label">صفحه هدف</label>
            <select id="graph-target" name="g_target" class="admin-input" dir="ltr">
                <option value="">همه صفحات</option>
                @foreach ($graphTargets as $target)
                    <option value="{{ $target }}" @selected($graphFilters['target'] === $target)>{{ $target }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-2 md:col-span-2 xl:col-span-3">
            <button type="submit" class="admin-btn-gold px-4 py-2.5 text-[11px]">
                <x-icon name="search" class="h-3.5 w-3.5" />
                اعمال فیلتر
            </button>
            <a href="{{ route('admin.seo.index', ['tab' => 'graph']) }}" class="admin-btn-ghost px-3 py-2.5 text-[11px]">پاک کردن</a>
            <p class="ms-auto hidden text-[10px] text-slate-400 lg:block">با کلیک روی هر کلمه، بریف کامل آن در کنار گراف نمایش داده می‌شود.</p>
        </div>
    </div>
</form>
