@php
    $priorityTones = [
        1 => 'bg-red-50 text-red-600 ring-red-100',
        2 => 'bg-amber-50 text-amber-700 ring-amber-100',
        3 => 'bg-slate-100 text-slate-500 ring-slate-200',
    ];
@endphp

<span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-medium ring-1 ring-inset {{ $priorityTones[$priority] ?? $priorityTones[2] }}">
    {{ $priorityOptions[$priority] ?? $priority }}
</span>
