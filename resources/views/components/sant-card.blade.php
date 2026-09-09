@props(['sant'])

@php
    $name = is_array($sant->name) ? ($sant->name['hi'] ?? $sant->name['en'] ?? '') : $sant->name;
    $title = is_array($sant->title) ? ($sant->title['hi'] ?? $sant->title['en'] ?? '') : $sant->title;
    $photo = $sant->photo ?: asset('images/jain/muni_vidyasagar.jpg');
    $currentVihar = $sant->vihars ? $sant->vihars->where('type', 'current')->first() : null;
    $slug = $sant->slug ?: $sant->id;
@endphp

<div class="spiritual-card">
    <div class="card-img-wrap" style="height: 270px;">
        <img src="{{ $photo }}" alt="{{ $name }}" loading="lazy">
        @if($title)
            <span class="card-badge-top">{{ $title }}</span>
        @endif
        @if($currentVihar)
            <span class="card-badge-right" style="background: #15803d;">
                <i class="fa-solid fa-location-dot me-1"></i> वर्तमान विहार
            </span>
        @endif
    </div>
    <div class="card-content">
        <h4 class="mb-2 font-spiritual" style="font-size: 19px;">
            <a href="{{ route('sants.show', $slug) }}" class="text-dark">{{ $name }}</a>
        </h4>

        @if($currentVihar)
            @php
                $viharLoc = is_array($currentVihar->location_title) ? ($currentVihar->location_title['hi'] ?? $currentVihar->location_title['en']) : $currentVihar->location_title;
            @endphp
            <div class="p-2 mb-3 rounded" style="background: var(--bg-warm-tint); font-size: 12.5px; border: 1px dashed var(--gold-border);">
                <i class="fa-solid fa-map-pin text-danger me-1"></i>
                <strong>स्थान:</strong> {{ $viharLoc }} ({{ $currentVihar->city }})
            </div>
        @elseif($sant->guru_name)
            @php
                $guru = is_array($sant->guru_name) ? ($sant->guru_name['hi'] ?? $sant->guru_name['en']) : $sant->guru_name;
            @endphp
            <div class="small text-muted mb-3">
                <i class="fa-solid fa-hands-praying text-warning me-1"></i> दीक्षा गुरु: {{ $guru }}
            </div>
        @endif

        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
            <span class="text-muted small">
                @if($sant->diksha_date)
                    दीक्षा: {{ \Carbon\Carbon::parse($sant->diksha_date)->format('Y') }}
                @else
                    परम पूज्य संत
                @endif
            </span>
            <a href="{{ route('sants.show', $slug) }}" class="btn btn-sm btn-spiritual-outline py-1 px-3" style="font-size: 12.5px;">
                दर्शन एवं परिचय <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
