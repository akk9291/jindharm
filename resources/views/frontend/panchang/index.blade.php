@extends('layouts.app')

@section('title', 'जैन पंचांग | दैनिक तिथि, नक्षत्र एवं पर्व व्रत काल गणना')
@section('meta_description', 'प्रामाणिक दिगम्बर जैन पंचांग, मासिक कैलेंडर, दैनिक तिथि, नक्षत्र, सूर्योदय-सूर्यास्त एवं पर्व व्रत की शुद्ध गणना।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'जैन पंचांग', 'url' => '']]" />

    <div class="container pb-5">
        <!-- Monthly Header & Navigation -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold mb-1">
                        <i class="fa-solid fa-moon me-1"></i> प्रामाणिक जैन काल गणना
                    </span>
                    <h1 class="font-spiritual mb-0" style="font-size: 28px;">
                        {{ $currentDate->format('F Y') }} ({{ $selectedPanchang->maas ?? 'भाद्रपद' }})
                    </h1>
                </div>

                <!-- Month navigation buttons -->
                @php
                    $prevMonth = (clone $currentDate)->subMonth();
                    $nextMonth = (clone $currentDate)->addMonth();
                @endphp
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('panchang.index', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-chevron-left me-1"></i> पिछला माह
                    </a>
                    <a href="{{ route('panchang.index') }}" class="btn btn-sm btn-spiritual-outline">आज</a>
                    <a href="{{ route('panchang.index', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}" class="btn btn-sm btn-outline-secondary">
                        अगला माह <i class="fa-solid fa-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Calendar Grid Column -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                    <h4 class="font-spiritual mb-4"><i class="fa-solid fa-calendar-days text-danger me-2"></i> मासिक कैलेंडर दृश्य</h4>
                    
                    <div class="row g-2">
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $dObj = \Carbon\Carbon::createFromDate($year, $month, $d);
                                $dStr = $dObj->toDateString();
                                $pRec = $panchangRecords[$dStr] ?? null;
                                $hasFest = isset($festivals[$dStr]);
                                $isSelected = $dStr === $selectedDateStr;
                                $isToday = $dStr === \Carbon\Carbon::today()->toDateString();
                            @endphp
                            <div class="col-md-3 col-4 col-sm-4">
                                <a href="{{ route('panchang.index', ['year' => $year, 'month' => $month, 'date' => $dStr]) }}" 
                                   class="d-block p-2 rounded-3 text-decoration-none text-center border transition-all {{ $isSelected ? 'border-danger bg-danger text-white shadow' : ($isToday ? 'border-warning bg-warning bg-opacity-10 text-dark' : 'bg-light text-dark hover-bg-light') }}" style="min-height: 85px;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold" style="font-size: 16px;">{{ $d }}</span>
                                        <small class="opacity-75" style="font-size: 11px;">{{ $dObj->format('D') }}</small>
                                    </div>
                                    <div class="small fw-semibold text-truncate" style="font-size: 11px;">
                                        {{ $pRec ? $pRec->tithi : '-' }}
                                    </div>
                                    @if($hasFest)
                                        <span class="badge bg-warning text-dark mt-1" style="font-size: 9px;">पर्व</span>
                                    @endif
                                </a>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Selected Date Details Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white position-sticky" style="top: 100px; border: 1.5px solid var(--gold-border) !important;">
                    <div class="text-center pb-3 border-bottom mb-3">
                        <span class="text-muted small">{{ \Carbon\Carbon::parse($selectedDateStr)->format('l, d F Y') }}</span>
                        <h3 class="font-spiritual text-danger mb-0 mt-1">
                            {{ $selectedPanchang ? $selectedPanchang->tithi : 'दैनिक पंचांग' }}
                        </h3>
                        <span class="badge bg-light text-dark border mt-1">{{ $selectedPanchang->paksha ?? '' }}</span>
                    </div>

                    @if($selectedPanchang)
                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="d-flex justify-content-between py-2 border-bottom small">
                                <span class="text-muted">मास (Month):</span>
                                <strong>{{ $selectedPanchang->maas }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom small">
                                <span class="text-muted">नक्षत्र (Nakshatra):</span>
                                <strong>{{ $selectedPanchang->nakshatra ?? 'स्वाति' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom small">
                                <span class="text-muted">सूर्योदय (Sunrise):</span>
                                <strong>{{ $selectedPanchang->sunrise ?? '०५:५४ AM' }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom small">
                                <span class="text-muted">सूर्यास्त (Sunset):</span>
                                <strong>{{ $selectedPanchang->sunset ?? '०६:४२ PM' }}</strong>
                            </div>
                        </div>

                        @if($selectedPanchang->notes)
                            <div class="p-3 rounded-3 bg-light border mb-3">
                                <div class="small fw-bold text-danger mb-1"><i class="fa-solid fa-bell me-1"></i> विशेष पर्व / व्रत निर्देश:</div>
                                <p class="small mb-0 text-dark">{{ $selectedPanchang->notes }}</p>
                            </div>
                        @endif
                    @endif

                    @if($selectedFestival)
                        @php
                            $fName = is_array($selectedFestival->festival_name) ? ($selectedFestival->festival_name['hi'] ?? $selectedFestival->festival_name['en']) : $selectedFestival->festival_name;
                        @endphp
                        <div class="p-3 rounded-3 bg-warning bg-opacity-25 border border-warning">
                            <h6 class="mb-1 text-dark fw-bold"><i class="fa-solid fa-sparkles text-warning me-1"></i> आज का महापर्व:</h6>
                            <p class="mb-0 text-dark small">{{ $fName }}</p>
                            <a href="{{ route('festivals.show', $selectedFestival->slug ?: $selectedFestival->id) }}" class="small text-danger fw-bold d-block mt-2">
                                पर्व की धार्मिक महत्ता पढ़ें <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
