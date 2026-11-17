@php
    use App\Enums\TaskCategory;

    $quickTaskOld = old('_modal') === 'task-quick';
    $quickCategory = $quickTaskOld ? old('category', TaskCategory::OnPage->value) : TaskCategory::OnPage->value;
    $quickPriority = $quickTaskOld ? old('priority', 2) : 2;
@endphp

<form method="POST" action="{{ route('admin.seo.tasks.store') }}" class="admin-card p-4">
    @csrf
    <input type="hidden" name="_modal" value="task-quick">

    <div class="grid gap-3 md:grid-cols-[1fr_180px_150px_auto] md:items-end">
        <div>
            <label for="quick-task-title" class="admin-label">کار جدید</label>
            <input
                id="quick-task-title"
                name="title"
                type="text"
                required
                value="{{ $quickTaskOld ? old('title') : '' }}"
                dir="auto"
                class="admin-input"
                placeholder="مثلاً افزودن نقشه سایت به سرچ کنسول"
                @if ($quickTaskOld && $errors->has('title')) aria-invalid="true" aria-describedby="quick-task-title-error" @endif
            >
        </div>

        <div>
            <label for="quick-task-category" class="admin-label">دسته‌بندی</label>
            <select id="quick-task-category" name="category" required class="admin-input">
                @foreach ($taskCategories as $category)
                    <option value="{{ $category->value }}" @selected($quickCategory === $category->value)>{{ $category->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="quick-task-priority" class="admin-label">اولویت</label>
            <select id="quick-task-priority" name="priority" required class="admin-input">
                @foreach ($priorityOptions as $value => $label)
                    <option value="{{ $value }}" @selected((int) $quickPriority === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="admin-btn-gold h-[42px] text-[11px]">
            <x-icon name="plus" class="h-3.5 w-3.5" />
            افزودن
        </button>
    </div>

    @if ($quickTaskOld && $errors->any())
        <p id="quick-task-title-error" class="mt-2 text-[10px] text-red-600">{{ $errors->first() }}</p>
    @endif

    <p class="mt-2 text-[10px] text-slate-400">برای تعیین مرحله، مهلت یا اتصال به کلیدواژه، کار را بعداً ویرایش کنید.</p>
</form>
