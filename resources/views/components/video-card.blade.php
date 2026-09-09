@props([
    'videoUrl' => '',
    'title' => '',
    'author' => 'पूज्य संत',
    'thumbnail' => asset('images/jain/muni_pravachan.jpg'),
])

@php
    // Extract YouTube ID if applicable
    $youtubeId = null;
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $videoUrl, $matches)) {
        $youtubeId = $matches[1];
        $thumbnail = "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";
    }
@endphp

<div class="spiritual-card">
    <div class="card-img-wrap" style="height: 200px;">
        <img src="{{ $thumbnail }}" alt="{{ $title }}" loading="lazy">
        <span class="card-badge-top">
            <i class="fa-brands fa-youtube text-danger me-1"></i> वीडियो
        </span>
        <a href="{{ $videoUrl }}" target="_blank" class="position-absolute top-50 start-50 translate-middle btn btn-danger rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;" aria-label="वीडियो देखें">
            <i class="fa-solid fa-play fs-5 text-white"></i>
        </a>
    </div>
    <div class="card-content">
        <small class="text-muted mb-1"><i class="fa-solid fa-user-tie text-warning me-1"></i> {{ $author }}</small>
        <h5 class="font-spiritual mb-2" style="font-size: 16px; line-height: 1.4;">{{ $title }}</h5>
        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
            <span class="badge bg-light text-dark border">प्रवचन वीडियो</span>
            <a href="{{ $videoUrl }}" target="_blank" class="text-danger fw-bold small">
                YouTube पर देखें <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i>
            </a>
        </div>
    </div>
</div>
