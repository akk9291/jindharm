@extends('layouts.app')

@php
    $title = is_array($page->title) ? ($page->title['hi'] ?? $page->title['en'] ?? '') : $page->title;
    $content = is_array($page->content) ? ($page->content['hi'] ?? $page->content['en'] ?? '') : $page->content;
@endphp

@section('title', $title . ' | जिनधर्म पोर्टल')

@section('content')
    <x-breadcrumbs :items="[['title' => $title, 'url' => '']]" />

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <article class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                    @if($page->featured_image)
                        <div class="rounded-4 overflow-hidden mb-4 shadow-sm" style="max-height: 380px;">
                            <img src="{{ $page->featured_image }}" class="w-100 h-100 object-fit-cover" alt="{{ $title }}">
                        </div>
                    @endif

                    <h1 class="font-spiritual mb-4 border-bottom pb-3" style="font-size: 32px;">{{ $title }}</h1>

                    <div class="content-body" style="font-size: 16.5px; line-height: 1.85;">
                        {!! $content !!}
                    </div>
                </article>
            </div>
        </div>
    </div>
@endsection
