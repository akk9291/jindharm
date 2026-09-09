@extends('layouts.app')

@php
    $name = is_array($saint->name) ? ($saint->name['hi'] ?? $saint->name['en'] ?? '') : $saint->name;
    $title = is_array($saint->title) ? ($saint->title['hi'] ?? $saint->title['en'] ?? '') : $saint->title;
    $photo = $saint->photo ?: asset('images/jain/muni_vidyasagar.jpg');
    $guru = is_array($saint->guru_name) ? ($saint->guru_name['hi'] ?? $saint->guru_name['en'] ?? '') : $saint->guru_name;
    $intro = is_array($saint->introduction) ? ($saint->introduction['hi'] ?? $saint->introduction['en'] ?? '') : $saint->introduction;
    $bio = is_array($saint->biography) ? ($saint->biography['hi'] ?? $saint->biography['en'] ?? '') : $saint->biography;
@endphp

@section('title', $name . ' - जीवन परिचय एवं वर्तमान विहार')
@section('meta_description', strip_tags(Str::limit($intro ?: $bio, 150)))
@section('og_image', $photo)

@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "{{ $name }}",
    "jobTitle": "{{ $title }}",
    "image": "{{ $photo }}",
    "description": "{{ strip_tags($intro ?: $bio) }}"
}
</script>
@endpush

@section('content')
    <x-breadcrumbs :items="[
        ['title' => 'संत परिचय', 'url' => route('sants.index')],
        ['title' => $name, 'url' => '']
    ]" />

    <div class="container pb-5">
        <!-- Saint Profile Hero -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
            <div class="card-body p-4 p-lg-5 bg-white">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-4 text-center">
                        <div class="rounded-4 overflow-hidden shadow border border-3 border-white d-inline-block" style="max-width: 320px;">
                            <img src="{{ $photo }}" alt="{{ $name }}" class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        @if($title)
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-2 fw-semibold" style="font-size: 13px;">
                                <i class="fa-solid fa-crown me-1"></i> {{ $title }}
                            </span>
                        @endif
                        <h1 class="font-spiritual mb-3" style="font-size: 32px;">{{ $name }}</h1>

                        <!-- Key Details Pills -->
                        <div class="d-flex flex-wrap gap-3 mb-4">
                            @if($guru)
                                <div class="p-2 px-3 rounded bg-light border">
                                    <small class="text-muted d-block" style="font-size: 11px;">दीक्षा गुरु</small>
                                    <strong>{{ $guru }}</strong>
                                </div>
                            @endif
                            @if($saint->diksha_date)
                                <div class="p-2 px-3 rounded bg-light border">
                                    <small class="text-muted d-block" style="font-size: 11px;">दीक्षा दिवस</small>
                                    <strong>{{ \Carbon\Carbon::parse($saint->diksha_date)->format('d M, Y') }}</strong>
                                </div>
                            @endif
                            @if($saint->parent)
                                <div class="p-2 px-3 rounded bg-light border">
                                    <small class="text-muted d-block" style="font-size: 11px;">गुरु परम्परा</small>
                                    <strong>{{ is_array($saint->parent->name) ? ($saint->parent->name['hi'] ?? $saint->parent->name['en']) : $saint->parent->name }}</strong>
                                </div>
                            @endif
                        </div>

                        <!-- Current Vihar Alert -->
                        @if($currentVihar)
                            @php
                                $loc = is_array($currentVihar->location_title) ? ($currentVihar->location_title['hi'] ?? $currentVihar->location_title['en']) : $currentVihar->location_title;
                            @endphp
                            <div class="alert alert-success d-flex align-items-center justify-content-between p-3 rounded-3 mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="fs-4 text-success"><i class="fa-solid fa-location-dot"></i></div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">वर्तमान विहार स्थिति</h6>
                                        <small>{{ $loc }} — {{ $currentVihar->city }}, {{ $currentVihar->state }}</small>
                                    </div>
                                </div>
                                @if($currentVihar->contact_number)
                                    <span class="badge bg-white text-dark border p-2">
                                        <i class="fa-solid fa-phone text-success me-1"></i> {{ $currentVihar->contact_number }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if($intro)
                            <p class="lead text-secondary fs-6 mb-0">{{ $intro }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Biography Section -->
        <div class="row g-4 mb-5">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white mb-4">
                    <h3 class="font-spiritual border-bottom pb-3 mb-4"><i class="fa-solid fa-book-open-reader text-danger me-2"></i> विस्तृत जीवन चरित्र एवं साधना</h3>
                    <div class="content-body" style="font-size: 16.5px; line-height: 1.8;">
                        {!! nl2br(e($bio)) !!}
                    </div>
                </div>

                <!-- Vihar History -->
                @if($pastVihars->isNotEmpty() || $upcomingVihars->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                        <h4 class="font-spiritual border-bottom pb-3 mb-4"><i class="fa-solid fa-route text-warning me-2"></i> विहार एवं चातुर्मास इतिहास</h4>
                        <div class="timeline">
                            @foreach($saint->vihars as $v)
                                @php
                                    $vTitle = is_array($v->location_title) ? ($v->location_title['hi'] ?? $v->location_title['en']) : $v->location_title;
                                @endphp
                                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                    <div class="text-danger fs-5"><i class="fa-solid fa-circle-dot"></i></div>
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $vTitle }} ({{ $v->city }})</h6>
                                        <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($v->start_date)->format('M Y') }} • {{ ucfirst($v->type) }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar: Guru Parampara & Related Pravachans -->
            <div class="col-lg-4">
                <!-- Related Pravachans -->
                @if($pravachans->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <h5 class="font-spiritual border-bottom pb-2 mb-3"><i class="fa-solid fa-microphone text-danger me-2"></i> पूज्य संत के प्रवचन</h5>
                        <div class="d-flex flex-column gap-3">
                            @foreach($pravachans as $pr)
                                @php
                                    $prTitle = is_array($pr->title) ? ($pr->title['hi'] ?? $pr->title['en']) : $pr->title;
                                @endphp
                                <a href="{{ route('pravachans.show', $pr->slug ?: $pr->id) }}" class="d-flex align-items-center gap-3 text-dark text-decoration-none p-2 rounded hover-bg-light">
                                    <div class="flex-shrink-0 rounded overflow-hidden" style="width: 60px; height: 60px;">
                                        <img src="{{ $pr->featured_image ?: asset('images/jain/muni_pravachan.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $prTitle }}">
                                    </div>
                                    <div>
                                        <h6 class="mb-1 font-spiritual" style="font-size: 14px; line-height: 1.3;">{{ $prTitle }}</h6>
                                        <small class="text-muted"><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($pr->publish_date)->format('d M, Y') }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Contact / Seva Cell -->
                <div class="card border-0 shadow-sm rounded-4 p-4" style="background: var(--bg-warm-tint); border: 1.5px solid var(--gold-border) !important;">
                    <h5 class="font-spiritual text-dark mb-2"><i class="fa-solid fa-hand-holding-heart text-danger me-2"></i> संत सेवा एवं आहार चर्या</h5>
                    <p class="small text-muted mb-3">पूज्य संतों की वैयावृत्ति, आहार चर्या एवं दर्शन समय की जानकारी हेतु समिति से संपर्क करें।</p>
                    <a href="{{ route('contact') }}" class="btn btn-sm btn-spiritual w-100">संपर्क केंद्र से जुड़ें</a>
                </div>
            </div>
        </div>
    </div>
@endsection
