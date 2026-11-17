<form method="GET" action="{{ route('admin.seo.index') }}" class="admin-card p-4">
    <input type="hidden" name="tab" value="keywords">
    @if ($filters['sort'] !== 'keyword' || $filters['direction'] !== 'asc')
        <input type="hidden" name="k_sort" value="{{ $filters['sort'] }}">
        <input type="hidden" name="k_dir" value="{{ $filters['direction'] }}">
    @endif

    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
        <div class="md:col-span-2">
            <label for="keyword-search" class="admin-label">جستجو</label>
            <div class="relative">
                <x-icon name="search" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                <input
                    id="keyword-search"
                    name="k_search"
                    type="search"
                    value="{{ $filters['search'] }}"
                    placeholder="کلیدواژه، خوشه یا آدرس هدف…"
                    dir="auto"
                    class="admin-input pr-10"
                >
            </div>
        </div>

        <div>
            <label for="keyword-filter-status" class="admin-label">وضعیت</label>
            <select id="keyword-filter-status" name="k_status" class="admin-input">
                <option value="">همه وضعیت‌ها</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected($filters['status'] === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="keyword-filter-priority" class="admin-label">اولویت</label>
            <select id="keyword-filter-priority" name="k_priority" class="admin-input">
                <option value="">همه اولویت‌ها</option>
                @foreach ($priorityOptions as $value => $label)
                    <option value="{{ $value }}" @selected($filters['priority'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="keyword-filter-cluster" class="admin-label">خوشه</label>
            <select id="keyword-filter-cluster" name="k_cluster" class="admin-input">
                <option value="">همه خوشه‌ها</option>
                @foreach ($clusters as $cluster)
                    <option value="{{ $cluster }}" @selected($filters['cluster'] === $cluster)>{{ $cluster }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="keyword-filter-intent" class="admin-label">قصد جستجو</label>
            <select id="keyword-filter-intent" name="k_intent" class="admin-input">
                <option value="">همه قصدها</option>
                @foreach ($intents as $intent)
                    <option value="{{ $intent->value }}" @selected($filters['intent'] === $intent->value)>{{ $intent->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="keyword-filter-content-type" class="admin-label">نوع محتوا</label>
            <select id="keyword-filter-content-type" name="k_content_type" class="admin-input">
                <option value="">همه انواع</option>
                @foreach ($contentTypes as $contentType)
                    <option value="{{ $contentType->value }}" @selected($filters['content_type'] === $contentType->value)>{{ $contentType->label() }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end gap-2 md:col-span-2 xl:col-span-2">
            <button type="submit" class="admin-btn-primary flex-1 py-2.5 text-[11px]">
                <x-icon name="search" class="h-3.5 w-3.5" />
                اعمال فیلترها
            </button>
            <a href="{{ route('admin.seo.index', ['tab' => 'keywords']) }}" class="admin-btn-ghost py-2.5 text-[11px]">پاک کردن</a>
        </div>
    </div>
</form>
