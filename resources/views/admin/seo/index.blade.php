@extends('admin.layouts.app')

@section('title', 'دفترچه سئو')
@section('subtitle', 'کلیدواژه‌ها، برنامه محتوا و کارهای سئو')

@section('content')
    <div class="space-y-5">
        @if ($tab !== 'graph')
            @include('admin.seo.partials.summary')
        @endif
        @include('admin.seo.partials.tabs')

        @if ($tab === 'tasks')
            @include('admin.seo.partials.tasks.panel')
        @elseif ($tab === 'graph')
            @include('admin.seo.partials.graph.panel')
        @else
            @include('admin.seo.partials.keywords.panel')
        @endif
    </div>
@endsection
