@extends('layouts.app')

@php
    $title = is_array($bhajan->title) ? ($bhajan->title['hi'] ?? $bhajan->title['en'] ?? '') : $bhajan->title;
    $author = is_array($bhajan->author) ? ($bhajan->author['hi'] ?? $bhajan->author['en'] ?? '') : $bhajan->author;
    $desc = is_array($bhajan->full_description) ? ($bhajan->full_description['hi'] ?? $bhajan->full_description['en'] ?? '') : $bhajan->full_description;
    $short = is_array($bhajan->short_description) ? ($bhajan->short_description['hi'] ?? $bhajan->short_description['en'] ?? '') : $bhajan->short_description;
    $image = $bhajan->featured_image ?: asset('images/jain/tirthankara_idol.jpg');
    $hasAudio = !empty($bhajan->audios) && count((array)$bhajan->audios) > 0;
    $audioUrl = $hasAudio ? ((array)$bhajan->audios)[0] : null;
@endphp

@section('title', $title . ' - ऑडियो एवं सम्पूर्ण बोल (Lyrics)')
@section('meta_description', strip_tags(Str::limit($short ?: $desc, 150)))
@section('og_image', $image)

@section('content')
    <x-breadcrumbs :items="[
        ['title' => 'भजन एवं स्तुति', 'url' => route('bhajans.index')],
        ['title' => $title, 'url' => '']
    ]" />

    <div class="container pb-5">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">
                <!-- Audio Player Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 text-center bg-white mb-4">
                    <div class="rounded-4 overflow-hidden shadow mx-auto mb-3" style="width: 180px; height: 180px;">
                        <img src="{{ $image }}" class="w-100 h-100 object-fit-cover" alt="{{ $title }}">
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mb-2 fw-semibold mx-auto">
                        <i class="fa-solid fa-music me-1"></i> भक्ति रस
                    </span>
                    <h1 class="font-spiritual mb-1" style="font-size: 26px;">{{ $title }}</h1>
                    @if($author)
                        <p class="text-muted small mb-3"><i class="fa-solid fa-pen-nib text-warning me-1"></i> रचयिता: <strong>{{ $author }}</strong></p>
                    @endif

                    @if($audioUrl)
                        <div class="my-3">
                            <x-audio-player :src="$audioUrl" :title="$title" />
                        </div>
                        <div class="d-flex justify-content-center gap-3 mt-2">
                            <a href="{{ $audioUrl }}" download class="btn btn-sm btn-outline-danger">
                                <i class="fa-solid fa-download me-1"></i> MP3 ऑडियो डाउनलोड करें
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Lyrics Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white mb-4 text-center">
                    <h4 class="font-spiritual border-bottom pb-3 mb-4 text-danger"><i class="fa-solid fa-lines-leaning me-2"></i> सम्पूर्ण पद / स्तुति के बोल (Lyrics)</h4>
                    <div class="lyrics-body font-spiritual" style="font-size: 18px; line-height: 2.2; color: #332a24;">
                        {!! $desc !!}
                    </div>
                </div>

                <!-- Related Bhajans -->
                @if($related->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="font-spiritual border-bottom pb-2 mb-3"><i class="fa-solid fa-compact-disc text-warning me-2"></i> अन्य मधुर भजन एवं स्तुति</h5>
                        <div class="row g-3">
                            @foreach($related as $rel)
                                <div class="col-md-6">
                                    <x-bhajan-card :bhajan="$rel" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
