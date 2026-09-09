@extends('layouts.app')

@section('title', 'जैन महापर्व एवं त्योहार | दशलक्षण, महावीर जयंती, दीपावली')
@section('meta_description', 'जैन धर्म के पवित्र महापर्व - दशलक्षण पर्युषण पर्व, महावीर जयंती, महावीर मोक्ष कल्याणक (दीपावली) का आध्यात्मिक महत्व एवं तिथियां।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'जैन पर्व एवं त्योहार', 'url' => '']]" />

    <div class="container pb-5">
        <div class="mb-4">
            <h1 class="font-spiritual mb-1" style="font-size: 30px;">जैन महापर्व एवं त्योहार</h1>
            <p class="text-muted small mb-0">आत्म-शुद्धि, त्याग, तपस्या और क्षमा भाव को समर्पित सनातन जैन पर्व।</p>
        </div>

        @if($festivals->isNotEmpty())
            <div class="row g-4">
                @foreach($festivals as $fest)
                    @php
                        $fName = is_array($fest->festival_name) ? ($fest->festival_name['hi'] ?? $fest->festival_name['en']) : $fest->festival_name;
                        $fDesc = is_array($fest->description) ? ($fest->description['hi'] ?? $fest->description['en'] ?? '') : $fest->description;
                        $fImage = $fest->image ?: asset('images/jain/tirthankara_idol.jpg');
                        $slug = $fest->slug ?: $fest->id;
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="spiritual-card">
                            <div class="card-img-wrap" style="height: 200px;">
                                <img src="{{ $fImage }}" alt="{{ $fName }}" loading="lazy">
                                <span class="card-badge-top" style="background: var(--primary);">
                                    <i class="fa-solid fa-sparkles text-warning me-1"></i> महापर्व
                                </span>
                                <span class="card-badge-right">
                                    <i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($fest->festival_date)->format('d M, Y') }}
                                </span>
                            </div>
                            <div class="card-content">
                                <h4 class="font-spiritual mb-2" style="font-size: 18px;">
                                    <a href="{{ route('festivals.show', $slug) }}" class="text-dark">{{ $fName }}</a>
                                </h4>
                                @if($fDesc)
                                    <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $fDesc }}
                                    </p>
                                @endif
                                <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between">
                                    <span class="badge bg-light text-dark border">पर्व आराधना</span>
                                    <a href="{{ route('festivals.show', $slug) }}" class="btn btn-sm btn-spiritual-outline py-1 px-3">
                                        धार्मिक महत्व पढ़ें <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$festivals" />
        @else
            <x-empty-state 
                title="कोई पर्व उपलब्ध नहीं है" 
                message="शीघ्र ही नए पर्वों की सूची यहाँ जोड़ी जाएगी।"
                icon="fa-award"
            />
        @endif
    </div>
@endsection
