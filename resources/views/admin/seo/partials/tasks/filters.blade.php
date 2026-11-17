<form method="GET" action="{{ route('admin.seo.index') }}" class="admin-card p-4">
    <input type="hidden" name="tab" value="tasks">

    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-5">
        <div>
            <label for="task-filter-status" class="admin-label">وضعیت</label>
            <select id="task-filter-status" name="t_status" class="admin-input">
                <option value="">همه وضعیت‌ها</option>
                @foreach ($taskStatuses as $status)
                    <option value="{{ $status->value }}" @selected($taskFilters['status'] === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="task-filter-category" class="admin-label">دسته‌بندی</label>
            <select id="task-filter-category" name="t_category" class="admin-input">
                <option value="">همه دسته‌ها</option>
                @foreach ($taskCategories as $category)
                    <option value="{{ $category->value }}" @selected($taskFilters['category'] === $category->value)>{{ $category->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="task-filter-priority" class="admin-label">اولویت</label>
            <select id="task-filter-priority" name="t_priority" class="admin-input">
                <option value="">همه اولویت‌ها</option>
                @foreach ($priorityOptions as $value => $label)
                    <option value="{{ $value }}" @selected($taskFilters['priority'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="task-filter-phase" class="admin-label">مرحله</label>
            <select id="task-filter-phase" name="t_phase" class="admin-input">
                <option value="">همه مراحل</option>
                @foreach ($phases as $phase)
                    <option value="{{ $phase }}" @selected($taskFilters['phase'] === $phase)>{{ $phase }}</option>
                @endforeach
                @if ($hasNoPhase)
                    <option value="__none__" @selected($taskFilters['phase'] === '__none__')>بدون مرحله</option>
                @endif
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="admin-btn-primary flex-1 py-2.5 text-[11px]">
                <x-icon name="search" class="h-3.5 w-3.5" />
                اعمال
            </button>
            <a href="{{ route('admin.seo.index', ['tab' => 'tasks']) }}" class="admin-btn-ghost py-2.5 text-[11px]">پاک کردن</a>
        </div>
    </div>
</form>
