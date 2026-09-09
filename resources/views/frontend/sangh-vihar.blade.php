@extends('layouts.app')

@php
    $isViharActive = ($viharSettings['vihar_status_active'] ?? '1') === '1';
    $sanghName = $viharSettings['vihar_sangh_name'] ?? 'परम पूज्य आचार्य श्री विद्यासागर जी महाराज ससंघ';
    $sanghLeader = $viharSettings['vihar_sangh_leader'] ?? 'आचार्य श्री 108 विद्यासागर जी महाराज';
    $currentLocation = $viharSettings['vihar_current_location'] ?? 'श्री सिद्धक्षेत्र कुंडलपुर जी (दमोह, म.प्र.)';
    $direction = $viharSettings['vihar_direction_destination'] ?? 'श्री दिगम्बर जैन अतिशय क्षेत्र पटेरा की ओर';
    $nightHalt = $viharSettings['vihar_night_halt'] ?? 'शासकीय उच्चतर माध्यमिक विद्यालय प्रांगण, जबेरा';
    $aaharCharya = $viharSettings['vihar_aahar_charya'] ?? 'प्रातः 9:30 बजे से 10:30 बजे तक - श्री शांतिनाथ जिनालय परिसर';
    $contacts = $viharSettings['vihar_contact_numbers'] ?? '+91 98765 43210, +91 94251 00000';
    $lastUpdated = $viharSettings['vihar_last_updated'] ?? 'आज प्रातः 08:30 बजे';
    $description = $viharSettings['vihar_description'] ?? 'पूज्य गुरुवर ससंघ का मंगल विहार धर्म प्रभावना एवं आत्म-कल्याण हेतु निरंतर गतिमान है। सभी भव्य आत्माएं यथासंभव आहार-जल एवं दर्शन लाभ लेकर पुण्य संचय करें।';
    $posterImage = $viharSettings['vihar_poster_image'] ?? '/images/jain/muni_vidyasagar.jpg';
    $marqueeText = $viharSettings['marquee_text'] ?? '🚩 लाइव विहार सूचना: पूज्य आचार्य संघ का मंगल विहार निरंतर जारी है। कृपया मार्ग में जीव-जंतुओं की रक्षा व अहिंसा का विशेष ध्यान रखें।';
@endphp

@section('title', 'संघ जानकारी एवं लाइव विहार स्थिति (Sangh Info & Live Vihar Status)')
@section('meta_description', 'पूज्य आचार्य श्री विद्यासागर जी महामुनिराज ससंघ की वर्तमान स्थिति, विहार दिशा, रात्रि विश्राम एवं आहारचर्या की प्रामाणिक लाइव जानकारी।')

@section('content')
    <!-- Top Live Vihar Announcement Marquee -->
    @if(!empty($marqueeText))
        <div class="bg-warning py-2 border-bottom border-warning-subtle shadow-sm">
            <div class="container d-flex align-items-center">
                <span class="badge bg-danger text-white me-2 px-2.5 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; animation: pulse 2s infinite;">
                    <i class="fa-solid fa-tower-broadcast me-1"></i> लाइव विहार
                </span>
                <marquee behavior="scroll" direction="left" scrollamount="6" class="text-dark fw-bold mb-0" style="font-size: 14px;">
                    {{ $marqueeText }}
                </marquee>
            </div>
        </div>
    @endif

    <x-breadcrumbs :items="[['title' => 'संघ जानकारी एवं लाइव विहार स्थिति', 'url' => '']]" />

    <div class="container pb-5">
        <!-- Main Section Title -->
        <div class="text-center mb-4">
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-3 py-1.5 rounded-pill fw-bold mb-2">
                <i class="fa-solid fa-location-dot me-1"></i> प्रामाणिक विहार ट्रैकर
            </span>
            <h1 class="font-spiritual mb-2" style="font-size: 32px; color: var(--primary);">
                संघ जानकारी एवं लाइव विहार स्थिति
            </h1>
            <p class="text-muted mx-auto" style="max-width: 750px; font-size: 15px;">
                पूज्य साधु-संत संघ की वर्तमान विराजमान स्थिति, मंगल विहार दिशा, रात्रि विश्राम स्थल एवं आहारचर्या की अद्यतन प्रामाणिक जानकारी।
            </p>
        </div>

        <!-- Live Vihar Hero Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5" style="border: 1.5px solid #fed7aa !important; background: #ffffff;">
            <!-- Status Header Strip -->
            <div class="p-3 px-4 d-flex flex-wrap align-items-center justify-content-between gap-2" style="background: {{ $isViharActive ? 'linear-gradient(90deg, #ea580c 0%, #c2410c 100%)' : 'linear-gradient(90deg, #059669 0%, #047857 100%)' }}; color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <span class="spinner-grow spinner-grow-sm text-light" role="status" aria-hidden="true"></span>
                    <strong style="font-size: 16px;">
                        @if($isViharActive)
                            <i class="fa-solid fa-person-walking me-1"></i> पूज्य संघ का मंगल विहार जारी है (Live Vihar in Progress)
                        @else
                            <i class="fa-solid fa-building-columns me-1"></i> पूज्य संघ वर्तमान में विराजित है (Currently Stationed)
                        @endif
                    </strong>
                </div>
                <div class="small px-3 py-1.5 rounded-pill shadow-sm" style="background: rgba(0, 0, 0, 0.45); color: #fef08a; border: 1.5px solid rgba(254, 240, 138, 0.6); font-weight: 600;">
                    <i class="fa-solid fa-rotate text-warning me-1"></i> <span class="text-white">अंतिम अद्यतन:</span> <span style="color: #fef08a;">{{ $lastUpdated }}</span>
                </div>
            </div>

            <!-- Content Body: Left Poster & Right Key Parameters -->
            <div class="p-4 p-md-5">
                <div class="row g-4 align-items-center">
                    <!-- Left: Featured Poster / Photo -->
                    <div class="col-lg-5 text-center">
                        <div class="position-relative rounded-4 overflow-hidden shadow-sm border" style="background: #faf6f0; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#sanghPosterModal" title="क्लिक करके बड़ा पोस्टर देखें">
                            <img src="{{ $posterImage }}" alt="{{ $sanghName }}" class="w-100" style="height: 340px; object-fit: contain; display: block; background: #fdfbf7;">
                            <div class="position-absolute bottom-0 start-0 end-0 p-2 text-center" style="background: linear-gradient(transparent, rgba(0,0,0,0.65));">
                                <span class="badge bg-warning text-dark shadow-sm" style="font-size: 11.5px; font-weight: 600;">
                                    <i class="fa-solid fa-magnifying-glass-plus me-1"></i> क्लिक कर पूर्ण पोस्टर देखें
                                </span>
                            </div>
                        </div>

                        <!-- Sangh Title & Leader Box (Clean & 100% High Contrast) -->
                        <div class="mt-3 p-3 bg-white rounded-3 shadow-sm text-start" style="border: 1.5px solid #fed7aa;">
                            <span class="badge bg-warning text-dark mb-1 px-2.5 py-1" style="font-size: 11px; font-weight: 600;">
                                <i class="fa-solid fa-om me-1"></i> पूज्य संत संघ
                            </span>
                            <h4 class="font-spiritual mb-1.5" style="font-size: 17.5px; color: #7c2d12; line-height: 1.35;">
                                {{ $sanghName }}
                            </h4>
                            <div class="text-secondary small fw-medium">
                                <i class="fa-solid fa-user-tie text-warning me-1"></i> <strong>संघ नायक:</strong> <span class="text-dark fw-bold">{{ $sanghLeader }}</span>
                            </div>
                        </div>

                        <!-- Emergency Contacts Box -->
                        <div class="mt-3 p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between text-start">
                            <div>
                                <small class="text-muted d-block" style="font-size: 11px;">संघ संपर्क सूत्र (Helpline):</small>
                                <strong class="text-dark" style="font-size: 13.5px;"><i class="fa-solid fa-phone-volume text-warning me-1"></i> {{ $contacts }}</strong>
                            </div>
                            <a href="tel:{{ preg_replace('/[^0-9]/', '', explode(',', $contacts)[0]) }}" class="btn btn-sm btn-spiritual rounded-pill px-3">
                                कॉल करें
                            </a>
                        </div>
                    </div>

                    <!-- Right: Structured Vihar Details Grid -->
                    <div class="col-lg-7">
                        <div class="d-flex flex-column gap-3">
                            <!-- 1. Current Location -->
                            <div class="p-3 rounded-3 border-start border-4 border-warning shadow-sm" style="background: #fffbeb; border-color: #f59e0b !important;">
                                <div class="text-warning-emphasis small fw-bold text-uppercase mb-1">
                                    <i class="fa-solid fa-map-location-dot me-1"></i> वर्तमान विराजमान स्थल (Current Location)
                                </div>
                                <h4 class="font-spiritual mb-0 text-dark" style="font-size: 20px;">
                                    {{ $currentLocation }}
                                </h4>
                            </div>

                            <!-- 2. Vihar Direction & Destination (if active) -->
                            @if($isViharActive && !empty($direction))
                                <div class="p-3 rounded-3 border-start border-4 border-danger shadow-sm" style="background: #fff1f2; border-color: #e11d48 !important;">
                                    <div class="text-danger small fw-bold text-uppercase mb-1">
                                        <i class="fa-solid fa-compass me-1"></i> विहार दिशा एवं गंतव्य (Direction & Next Destination)
                                    </div>
                                    <h5 class="font-spiritual mb-0 text-dark" style="font-size: 18px;">
                                        {{ $direction }}
                                    </h5>
                                </div>
                            @endif

                            <!-- 3. Night Halt (Ratri Vishram) -->
                            @if(!empty($nightHalt))
                                <div class="p-3 rounded-3 border-start border-4 border-info shadow-sm" style="background: #f0f9ff; border-color: #0284c7 !important;">
                                    <div class="text-info small fw-bold text-uppercase mb-1">
                                        <i class="fa-solid fa-moon me-1"></i> रात्रि विश्राम स्थल (Night Halt / Rest Place)
                                    </div>
                                    <h6 class="mb-0 text-dark fw-semibold" style="font-size: 16px;">
                                        {{ $nightHalt }}
                                    </h6>
                                </div>
                            @endif

                            <!-- 4. Aahar Charya Timing -->
                            @if(!empty($aaharCharya))
                                <div class="p-3 rounded-3 border-start border-4 border-success shadow-sm" style="background: #f0fdf4; border-color: #16a34a !important;">
                                    <div class="text-success small fw-bold text-uppercase mb-1">
                                        <i class="fa-solid fa-bowl-rice me-1"></i> आहारचर्या समय व स्थल (Aahar Charya Timing)
                                    </div>
                                    <h6 class="mb-0 text-dark fw-semibold" style="font-size: 15px;">
                                        {{ $aaharCharya }}
                                    </h6>
                                </div>
                            @endif

                            <!-- 5. General Sangh Note & Guidelines -->
                            @if(!empty($description))
                                <div class="p-3 rounded-3 bg-light border">
                                    <div class="text-muted small fw-bold text-uppercase mb-1">
                                        <i class="fa-solid fa-circle-info me-1"></i> विशेष संदेश व दिशानिर्देश:
                                    </div>
                                    <p class="text-secondary small mb-0 leading-relaxed" style="line-height: 1.6;">
                                        {{ $description }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Saints & Sangh Directory -->
        @if($saints->isNotEmpty())
            <div class="mb-5">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h3 class="font-spiritual mb-1" style="font-size: 24px;">
                            <i class="fa-solid fa-users me-2 text-warning"></i> पूज्य साधु संघ परिचय (Revered Saints)
                        </h3>
                        <p class="text-muted small mb-0">संघ में विराजमान पूज्य मुनि, आर्यिका एवं क्षुल्लक श्री की परिचय सूची।</p>
                    </div>
                    <a href="{{ route('sants.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        सभी संत देखें <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4">
                    @foreach($saints as $saint)
                        @php
                            $sName = $saint->getLocalized('name');
                            $sTitle = $saint->getLocalized('title');
                            $sImage = $saint->profile_image ?: '/images/jain/muni_vidyasagar.jpg';
                            $currentSaintVihar = $saint->vihars->first();
                        @endphp
                        <div class="col-lg-3 col-md-6">
                            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden text-center" style="transition: transform 0.2s ease;">
                                <div style="height: 220px; overflow: hidden; position: relative;">
                                    <img src="{{ $sImage }}" alt="{{ $sName }}" class="w-100 h-100 object-fit-cover">
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark shadow-sm" style="font-size: 11px;">
                                        {{ $sTitle }}
                                    </span>
                                </div>
                                <div class="card-body p-3 d-flex flex-column justify-content-between bg-white">
                                    <div>
                                        <h5 class="font-spiritual mb-1" style="font-size: 16px;">
                                            <a href="{{ route('sants.show', $saint->slug ?: $saint->id) }}" class="text-dark text-decoration-none">
                                                {{ $sName }}
                                            </a>
                                        </h5>
                                        @if($currentSaintVihar)
                                            <p class="text-muted small mb-2" style="font-size: 12px;">
                                                <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $currentSaintVihar->location_name }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="pt-2 border-top">
                                        <a href="{{ route('sants.show', $saint->slug ?: $saint->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill w-100" style="font-size: 12px;">
                                            जीवन परिचय देखें
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Devotee Guidelines Box -->
        <div class="p-4 rounded-4" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1px solid #fde68a;">
            <h4 class="font-spiritual text-warning-emphasis mb-3" style="font-size: 20px;">
                <i class="fa-solid fa-hands-praying me-2 text-warning"></i> विहार सेवा एवं दर्शन हेतु आवश्यक नियम:
            </h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 bg-white rounded-3 h-100 shadow-sm border border-warning-subtle">
                        <strong class="text-dark d-block mb-1"><i class="fa-solid fa-shield-halved text-success me-1"></i> अहिंसा व जीव-रक्षा:</strong>
                        <p class="text-muted small mb-0">विहार के दौरान सड़क व मार्ग में छोटे जीव-जंतुओं की रक्षा का विशेष ध्यान रखें और वाहन धीरे चलाएं।</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-white rounded-3 h-100 shadow-sm border border-warning-subtle">
                        <strong class="text-dark d-block mb-1"><i class="fa-solid fa-shirt text-primary me-1"></i> सादगी व शुद्ध वस्त्र:</strong>
                        <p class="text-muted small mb-0">पूज्य संतों के दर्शन व आहारचर्या के समय शुद्ध, धुले हुए मर्यादित वस्त्र पहनकर ही उपस्थित हों।</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-white rounded-3 h-100 shadow-sm border border-warning-subtle">
                        <strong class="text-dark d-block mb-1"><i class="fa-solid fa-volume-xmark text-danger me-1"></i> अनुशासन व शांति:</strong>
                        <p class="text-muted small mb-0">मोबाइल फोन साइलेंट रखें एवं संघ व्यवस्थापकों के निर्देशों का पूर्ण आदर व पालन करें।</p>
                    </div>
                </div>
        </div>
    </div>

    <!-- Full Poster View Modal -->
    <div class="modal fade" id="sanghPosterModal" tabindex="-1" aria-labelledby="sanghPosterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-warning py-2.5 px-4 border-0">
                    <h5 class="modal-title font-spiritual text-dark" id="sanghPosterModalLabel" style="font-size: 17px;">
                        <i class="fa-solid fa-image text-danger me-2"></i> {{ $sanghName }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="बंद करें"></button>
                </div>
                <div class="modal-body p-0 text-center bg-dark">
                    <img src="{{ $posterImage }}" alt="{{ $sanghName }}" class="img-fluid" style="max-height: 80vh; object-fit: contain;">
                </div>
                <div class="modal-footer bg-light py-2 px-4 border-0 justify-content-between">
                    <span class="text-muted small">
                        <i class="fa-solid fa-user-tie me-1 text-warning"></i> <strong>संघ नायक:</strong> {{ $sanghLeader }}
                    </span>
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">बंद करें</button>
                </div>
            </div>
        </div>
    </div>
@endsection
