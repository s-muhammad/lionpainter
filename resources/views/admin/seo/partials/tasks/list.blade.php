@if ($taskGroups->isEmpty())
    <div class="admin-card px-6 py-16 text-center">
        <x-icon name="check-circle" class="mx-auto h-7 w-7 text-slate-300" />
        <p class="mt-3 text-[12px] text-slate-500">کاری با این مشخصات پیدا نشد.</p>
        <p class="mt-1 text-[10px] text-slate-400">فیلترها را پاک کنید یا از فرم بالا اولین کار را اضافه کنید.</p>
    </div>
@else
    <div class="space-y-4">
        @foreach ($taskGroups as $phaseKey => $group)
            @php
                $phaseLabel = $phaseKey === '__none__' ? 'بدون مرحله' : $phaseKey;
            @endphp
            <section class="admin-card overflow-hidden">
                <header class="flex items-center justify-between gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-3">
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-gold-600 ring-1 ring-inset ring-slate-200">
                            <x-icon name="layers" class="h-3.5 w-3.5" />
                        </span>
                        <h3 class="text-[12px] font-bold text-slate-700">{{ $phaseLabel }}</h3>
                    </div>
                    <span class="rounded-full bg-white px-2.5 py-1 text-[9px] font-medium text-slate-500 ring-1 ring-inset ring-slate-200">{{ number_format($group->count()) }} کار</span>
                </header>

                <ul class="divide-y divide-slate-100">
                    @foreach ($group as $task)
                        <li class="flex items-start gap-3 px-5 py-3.5 transition-colors {{ $task->isOverdue() ? 'bg-red-50/50' : 'hover:bg-slate-50/50' }}">
                            <form method="POST" action="{{ route('admin.seo.tasks.toggle', $task) }}" class="pt-0.5">
                                @csrf
                                @method('PATCH')
                                <label class="inline-flex cursor-pointer items-center">
                                    <span class="sr-only">{{ $task->isDone() ? 'بازگرداندن' : 'تکمیل' }} {{ $task->title }}</span>
                                    <input
                                        type="checkbox"
                                        onchange="this.form.submit()"
                                        @checked($task->isDone())
                                        class="h-4 w-4 rounded border-slate-300 accent-[#c6a45f]"
                                    >
                                </label>
                            </form>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <p class="text-[12px] font-semibold {{ $task->isDone() ? 'text-slate-400 line-through' : 'text-slate-700' }}">{{ $task->title }}</p>
                                    @include('admin.seo.partials.priority-badge', ['priority' => $task->priority])

                                    @if ($task->isOverdue())
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[9px] font-medium text-red-600">عقب‌افتاده</span>
                                    @endif

                                    @if ($task->keyword)
                                        <a
                                            href="{{ route('admin.seo.index', ['tab' => 'keywords', 'k_search' => $task->keyword->keyword]) }}"
                                            class="inline-flex items-center gap-1 rounded-full bg-gold-500/10 px-2 py-0.5 text-[9px] font-medium text-gold-700 transition-colors hover:bg-gold-500/20"
                                            title="کلیدواژه مرتبط: {{ $task->keyword->keyword }}"
                                        >
                                            <x-icon name="search" class="h-2.5 w-2.5" />
                                            {{ $task->keyword->keyword }}
                                        </a>
                                    @endif
                                </div>

                                <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] text-slate-400">
                                    <span>{{ $task->category->label() }}</span>
                                    @if ($task->due_date)
                                        <span class="{{ $task->isOverdue() ? 'font-medium text-red-500' : '' }}">مهلت: {{ $task->due_date->locale('fa')->translatedFormat('j M Y') }}</span>
                                    @endif
                                    @if ($task->completed_at)
                                        <span class="text-emerald-600">انجام‌شده: {{ $task->completed_at->locale('fa')->translatedFormat('j M Y، H:i') }}</span>
                                    @endif
                                </div>

                                @if (filled($task->notes))
                                    <p class="mt-1.5 max-w-3xl text-[10px] leading-relaxed text-slate-400">{{ \Illuminate\Support\Str::limit($task->notes, 160) }}</p>
                                @endif
                            </div>

                            <div class="flex shrink-0 items-center gap-1 pt-0.5">
                                <button
                                    type="button"
                                    x-on:click="$dispatch('edit-task', {{ Js::from($task->toFormArray()) }})"
                                    class="inline-flex items-center gap-1 rounded-lg px-2 py-1.5 text-[9px] font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-sky-600"
                                    aria-label="ویرایش {{ $task->title }}"
                                >
                                    <x-icon name="edit" class="h-3 w-3" />
                                    ویرایش
                                </button>
                                <form method="POST" action="{{ route('admin.seo.tasks.destroy', $task) }}" onsubmit="return confirm('آیا از حذف این کار مطمئن هستید؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg px-2 py-1.5 text-[9px] font-medium text-slate-500 transition-colors hover:bg-red-50 hover:text-red-600" aria-label="حذف {{ $task->title }}">
                                        <x-icon name="trash" class="h-3 w-3" />
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach
    </div>
@endif
