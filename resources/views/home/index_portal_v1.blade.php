@extends('layouts.app')

@section('title', 'सर्वोदय जिनधर्म | जैन धर्म, ग्रन्थ, प्रवचन, तीर्थ निर्देशिका एवं दैनिक पंचांग पोर्टल')
@section('meta_description', 'जैन धर्म के पवित्र ग्रन्थ, आचार्यश्री के अमृत प्रवचन, भक्ति भजन, तीर्थ क्षेत्र संपर्क निर्देशिका, दैनिक पंचांग, सुविचार एवं PDF ई-लाइब्रेरी का सम्पूर्ण डिजिटल मंच।')

@section('content')

    <!-- 1. Top Marquee Announcement Bar -->
    @php
        $marqueeText = $siteSettings['marquee_text'] ?? 'एक महत्वपूर्ण संदेश - सभी जैनतीर्थ पर पूर्ण अनुशासन व मर्यादा प्रार्थनीय हैं। सजग रहें, अगर किसी तीर्थ पर अनुशासन न दिखे, तो तुरन्त हमें अवगत करें';
    @endphp
    @if(!empty($marqueeText))
        <div class="vihar-marquee-bar py-1.5" style="background: linear-gradient(90deg, #7c2d12 0%, #9a3412 50%, #7c2d12 100%); color: #fff; border-bottom: 2px solid var(--gold); position: relative; overflow: hidden; z-index: 10;">
            <div class="container-fluid px-3 d-flex align-items-center">
                <div class="d-flex align-items-center gap-2 flex-shrink-0 pe-3 border-end border-warning border-opacity-50" style="z-index: 2; background: inherit;">
                    <span class="badge" style="background: #fef08a; color: #7c2d12; font-weight: 700; font-size: 11.5px; padding: 4px 10px; border-radius: 20px;">
                        <i class="fa-solid fa-bullhorn fa-bounce me-1 text-danger"></i> तीर्थ सूचना
                    </span>
                </div>
                <div class="vihar-marquee-wrap flex-grow-1 overflow-hidden position-relative" style="white-space: nowrap; height: 24px;">
                    <div class="vihar-marquee-content d-inline-block ps-4 fw-medium" style="font-size: 13.5px; letter-spacing: 0.3px;">
                        <span class="text-warning me-2"><i class="fa-solid fa-bell"></i></span>
                        {{ $marqueeText }}
                        <span class="mx-4 text-warning opacity-75">•</span>
                        <span class="text-warning me-2"><i class="fa-solid fa-hands-praying"></i></span>
                        {{ $marqueeText }}
                    </div>
                </div>
                <div class="d-none d-md-flex align-items-center ps-3 flex-shrink-0 border-start border-warning border-opacity-50" style="z-index: 2; background: inherit;">
                    <a href="{{ route('sangh-vihar') }}" class="btn btn-sm text-white" style="background: rgba(254, 240, 138, 0.2); border: 1px solid rgba(254, 240, 138, 0.4); font-size: 11.5px; padding: 2px 10px; border-radius: 14px;">
                        <i class="fa-solid fa-location-dot me-1 text-warning"></i> लाइव विहार स्थिति
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- 2. Portal Hero & Unified Instant Universal Search -->
    <section class="portal-hero-section position-relative text-white py-5" style="background: linear-gradient(135deg, #451a03 0%, #7c2d12 45%, #9a3412 100%);">
        <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: radial-gradient(#fbbf24 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
        
        <div class="container position-relative" style="z-index: 2;">
            <div class="row justify-content-center text-center mb-4">
                <div class="col-lg-10">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill mb-3" style="background: rgba(254, 240, 138, 0.15); border: 1px solid rgba(254, 240, 138, 0.3); backdrop-filter: blur(4px);">
                        <span class="badge bg-warning text-dark px-2 py-0.5 rounded-pill fw-bold" style="font-size: 11px;">नया संस्करण</span>
                        <span class="text-warning small fw-semibold">डिजिटल जैन ज्ञानकोश एवं तीर्थ पोर्टल</span>
                    </div>

                    <h1 class="font-spiritual display-6 fw-bold mb-2 text-white" style="font-size: clamp(26px, 4vw, 42px); text-shadow: 0 2px 8px rgba(0,0,0,0.4);">
                        {{ $siteSettings['website_name'] ?? 'सर्वोदय जिनधर्म' }}
                    </h1>
                    <p class="text-white text-opacity-85 fs-6 mb-4 mx-auto" style="max-width: 780px;">
                        {{ $siteSettings['site_tagline'] ?? 'पवित्र ग्रन्थ, अमृत प्रवचन, भक्ति भजन, तीर्थ क्षेत्र संपर्क निर्देशिका, दैनिक पंचांग, सुविचार एवं PDF ई-लाइब्रेरी का सम्पूर्ण मंच।' }}
                    </p>

                    <!-- Universal Search Bar -->
                    <div class="hero-search-box mx-auto shadow-lg" style="max-width: 820px; background: #ffffff; border-radius: 50px; padding: 6px 8px; border: 3px solid rgba(254, 240, 138, 0.5);">
                        <form action="{{ route('search') }}" method="GET" class="d-flex align-items-center flex-wrap flex-md-nowrap gap-2">
                            
                            <!-- Search Category Select -->
                            <div class="d-none d-md-block flex-shrink-0 ps-2 pe-1 border-end border-2">
                                <select name="type" class="form-select border-0 shadow-none text-muted fw-semibold bg-transparent" style="font-size: 13.5px; width: 140px; cursor: pointer;">
                                    <option value="">सभी श्रेणियां</option>
                                    <option value="granth">📖 ग्रन्थ (Books)</option>
                                    <option value="pravachan">🎙️ प्रवचन</option>
                                    <option value="bhajan">🎶 भजन व पाठ</option>
                                    <option value="news">📰 समाचार</option>
                                    <option value="saint">🙏 साधु-संत</option>
                                    <option value="quote">✨ सुविचार</option>
                                </select>
                            </div>

                            <!-- Search Input -->
                            <div class="d-flex align-items-center flex-grow-1 px-3 py-1">
                                <i class="fa-solid fa-magnifying-glass text-muted me-2" style="font-size: 17px;"></i>
                                <input type="text" name="q" class="form-control border-0 shadow-none p-0 text-dark" placeholder="ग्रन्थ, भजन, महाराज जी, तीर्थ क्षेत्र या विषय खोजें..." style="font-size: 15px;" required>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-spiritual rounded-pill px-4 py-2.5 fw-bold d-flex align-items-center gap-2 flex-shrink-0 shadow-sm" style="font-size: 14.5px;">
                                <span>खोजें</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Popular Search Quick Tags -->
                    <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 mt-3 pt-1">
                        <span class="text-white text-opacity-75 small fw-semibold me-1"><i class="fa-solid fa-fire text-warning me-1"></i>लोकप्रिय खोज:</span>
                        <a href="{{ route('granths.show', 'tattvartha-sutra') }}" class="badge text-decoration-none px-2.5 py-1 rounded-pill" style="background: rgba(255,255,255,0.18); color: #fff; font-size: 12px; border: 1px solid rgba(255,255,255,0.25);">तत्त्वार्थ सूत्र</a>
                        <a href="{{ route('granths.show', 'samayasara-granth') }}" class="badge text-decoration-none px-2.5 py-1 rounded-pill" style="background: rgba(255,255,255,0.18); color: #fff; font-size: 12px; border: 1px solid rgba(255,255,255,0.25);">समयसार</a>
                        <a href="{{ route('bhajans.show', 'bhaktamar-stotra-pdf') }}" class="badge text-decoration-none px-2.5 py-1 rounded-pill" style="background: rgba(255,255,255,0.18); color: #fff; font-size: 12px; border: 1px solid rgba(255,255,255,0.25);">भक्तामर स्तोत्र</a>
                        <a href="{{ route('gallery.pdfs') }}" class="badge text-decoration-none px-2.5 py-1 rounded-pill" style="background: rgba(255,255,255,0.18); color: #fff; font-size: 12px; border: 1px solid rgba(255,255,255,0.25);"><i class="fa-solid fa-file-pdf text-warning me-1"></i>PDF ई-लाइब्रेरी</a>
                        <a href="{{ route('panchang.index') }}" class="badge text-decoration-none px-2.5 py-1 rounded-pill" style="background: rgba(255,255,255,0.18); color: #fff; font-size: 12px; border: 1px solid rgba(255,255,255,0.25);">पंचांग २०२६</a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- 3. Smart 10-Category Visual Discovery Hub (Navigation Grid) -->
    <section class="py-4 bg-white border-bottom shadow-sm position-relative" style="margin-top: -24px; z-index: 4;">
        <div class="container">
            <div class="category-discovery-grid">
                
                <!-- 1. Granth -->
                <a href="{{ route('granths.index') }}" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #fef2f2; color: #dc2626;">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">पवित्र ग्रन्थ</span>
                        <span class="cat-count">{{ $stats['granths'] ?? 5 }}+ शास्त्र</span>
                    </div>
                </a>

                <!-- 2. Pravachan -->
                <a href="{{ route('pravachans.index') }}" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #fff7ed; color: #ea580c;">
                        <i class="fa-solid fa-microphone-lines"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">अमृत प्रवचन</span>
                        <span class="cat-count">{{ $stats['pravachans'] ?? 8 }}+ व्याख्यान</span>
                    </div>
                </a>

                <!-- 3. Bhajan -->
                <a href="{{ route('bhajans.index') }}" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #fefce8; color: #ca8a04;">
                        <i class="fa-solid fa-music"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">भजन व पाठ</span>
                        <span class="cat-count">{{ $stats['bhajans'] ?? 10 }}+ स्तुतियां</span>
                    </div>
                </a>

                <!-- 4. Suvichar -->
                <a href="{{ route('suvichar.index') }}" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #fdf4ff; color: #c026d3;">
                        <i class="fa-solid fa-quote-left"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">दैनिक सुविचार</span>
                        <span class="cat-count">{{ $stats['quotes'] ?? 9 }}+ प्रेरणा</span>
                    </div>
                </a>

                <!-- 5. Tirth Directory -->
                <a href="#tirth-section" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #ecfdf5; color: #059669;">
                        <i class="fa-solid fa-gopuram"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">तीर्थ निर्देशिका</span>
                        <span class="cat-count">{{ $stats['tirth'] ?? 6 }}+ सिद्धक्षेत्र</span>
                    </div>
                </a>

                <!-- 6. PDF Library -->
                <a href="{{ route('gallery.pdfs') }}" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #fef2f2; color: #991b1b;">
                        <i class="fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">PDF ई-पुस्तिका</span>
                        <span class="cat-count">{{ $stats['pdfs'] ?? 6 }}+ ई-ग्रन्थ</span>
                    </div>
                </a>

                <!-- 7. Videos -->
                <a href="{{ route('gallery.videos') }}" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #fff1f2; color: #e11d48;">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">वीडियो दीर्घा</span>
                        <span class="cat-count">{{ $stats['videos'] ?? 6 }}+ वीडियो</span>
                    </div>
                </a>

                <!-- 8. News -->
                <a href="{{ route('news.index') }}" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #eff6ff; color: #2563eb;">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">धर्म समाचार</span>
                        <span class="cat-count">{{ $stats['news'] ?? 4 }}+ पत्रिकाएं</span>
                    </div>
                </a>

                <!-- 9. Sangh Vihar -->
                <a href="{{ route('sangh-vihar') }}" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #f0fdf4; color: #16a34a;">
                        <i class="fa-solid fa-person-walking"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">संघ व विहार</span>
                        <span class="cat-count">लाइव स्थिति</span>
                    </div>
                </a>

                <!-- 10. Panchang -->
                <a href="{{ route('panchang.index') }}" class="category-quick-card">
                    <div class="cat-icon-circle" style="background: #fffbeb; color: #d97706;">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div class="cat-card-info">
                        <span class="cat-title">जैन पंचांग</span>
                        <span class="cat-count">तिथि व पर्व</span>
                    </div>
                </a>

            </div>
        </div>
    </section>

    <!-- 4. Today's Compact Spiritual Dashboard (Panchang + Daily Quote) -->
    <section class="py-4" style="background: #fcf9f5;">
        <div class="container">
            <div class="row g-3 align-items-stretch">
                
                <!-- Left: Today's Jain Panchang -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3.5 bg-white position-relative overflow-hidden" style="border-left: 5px solid #d97706 !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fw-bold" style="font-size: 11.5px;">
                                    <i class="fa-solid fa-calendar-day me-1"></i> आज का पंचांग
                                </span>
                                <span class="text-muted small fw-semibold">{{ date('d F, Y') }}</span>
                            </div>
                            <a href="{{ route('panchang.index') }}" class="text-primary small fw-semibold text-decoration-none">
                                सम्पूर्ण पंचांग <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>

                        @if($todayPanchang)
                            @php
                                $tithiName = is_array($todayPanchang->tithi) ? ($todayPanchang->tithi['hi'] ?? $todayPanchang->tithi['en'] ?? 'शुद्ध तिथि') : ($todayPanchang->tithi ?: 'एकादशी');
                                $pakshaName = is_array($todayPanchang->paksha) ? ($todayPanchang->paksha['hi'] ?? $todayPanchang->paksha['en'] ?? 'शुक्ल पक्ष') : ($todayPanchang->paksha ?: 'शुक्ल पक्ष');
                                $festName = is_array($todayPanchang->festival_name) ? ($todayPanchang->festival_name['hi'] ?? $todayPanchang->festival_name['en'] ?? '') : $todayPanchang->festival_name;
                            @endphp

                            <div class="p-2.5 rounded-3 mb-2" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
                                <div class="row g-2 text-center">
                                    <div class="col-4 border-end border-warning border-opacity-50">
                                        <div class="text-muted small" style="font-size: 11px;">तिथि (Tithi)</div>
                                        <div class="fw-bold font-spiritual text-dark" style="font-size: 16px;">{{ $tithiName }}</div>
                                    </div>
                                    <div class="col-4 border-end border-warning border-opacity-50">
                                        <div class="text-muted small" style="font-size: 11px;">पक्ष (Paksha)</div>
                                        <div class="fw-bold font-spiritual text-dark" style="font-size: 16px;">{{ $pakshaName }}</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-muted small" style="font-size: 11px;">सूर्योदय (Sunrise)</div>
                                        <div class="fw-bold text-dark" style="font-size: 15px;">{{ $todayPanchang->sunrise ?: '०६:०५ AM' }}</div>
                                    </div>
                                </div>
                            </div>

                            @if($festName)
                                <div class="d-flex align-items-center gap-2 p-2 rounded-2 bg-danger bg-opacity-10 text-danger small fw-semibold">
                                    <i class="fa-solid fa-award"></i>
                                    <span>आज का पावन पर्व: <strong>{{ $festName }}</strong></span>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-between text-muted small pt-1">
                                    <span><i class="fa-regular fa-clock me-1 text-warning"></i> सूर्यास्त: {{ $todayPanchang->sunset ?: '०६:४० PM' }}</span>
                                    <span><i class="fa-solid fa-moon me-1 text-secondary"></i> नक्षत्र: {{ $todayPanchang->nakshatra ?? 'रोहिणी' }}</span>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-3 text-muted small">
                                <p class="mb-0">दैनिक पंचांग तालिका लोड हो रही है...</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Today's Featured Suvichar / Quote -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3.5 bg-white position-relative overflow-hidden" style="border-left: 5px solid var(--primary) !important;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill fw-bold" style="font-size: 11.5px;">
                                <i class="fa-solid fa-feather-pointed me-1"></i> आज का पावन सुविचार
                            </span>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('suvichar.index') }}" class="text-primary small fw-semibold text-decoration-none">
                                    सभी सुविचार <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>

                        @if($todayQuote)
                            @php
                                $qText = is_array($todayQuote->quote_text) ? ($todayQuote->quote_text['hi'] ?? $todayQuote->quote_text['en'] ?? '') : $todayQuote->quote_text;
                                $qAuthor = is_array($todayQuote->author) ? ($todayQuote->author['hi'] ?? $todayQuote->author['en'] ?? '') : $todayQuote->author;
                                $shareText = urlencode("✨ " . $qText . "\n\n— " . ($qAuthor ?: 'भगवान महावीर') . "\n\nजिनधर्म डिजिटल पोर्टल: " . url('/'));
                            @endphp

                            <div class="d-flex gap-3 align-items-start">
                                <div class="quote-symbol-badge flex-shrink-0">
                                    <i class="fa-solid fa-quote-left fa-2x text-warning opacity-75"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="font-spiritual mb-2 fw-semibold text-dark" style="font-size: 15.5px; line-height: 1.6;">
                                        "{{ $qText }}"
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-1 border-top">
                                        <div class="text-muted small">
                                            <i class="fa-solid fa-user-tie text-warning me-1"></i>
                                            <strong>{{ $qAuthor ?: 'भगवान महावीर स्वामी' }}</strong>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button class="btn btn-sm btn-light py-1 px-2.5 rounded-pill text-muted btn-copy-quote" data-quote="{{ $qText }} — {{ $qAuthor }}" title="सुविचार कॉपी करें">
                                                <i class="fa-regular fa-copy me-1"></i> कॉपी
                                            </button>
                                            <a href="https://api.whatsapp.com/send?text={{ $shareText }}" target="_blank" class="btn btn-sm btn-success py-1 px-2.5 rounded-pill text-white fw-semibold" style="font-size: 11.5px;">
                                                <i class="fa-brands fa-whatsapp me-1"></i> शेयर
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 5. Interactive Multi-Category Content Feed (Tabbed Explorer - Eliminates huge vertical clutter) -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fw-semibold mb-1" style="font-size: 12px;">
                        <i class="fa-solid fa-layer-group me-1"></i> स्वाध्याय एवं ज्ञान भण्डार
                    </span>
                    <h2 class="font-spiritual mb-0" style="font-size: 26px; color: var(--primary);">
                        सर्वोत्तम धार्मिक सामग्री संग्रह
                    </h2>
                </div>

                <!-- Fast Category Filter Tabs -->
                <ul class="nav nav-pills category-filter-nav gap-1" id="contentTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill px-3 py-1.5 fw-semibold" id="tab-all-btn" data-bs-toggle="pill" data-bs-target="#tab-all" type="button" role="tab">
                            ⭐ सभी (All)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold" id="tab-granth-btn" data-bs-toggle="pill" data-bs-target="#tab-granth" type="button" role="tab">
                            📖 ग्रन्थ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold" id="tab-pravachan-btn" data-bs-toggle="pill" data-bs-target="#tab-pravachan" type="button" role="tab">
                            🎙️ प्रवचन
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold" id="tab-bhajan-btn" data-bs-toggle="pill" data-bs-target="#tab-bhajan" type="button" role="tab">
                            🎶 भजन व पाठ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold" id="tab-pdf-btn" data-bs-toggle="pill" data-bs-target="#tab-pdf" type="button" role="tab">
                            📄 PDF ई-ग्रन्थ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1.5 fw-semibold" id="tab-news-btn" data-bs-toggle="pill" data-bs-target="#tab-news" type="button" role="tab">
                            📰 समाचार
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Contents -->
            <div class="tab-content" id="contentTabContent">
                
                <!-- TAB 1: ALL (Curated Mix of Granths, Pravachans, Bhajans) -->
                <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                    <div class="row g-4">
                        @foreach($granths->take(2) as $g)
                            <div class="col-lg-3 col-md-6">
                                <x-granth-card :granth="$g" />
                            </div>
                        @endforeach

                        @foreach($pravachans->take(2) as $p)
                            <div class="col-lg-3 col-md-6">
                                <x-pravachan-card :pravachan="$p" />
                            </div>
                        @endforeach

                        @foreach($bhajans->take(2) as $b)
                            <div class="col-lg-3 col-md-6">
                                <x-bhajan-card :bhajan="$b" />
                            </div>
                        @endforeach

                        @foreach($news->take(2) as $n)
                            <div class="col-lg-3 col-md-6">
                                <x-news-card :news="$n" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- TAB 2: GRANTHS ONLY -->
                <div class="tab-pane fade" id="tab-granth" role="tabpanel">
                    <div class="row g-4">
                        @foreach($granths as $g)
                            <div class="col-lg-3 col-md-6">
                                <x-granth-card :granth="$g" />
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('granths.index') }}" class="btn btn-outline-danger rounded-pill px-4 fw-semibold">
                            सभी पवित्र ग्रन्थ देखें <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- TAB 3: PRAVACHANS ONLY -->
                <div class="tab-pane fade" id="tab-pravachan" role="tabpanel">
                    <div class="row g-4">
                        @foreach($pravachans as $p)
                            <div class="col-lg-3 col-md-6">
                                <x-pravachan-card :pravachan="$p" />
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('pravachans.index') }}" class="btn btn-outline-danger rounded-pill px-4 fw-semibold">
                            सभी अमृत प्रवचन सुनें <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- TAB 4: BHAJANS ONLY -->
                <div class="tab-pane fade" id="tab-bhajan" role="tabpanel">
                    <div class="row g-4">
                        @foreach($bhajans as $b)
                            <div class="col-lg-3 col-md-6">
                                <x-bhajan-card :bhajan="$b" />
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('bhajans.index') }}" class="btn btn-outline-danger rounded-pill px-4 fw-semibold">
                            सभी भजन, स्तुति व आरती संग्रह <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- TAB 5: PDF LIBRARY -->
                <div class="tab-pane fade" id="tab-pdf" role="tabpanel">
                    <div class="row g-4">
                        @foreach($pdfLibrary as $item)
                            @php
                                $title = $item->getLocalized('title');
                                $author = $item->getLocalized('author');
                                $desc = $item->getLocalized('short_description') ?: $item->getLocalized('full_description');
                                $rawFiles = $item->pdfs ?: $item->pdf_files;
                                $files = is_array($rawFiles) ? $rawFiles : json_decode($rawFiles, true);
                                $primaryFile = is_array($files) && count($files) > 0 ? $files[0] : null;
                                $fileUrl = is_array($primaryFile) ? ($primaryFile['url'] ?? '#') : (is_string($primaryFile) ? $primaryFile : '#');
                                $fileSize = is_array($primaryFile) ? ($primaryFile['size'] ?? 'PDF') : 'PDF';
                            @endphp
                            <div class="col-lg-4 col-md-6">
                                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                                    <div class="p-3 bg-danger bg-opacity-10 border-bottom d-flex align-items-center justify-content-between">
                                        <span class="badge bg-white text-danger fw-bold rounded-pill px-2.5 py-1">
                                            <i class="fa-solid fa-file-pdf me-1"></i> ई-ग्रन्थ PDF
                                        </span>
                                        <span class="badge bg-warning text-dark fw-bold rounded-pill">{{ $fileSize }}</span>
                                    </div>
                                    <div class="p-3.5 d-flex flex-column justify-content-between flex-grow-1">
                                        <div>
                                            <h5 class="font-spiritual mb-1 line-clamp-1" style="font-size: 16.5px;">{{ $title }}</h5>
                                            <p class="text-muted small mb-2"><i class="fa-solid fa-pen-nib text-warning me-1"></i> {{ $author ?: 'आचार्य परम्परा' }}</p>
                                            <p class="text-muted small line-clamp-2 mb-3" style="font-size: 13px;">{{ Str::limit(strip_tags($desc), 90) }}</p>
                                        </div>
                                        <div class="d-flex gap-2 pt-2 border-top">
                                            <a href="{{ route('gallery.pdfs') }}" class="btn btn-sm btn-outline-danger rounded-pill flex-fill">
                                                <i class="fa-solid fa-book-open me-1"></i> पढ़ें
                                            </a>
                                            <a href="{{ $fileUrl }}" download class="btn btn-sm btn-spiritual rounded-pill flex-fill text-center">
                                                <i class="fa-solid fa-download me-1"></i> डाउनलोड
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('gallery.pdfs') }}" class="btn btn-spiritual rounded-pill px-4 fw-semibold">
                            सम्पूर्ण PDF ई-लाइब्रेरी देखें <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- TAB 6: NEWS ONLY -->
                <div class="tab-pane fade" id="tab-news" role="tabpanel">
                    <div class="row g-4">
                        @foreach($news as $n)
                            <div class="col-lg-4 col-md-6">
                                <x-news-card :news="$n" />
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('news.index') }}" class="btn btn-outline-danger rounded-pill px-4 fw-semibold">
                            सभी धर्म समाचार एवं पत्रिकाएं <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- 6. Tirth Kshetra & Temple Directory (तीर्थ क्षेत्र एवं संपर्क निर्देशिका) -->
    <section id="tirth-section" class="py-5" style="background: radial-gradient(circle at top, #fffdfa 0%, #faf3e8 100%);">
        <div class="container">
            
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold mb-1" style="font-size: 12px;">
                        <i class="fa-solid fa-gopuram me-1"></i> पावन सिद्धक्षेत्र व अतिशय क्षेत्र
                    </span>
                    <h2 class="font-spiritual mb-1" style="font-size: 26px; color: #7c2d12;">
                        तीर्थ क्षेत्र एवं जिनालय संपर्क निर्देशिका
                    </h2>
                    <p class="text-muted small mb-0">पावन जैन तीर्थों के प्रबंधक कार्यालय, धर्मशाला संपर्क नंबर एवं गूगल मैप्स दिशा-निर्देश।</p>
                </div>
                <div>
                    <a href="{{ route('contact') }}" class="btn btn-sm btn-spiritual rounded-pill px-3.5 py-2 fw-semibold">
                        <i class="fa-solid fa-phone me-1"></i> नया तीर्थ जोड़ें / संपर्क
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @foreach($tirthList as $tirth)
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white tirth-directory-card" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            
                            <!-- Tirth Header Frame -->
                            <div class="position-relative" style="height: 160px; overflow: hidden;">
                                <img src="{{ $tirth['image'] }}" alt="{{ $tirth['name'] }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.65) 100%);"></div>
                                
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 rounded-pill fw-bold shadow-sm" style="font-size: 11px;">
                                    <i class="fa-solid fa-location-dot me-1"></i> {{ $tirth['state'] }}
                                </span>

                                <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white">
                                    <h5 class="font-spiritual text-white mb-0 line-clamp-1" style="font-size: 17px;">{{ $tirth['name'] }}</h5>
                                    <small class="text-warning text-opacity-90" style="font-size: 12px;">{{ $tirth['tirthankara'] }}</small>
                                </div>
                            </div>

                            <!-- Tirth Body -->
                            <div class="p-3.5 d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <p class="text-muted small mb-3 line-clamp-2" style="font-size: 13px; line-height: 1.55;">
                                        {{ $tirth['description'] }}
                                    </p>
                                    
                                    <div class="p-2.5 rounded-3 bg-light border mb-3">
                                        <div class="d-flex align-items-center gap-2 mb-1 text-muted small" style="font-size: 12px;">
                                            <i class="fa-solid fa-building-user text-warning"></i>
                                            <span class="text-truncate">{{ $tirth['manager'] }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 fw-semibold text-dark" style="font-size: 13.5px;">
                                            <i class="fa-solid fa-phone text-success"></i>
                                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $tirth['phone']) }}" class="text-dark text-decoration-none hover-primary">
                                                {{ $tirth['phone'] }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 pt-2 border-top">
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $tirth['phone']) }}" class="btn btn-sm btn-outline-success rounded-pill flex-fill fw-semibold">
                                        <i class="fa-solid fa-phone me-1"></i> कॉल करें
                                    </a>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($tirth['map_query']) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill flex-fill fw-semibold">
                                        <i class="fa-solid fa-map-location-dot me-1"></i> नक्शा / मार्ग
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 7. Pujya Sant Sangh & Live Vihar Spotlight (पूज्य संत व विहार) -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fw-semibold mb-1" style="font-size: 12px;">
                        <i class="fa-solid fa-hands-praying me-1"></i> गुरु परम्परा व श्रमण संघ
                    </span>
                    <h2 class="font-spiritual mb-1" style="font-size: 26px; color: var(--primary);">
                        पूज्य संत संघ एवं पावन विहार
                    </h2>
                    <p class="text-muted small mb-0">आचार्यश्री एवं मुनि संघ का पावन परिचय, दीक्षा तिथि एवं वर्तमान प्रवास।</p>
                </div>
                <div>
                    <a href="{{ route('sangh-vihar') }}" class="btn btn-sm btn-spiritual rounded-pill px-3.5 py-2 fw-semibold">
                        <i class="fa-solid fa-location-dot me-1"></i> सम्पूर्ण विहार स्थिति देखें
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @foreach($saints as $saint)
                    @php
                        $sName = is_array($saint->name) ? ($saint->name['hi'] ?? $saint->name['en'] ?? '') : $saint->name;
                        $sTitle = is_array($saint->title) ? ($saint->title['hi'] ?? $saint->title['en'] ?? '') : $saint->title;
                        $sPhoto = $saint->photo ?: asset('images/jain/muni_vidyasagar.jpg');
                        $currentVihar = $saint->vihars->first();
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white saint-card-portal" style="border: 1px solid #fed7aa !important;">
                            <div class="d-flex p-3 gap-3 align-items-center">
                                <div class="saint-avatar-frame flex-shrink-0" style="width: 76px; height: 76px; border-radius: 50%; overflow: hidden; border: 3px solid #f97316; box-shadow: 0 4px 10px rgba(249, 115, 22, 0.2);">
                                    <img src="{{ $sPhoto }}" alt="{{ $sName }}" class="w-100 h-100 object-fit-cover">
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5 small fw-semibold" style="font-size: 11px;">
                                        {{ $sTitle ?: 'पूज्य संत' }}
                                    </span>
                                    <h5 class="font-spiritual mb-1 line-clamp-1 mt-1" style="font-size: 17px;">
                                        <a href="{{ route('sants.show', $saint->slug ?: $saint->id) }}" class="text-dark text-decoration-none hover-primary">
                                            {{ $sName }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-0 line-clamp-1">
                                        गुरु: {{ is_array($saint->guru_name) ? ($saint->guru_name['hi'] ?? $saint->guru_name['en'] ?? 'आचार्य परम्परा') : ($saint->guru_name ?: 'आचार्य परम्परा') }}
                                    </p>
                                </div>
                            </div>

                            <div class="px-3 pb-3 pt-2 bg-light border-top">
                                <div class="d-flex align-items-center justify-content-between text-muted small mb-2" style="font-size: 12.5px;">
                                    <span>
                                        <i class="fa-solid fa-location-dot text-danger me-1"></i>
                                        <strong>{{ $currentVihar ? $currentVihar->current_location : ($siteSettings['vihar_current_stay'] ?? 'भेलूपुर, वाराणसी') }}</strong>
                                    </span>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">सक्रिय प्रवास</span>
                                </div>
                                <a href="{{ route('sants.show', $saint->slug ?: $saint->id) }}" class="btn btn-sm btn-outline-danger w-100 rounded-pill fw-semibold" style="font-size: 12.5px;">
                                    संत परिचय व प्रवचन देखें <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 8. Video & Media Hub (वीडियो एवं दर्शन) -->
    <section class="py-5" style="background: #1c1917; color: #ffffff;">
        <div class="container">
            
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="badge bg-danger text-white px-2.5 py-1 rounded-pill fw-semibold mb-1" style="font-size: 12px;">
                        <i class="fa-solid fa-play me-1"></i> वीडियो एवं लाइव प्रवचन
                    </span>
                    <h2 class="font-spiritual text-white mb-0" style="font-size: 26px;">
                        अमृतवाणी एवं धार्मिक वीडियो संग्रह
                    </h2>
                </div>
                <div>
                    <a href="{{ route('gallery.videos') }}" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3.5 py-2">
                        <i class="fa-solid fa-video me-1"></i> सभी वीडियो देखें
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @foreach($videos->take(3) as $vid)
                    @php
                        $vUrl = is_array($vid->videos) ? $vid->videos[0] : $vid->videos;
                        $vTitle = $vid->getLocalized('title');
                        $vAuthor = $vid->getLocalized('author');
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <x-video-card 
                            :videoUrl="$vUrl"
                            :title="$vTitle"
                            :author="$vAuthor ?: 'पूज्य संत'"
                        />
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 9. Quick Switcher Banner (To Classic Version) -->
    <div class="py-2.5 bg-light border-top text-center">
        <div class="container d-flex align-items-center justify-content-center flex-wrap gap-2 text-muted small">
            <span>क्या आप पुराना विस्तृत दृश्य देखना चाहते हैं?</span>
            <a href="{{ route('home.classic') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-semibold" style="font-size: 12px;">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> क्लासिक होमपेज देखें (Classic View)
            </a>
        </div>
    </div>

    <!-- Scripts for portal interactions -->
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Copy quote button
            document.querySelectorAll('.btn-copy-quote').forEach(btn => {
                btn.addEventListener('click', function() {
                    const text = this.getAttribute('data-quote');
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(text).then(() => {
                            const original = this.innerHTML;
                            this.innerHTML = '<i class="fa-solid fa-check text-success me-1"></i> कॉपी हुआ';
                            setTimeout(() => {
                                this.innerHTML = original;
                            }, 2000);
                        });
                    }
                });
            });
        });
    </script>
    @endpush

    <!-- Portal Custom CSS -->
    <style>
        .portal-hero-section {
            box-shadow: inset 0 -10px 30px rgba(0,0,0,0.25);
        }
        .category-discovery-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
        }
        @media (max-width: 991px) {
            .category-discovery-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (max-width: 575px) {
            .category-discovery-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .category-quick-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            background: #ffffff;
            border: 1px solid #e7e5e4;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .category-quick-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(133, 56, 12, 0.12);
            border-color: #f97316;
        }
        .cat-icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            flex-shrink: 0;
        }
        .cat-card-info {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .cat-title {
            font-family: var(--font-serif);
            font-size: 14px;
            font-weight: 700;
            color: #1c1917;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cat-count {
            font-size: 11.5px;
            color: #78716c;
        }
        .category-filter-nav .nav-link {
            color: #57534e;
            background: #f5f5f4;
            font-size: 13.5px;
            border: 1px solid #e7e5e4;
            transition: all 0.2s ease;
        }
        .category-filter-nav .nav-link.active {
            background: var(--primary) !important;
            color: #ffffff !important;
            border-color: var(--primary) !important;
            box-shadow: 0 4px 12px rgba(194, 65, 12, 0.25);
        }
        .tirth-directory-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(133, 56, 12, 0.14) !important;
        }
        .saint-card-portal:hover {
            box-shadow: 0 14px 28px rgba(249, 115, 22, 0.15) !important;
            transform: translateY(-4px);
        }
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .hover-primary:hover {
            color: var(--primary) !important;
        }
    </style>

@endsection