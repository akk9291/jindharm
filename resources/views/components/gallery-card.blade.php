@props([
    'src' => '',
    'caption' => '',
    'category' => 'तीर्थ दर्शन',
])

<div class="spiritual-card">
    <div class="card-img-wrap" style="height: 240px;">
        <img src="{{ $src }}" alt="{{ $caption }}" loading="lazy" class="gallery-img">
        @if($category)
            <span class="card-badge-top">{{ $category }}</span>
        @endif
        <a href="{{ $src }}" target="_blank" class="position-absolute top-50 start-50 translate-middle btn btn-light rounded-circle shadow opacity-0 spiritual-hover-show" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;" title="बड़ा चित्र देखें">
            <i class="fa-solid fa-up-right-and-down-left-from-center text-dark"></i>
        </a>
    </div>
    @if($caption)
        <div class="p-3 bg-white border-top">
            <p class="mb-0 text-dark small fw-semibold text-truncate">
                <i class="fa-solid fa-image text-warning me-1"></i> {{ $caption }}
            </p>
        </div>
    @endif
</div>
