@php
    use App\Enums\TaskCategory;
    use App\Enums\TaskStatus;

    $taskModalOpen = $errors->any() && old('_modal') === 'task';

    $taskDefaults = [
        'id' => null,
        'title' => '',
        'category' => TaskCategory::OnPage->value,
        'priority' => 2,
        'status' => TaskStatus::Todo->value,
        'phase' => '',
        'due_date' => '',
        'keyword_id' => '',
        'notes' => '',
        'sort_order' => 0,
    ];

    $taskModalInitial = $taskModalOpen
        ? array_merge($taskDefaults, [
            'id' => old('_task_id'),
            'title' => old('title', ''),
            'category' => old('category', $taskDefaults['category']),
            'priority' => old('priority', 2),
            'status' => old('status', $taskDefaults['status']),
            'phase' => old('phase', ''),
            'due_date' => old('due_date', ''),
            'keyword_id' => old('keyword_id', ''),
            'notes' => old('notes', ''),
            'sort_order' => old('sort_order', 0),
        ])
        : $taskDefaults;
@endphp

<div
    x-data="seoTaskModal({
        open: {{ $taskModalOpen ? 'true' : 'false' }},
        fields: @js($taskModalInitial),
        defaults: @js($taskDefaults),
        storeUrl: @js(route('admin.seo.tasks.store')),
        updateUrl: @js(route('admin.seo.tasks.update', ['task' => '__ID__']))
    })"
    @add-task.window="showAdd()"
    @edit-task.window="showEdit($event.detail)"
    @keydown.escape.window="close()"
>
    {{-- Inline display:none keeps the dialog hidden even if the Alpine component fails to boot. --}}
    <div x-show="open" x-cloak style="display: none" class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="task-modal-title">
        <div class="flex min-h-full items-end justify-center p-3 sm:items-center sm:p-6">
            <div class="fixed inset-0 bg-ink-950/60 backdrop-blur-sm" @click="close()" aria-hidden="true"></div>

            <div class="relative w-full max-w-xl overflow-hidden rounded-2xl bg-white shadow-2xl" x-transition.opacity.duration.150ms>
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 id="task-modal-title" class="text-[14px] font-bold text-slate-800" x-text="editing ? 'ویرایش کار' : 'افزودن کار'"></h2>
                        <p class="mt-0.5 text-[10px] text-slate-400">جزئیات کار سئو را تکمیل کنید.</p>
                    </div>
                    <button type="button" @click="close()" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600" aria-label="بستن پنجره">
                        <x-icon name="close" class="h-4 w-4" />
                    </button>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.seo.tasks.store') }}"
                    :action="action"
                    class="max-h-[calc(100dvh-6rem)] overflow-y-auto px-5 py-4"
                >
                    @csrf
                    <input type="hidden" name="_method" :value="editing ? 'PUT' : 'POST'">
                    <input type="hidden" name="_modal" value="task">
                    <input type="hidden" name="_task_id" :value="fields.id ?? ''">

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="task-form-title" class="admin-label">عنوان کار <span class="text-red-500">*</span></label>
                            <input
                                x-ref="firstInput"
                                x-model="fields.title"
                                id="task-form-title"
                                name="title"
                                type="text"
                                required
                                dir="auto"
                                class="admin-input"
                                placeholder="مثلاً بهینه‌سازی عنوان صفحه خدمات"
                                @if ($taskModalOpen && $errors->has('title')) aria-invalid="true" aria-describedby="task-form-title-error" @endif
                            >
                            @if ($taskModalOpen)
                                @error('title') <p id="task-form-title-error" class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="task-form-category" class="admin-label">دسته‌بندی <span class="text-red-500">*</span></label>
                            <select x-model="fields.category" id="task-form-category" name="category" required class="admin-input">
                                @foreach ($taskCategories as $category)
                                    <option value="{{ $category->value }}">{{ $category->label() }}</option>
                                @endforeach
                            </select>
                            @if ($taskModalOpen)
                                @error('category') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="task-form-priority" class="admin-label">اولویت <span class="text-red-500">*</span></label>
                            <select x-model="fields.priority" id="task-form-priority" name="priority" required class="admin-input">
                                @foreach ($priorityOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @if ($taskModalOpen)
                                @error('priority') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="task-form-status" class="admin-label">وضعیت <span class="text-red-500">*</span></label>
                            <select x-model="fields.status" id="task-form-status" name="status" required class="admin-input">
                                @foreach ($taskStatuses as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                            @if ($taskModalOpen)
                                @error('status') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="task-form-phase" class="admin-label">مرحله</label>
                            <input x-model="fields.phase" id="task-form-phase" name="phase" type="text" list="task-phases" dir="auto" class="admin-input" placeholder="هفته ۱، ماه ۲، ماهانه…">
                            <datalist id="task-phases">
                                @foreach ($phases as $phase)
                                    <option value="{{ $phase }}"></option>
                                @endforeach
                            </datalist>
                            @if ($taskModalOpen)
                                @error('phase') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="task-form-due-date" class="admin-label">مهلت انجام</label>
                            <input x-model="fields.due_date" id="task-form-due-date" name="due_date" type="date" class="admin-input">
                            @if ($taskModalOpen)
                                @error('due_date') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="task-form-keyword" class="admin-label">کلیدواژه مرتبط</label>
                            <select x-model="fields.keyword_id" id="task-form-keyword" name="keyword_id" class="admin-input">
                                <option value="">بدون اتصال</option>
                                @foreach ($keywordOptions as $keywordOption)
                                    <option value="{{ $keywordOption->id }}">{{ $keywordOption->keyword }}</option>
                                @endforeach
                            </select>
                            @if ($taskModalOpen)
                                @error('keyword_id') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="task-form-sort-order" class="admin-label">ترتیب نمایش</label>
                            <input x-model="fields.sort_order" id="task-form-sort-order" name="sort_order" type="number" min="0" step="1" inputmode="numeric" class="admin-input">
                            @if ($taskModalOpen)
                                @error('sort_order') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div class="sm:col-span-2">
                            <label for="task-form-notes" class="admin-label">یادداشت</label>
                            <textarea x-model="fields.notes" id="task-form-notes" name="notes" rows="3" class="admin-input" placeholder="جزئیات اجرا، لینک منابع یا نکات مهم…"></textarea>
                            @if ($taskModalOpen)
                                @error('notes') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>
                    </div>

                    <p class="mt-3 text-[10px] text-slate-400">با انتخاب وضعیت «انجام‌شده»، زمان تکمیل به‌صورت خودکار ثبت می‌شود؛ با خارج شدن از این وضعیت پاک می‌شود.</p>

                    <div class="mt-5 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                        <button type="button" @click="close()" class="admin-btn-ghost text-[11px]">انصراف</button>
                        <button type="submit" class="admin-btn-gold text-[11px]" x-text="editing ? 'ذخیره تغییرات' : 'افزودن کار'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
