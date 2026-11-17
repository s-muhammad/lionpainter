<div class="space-y-4">
    @include('admin.seo.partials.graph.kpis')
    @include('admin.seo.partials.graph.filters')

    <div
        class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_21rem]"
        x-data="seoGraph"
        data-graph="{{ json_encode($graph, JSON_UNESCAPED_UNICODE) }}"
    >
        @include('admin.seo.partials.graph.canvas')
        @include('admin.seo.partials.graph.brief')
    </div>
</div>
