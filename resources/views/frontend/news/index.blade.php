@extends('layouts.app')

@section('title', 'धर्म समाचार | तीर्थ एवं समाज की अद्यतन खबरें')
@section('meta_description', 'जैन तीर्थ क्षेत्र, संत विहार, महामहोत्सव एवं सामाजिक गतिविधियों की ताजा प्रामाणिक खबरें।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'धर्म समाचार', 'url' => '']]" />

    <div class="container pb-5">
        <div class="row align-items-center justify-content-between mb-4 g-3">
            <div class="col-md-7">
                <h1 class="font-spiritual mb-1" style="font-size: 30px;">धर्म प्रभावना समाचार</h1>
                <p class="text-muted small mb-0">तीर्थ वंदना, संत संघ, महामहोत्सव और समाज सेवा की प्रामाणिक रिपोर्ट।</p>
            </div>
            <div class="col-md-5">
                <form action="{{ route('news.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="समाचार खोजें..." aria-label="Search">
                    <button type="submit" class="btn btn-spiritual flex-shrink-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                    @if(request('search'))
                        <a href="{{ route('news.index') }}" class="btn btn-outline-secondary flex-shrink-0">रीसेट</a>
                    @endif
                </form>
            </div>
        </div>

        @if($newsList->isNotEmpty())
            <div class="row g-4">
                @foreach($newsList as $item)
                    <div class="col-lg-4 col-md-6">
                        <x-news-card :news="$item" />
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$newsList" />
        @else
            <x-empty-state 
                title="कोई समाचार नहीं मिला" 
                message="आपकी खोज के अनुसार कोई समाचार उपलब्ध नहीं है।"
                icon="fa-newspaper"
                actionText="सभी समाचार देखें"
                :actionUrl="route('news.index')"
            />
        @endif
    </div>
@endsection
