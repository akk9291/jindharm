@props(['pravachan'])

@php
    $title = is_array($pravachan->title) ? ($pravachan->title['hi'] ?? $pravachan->title['en'] ?? '') : $pravachan->title;
    $author = is_array($pravachan->author) ? ($pravachan->author['hi'] ?? $pravachan->author['en'] ?? '') : $pravachan->author;
    $excerpt = is_array($pravachan->short_description) ? ($pravachan->short_description['hi'] ?? $pravachan->short_description['en'] ?? '') : $pravachan->short_description;
    $image = $pravachan->featured_image ?: asset('images/jain/muni_pravachan.jpg');
    $slug = $pravachan->slug ?: $pravachan->id;
    $hasVideo = !empty($pravachan->videos) && count((array)$pravachan->videos) > 0;
    $hasAudio = !empty($pravachan->audios) && count((array)$pravachan->audios) > 0;
@endphp

<div class="spiritual-card">
    <div class="card-img-wrap" style="height: 200px;">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        <span class="card-badge-top">
            <i class="fa-solid fa-microphone-lines me-1 text-warning"></i> प्रवचन
        </span>
        <div class="position-absolute top-50 start-50 translate-middle">
            <a href="{{ route('pravachans.show', $slug) }}" class="btn btn-light rounded-circle shadow-lg d-flex align-items-center justify-content-center text-danger" style="width: 50px; height: 50px;" aria-label="प्रवचन सुनें">
                <i class="fa-solid fa-play fs-5"></i>
            </a>
        </div>
        <div class="card-badge-right">
            @if($hasVideo && $hasAudio)
                <i class="fa-solid fa-video me-1"></i> वीडियो + ऑडियो
            @elseif($hasVideo)
                <i class="fa-solid fa-video me-1"></i> वीडियो
            @else
                <i class="fa-solid fa-headphones me-1"></i> ऑडियो
            @endif
        </div>
    </div>
    <div class="card-content">
        <div class="d-flex align-items-center justify-content-between text-muted small mb-2">
            <span><i class="fa-solid fa-user-tie text-warning me-1"></i> {{ $author ?: 'पूज्य संत' }}</span>
            <span><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($pravachan->publish_date)->format('d M, Y') }}</span>
        </div>
        <h4 class="mb-2 font-spiritual" style="font-size: 17px; line-height: 1.4;">
            <a href="{{ route('pravachans.show', $slug) }}" class="text-dark">{{ $title }}</a>
        </h4>
        @if($excerpt)
            <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $excerpt }}
            </p>
        @endif
        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
            <span class="badge bg-light text-dark border">स्वाध्याय</span>
            <a href="{{ route('pravachans.show', $slug) }}" class="text-danger fw-bold small">
                प्रवचन सुनें <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
