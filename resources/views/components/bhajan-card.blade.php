@props(['bhajan'])

@php
    $title = is_array($bhajan->title) ? ($bhajan->title['hi'] ?? $bhajan->title['en'] ?? '') : $bhajan->title;
    $author = is_array($bhajan->author) ? ($bhajan->author['hi'] ?? $bhajan->author['en'] ?? '') : $bhajan->author;
    $image = $bhajan->featured_image ?: asset('images/jain/tirthankara_idol.jpg');
    $slug = $bhajan->slug ?: $bhajan->id;
    $rawAudios = !empty($bhajan->audios) ? (array)$bhajan->audios : [];
    $rawFirstAudio = count($rawAudios) > 0 ? $rawAudios[0] : null;
    $audioUrl = is_array($rawFirstAudio) ? ($rawFirstAudio['url'] ?? null) : $rawFirstAudio;
    $hasAudio = !empty($audioUrl);
@endphp

<div class="spiritual-card">
    <div class="card-img-wrap" style="height: 180px;">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        <span class="card-badge-top">
            <i class="fa-solid fa-music text-warning me-1"></i> भक्ति संगीत
        </span>
        <span class="card-badge-right">
            <i class="fa-solid fa-headphones me-1"></i> MP3
        </span>
    </div>
    <div class="card-content">
        <div class="d-flex align-items-center justify-content-between text-muted small mb-1">
            <span><i class="fa-solid fa-feather text-warning me-1"></i> {{ $author ?: 'पारंपरिक' }}</span>
            <span><i class="fa-solid fa-compact-disc me-1"></i> भक्ति</span>
        </div>
        <h4 class="mb-2 font-spiritual" style="font-size: 17px;">
            <a href="{{ route('bhajans.show', $slug) }}" class="text-dark">{{ $title }}</a>
        </h4>

        @if($audioUrl)
            <x-audio-player :src="$audioUrl" :title="$title" />
        @endif

        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
            <a href="{{ route('bhajans.show', $slug) }}" class="text-secondary small">
                <i class="fa-solid fa-lines-leaning me-1"></i> बोल (Lyrics) देखें
            </a>
            @if($audioUrl)
                <a href="{{ $audioUrl }}" download class="btn btn-sm btn-link text-danger p-0" title="डाउनलोड करें">
                    <i class="fa-solid fa-download"></i>
                </a>
            @endif
        </div>
    </div>
</div>
