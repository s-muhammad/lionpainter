@if ($paginator->hasPages())
    <nav role="navigation" aria-label="صفحه‌بندی" class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-[11px] text-slate-400">
            نمایش {{ $paginator->firstItem() }} تا {{ $paginator->lastItem() }} از {{ $paginator->total() }} مورد
        </p>
        <div class="flex items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] text-slate-300">قبلی</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-[11px] text-slate-600 transition-colors hover:border-slate-300 hover:text-slate-900">قبلی</a>
            @endif

            <span class="px-2 text-[11px] text-slate-500">صفحه {{ $paginator->currentPage() }} از {{ $paginator->lastPage() }}</span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-[11px] text-slate-600 transition-colors hover:border-slate-300 hover:text-slate-900">بعدی</a>
            @else
                <span class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] text-slate-300">بعدی</span>
            @endif
        </div>
    </nav>
@endif
