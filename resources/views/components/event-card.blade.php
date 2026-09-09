@props(['event'])

@php
    $title = is_array($event->event_name) ? ($event->event_name['hi'] ?? $event->event_name['en'] ?? '') : $event->event_name;
    $venue = is_array($event->venue) ? ($event->venue['hi'] ?? $event->venue['en'] ?? '') : $event->venue;
    $image = $event->banner ?: asset('images/jain/temple_shikhar.jpg');
    $slug = $event->slug ?: $event->id;
    $startDate = \Carbon\Carbon::parse($event->start_date);
@endphp

<div class="spiritual-card">
    <div class="card-img-wrap" style="height: 190px;">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        <div class="card-badge-top" style="background: var(--primary);">
            <i class="fa-solid fa-calendar-check me-1"></i> {{ $startDate->format('d M, Y') }}
        </div>
        <span class="card-badge-right">
            <i class="fa-regular fa-clock me-1"></i> {{ $startDate->format('h:i A') }}
        </span>
    </div>
    <div class="card-content">
        <h4 class="mb-2 font-spiritual" style="font-size: 17px; line-height: 1.4;">
            <a href="{{ route('events.show', $slug) }}" class="text-dark">{{ $title }}</a>
        </h4>
        @if($venue)
            <p class="text-muted small mb-3">
                <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $venue }}
            </p>
        @endif
        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
            <span class="badge bg-light text-dark border">मांगलिक आयोजन</span>
            <a href="{{ route('events.show', $slug) }}" class="btn btn-sm btn-spiritual-outline py-1 px-3" style="font-size: 12.5px;">
                कार्यक्रम विवरण <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
