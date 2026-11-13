@extends('layouts.app')

@section('title', 'सर्वोदय जिनधर्म | आधिकारिक जैन धर्म एवं अध्यात्म पोर्टल')

@section('content')

    <!-- 1. Hero Section -->
    @if(in_array('hero', $activeSections))
        <x-hero 
            :title="$siteSettings['website_name'] ?? 'सर्वोदय जिनधर्म'"
            titleHighlight="पावन पोर्टल"
            :description="$siteSettings['site_tagline'] ?? 'भगवान महावीर के अहिंसा, अपरिग्रह और अनेकांत के दर्शन को समर्पित प्रामाणिक जैन डिजिटल मंच।'"
            :image="isset($saints[0]) ? ($saints[0]->photo ?: asset('images/jain/muni_vidyasagar.jpg')) : asset('images/jain/muni_vidyasagar.jpg')"
            :saintHighlight="isset($saints[0]) ? (is_array($saints[0]->name) ? ($saints[0]->name['hi'] ?? $saints[0]->name['en']) : $saints[0]->name) : 'पूज्य साधु संघ'"
        />
    @endif

    <!-- 1.5. Top Marquee Announcement Bar -->
    @php
        $marqueeText = $siteSettings['marquee_text'] ?? 'एक महत्वपूर्ण संदेश - सभी जैनतीर्थ पर पूर्ण अनुशासन व मर्यादा प्रार्थनीय हैं। सजग रहें, अगर किसी तीर्थ पर अनुशासन न दिखे, तो तुरन्त हमें अवगत करें';
    @endphp
    @if(!empty($marqueeText))
        <div class="vihar-marquee-bar py-2" style="background: linear-gradient(90deg, #7c2d12 0%, #9a3412 50%, #7c2d12 100%); color: #fff; border-bottom: 2px solid var(--gold); position: relative; overflow: hidden; z-index: 10;">
            <div class="container-fluid px-3 d-flex align-items-center">
                <div class="d-flex align-items-center gap-2 flex-shrink-0 pe-3 border-end border-warning border-opacity-50" style="z-index: 2; background: inherit;">
                    <span class="badge" style="background: #fef08a; color: #7c2d12; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                        <i class="fa-solid fa-bullhorn fa-bounce me-1 text-danger"></i> तीर्थ सूचना
                    </span>
                </div>
                <div class="vihar-marquee-wrap flex-grow-1 overflow-hidden position-relative" style="white-space: nowrap; height: 26px;">
                    <div class="vihar-marquee-content d-inline-block ps-4 fw-medium" style="font-size: 14px; letter-spacing: 0.3px;">
                        <span class="text-warning me-2"><i class="fa-solid fa-bell"></i></span>
                        {{ $marqueeText }}
                        <span class="mx-4 text-warning opacity-75">•</span>
                        <span class="text-warning me-2"><i class="fa-solid fa-hands-praying"></i></span>
                        {{ $marqueeText }}
                    </div>
                </div>
                <div class="d-none d-md-flex align-items-center ps-3 flex-shrink-0 border-start border-warning border-opacity-50" style="z-index: 2; background: inherit;">
                    <a href="{{ route('contact') }}" class="btn btn-sm text-white" style="background: rgba(254, 240, 138, 0.2); border: 1px solid rgba(254, 240, 138, 0.4); font-size: 11.5px; padding: 2px 10px; border-radius: 14px;">
                        <i class="fa-solid fa-phone me-1 text-warning"></i> संपर्क करें
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- 1.6. Sangh Information & Real-Time Vihar Status Section -->
    @php
        $viharTitle = $siteSettings['vihar_section_title'] ?? '२८ वां अनेक कल्याणक भूमि वर्षा योग २०२६';
        $viharTirth = $siteSettings['vihar_tirth_location'] ?? 'श्री पार्श्वनाथ दिगंबर जैन तीर्थ क्षेत्र भेलूपुर वाराणसी (उत्तर प्रदेश)';
        $viharSubtitle = $siteSettings['vihar_tirth_subtitle'] ?? 'गर्भ, जन्म, तप कल्याणक भूमि';
        $viharPoster = $siteSettings['vihar_poster_image'] ?? asset('images/jain/varsha_yog_poster_cropped.jpg');
        $viharAshish = $siteSettings['vihar_ashish_chhav'] ?? 'महासमाधि धारक परम पूज्य आचार्यश्री 108 विद्यासागर जी महामुनिराज';
        $viharAashirwad = $siteSettings['vihar_aashirwad_pradata'] ?? 'परम पूज्य आचार्यश्री 108 समयसागर जी महामुनिराज';
        $viharSanidhya = $siteSettings['vihar_punyavardhan_sanidhya'] ?? 'परम पूज्य मुनिश्री 108 धर्मसागर जी महाराज, परम पूज्य मुनिश्री 108 भावसागर जी महाराज';
        $viharDisha = $siteSettings['vihar_disha'] ?? 'वाराणसी से अयोध्या जी की ओर';
        $viharRatri = $siteSettings['vihar_ratri_vishram'] ?? 'दिगम्बर जैन धर्मशाला, जौनपुर';
        $viharAahar = $siteSettings['vihar_aahar_charya'] ?? 'प्रातः ०९:३० बजे, श्री आदिनाथ जिनालय परिसर';
        $viharStay = $siteSettings['vihar_current_stay'] ?? 'भेलूपुर जैन मन्दिर, वाराणसी';
        $isViharActive = ($siteSettings['vihar_status_active'] ?? '1') == '1';
        $viharWebsite = $siteSettings['vihar_website_url'] ?? 'www.jindharma.com';
        $viharYoutube = $siteSettings['vihar_youtube_text'] ?? 'जैन धर्म वाणी, एवं जैन वर्ल्ड विद्या';
        $viharMap = $siteSettings['vihar_map_link'] ?? 'https://maps.google.com/?q=' . urlencode($viharTirth);
        $viharQr = $siteSettings['vihar_qr_link'] ?? 'https://linktr.ee/Jindharmmatter';

        $rawContacts = $siteSettings['vihar_contacts'] ?? '[]';
        $contactsList = is_array($rawContacts) ? $rawContacts : json_decode($rawContacts, true);
        if (!is_array($contactsList) || empty($contactsList)) {
            $contactsList = [
                ['name' => 'रामू भैया नौहटा', 'phone' => '7879068125', 'role' => 'व्यवस्थापक'],
                ['name' => 'राघव जैन भोपाल', 'phone' => '6260151350', 'role' => 'व्यवस्थापक'],
                ['name' => 'आर. सी. जैन', 'phone' => '9415201372', 'role' => 'संपर्क'],
                ['name' => 'विनोद कुमार जैन', 'phone' => '7376895750', 'role' => 'संपर्क'],
                ['name' => 'सौरभ कुमार जैन', 'phone' => '9415222071', 'role' => 'संपर्क'],
                ['name' => 'राहुल कुमार जैन', 'phone' => '9984226644', 'role' => 'संपर्क'],
                ['name' => 'संजय जैन', 'phone' => '6388234656', 'role' => 'संपर्क'],
            ];
        }

        $viharShareMsg = urlencode("🪷 " . $viharTitle . " 🪷\n📍 " . $viharTirth . "\n\n🚶‍♂️ वर्तमान विहार स्थिति:\n• दिशा: " . $viharDisha . "\n• रात्रि विश्राम: " . $viharRatri . "\n• आहार चर्या: " . $viharAahar . "\n• वर्तमान प्रवास: " . $viharStay . "\n\nपूज्य संघ दर्शन व सम्पूर्ण जानकारी हेतु देखें:\n" . url('/'));
    @endphp

    <section class="py-5 vihar-sangh-section" style="background: radial-gradient(circle at top left, #fffdfa 0%, #fbf4ea 60%, #f5ece0 100%); border-bottom: 2px solid var(--gold-border);">
        <div class="container">
            
            <!-- Section Header Banner -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-2 rounded-pill" style="background: rgba(194, 132, 31, 0.12); border: 1.5px solid var(--gold-border);">
                    <span class="pulse-live-dot"></span>
                    <span class="fw-bold" style="font-size: 13px; color: var(--primary);">लाइव संघ प्रवास एवं वर्षा योग सूचना</span>
                </div>
                <h2 class="font-spiritual mb-1" style="font-size: clamp(22px, 3.5vw, 32px); color: #7c2d12;">
                    {{ $viharTitle }}
                </h2>
                <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 text-muted" style="font-size: 14.5px;">
                    <span class="fw-semibold" style="color: var(--terracotta);">
                        <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $viharTirth }}
                    </span>
                    @if(!empty($viharSubtitle))
                        <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 11.5px; border-radius: 12px;">
                            <i class="fa-solid fa-om me-1"></i> {{ $viharSubtitle }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                
                <!-- LEFT COLUMN: High-Resolution Poster Card with Interactive Controls -->
                <div class="col-lg-5 col-xl-5">
                    <div class="vihar-poster-card h-100 d-flex flex-column justify-content-between p-3 shadow-sm" style="background: #ffffff; border-radius: 20px; border: 1.5px solid var(--gold-border);">
                        
                        <!-- Poster Image Frame -->
                        <div class="position-relative rounded-4 overflow-hidden mb-3" style="border: 2px solid #ecd8bd; background: #faf6f0; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#viharPosterModal" title="क्लिक करके बड़ा पोस्टर देखें">
                            <img src="{{ $viharPoster }}" alt="{{ $viharTitle }}" style="width: 100%; height: auto; max-height: 480px; object-fit: contain; display: block; transition: transform 0.4s ease;" class="vihar-poster-img" loading="lazy">
                            <div class="position-absolute bottom-0 start-0 end-0 p-2 text-center" style="background: linear-gradient(transparent, rgba(0,0,0,0.75));">
                                <span class="badge bg-warning text-dark" style="font-size: 11.5px; font-weight: 600;">
                                    <i class="fa-solid fa-magnifying-glass-plus me-1"></i> क्लिक कर पूर्ण पोस्टर देखें
                                </span>
                            </div>
                        </div>

                        <!-- Poster Quick Action Buttons -->
                        <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                            <button type="button" class="btn btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#viharPosterModal" style="background: #fdf2e9; color: var(--primary); border: 1px solid var(--gold-border); font-size: 12.5px; font-weight: 600;">
                                <i class="fa-solid fa-expand me-1"></i> बड़ा देखें
                            </button>

                            @if(!empty($viharPoster))
                                <a href="{{ $viharPoster }}" download="varsha_yog_poster.jpg" class="btn btn-sm flex-fill" style="background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 12.5px; font-weight: 600;">
                                    <i class="fa-solid fa-download me-1"></i> डाउनलोड
                                </a>
                            @endif

                            <a href="https://api.whatsapp.com/send?text={{ $viharShareMsg }}" target="_blank" class="btn btn-sm flex-fill" style="background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; font-size: 12.5px; font-weight: 600;" title="WhatsApp पर शेयर करें">
                                <i class="fa-brands fa-whatsapp me-1"></i> शेयर
                            </a>

                            @if(!empty($viharMap))
                                <a href="{{ $viharMap }}" target="_blank" class="btn btn-sm flex-fill" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 12.5px; font-weight: 600;" title="गूगल मैप्स लोकेशन">
                                    <i class="fa-solid fa-map-location-dot me-1"></i> लोकेशन
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Detailed Sangh Information, Real-time Vihar Status & Contacts -->
                <div class="col-lg-7 col-xl-7">
                    <div class="h-100 d-flex flex-column gap-3">
                        
                        <!-- 1. Sangh Leadership & Blessings Card -->
                        <div class="p-3 shadow-sm rounded-4" style="background: #ffffff; border: 1.5px solid var(--gold-border);">
                            <div class="d-flex align-items-center gap-2 mb-2 pb-2 border-bottom">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; background: rgba(194, 132, 31, 0.15); color: var(--primary); font-size: 14px;">
                                    <i class="fa-solid fa-hands-praying"></i>
                                </span>
                                <h5 class="font-spiritual mb-0" style="font-size: 16px; color: #7c2d12;">पूज्य संघ सानिध्य एवं पावन आशीष</h5>
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <!-- Ashish Chhav (Acharya Vidyasagar Ji) -->
                                <div class="p-2 rounded-3 d-flex align-items-start gap-2" style="background: #fff8eb; border-left: 4px solid var(--gold);">
                                    <i class="fa-solid fa-feather-pointed text-warning mt-1 fs-6"></i>
                                    <div>
                                        <div class="text-muted fw-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">आशीष छांव:</div>
                                        <div class="fw-bold" style="font-size: 14px; color: #7c2d12;">
                                            {{ $viharAshish }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Aashirwad Pradata (Acharya Samaysagar Ji) -->
                                <div class="p-2 rounded-3 d-flex align-items-start gap-2" style="background: #fdfbf7; border-left: 4px solid #ea580c;">
                                    <i class="fa-solid fa-om text-danger mt-1 fs-6"></i>
                                    <div>
                                        <div class="text-muted fw-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">आशीर्वाद प्रदाता:</div>
                                        <div class="fw-bold" style="font-size: 14px; color: #9a3412;">
                                            {{ $viharAashirwad }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Punyavardhan Sanidhya (Munishri Gan) -->
                                <div class="p-2 rounded-3 d-flex align-items-start gap-2" style="background: #fdfbf7; border-left: 4px solid #d97706;">
                                    <i class="fa-solid fa-people-group text-warning mt-1 fs-6"></i>
                                    <div>
                                        <div class="text-muted fw-bold" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">पुण्यवर्धन सानिध्य:</div>
                                        <div class="fw-semibold" style="font-size: 13.5px; color: #1c1917;">
                                            {{ $viharSanidhya }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Real-time Live Vihar Status & Daily Routine -->
                        <div class="p-3 shadow-sm rounded-4" style="background: linear-gradient(135deg, #fff9f0 0%, #fff 100%); border: 2px solid #f59e0b;">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom border-warning border-opacity-50">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="pulse-live-dot"></span>
                                    <h5 class="font-spiritual mb-0 fw-bold" style="font-size: 16px; color: #9a3412;">
                                        लाइव विहार स्थिति एवं दैनिक चर्या
                                    </h5>
                                </div>
                                <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; font-size: 11px; border-radius: 12px; padding: 4px 8px;">
                                    <i class="fa-solid fa-clock-rotate-left me-1"></i> अद्यतन स्थिति
                                </span>
                            </div>

                            <!-- 4-Metrics Status Grid -->
                            <div class="row g-2">
                                <!-- Vihar Disha -->
                                <div class="col-sm-6">
                                    <div class="p-2 rounded-3 h-100" style="background: #ffffff; border: 1.5px solid #fed7aa; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-danger bg-opacity-10 text-danger p-1 rounded-circle">
                                                <i class="fa-solid fa-person-walking-arrow-right"></i>
                                            </span>
                                            <span class="text-muted fw-bold" style="font-size: 11px;">विहार दिशा:</span>
                                        </div>
                                        <div class="fw-bold ps-4 text-dark" style="font-size: 13.5px;">
                                            {{ $viharDisha }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Ratri Vishram -->
                                <div class="col-sm-6">
                                    <div class="p-2 rounded-3 h-100" style="background: #ffffff; border: 1.5px solid #fed7aa; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-primary bg-opacity-10 text-primary p-1 rounded-circle">
                                                <i class="fa-solid fa-moon"></i>
                                            </span>
                                            <span class="text-muted fw-bold" style="font-size: 11px;">रात्रि विश्राम स्थल:</span>
                                        </div>
                                        <div class="fw-bold ps-4 text-dark" style="font-size: 13.5px;">
                                            {{ $viharRatri }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Aahar Charya -->
                                <div class="col-sm-6">
                                    <div class="p-2 rounded-3 h-100" style="background: #ffffff; border: 1.5px solid #fed7aa; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-success bg-opacity-10 text-success p-1 rounded-circle">
                                                <i class="fa-solid fa-bowl-food"></i>
                                            </span>
                                            <span class="text-muted fw-bold" style="font-size: 11px;">आहार चर्या समय व स्थल:</span>
                                        </div>
                                        <div class="fw-bold ps-4 text-dark" style="font-size: 13.5px;">
                                            {{ $viharAahar }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Current Stay -->
                                <div class="col-sm-6">
                                    <div class="p-2 rounded-3 h-100" style="background: #ffffff; border: 1.5px solid #fed7aa; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-warning bg-opacity-25 text-dark p-1 rounded-circle">
                                                <i class="fa-solid fa-location-crosshairs text-danger"></i>
                                            </span>
                                            <span class="text-muted fw-bold" style="font-size: 11px;">वर्तमान पड़ाव / प्रवास:</span>
                                        </div>
                                        <div class="fw-bold ps-4 text-danger" style="font-size: 13.5px;">
                                            {{ $viharStay }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Multi-Sampark Sutra Directory (Click to Call) -->
                        <div class="p-3 shadow-sm rounded-4" style="background: #ffffff; border: 1.5px solid var(--gold-border);">
                            <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 28px; height: 28px; background: rgba(34, 197, 94, 0.15); color: #15803d; font-size: 13px;">
                                        <i class="fa-solid fa-phone-volume"></i>
                                    </span>
                                    <h6 class="font-spiritual mb-0 fw-bold" style="font-size: 14.5px; color: #1c1917;">
                                        बहु-संपर्क सूत्र (व्यवस्थापक एवं समिति पदाधिकारी)
                                    </h6>
                                </div>
                                <small class="text-muted" style="font-size: 11px;">(क्लिक कर तुरंत कॉल करें)</small>
                            </div>

                            <div class="d-flex flex-wrap gap-2 pt-1">
                                @foreach($contactsList as $contact)
                                    @php
                                        $cName = is_array($contact) ? ($contact['name'] ?? '') : '';
                                        $cPhone = is_array($contact) ? ($contact['phone'] ?? '') : '';
                                        $cRole = is_array($contact) ? ($contact['role'] ?? '') : '';
                                    @endphp
                                    @if(!empty($cPhone))
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $cPhone) }}" class="btn btn-sm d-inline-flex align-items-center gap-2 py-1 px-2" style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 12px; color: #334155; transition: var(--transition);" title="{{ $cName }} को कॉल करें" onmouseover="this.style.borderColor='var(--primary)'; this.style.background='#fff8eb';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='#f8fafc';">
                                            <i class="fa-solid fa-phone text-success" style="font-size: 11px;"></i>
                                            <span class="fw-semibold">{{ $cName }}</span>
                                            <span class="badge bg-secondary bg-opacity-25 text-dark fw-normal" style="font-size: 11px;">{{ $cPhone }}</span>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- 4. Digital Channels & Social Footer -->
                        <div class="p-2 px-3 rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #fdfbf7; border: 1px dashed var(--gold-border); font-size: 12px;">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span><i class="fa-solid fa-globe text-primary me-1"></i> <a href="https://{{ $viharWebsite }}" target="_blank" class="fw-semibold text-dark">{{ $viharWebsite }}</a></span>
                                <span><i class="fa-brands fa-youtube text-danger me-1"></i> <span class="text-muted">{{ $viharYoutube }}</span></span>
                            </div>
                            @if(!empty($viharQr))
                                <a href="{{ $viharQr }}" target="_blank" class="badge" style="background: var(--primary); color: #fff; text-decoration: none; padding: 5px 10px; border-radius: 8px;">
                                    <i class="fa-solid fa-qrcode me-1"></i> सम्पूर्ण डिजिटल सामग्री
                                </a>
                            @endif
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 2. Combined Section: Today's Spiritual Quotation (Left) & Today's Jain Panchang (Right) -->
    @if(in_array('message', $activeSections) || in_array('panchang', $activeSections))
        @php
            $quoteTitle = $siteSettings['daily_quote_title'] ?? 'आज का आध्यात्मिक संदेश';
            $quoteText = $siteSettings['spiritual_message_quote'] ?? 'क्रोध को शांति से, मान को नम्रता से, माया को सरलता से और लोभ को संतोष से जीतें।';
            $quoteAuthor = $siteSettings['spiritual_message_author'] ?? 'भगवान महावीर स्वामी (24वें तीर्थंकर)';
            $quoteImage = $siteSettings['daily_quote_image'] ?? '/images/jain/muni_vidyasagar.jpg';
            $showShare = ($siteSettings['daily_quote_show_share'] ?? '1') == '1';
            $showDownload = ($siteSettings['daily_quote_show_download'] ?? '1') == '1';
            $showCopy = ($siteSettings['daily_quote_show_copy'] ?? '1') == '1';

            $whatsappShareText = urlencode("✨ " . $quoteTitle . " ✨\n\n\"" . $quoteText . "\"\n— " . $quoteAuthor . "\n\nधर्म लाभ हेतु देखें: " . url('/'));
        @endphp

        <section class="py-5" style="background: radial-gradient(circle at top center, #fdfbf8 0%, #f7f1e6 100%); border-bottom: 1.5px solid var(--gold-border);">
            <div class="container">
                <div class="row g-4 align-items-stretch">
                    
                    <!-- LEFT COLUMN: Daily Spiritual Quotation with Sacred Image -->
                    <div class="col-lg-7 col-xl-7">
                        <div class="daily-suvichar-card h-100 d-flex flex-column justify-content-between p-0 shadow-sm" style="border-radius: 18px; border: 1.5px solid var(--gold-border); background: #ffffff; overflow: hidden;">
                            <div class="row g-0 h-100 align-items-stretch">
                                <!-- Sacred Image (Left) -->
                                <div class="col-md-5 col-sm-12">
                                    <div class="suvichar-img-wrap h-100 position-relative overflow-hidden" data-bs-toggle="modal" data-bs-target="#suvicharImageModal" title="चित्र को बड़े आकार में देखें" style="min-height: 250px; cursor: pointer;">
                                        <img src="{{ $quoteImage }}" alt="{{ $quoteAuthor }} - {{ $quoteTitle }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                        <div class="suvichar-img-overlay">
                                            <span class="badge bg-dark bg-opacity-75 text-light small">
                                                <i class="fa-solid fa-magnifying-glass-plus me-1"></i> बड़ा चित्र देखें
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quote Body & Actions (Right) -->
                                <div class="col-md-7 col-sm-12">
                                    <div class="suvichar-body p-4 h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                                <div class="suvichar-badge m-0">
                                                    <i class="fa-solid fa-sparkles text-warning"></i> {{ $quoteTitle }}
                                                </div>
                                                <small class="text-muted" style="font-size: 11px;">
                                                    <i class="fa-regular fa-calendar-check me-1"></i> आज का संदेश
                                                </small>
                                            </div>

                                            <div class="suvichar-quote-text my-3" style="font-size: 18px; line-height: 1.6;">
                                                <i class="fa-solid fa-quote-left me-1 text-warning opacity-50 fs-5"></i>
                                                "{{ $quoteText }}"
                                            </div>

                                            <div class="suvichar-author mb-3 fw-semibold" style="font-size: 13.5px; color: var(--terracotta);">
                                                — {{ $quoteAuthor }}
                                            </div>
                                        </div>

                                        <div class="suvichar-actions d-flex flex-wrap gap-2 pt-3 border-top mt-auto">
                                            @if($showShare)
                                                <a href="https://api.whatsapp.com/send?text={{ $whatsappShareText }}" target="_blank" class="suvichar-btn-whatsapp py-1 px-3" style="font-size:12px;" title="WhatsApp पर शेयर करें">
                                                    <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                                                </a>
                                            @endif

                                            @if($showDownload && !empty($quoteImage))
                                                <a href="{{ $quoteImage }}" download="jain_suvichar.jpg" class="suvichar-btn-action py-1 px-2" style="font-size:12px;" title="चित्र डाउनलोड">
                                                    <i class="fa-solid fa-download text-primary me-1"></i> डाउनलोड
                                                </a>
                                            @endif

                                            @if($showCopy)
                                                <button type="button" class="suvichar-btn-action py-1 px-2" style="font-size:12px;" onclick="copySuvicharText('{{ addslashes($quoteText) }}', '{{ addslashes($quoteAuthor) }}', this)" title="कॉपी करें">
                                                    <i class="fa-regular fa-copy text-secondary me-1"></i> <span>कॉपी</span>
                                                </button>
                                            @endif

                                            <a href="{{ route('suvichar.index') }}" class="suvichar-btn-action py-1 px-2 ms-auto" style="font-size:12px;" title="सभी सुविचार संग्रह">
                                                <i class="fa-solid fa-layer-group text-warning me-1"></i> संग्रह <i class="fa-solid fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Today's Jain Panchang -->
                    <div class="col-lg-5 col-xl-5">
                        <div class="panchang-widget-card h-100 d-flex flex-column justify-content-between p-4 shadow-sm" style="border-radius: 18px; border: 1.5px solid var(--gold-border); background: #ffffff;">
                            <div>
                                <!-- Header -->
                                <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 38px; height: 38px; background: rgba(194, 132, 31, 0.15); color: var(--terracotta); font-size: 16px;">
                                            <i class="fa-solid fa-calendar-days"></i>
                                        </div>
                                        <div>
                                            <h5 class="font-spiritual mb-0" style="font-size: 17px;">आज का जैन पंचांग</h5>
                                            <small class="text-muted" style="font-size: 11px;">दैनिक काल गणना एवं शुभ मुहूर्त</small>
                                        </div>
                                    </div>
                                    @if($todayPanchang)
                                        <span class="badge" style="background: rgba(194, 132, 31, 0.12); color: var(--terracotta); border: 1px solid var(--gold-border); font-size: 11.5px; padding: 5px 10px; border-radius: 20px;">
                                            {{ \Carbon\Carbon::parse($todayPanchang->date)->format('d M, Y') }}
                                        </span>
                                    @endif
                                </div>

                                @if($todayPanchang)
                                    <!-- 4-Item Grid -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="panchang-grid-item p-2">
                                                <span class="label" style="font-size: 10.5px;">माह एवं पक्ष</span>
                                                <span class="value" style="font-size: 13.5px;">{{ $todayPanchang->maas }} • {{ $todayPanchang->paksha }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="panchang-grid-item p-2" style="background: rgba(234, 88, 12, 0.08); border-color: rgba(234, 88, 12, 0.25);">
                                                <span class="label" style="font-size: 10.5px; color: #c2410c;">तिथि (Tithi)</span>
                                                <span class="value text-danger" style="font-size: 13.5px;">{{ $todayPanchang->tithi }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="panchang-grid-item p-2">
                                                <span class="label" style="font-size: 10.5px;">नक्षत्र (Nakshatra)</span>
                                                <span class="value" style="font-size: 13.5px;">{{ $todayPanchang->nakshatra ?? 'स्वाति' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="panchang-grid-item p-2">
                                                <span class="label" style="font-size: 10.5px;">सूर्योदय / सूर्यास्त</span>
                                                <span class="value" style="font-size: 12.5px; font-weight: 600;">{{ $todayPanchang->sunrise ?? '०५:५४ AM' }} | {{ $todayPanchang->sunset ?? '०६:४२ PM' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notes / Special Parva -->
                                    @if($todayPanchang->notes)
                                        <div class="p-2 px-3 rounded-3 mb-3 small d-flex align-items-center gap-2" style="background: #fff7ed; border-left: 3.5px solid var(--primary); color: var(--terracotta);">
                                            <i class="fa-solid fa-om fs-6"></i>
                                            <span class="fw-semibold">{{ $todayPanchang->notes }}</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-4 text-muted small">
                                        <i class="fa-solid fa-calendar-xmark fs-3 mb-2 opacity-50"></i>
                                        <p class="mb-0">आज का पंचांग विवरण उपलब्ध नहीं है।</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Bottom Links -->
                            <div class="d-flex align-items-center justify-content-between pt-3 border-top gap-2 mt-auto">
                                <a href="{{ route('festivals.index') }}" class="small text-decoration-none fw-semibold text-muted">
                                    <i class="fa-solid fa-sparkles text-warning me-1"></i> जैन त्योहार व पर्व
                                </a>
                                <a href="{{ route('panchang.index') }}" class="btn btn-sm btn-spiritual py-1 px-3" style="font-size: 12.5px;">
                                    <i class="fa-solid fa-calendar-days me-1"></i> पूरा पंचांग देखें
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Suvichar Image Lightbox Modal -->
        @if(!empty($quoteImage))
            <div class="modal fade" id="suvicharImageModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #1c0d02;">
                        <div class="modal-header border-0 pb-0 text-white">
                            <h6 class="modal-title font-spiritual text-white">
                                <i class="fa-solid fa-hands-praying text-warning me-2"></i> {{ $quoteTitle }}
                            </h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center p-3">
                            <img src="{{ $quoteImage }}" class="img-fluid rounded-3 shadow mb-3" style="max-height: 70vh; object-fit: contain;" alt="{{ $quoteAuthor }}">
                            <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.08); color: #fff;">
                                <p class="font-spiritual fs-5 mb-1 text-warning">"{{ $quoteText }}"</p>
                                <small class="text-light opacity-75">— {{ $quoteAuthor }}</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                            <a href="{{ $quoteImage }}" download="jain_suvichar.jpg" class="btn btn-sm btn-spiritual">
                                <i class="fa-solid fa-download me-1"></i> चित्र डाउनलोड करें
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ $whatsappShareText }}" target="_blank" class="btn btn-sm suvichar-btn-whatsapp">
                                <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp शेयर
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- 4. Featured Saints (Sant Parichay) -->
    @if(in_array('saints', $activeSections) && $saints->isNotEmpty())
        <section class="py-5 bg-white border-bottom">
            <div class="container">
                <x-section-title 
                    pretitle="त्याग एवं तपस्या" 
                    title="पूज्य साधु संघ" 
                    subtitle="दिगम्बर जैन मुनि परम्परा के परम पूज्य तपस्वी साधुओं का जीवन परिचय एवं विहार स्थिति।" 
                />

                <div class="row g-4">
                    @foreach($saints as $sant)
                        <div class="col-lg-3 col-md-6">
                            <x-sant-card :sant="$sant" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('sants.index') }}" class="btn-spiritual-outline">
                        सभी संत संघ देखें <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 5. Latest Pravachan -->
    @if(in_array('pravachan', $activeSections) && $pravachans->isNotEmpty())
        <section class="py-5" style="background: var(--bg-warm-tint);">
            <div class="container">
                <x-section-title 
                    pretitle="अमृत देशना" 
                    title="नवीनतम अमृत प्रवचन" 
                    subtitle="पूज्य आचार्यों एवं मुनिराजों के अमृत वचन, जो जीवन को सत्य और समता का मार्ग दिखाते हैं।" 
                />

                <div class="row g-4">
                    @foreach($pravachans as $pravachan)
                        <div class="col-lg-4 col-md-6">
                            <x-pravachan-card :pravachan="$pravachan" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('pravachans.index') }}" class="btn-spiritual">
                        सभी प्रवचन सुनें <i class="fa-solid fa-headphones ms-1"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 6. Jain Granth (Gyan Bhandar) -->
    @if(in_array('granth', $activeSections) && $granths->isNotEmpty())
        <section class="py-5 bg-white border-bottom">
            <div class="container">
                <x-section-title 
                    pretitle="ज्ञान भण्डार" 
                    title="पवित्र जैन ग्रन्थ" 
                    subtitle="तत्त्वार्थ सूत्र, समयसार, नियमसार आदि प्रामाणिक आगम ग्रन्थों का निःशुल्क डिजिटल स्वाध्याय।" 
                />

                <div class="row g-4">
                    @foreach($granths as $granth)
                        <div class="col-lg-4 col-md-6">
                            <x-granth-card :granth="$granth" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('granths.index') }}" class="btn-spiritual-outline">
                        समस्त ग्रन्थ संग्रह <i class="fa-solid fa-book-open ms-1"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 7. Bhajan / Stuti -->
    @if(in_array('bhajan', $activeSections) && $bhajans->isNotEmpty())
        <section class="py-5" style="background: var(--bg-warm-tint);">
            <div class="container">
                <x-section-title 
                    pretitle="भक्ति रस" 
                    title="भजन, आरती एवं स्तुति" 
                    subtitle="जिनेंद्र प्रभु की भक्ति, भक्तामर स्तोत्र एवं आत्म-शांति प्रदान करने वाले मधुर भजन।" 
                />

                <div class="row g-4">
                    @foreach($bhajans as $bhajan)
                        <div class="col-lg-4 col-md-6">
                            <x-bhajan-card :bhajan="$bhajan" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('bhajans.index') }}" class="btn-spiritual">
                        सभी भजन एवं स्तुति <i class="fa-solid fa-music ms-1"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 8. Latest News / Samachar -->
    @if(in_array('news', $activeSections) && $news->isNotEmpty())
        <section class="py-5 bg-white border-bottom">
            <div class="container">
                <x-section-title 
                    pretitle="धर्म प्रभावना" 
                    title="नवीनतम जैन समाचार" 
                    subtitle="तीर्थ क्षेत्र, संत विहार, समाज सेवा एवं धार्मिक सम्मेलनों की अद्यतन खबरें।" 
                />

                <div class="row g-4">
                    @foreach($news as $item)
                        <div class="col-lg-4 col-md-6">
                            <x-news-card :news="$item" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('news.index') }}" class="btn-spiritual-outline">
                        सभी समाचार पढ़ें <i class="fa-solid fa-newspaper ms-1"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 9. Upcoming Events (Karyakram) -->
    @if(in_array('events', $activeSections) && $events->isNotEmpty())
        <section class="py-5" style="background: var(--bg-warm-tint);">
            <div class="container">
                <x-section-title 
                    pretitle="मांगलिक आयोजन" 
                    title="आगामी धार्मिक कार्यक्रम" 
                    subtitle="पंचकल्याणक, विधान, चातुर्मास स्थापना एवं राष्ट्रीय धार्मिक सम्मेलनों की तिथियां।" 
                />

                <div class="row g-4">
                    @foreach($events as $event)
                        <div class="col-lg-4 col-md-6">
                            <x-event-card :event="$event" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('events.index') }}" class="btn-spiritual">
                        सभी कार्यक्रम कैलेंडर देखें <i class="fa-solid fa-calendar-days ms-1"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 10. Photo Gallery -->
    @if(in_array('gallery', $activeSections) && $photos->isNotEmpty())
        <section class="py-5 bg-white border-bottom">
            <div class="container">
                <x-section-title 
                    pretitle="पावन दृश्य" 
                    title="चित्र दीर्घा (Photo Gallery)" 
                    subtitle="तीर्थ वंदना, संत दर्शन एवं धार्मिक उत्सवों के पावन संस्मरण।" 
                />

                <div class="row g-3">
                    @foreach($photos as $photo)
                        @php
                            $caption = is_array($photo->caption) ? ($photo->caption['hi'] ?? $photo->caption['en'] ?? '') : $photo->caption;
                        @endphp
                        <div class="col-lg-4 col-md-6">
                            <x-gallery-card 
                                :src="$photo->photo_path" 
                                :caption="$caption"
                                :category="$photo->album ? (is_array($photo->album->name) ? ($photo->album->name['hi'] ?? $photo->album->name['en']) : $photo->album->name) : 'चित्र'"
                            />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('gallery.photos') }}" class="btn-spiritual-outline">
                        पूरी चित्र दीर्घा देखें <i class="fa-solid fa-images ms-1"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 11. Video Gallery -->
    @if(in_array('video_gallery', $activeSections) && $videos->isNotEmpty())
        <section class="py-5" style="background: var(--bg-warm-tint);">
            <div class="container">
                <x-section-title 
                    pretitle="दृव्य-श्रव्य स्वाध्याय" 
                    title="वीडियो दीर्घा (Video Gallery)" 
                    subtitle="पूज्य संतों के दुर्लभ वीडियो प्रवचन एवं तीर्थ वृत्तचित्र।" 
                />

                <div class="row g-4">
                    @foreach($videos as $vid)
                        @php
                            $vUrl = is_array($vid->videos) ? $vid->videos[0] : $vid->videos;
                            $vTitle = is_array($vid->title) ? ($vid->title['hi'] ?? $vid->title['en'] ?? '') : $vid->title;
                            $vAuthor = is_array($vid->author) ? ($vid->author['hi'] ?? $vid->author['en'] ?? '') : $vid->author;
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

                <div class="text-center mt-5">
                    <a href="{{ route('gallery.videos') }}" class="btn-spiritual">
                        सभी वीडियो देखें <i class="fa-solid fa-video ms-1"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 12. Spiritual Quote Banner -->
    @if(in_array('quote_banner', $activeSections))
        <section class="py-5 text-white" style="background: linear-gradient(135deg, #2b1104 0%, #170a02 100%); border-top: 2px solid var(--gold);">
            <div class="container text-center py-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 60px; height: 60px; background: rgba(194, 132, 31, 0.2); color: #fef08a; font-size: 26px;">
                    <i class="fa-solid fa-hands-praying"></i>
                </div>
                <h3 class="font-spiritual text-white mb-3" style="font-size: 28px;">
                    "परस्परोपग्रहो जीवानाम्"
                </h3>
                <p class="mx-auto text-light opacity-75 mb-4" style="max-width: 620px; font-size: 16px;">
                    समस्त जीव एक-दूसरे के उपकारक हैं। अहिंसा, करुणा एवं परोपकार ही मानव जीवन का सर्वोच्च धर्म है।
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('granths.index') }}" class="btn btn-sm btn-spiritual">स्वाध्याय प्रारंभ करें</a>
                    <a href="{{ route('contact') }}" class="btn btn-sm btn-outline-light">समिति से जुड़ें</a>
                </div>
            </div>
        </section>
    @endif

    <!-- Vihar Poster Lightbox Modal -->
    <div class="modal fade" id="viharPosterModal" tabindex="-1" aria-labelledby="viharPosterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: 2px solid var(--gold); overflow: hidden; background: #fffefb;">
                <div class="modal-header py-3 px-4" style="background: linear-gradient(135deg, #7c2d12 0%, #9a3412 100%); color: #fff;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-om text-warning fs-5"></i>
                        <div>
                            <h6 class="modal-title font-spiritual text-white mb-0" id="viharPosterModalLabel" style="font-size: 17px;">
                                {{ $viharTitle ?? 'वर्षा योग एवं विहार पावन पोस्टर' }}
                            </h6>
                            <small class="text-warning opacity-75" style="font-size: 12px;">{{ $viharTirth ?? '' }}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2 text-center" style="background: #1c1917;">
                    <img src="{{ $viharPoster ?? asset('images/jain/varsha_yog_poster_cropped.jpg') }}" alt="{{ $viharTitle ?? 'वर्षा योग पोस्टर' }}" class="img-fluid rounded shadow" style="max-height: 80vh; object-fit: contain;">
                </div>
                <div class="modal-footer py-2 px-4 d-flex justify-content-between flex-wrap gap-2" style="background: #faf6f0; border-top: 1.5px solid var(--gold-border);">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ $viharPoster ?? asset('images/jain/varsha_yog_poster_cropped.jpg') }}" download="jain_varsha_yog_poster.jpg" class="btn btn-sm btn-primary" style="background: var(--primary); border:none; font-size: 12.5px;">
                            <i class="fa-solid fa-download me-1"></i> पोस्टर डाउनलोड
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ $viharShareMsg ?? '' }}" target="_blank" class="btn btn-sm btn-success" style="font-size: 12.5px;">
                            <i class="fa-brands fa-whatsapp me-1"></i> व्हाट्सएप शेयर
                        </a>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12.5px;">बंद करें</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Suvichar Image Modal -->
    <div class="modal fade" id="suvicharImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content" style="border-radius: 18px; border: 2px solid var(--gold); overflow: hidden; background: #fffefb;">
                <div class="modal-header py-2 px-3" style="background: #7c2d12; color: #fff;">
                    <h6 class="modal-title font-spiritual text-white mb-0" style="font-size: 15px;">
                        <i class="fa-solid fa-sparkles text-warning me-1"></i> {{ $siteSettings['daily_quote_title'] ?? 'आज का आध्यात्मिक संदेश' }}
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-2 text-center" style="background: #000;">
                    <img src="{{ $siteSettings['daily_quote_image'] ?? asset('images/jain/muni_vidyasagar.jpg') }}" alt="Daily Quote" class="img-fluid rounded" style="max-height: 75vh; object-fit: contain;">
                </div>
                <div class="modal-footer py-2 px-3 d-flex justify-content-between" style="background: #faf6f0;">
                    <a href="{{ $siteSettings['daily_quote_image'] ?? asset('images/jain/muni_vidyasagar.jpg') }}" download="jain_suvichar.jpg" class="btn btn-sm btn-outline-dark" style="font-size: 12px;">
                        <i class="fa-solid fa-download me-1"></i> चित्र डाउनलोड
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 12px;">बंद करें</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    @keyframes viharMarqueeAnim {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    .vihar-marquee-content {
        display: inline-block;
        white-space: nowrap;
        animation: viharMarqueeAnim 32s linear infinite;
    }
    .vihar-marquee-wrap:hover .vihar-marquee-content {
        animation-play-state: paused;
    }
    @keyframes pulseDotAnim {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
        70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(220, 38, 38, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }
    .pulse-live-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        background: #dc2626;
        border-radius: 50%;
        animation: pulseDotAnim 1.8s infinite;
        flex-shrink: 0;
    }
    .vihar-poster-card:hover .vihar-poster-img {
        transform: scale(1.03);
    }
</style>
@endpush

@push('scripts')
<script>
    function copySuvicharText(quote, author, btnElement) {
        const textToCopy = `"${quote}"\n— ${author}\n\nसर्वोदय जिनधर्म: ${window.location.origin}`;
        navigator.clipboard.writeText(textToCopy).then(() => {
            const originalHTML = btnElement.innerHTML;
            btnElement.innerHTML = `<i class="fa-solid fa-check text-success"></i> <span class="text-success">कॉपी हो गया!</span>`;
            setTimeout(() => {
                btnElement.innerHTML = originalHTML;
            }, 2500);
        }).catch(err => {
            alert('वचन कॉपी किया गया!');
        });
    }
</script>
@endpush
