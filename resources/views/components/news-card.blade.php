@props(['news'])

@php
    $title = is_array($news->title) ? ($news->title['hi'] ?? $news->title['en'] ?? '') : $news->title;
    $excerpt = is_array($news->short_description) ? ($news->short_description['hi'] ?? $news->short_description['en'] ?? '') : $news->short_description;
    $image = $news->featured_image ?: asset('images/jain/temple_shikhar.jpg');
    $slug = $news->slug ?: $news->id;
    $categoryName = $news->category ? (is_array($news->category->name) ? ($news->category->name['hi'] ?? $news->category->name['en']) : $news->category->name) : 'समाचार';
@endphp

<div class="spiritual-card">
    <div class="card-img-wrap" style="height: 190px;">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        <span class="card-badge-top">
            <i class="fa-solid fa-newspaper text-warning me-1"></i> {{ $categoryName }}
        </span>
        <span class="card-badge-right">
            <i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($news->publish_date)->format('d M, Y') }}
        </span>
    </div>
    <div class="card-content">
        <h4 class="mb-2 font-spiritual" style="font-size: 17px; line-height: 1.4;">
            <a href="{{ route('news.show', $slug) }}" class="text-dark">{{ $title }}</a>
        </h4>
        @if($excerpt)
            <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $excerpt }}
            </p>
        @endif
        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
            <span class="text-muted small">
                <i class="fa-solid fa-feather-pointed me-1"></i> धर्म प्रभावना
            </span>
            <a href="{{ route('news.show', $slug) }}" class="text-danger fw-bold small">
                विस्तार से पढ़ें <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
