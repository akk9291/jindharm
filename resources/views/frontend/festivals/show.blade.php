@extends('layouts.app')

@php
    $fName = is_array($festival->festival_name) ? ($festival->festival_name['hi'] ?? $festival->festival_name['en']) : $festival->festival_name;
    $fDesc = is_array($festival->description) ? ($festival->description['hi'] ?? $festival->description['en'] ?? '') : $festival->description;
    $fImage = $festival->image ?: asset('images/jain/tirthankara_idol.jpg');
@endphp

@section('title', $fName . ' - धार्मिक महत्व एवं विधि')
@section('meta_description', strip_tags(Str::limit($fDesc, 150)))
@section('og_image', $fImage)

@section('content')
    <x-breadcrumbs :items="[
        ['title' => 'जैन महापर्व', 'url' => route('festivals.index')],
        ['title' => $fName, 'url' => '']
    ]" />

    <div class="container pb-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                    <img src="{{ $fImage }}" class="w-100 object-fit-cover" style="max-height: 420px;" alt="{{ $fName }}">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-3 border-bottom">
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold">
                                <i class="fa-solid fa-sparkles me-1"></i> महापर्व विवरण
                            </span>
                            <span class="text-muted small">
                                <i class="fa-regular fa-calendar me-1"></i> तिथि: <strong>{{ \Carbon\Carbon::parse($festival->festival_date)->format('d F, Y') }}</strong>
                            </span>
                        </div>

                        <h1 class="font-spiritual mb-3" style="font-size: 30px;">{{ $fName }}</h1>

                        <div class="p-3 rounded-3 mb-4" style="background: var(--bg-warm-tint); border-left: 4px solid var(--gold);">
                            <p class="mb-0 text-dark fw-medium">
                                "पर्व वही है जो आत्मा को विकारों से बचाकर पवित्रता और संयम के सर्वोच्च शिखर पर आरूढ़ करे।"
                            </p>
                        </div>

                        <div class="content-body" style="font-size: 16.5px; line-height: 1.8;">
                            {!! nl2br(e($fDesc)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar: Other Festivals -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="font-spiritual border-bottom pb-2 mb-3"><i class="fa-solid fa-award text-warning me-2"></i> अन्य पावन पर्व</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($related as $r)
                            @php
                                $rName = is_array($r->festival_name) ? ($r->festival_name['hi'] ?? $r->festival_name['en']) : $r->festival_name;
                            @endphp
                            <a href="{{ route('festivals.show', $r->slug ?: $r->id) }}" class="d-flex align-items-center gap-3 text-dark text-decoration-none p-2 rounded hover-bg-light">
                                <div class="flex-shrink-0 rounded overflow-hidden" style="width: 70px; height: 60px;">
                                    <img src="{{ $r->image ?: asset('images/jain/temple_shikhar.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $rName }}">
                                </div>
                                <div>
                                    <h6 class="mb-1 font-spiritual" style="font-size: 14px; line-height: 1.3;">{{ $rName }}</h6>
                                    <small class="text-muted d-block" style="font-size: 11px;"><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($r->festival_date)->format('d M, Y') }}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Panchang Link -->
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center" style="background: var(--bg-warm-tint); border: 1.5px solid var(--gold-border) !important;">
                    <i class="fa-solid fa-calendar-days text-danger fs-2 mb-2"></i>
                    <h5 class="font-spiritual">दैनिक पंचांग देखें</h5>
                    <p class="small text-muted mb-3">आज की तिथि, पक्ष, नक्षत्र एवं सूर्योदय-सूर्यास्त काल गणना देखें।</p>
                    <a href="{{ route('panchang.index') }}" class="btn btn-sm btn-spiritual">पंचांग खोलें</a>
                </div>
            </div>
        </div>
    </div>
@endsection
