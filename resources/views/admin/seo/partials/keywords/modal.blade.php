@php
    use App\Enums\KeywordContentType;
    use App\Enums\KeywordIntent;
    use App\Enums\KeywordStatus;

    $keywordModalOpen = $errors->any() && old('_modal') === 'keyword';

    $keywordDefaults = [
        'id' => null,
        'keyword' => '',
        'cluster' => '',
        'intent' => KeywordIntent::Commercial->value,
        'content_type' => KeywordContentType::BlogPost->value,
        'target_url' => '',
        'priority' => 2,
        'status' => KeywordStatus::Idea->value,
        'difficulty' => '',
        'search_volume' => '',
        'current_position' => '',
        'target_date' => '',
        'notes' => '',
    ];

    $keywordModalInitial = $keywordModalOpen
        ? array_merge($keywordDefaults, [
            'id' => old('_keyword_id'),
            'keyword' => old('keyword', ''),
            'cluster' => old('cluster', ''),
            'intent' => old('intent', $keywordDefaults['intent']),
            'content_type' => old('content_type', $keywordDefaults['content_type']),
            'target_url' => old('target_url', ''),
            'priority' => old('priority', 2),
            'status' => old('status', $keywordDefaults['status']),
            'difficulty' => old('difficulty', ''),
            'search_volume' => old('search_volume', ''),
            'current_position' => old('current_position', ''),
            'target_date' => old('target_date', ''),
            'notes' => old('notes', ''),
        ])
        : $keywordDefaults;
@endphp

<div
    x-data="seoKeywordModal({
        open: {{ $keywordModalOpen ? 'true' : 'false' }},
        fields: @js($keywordModalInitial),
        defaults: @js($keywordDefaults),
        storeUrl: @js(route('admin.seo.keywords.store')),
        updateUrl: @js(route('admin.seo.keywords.update', ['keyword' => '__ID__']))
    })"
    @add-keyword.window="showAdd()"
    @edit-keyword.window="showEdit($event.detail)"
    @keydown.escape.window="close()"
>
    <div x-show="open" x-cloak class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="keyword-modal-title">
        <div class="flex min-h-full items-end justify-center p-3 sm:items-center sm:p-6">
            <div class="fixed inset-0 bg-ink-950/60 backdrop-blur-sm" @click="close()" aria-hidden="true"></div>

            <div class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl" x-transition.opacity.duration.150ms>
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 id="keyword-modal-title" class="text-[14px] font-bold text-slate-800" x-text="editing ? 'ویرایش کلیدواژه' : 'افزودن کلیدواژه'"></h2>
                        <p class="mt-0.5 text-[10px] text-slate-400">اطلاعات این کلیدواژه را در دفترچه سئو کامل کنید.</p>
                    </div>
                    <button type="button" @click="close()" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-600" aria-label="بستن پنجره">
                        <x-icon name="close" class="h-4 w-4" />
                    </button>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.seo.keywords.store') }}"
                    :action="action"
                    class="max-h-[calc(100dvh-6rem)] overflow-y-auto px-5 py-4"
                >
                    @csrf
                    <input type="hidden" name="_method" :value="editing ? 'PUT' : 'POST'">
                    <input type="hidden" name="_modal" value="keyword">
                    <input type="hidden" name="_keyword_id" :value="fields.id ?? ''">

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="keyword-form-keyword" class="admin-label">کلیدواژه <span class="text-red-500">*</span></label>
                            <input
                                x-ref="firstInput"
                                x-model="fields.keyword"
                                id="keyword-form-keyword"
                                name="keyword"
                                type="text"
                                required
                                dir="auto"
                                class="admin-input"
                                placeholder="مثلاً نقاشی ساختمان ملبورن"
                                @if ($keywordModalOpen && $errors->has('keyword')) aria-invalid="true" aria-describedby="keyword-form-keyword-error" @endif
                            >
                            @if ($keywordModalOpen)
                                @error('keyword') <p id="keyword-form-keyword-error" class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="keyword-form-cluster" class="admin-label">خوشه</label>
                            <input x-model="fields.cluster" id="keyword-form-cluster" name="cluster" type="text" list="keyword-clusters" dir="auto" class="admin-input" placeholder="Core، Heritage، Cost…">
                            <datalist id="keyword-clusters">
                                @foreach ($clusters as $cluster)
                                    <option value="{{ $cluster }}"></option>
                                @endforeach
                            </datalist>
                            @if ($keywordModalOpen)
                                @error('cluster') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="keyword-form-intent" class="admin-label">قصد جستجو <span class="text-red-500">*</span></label>
                            <select x-model="fields.intent" id="keyword-form-intent" name="intent" required class="admin-input">
                                @foreach ($intents as $intent)
                                    <option value="{{ $intent->value }}">{{ $intent->label() }}</option>
                                @endforeach
                            </select>
                            @if ($keywordModalOpen)
                                @error('intent') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="keyword-form-content-type" class="admin-label">نوع محتوا <span class="text-red-500">*</span></label>
                            <select x-model="fields.content_type" id="keyword-form-content-type" name="content_type" required class="admin-input">
                                @foreach ($contentTypes as $contentType)
                                    <option value="{{ $contentType->value }}">{{ $contentType->label() }}</option>
                                @endforeach
                            </select>
                            @if ($keywordModalOpen)
                                @error('content_type') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="keyword-form-target-url" class="admin-label">آدرس هدف</label>
                            <input x-model="fields.target_url" id="keyword-form-target-url" name="target_url" type="text" dir="ltr" class="admin-input" placeholder="/services/interior-painting">
                            @if ($keywordModalOpen)
                                @error('target_url') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="keyword-form-priority" class="admin-label">اولویت <span class="text-red-500">*</span></label>
                            <select x-model="fields.priority" id="keyword-form-priority" name="priority" required class="admin-input">
                                @foreach ($priorityOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @if ($keywordModalOpen)
                                @error('priority') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="keyword-form-status" class="admin-label">وضعیت <span class="text-red-500">*</span></label>
                            <select x-model="fields.status" id="keyword-form-status" name="status" required class="admin-input">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                            @if ($keywordModalOpen)
                                @error('status') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="keyword-form-difficulty" class="admin-label">سختی (تخمین دستی)</label>
                            <select x-model="fields.difficulty" id="keyword-form-difficulty" name="difficulty" class="admin-input">
                                <option value="">نامشخص</option>
                                @foreach ($difficulties as $difficulty)
                                    <option value="{{ $difficulty->value }}">{{ $difficulty->label() }}</option>
                                @endforeach
                            </select>
                            @if ($keywordModalOpen)
                                @error('difficulty') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="keyword-form-search-volume" class="admin-label">حجم جستجوی ماهانه</label>
                            <input x-model="fields.search_volume" id="keyword-form-search-volume" name="search_volume" type="number" min="0" step="1" inputmode="numeric" class="admin-input" placeholder="مثلاً 320">
                            @if ($keywordModalOpen)
                                @error('search_volume') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label for="keyword-form-position" class="admin-label">جایگاه فعلی</label>
                            <input x-model="fields.current_position" id="keyword-form-position" name="current_position" type="number" min="0" step="1" inputmode="numeric" class="admin-input" placeholder="مثلاً 8">
                            @if ($keywordModalOpen)
                                @error('current_position') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div class="sm:col-span-2">
                            <label for="keyword-form-target-date" class="admin-label">تاریخ هدف</label>
                            <input x-model="fields.target_date" id="keyword-form-target-date" name="target_date" type="date" class="admin-input">
                            @if ($keywordModalOpen)
                                @error('target_date') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div class="sm:col-span-2">
                            <label for="keyword-form-notes" class="admin-label">یادداشت</label>
                            <textarea x-model="fields.notes" id="keyword-form-notes" name="notes" rows="3" class="admin-input" placeholder="ایده محتوا، نکته رقابتی یا منبع…"></textarea>
                            @if ($keywordModalOpen)
                                @error('notes') <p class="mt-1 text-[10px] text-red-600">{{ $message }}</p> @enderror
                            @endif
                        </div>
                    </div>

                    <div class="mt-5 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                        <button type="button" @click="close()" class="admin-btn-ghost text-[11px]">انصراف</button>
                        <button type="submit" class="admin-btn-gold text-[11px]" x-text="editing ? 'ذخیره تغییرات' : 'افزودن کلیدواژه'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
