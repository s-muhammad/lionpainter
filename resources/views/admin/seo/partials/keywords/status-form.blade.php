<form method="POST" action="{{ route('admin.seo.keywords.status', $keyword) }}">
    @csrf
    @method('PATCH')
    <label for="keyword-status-{{ $keyword->id }}" class="sr-only">تغییر وضعیت {{ $keyword->keyword }}</label>
    <select
        id="keyword-status-{{ $keyword->id }}"
        name="status"
        onchange="this.form.submit()"
        class="w-full rounded-lg border border-slate-200 bg-white px-2 py-1 text-[10px] font-medium text-slate-600 transition-colors focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-500/15"
    >
        @foreach ($statuses as $status)
            <option value="{{ $status->value }}" @selected($keyword->status === $status)>{{ $status->label() }}</option>
        @endforeach
    </select>
</form>
