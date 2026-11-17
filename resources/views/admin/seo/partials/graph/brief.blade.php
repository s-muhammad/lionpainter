<aside class="admin-card flex min-w-0 flex-col overflow-hidden">
    <header class="border-b border-slate-100 px-4 py-3">
        <p class="text-[10px] font-medium text-gold-700">بریف کلمه کلیدی</p>
        <h3 class="mt-1 text-[12px] font-bold leading-5 text-slate-800" x-text="selected ? selected.label : 'کلمه‌ای انتخاب نشده'"></h3>

        <div class="mt-2 flex flex-wrap items-center gap-1.5" x-show="selected">
            <span
                class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-medium"
                :style="`background: ${selected?.statusBg}; color: ${selected?.statusText}`"
                x-text="selected?.statusLabel"
            ></span>
            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-medium text-slate-500">
                اولویت
                <span :style="`color: ${selected?.priorityText}`" x-text="selected?.priorityLabel"></span>
            </span>
            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[9px] font-medium text-slate-500" x-text="selected?.intentLabel"></span>
        </div>
    </header>

    <template x-if="selected">
        <div class="flex flex-1 flex-col gap-4 overflow-y-auto px-4 py-3">
            <dl class="space-y-2.5 text-[11px]">
                <div class="flex items-start justify-between gap-3">
                    <dt class="shrink-0 text-slate-400">صفحه هدف</dt>
                    <dd class="min-w-0 text-left">
                        <template x-if="selected.targetUrl">
                            <a
                                :href="selected.targetUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                dir="ltr"
                                class="block max-w-[11rem] truncate text-gold-700 hover:underline"
                                x-text="selected.targetUrl"
                            ></a>
                        </template>
                        <template x-if="!selected.targetUrl">
                            <span class="text-slate-400">تعیین نشده</span>
                        </template>
                    </dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="shrink-0 text-slate-400">نوع صفحه</dt>
                    <dd class="min-w-0 text-left text-slate-600" x-text="selected.contentTypeLabel"></dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="shrink-0 text-slate-400">رتبه فعلی</dt>
                    <dd class="min-w-0 text-left text-slate-600" x-text="selected.position ?? '—'"></dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="shrink-0 text-slate-400">حجم جستجو</dt>
                    <dd class="min-w-0 text-left text-slate-600" x-text="selected.volume === null ? '—' : new Intl.NumberFormat('en-US').format(selected.volume)"></dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="shrink-0 text-slate-400">سختی</dt>
                    <dd class="min-w-0 text-left text-slate-600" x-text="selected.difficultyLabel ?? '—'"></dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="shrink-0 text-slate-400">تاریخ هدف</dt>
                    <dd class="min-w-0 text-left text-slate-600" x-text="selected.targetDate ?? '—'"></dd>
                </div>
                <div class="flex items-start justify-between gap-3">
                    <dt class="shrink-0 text-slate-400">گروه</dt>
                    <dd class="min-w-0 truncate text-left text-slate-600" :title="selected.groups?.[groupBy]" x-text="selected.groups?.[groupBy]"></dd>
                </div>
            </dl>

            <div class="border-t border-slate-100 pt-3">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-[10px] font-semibold text-slate-500">تسک‌ها</p>
                    <span class="text-[9px] text-slate-400"><span x-text="selected.tasksCount"></span> تسک</span>
                </div>

                <template x-if="selected.tasks.length === 0">
                    <p class="mt-1.5 rounded-lg bg-slate-50 px-3 py-2 text-[10px] text-slate-400">تسکی به این کلمه متصل نیست.</p>
                </template>

                <ul class="mt-1.5 space-y-1.5">
                    <template x-for="task in selected.tasks" :key="task.id">
                        <li class="flex items-start gap-2 rounded-lg border border-slate-100 px-2.5 py-1.5">
                            <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full" :class="task.done ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                            <span
                                class="min-w-0 flex-1 text-[10px] leading-4"
                                :class="task.done ? 'text-slate-400 line-through' : 'text-slate-600'"
                                x-text="task.title"
                            ></span>
                        </li>
                    </template>
                </ul>
            </div>

            <template x-if="selected.targetUrl">
                <a
                    :href="selected.targetUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="admin-btn-ghost mt-auto w-full justify-center py-2 text-[10px]"
                >
                    <x-icon name="arrow-up-right" class="h-3.5 w-3.5" />
                    مشاهده صفحه هدف
                </a>
            </template>
        </div>
    </template>

    <template x-if="!selected">
        <div class="flex flex-1 flex-col items-center justify-center gap-2 px-5 py-10 text-center">
            <x-icon name="target" class="h-6 w-6 text-slate-300" />
            <p class="text-[11px] text-slate-500">روی یک گره در گراف کلیک کنید.</p>
            <p class="text-[10px] text-slate-400">بریف کامل کلمه، صفحه هدف و تسک‌های مرتبط همین‌جا نمایش داده می‌شود.</p>
        </div>
    </template>
</aside>
