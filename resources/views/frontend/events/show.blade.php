@extends('layouts.app')

@php
    $title = is_array($event->event_name) ? ($event->event_name['hi'] ?? $event->event_name['en'] ?? '') : $event->event_name;
    $venue = is_array($event->venue) ? ($event->venue['hi'] ?? $event->venue['en'] ?? '') : $event->venue;
    $desc = is_array($event->description) ? ($event->description['hi'] ?? $event->description['en'] ?? '') : $event->description;
    $image = $event->banner ?: asset('images/jain/temple_shikhar.jpg');
    $startDate = \Carbon\Carbon::parse($event->start_date);
@endphp

@section('title', $title . ' - कार्यक्रम विवरण')
@section('meta_description', strip_tags(Str::limit($desc ?: $title, 150)))
@section('og_image', $image)

@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Event",
    "name": "{{ $title }}",
    "startDate": "{{ $startDate->toIso8601String() }}",
    "location": {
        "@type": "Place",
        "name": "{{ $venue ?: 'तीर्थ क्षेत्र' }}",
        "address": "{{ $venue }}"
    },
    "image": "{{ $image }}",
    "description": "{{ strip_tags($desc) }}"
}
</script>
@endpush

@section('content')
    <x-breadcrumbs :items="[
        ['title' => 'मांगलिक कार्यक्रम', 'url' => route('events.index')],
        ['title' => $title, 'url' => '']
    ]" />

    <div class="container pb-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                    <img src="{{ $image }}" class="w-100 object-fit-cover" style="max-height: 420px;" alt="{{ $title }}">
                    <div class="card-body p-4 p-lg-5">
                        <span class="badge bg-danger px-3 py-1 rounded-pill mb-2 fw-semibold">
                            <i class="fa-solid fa-bell me-1"></i> मांगलिक आयोजन
                        </span>
                        <h1 class="font-spiritual mb-3" style="font-size: 30px;">{{ $title }}</h1>

                        <!-- Schedule Bar -->
                        <div class="row g-3 my-3 p-3 rounded-3 bg-light border">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-calendar-day text-danger fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 11px;">प्रारंभ तिथि</small>
                                        <strong>{{ $startDate->format('d M, Y (l)') }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-clock text-warning fs-4"></i>
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 11px;">समय</small>
                                        <strong>प्रातः {{ $startDate->format('h:i A') }} से</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Venue Box -->
                        @if($venue)
                            <div class="p-3 rounded-3 mb-4 d-flex align-items-center gap-3" style="background: var(--bg-warm-tint); border: 1.5px solid var(--gold-border);">
                                <i class="fa-solid fa-map-location-dot text-danger fs-3"></i>
                                <div>
                                    <h6 class="mb-0 fw-bold">आयोजन स्थल (Venue):</h6>
                                    <p class="mb-0 text-dark small">{{ $venue }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="content-body" style="font-size: 16.5px; line-height: 1.8;">
                            {!! nl2br(e($desc)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Other Events -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="font-spiritual border-bottom pb-2 mb-3"><i class="fa-solid fa-calendar-check text-danger me-2"></i> अन्य कार्यक्रम</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($related as $r)
                            @php
                                $rTitle = is_array($r->event_name) ? ($r->event_name['hi'] ?? $r->event_name['en']) : $r->event_name;
                            @endphp
                            <a href="{{ route('events.show', $r->slug ?: $r->id) }}" class="d-flex align-items-center gap-3 text-dark text-decoration-none p-2 rounded hover-bg-light">
                                <div class="flex-shrink-0 rounded overflow-hidden" style="width: 70px; height: 60px;">
                                    <img src="{{ $r->banner ?: asset('images/jain/temple_shikhar.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $rTitle }}">
                                </div>
                                <div>
                                    <h6 class="mb-1 font-spiritual" style="font-size: 14px; line-height: 1.3;">{{ $rTitle }}</h6>
                                    <small class="text-muted d-block" style="font-size: 11px;"><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($r->start_date)->format('d M') }}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
