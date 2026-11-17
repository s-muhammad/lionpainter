<section class="admin-card flex min-w-0 flex-col overflow-hidden">
    <header class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-3">
        <div>
            <h3 class="text-[12px] font-bold text-slate-700">گراف کلمات کلیدی</h3>
            <p class="mt-0.5 text-[10px] text-slate-400">
                {{ number_format($graphStats['keywords']) }} کلمه · {{ number_format($graphStats['links']) }} پیوند
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <label class="flex items-center gap-1.5 text-[10px] text-slate-500">
                <span class="shrink-0">گروه‌بندی</span>
                <select
                    x-model="groupBy"
                    @change="relayout()"
                    class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-[10px] text-slate-600"
                >
                    <option value="target">صفحه هدف</option>
                    <option value="cluster">خوشه</option>
                    <option value="status">وضعیت</option>
                </select>
            </label>

            <label class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2 py-1 text-[10px] text-slate-500">
                <input type="checkbox" x-model="showText" @change="paint()" class="h-3.5 w-3.5 rounded border-slate-300 accent-[#c6a45f]">
                ارتباط متنی
            </label>

            <label class="flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-2 py-1 text-[10px] text-slate-500">
                <input type="checkbox" x-model="showLabels" @change="paint()" class="h-3.5 w-3.5 rounded border-slate-300 accent-[#c6a45f]">
                برچسب‌ها
            </label>

            <button type="button" @click="relayout()" class="admin-btn-ghost py-1.5 text-[10px]">
                <x-icon name="layers" class="h-3.5 w-3.5" />
                بازی‌چینش
            </button>
        </div>
    </header>

    <div class="relative h-[520px] w-full" x-ref="canvas">
        <p class="pointer-events-none absolute right-3 top-2.5 z-10 max-w-[16rem] text-left text-[9px] leading-relaxed text-slate-400">
            برای جابه‌جایی بکشید، با اسکرول زوم کنید و روی کلمه کلیک کنید.
        </p>

        <svg
            x-ref="svg"
            class="h-full w-full touch-none"
            :class="pan ? 'cursor-grabbing' : 'cursor-grab'"
            @pointerdown="startPan($event)"
            @pointermove="movePointer($event)"
            @pointerup="endPointer()"
            @pointercancel="endPointer()"
            @pointerleave="endPointer()"
            @wheel.prevent="wheelZoom($event)"
            role="img"
            aria-label="گراف ارتباط کلیدواژه‌ها؛ با کلیدهای جهت‌دار یا کلیک روی هر گره، بریف آن را ببینید."
        >
            <g :transform="`translate(${panX} ${panY}) scale(${zoom})`">
                <g x-ref="edgeLayer"></g>
                <g x-ref="nodeLayer"></g>
            </g>
        </svg>

        <div
            x-show="count === 0"
            class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-2 bg-white/85 px-6 text-center"
        >
            <x-icon name="network" class="h-7 w-7 text-slate-300" />
            <p class="text-[11px] text-slate-500">کلیدواژه‌ای برای نمایش در گراف پیدا نشد.</p>
            <p class="text-[10px] text-slate-400">فیلترها را پاک کنید یا کلیدواژه جدیدی اضافه کنید.</p>
            <a href="{{ route('admin.seo.index', ['tab' => 'graph']) }}" class="admin-btn-ghost mt-1 py-1.5 text-[10px]">
                پاک کردن فیلترها
            </a>
        </div>

        <div class="absolute bottom-3 left-3 z-10 flex items-center gap-1 rounded-xl border border-slate-200 bg-white p-1 shadow-sm">
            <button type="button" @click="resetView()" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-50 hover:text-slate-700" aria-label="بازنشانی نمایش">
                <x-icon name="grid" class="h-3.5 w-3.5" />
            </button>
            <button type="button" @click="zoomBy(0.85)" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-50 hover:text-slate-700" aria-label="کوچک‌نمایی">
                <x-icon name="minus" class="h-3.5 w-3.5" />
            </button>
            <button type="button" @click="zoomBy(1.18)" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-50 hover:text-slate-700" aria-label="بزرگ‌نمایی">
                <x-icon name="plus" class="h-3.5 w-3.5" />
            </button>
        </div>
    </div>
</section>
