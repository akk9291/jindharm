<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Primary SEO Meta Tags -->
    <title>@yield('title', $siteSettings['website_name'] ?? 'सर्वोदय जिनधर्म संघ') - {{ $siteSettings['site_tagline'] ?? 'अहिंसा परमो धर्मः' }}</title>
    <meta name="description" content="@yield('meta_description', $siteSettings['footer_about'] ?? 'जैन धर्म, साधु-संत परिचय, विहार स्थिति, दैनिक पंचांग, तत्त्वार्थ सूत्र एवं प्रवचन का प्रामाणिक मंच।')">
    <meta name="keywords" content="@yield('meta_keywords', 'जैन धर्म, दिगम्बर जैन, विद्यासागर जी, पंचांग, तत्त्वार्थ सूत्र, समयसार, जैन भजन, प्रवचन, जैन तीर्थ')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', $siteSettings['website_name'] ?? 'सर्वोदय जिनधर्म संघ')">
    <meta property="og:description" content="@yield('og_description', $siteSettings['site_tagline'] ?? 'अहिंसा परमो धर्मः')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-share.jpg'))">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', $siteSettings['website_name'] ?? 'सर्वोदय जिनधर्म संघ')">
    <meta name="twitter:description" content="@yield('og_description', $siteSettings['site_tagline'] ?? 'अहिंसा परमो धर्मः')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-share.jpg'))">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Noto Serif Devanagari, Noto Sans Devanagari & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Devanagari:wght@400;500;600;700&family=Noto+Serif+Devanagari:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom Spiritual Frontend CSS -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

    <!-- JSON-LD Structured Data Schema -->
    @stack('schema')

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ $siteSettings['website_name'] ?? 'सर्वोदय जिनधर्म संघ' }}",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "sameAs": [
            "{{ $siteSettings['social_facebook'] ?? '' }}",
            "{{ $siteSettings['social_youtube'] ?? '' }}",
            "{{ $siteSettings['social_instagram'] ?? '' }}"
        ]
    }
    </script>

    @stack('styles')
</head>
<body>

    <!-- 1. Top Utility Bar -->
    <div class="top-utility-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Left: Tithi & Festival -->
                <div class="d-flex align-items-center gap-3">
                    @if($todayPanchang)
                        <span class="tithi-pill">
                            <i class="fa-solid fa-moon"></i>
                            {{ $todayPanchang->maas }} - {{ $todayPanchang->paksha }} {{ $todayPanchang->tithi }}
                        </span>
                    @endif

                    @if($todayFestival)
                        <span class="d-none d-md-inline text-truncate" style="max-width: 320px;">
                            <i class="fa-solid fa-sparkles text-warning me-1"></i>
                            <strong>आज का पर्व:</strong> 
                            {{ is_array($todayFestival->festival_name) ? ($todayFestival->festival_name['hi'] ?? $todayFestival->festival_name['en']) : $todayFestival->festival_name }}
                        </span>
                    @endif
                </div>

                <!-- Right: Social & Contact -->
                <div class="d-flex align-items-center gap-3">
                    <span class="d-none d-lg-inline">
                        <i class="fa-solid fa-phone-volume text-warning me-1"></i> 
                        {{ $siteSettings['contact_mobile'] ?? '+91 98765 43210' }}
                    </span>
                    <div class="d-flex align-items-center gap-2">
                        @if(!empty($siteSettings['social_facebook']))
                            <a href="{{ $siteSettings['social_facebook'] }}" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($siteSettings['social_youtube']))
                            <a href="{{ $siteSettings['social_youtube'] }}" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if(!empty($siteSettings['social_whatsapp']))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['social_whatsapp']) }}" target="_blank" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Sticky Site Header -->
    <header class="site-header" id="siteHeader">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between py-2">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="brand-logo-wrapper">
                    <div class="brand-symbol">
                        <i class="fa-solid fa-om"></i>
                    </div>
                    <div class="brand-text">
                        <h1>{{ $siteSettings['website_name'] ?? 'सर्वोदय जिनधर्म' }}</h1>
                        <p>{{ $siteSettings['site_tagline'] ?? 'अहिंसा परमो धर्मः' }}</p>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="d-none d-xl-flex align-items-center gap-1">
                    @if(isset($headerMenu) && $headerMenu->items->count() > 0)
                        @foreach($headerMenu->items as $item)
                            @php
                                $itemUrl = $item->url;
                                $isActive = ($itemUrl === '/' && request()->is('/')) || ($itemUrl !== '/' && request()->is(ltrim($itemUrl, '/') . '*'));
                                $hasChildren = $item->children && $item->children->count() > 0;
                            @endphp

                            @if($hasChildren)
                                <div class="nav-item-dropdown">
                                    <a href="{{ url($itemUrl) }}" class="nav-link-custom {{ $isActive ? 'active' : '' }}" target="{{ $item->target ?: '_self' }}">
                                        @if($item->icon)<i class="{{ $item->icon }}"></i>@endif
                                        <span>{{ $item->getLocalized('label') }}</span>
                                        <i class="fa-solid fa-chevron-down ms-1" style="font-size: 10px; opacity: 0.7;"></i>
                                    </a>
                                    <ul class="nav-dropdown-menu">
                                        @foreach($item->children as $child)
                                            @php
                                                $childUrl = $child->url;
                                                $isChildActive = request()->is(ltrim($childUrl, '/') . '*');
                                            @endphp
                                            <li>
                                                <a href="{{ url($childUrl) }}" class="nav-dropdown-item {{ $isChildActive ? 'active' : '' }}" target="{{ $child->target ?: '_self' }}">
                                                    @if($child->icon)
                                                        <i class="{{ $child->icon }}"></i>
                                                    @else
                                                        <i class="fa-solid fa-angle-right"></i>
                                                    @endif
                                                    <span>{{ $child->getLocalized('label') }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a href="{{ url($itemUrl) }}" class="nav-link-custom {{ $isActive ? 'active' : '' }}" target="{{ $item->target ?: '_self' }}">
                                    @if($item->icon)<i class="{{ $item->icon }}"></i>@endif
                                    <span>{{ $item->getLocalized('label') }}</span>
                                </a>
                            @endif
                        @endforeach
                    @else
                        <!-- Fallback Navigation -->
                        <a href="{{ route('home') }}" class="nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}">गृह (Home)</a>
                        <a href="{{ route('suvichar.index') }}" class="nav-link-custom {{ request()->routeIs('suvichar.*') ? 'active' : '' }}">सुविचार</a>
                        <a href="{{ route('sants.index') }}" class="nav-link-custom {{ request()->routeIs('sants.*') ? 'active' : '' }}">संत परिचय</a>
                        <a href="{{ route('pravachans.index') }}" class="nav-link-custom {{ request()->routeIs('pravachans.*') ? 'active' : '' }}">प्रवचन</a>
                        <a href="{{ route('granths.index') }}" class="nav-link-custom {{ request()->routeIs('granths.*') ? 'active' : '' }}">ज्ञान भण्डार</a>
                        <a href="{{ route('bhajans.index') }}" class="nav-link-custom {{ request()->routeIs('bhajans.*') ? 'active' : '' }}">भजन-स्तुति</a>
                        <a href="{{ route('news.index') }}" class="nav-link-custom {{ request()->routeIs('news.*') ? 'active' : '' }}">समाचार</a>
                        <a href="{{ route('panchang.index') }}" class="nav-link-custom {{ request()->routeIs('panchang.*') ? 'active' : '' }}">पंचांग</a>
                        <a href="{{ route('gallery.photos') }}" class="nav-link-custom {{ request()->routeIs('gallery.*') ? 'active' : '' }}">दीर्घा</a>
                        <a href="{{ route('events.index') }}" class="nav-link-custom {{ request()->routeIs('events.*') ? 'active' : '' }}">कार्यक्रम</a>
                        <a href="{{ route('contact') }}" class="nav-link-custom {{ request()->routeIs('contact') ? 'active' : '' }}">संपर्क</a>
                    @endif
                </nav>

                <!-- Header Actions -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Search Trigger -->
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 38px; height: 38px;" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="खोजें">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                    <!-- Mobile Drawer Toggle Button -->
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle d-xl-none" id="drawerOpenBtn" style="width: 38px; height: 38px;" aria-label="मेनू खोलें">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. Mobile Navigation Drawer -->
    <div class="drawer-overlay" id="drawerOverlay"></div>
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-symbol" style="width: 34px; height: 34px; font-size: 16px;">
                    <i class="fa-solid fa-om"></i>
                </div>
                <strong style="color: var(--primary);">जिनधर्म मेनू</strong>
            </div>
            <button type="button" class="btn-close" id="drawerCloseBtn" aria-label="बंद करें"></button>
        </div>
        <div class="p-3 overflow-auto flex-grow-1">
            <ul class="list-unstyled mb-0">
                @if(isset($headerMenu) && $headerMenu->items->count() > 0)
                    @foreach($headerMenu->items as $item)
                        @php
                            $itemUrl = $item->url;
                            $isActive = ($itemUrl === '/' && request()->is('/')) || ($itemUrl !== '/' && request()->is(ltrim($itemUrl, '/') . '*'));
                            $hasChildren = $item->children && $item->children->count() > 0;
                        @endphp
                        <li class="mb-2">
                            @if($hasChildren)
                                <div class="d-flex align-items-center justify-content-between py-2 px-3 rounded {{ $isActive ? 'bg-light text-danger fw-bold' : 'text-dark' }}">
                                    <a href="{{ url($itemUrl) }}" class="text-reset text-decoration-none d-flex align-items-center gap-2" target="{{ $item->target ?: '_self' }}">
                                        @if($item->icon)<i class="{{ $item->icon }}"></i>@endif
                                        <span>{{ $item->getLocalized('label') }}</span>
                                    </a>
                                    <a class="text-muted" data-bs-toggle="collapse" href="#mobSubMenu{{ $item->id }}" role="button" aria-expanded="false" style="padding: 2px 6px;">
                                        <i class="fa-solid fa-chevron-down" style="font-size: 11px;"></i>
                                    </a>
                                </div>
                                <div class="collapse ps-3 mt-1" id="mobSubMenu{{ $item->id }}">
                                    <ul class="list-unstyled border-start ps-2" style="border-color: #e2e8f0 !important;">
                                        @foreach($item->children as $child)
                                            <li class="my-1">
                                                <a href="{{ url($child->url) }}" class="d-block py-1 px-2 rounded small text-secondary" target="{{ $child->target ?: '_self' }}">
                                                    @if($child->icon)<i class="{{ $child->icon }} me-1" style="font-size: 11px;"></i>@endif
                                                    {{ $child->getLocalized('label') }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a href="{{ url($itemUrl) }}" class="d-block py-2 px-3 rounded {{ $isActive ? 'bg-light text-danger fw-bold' : 'text-dark' }}" target="{{ $item->target ?: '_self' }}">
                                    @if($item->icon)<i class="{{ $item->icon }} me-2"></i>@endif
                                    {{ $item->getLocalized('label') }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @else
                    <li class="mb-2"><a href="{{ route('home') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('home') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-house me-2"></i> गृह (Home)</a></li>
                    <li class="mb-2"><a href="{{ route('suvichar.index') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('suvichar.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-quote-left me-2"></i> दैनिक सुविचार आर्काइव</a></li>
                    <li class="mb-2"><a href="{{ route('sants.index') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('sants.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-user-tie me-2"></i> पूज्य साधु संघ</a></li>
                    <li class="mb-2"><a href="{{ route('pravachans.index') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('pravachans.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-microphone me-2"></i> अमृत प्रवचन</a></li>
                    <li class="mb-2"><a href="{{ route('granths.index') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('granths.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-book-open me-2"></i> ज्ञान भण्डार (ग्रन्थ)</a></li>
                    <li class="mb-2"><a href="{{ route('bhajans.index') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('bhajans.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-music me-2"></i> भजन एवं स्तुति</a></li>
                    <li class="mb-2"><a href="{{ route('news.index') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('news.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-newspaper me-2"></i> धर्म समाचार</a></li>
                    <li class="mb-2"><a href="{{ route('panchang.index') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('panchang.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-calendar-days me-2"></i> जैन पंचांग</a></li>
                    <li class="mb-2"><a href="{{ route('festivals.index') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('festivals.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-award me-2"></i> पर्व एवं त्योहार</a></li>
                    <li class="mb-2"><a href="{{ route('gallery.photos') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('gallery.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-images me-2"></i> चित्र दीर्घा</a></li>
                    <li class="mb-2"><a href="{{ route('events.index') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('events.*') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-clock me-2"></i> आगामी कार्यक्रम</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}" class="d-block py-2 px-3 rounded {{ request()->routeIs('contact') ? 'bg-light text-danger fw-bold' : 'text-dark' }}"><i class="fa-solid fa-envelope me-2"></i> संपर्क करें</a></li>
                @endif
            </ul>
        </div>
        <div class="p-3 border-top bg-light">
            <small class="text-muted d-block mb-1">हेल्पलाइन नंबर:</small>
            <strong>{{ $siteSettings['contact_mobile'] ?? '+91 98765 43210' }}</strong>
        </div>
    </div>

    <!-- 4. Main Body Content Slot -->
    <main>
        @yield('content')
    </main>

    <!-- 5. Four-Column Spiritual Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4">
                <!-- Col 1: About -->
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-symbol" style="width: 38px; height: 38px; font-size: 18px;">
                            <i class="fa-solid fa-om"></i>
                        </div>
                        <h4 class="mb-0 text-white font-spiritual" style="font-size: 20px;">{{ $siteSettings['website_name'] ?? 'सर्वोदय जिनधर्म' }}</h4>
                    </div>
                    <p class="text-secondary small leading-relaxed" style="color: #a8a29e !important;">
                        {{ $siteSettings['footer_about'] ?? 'भगवान महावीर के अहिंसा, अपरिग्रह और अनेकांत के दर्शन को समर्पित प्रामाणिक जैन डिजिटल मंच।' }}
                    </p>
                    <div class="d-flex align-items-center gap-3 mt-3">
                        @if(!empty($siteSettings['social_facebook']))
                            <a href="{{ $siteSettings['social_facebook'] }}" class="btn btn-sm btn-outline-light rounded-circle" style="width: 34px; height: 34px; padding: 6px;"><i class="fa-brands fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($siteSettings['social_youtube']))
                            <a href="{{ $siteSettings['social_youtube'] }}" class="btn btn-sm btn-outline-light rounded-circle" style="width: 34px; height: 34px; padding: 6px;"><i class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if(!empty($siteSettings['social_instagram']))
                            <a href="{{ $siteSettings['social_instagram'] }}" class="btn btn-sm btn-outline-light rounded-circle" style="width: 34px; height: 34px; padding: 6px;"><i class="fa-brands fa-instagram"></i></a>
                        @endif
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h5>त्वरित लिंक</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> मुख्य पृष्ठ</a></li>
                        <li><a href="{{ route('sants.index') }}"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> पूज्य संत संघ</a></li>
                        <li><a href="{{ route('pravachans.index') }}"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> अमृत प्रवचन</a></li>
                        <li><a href="{{ route('events.index') }}"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> धर्म उत्सव</a></li>
                        <li><a href="{{ route('panchang.index') }}"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> दैनिक पंचांग</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i> संपर्क केंद्र</a></li>
                    </ul>
                </div>

                <!-- Col 3: Knowledge Library -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h5>ज्ञान भण्डार</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('granths.index') }}"><i class="fa-solid fa-book" style="font-size: 10px;"></i> तत्त्वार्थ सूत्र</a></li>
                        <li><a href="{{ route('granths.index') }}"><i class="fa-solid fa-book" style="font-size: 10px;"></i> समयसार परमागम</a></li>
                        <li><a href="{{ route('bhajans.index') }}"><i class="fa-solid fa-music" style="font-size: 10px;"></i> मेरी भावना (प्रार्थना)</a></li>
                        <li><a href="{{ route('bhajans.index') }}"><i class="fa-solid fa-music" style="font-size: 10px;"></i> भक्तामर स्तोत्र पाठ</a></li>
                        <li><a href="{{ route('festivals.index') }}"><i class="fa-solid fa-award" style="font-size: 10px;"></i> दशलक्षण महापर्व</a></li>
                        <li><a href="{{ route('pages.show', 'about-us') }}"><i class="fa-solid fa-info-circle" style="font-size: 10px;"></i> हमारे बारे में</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact & Office -->
                <div class="col-lg-3 col-md-6">
                    <h5>संपर्क एवं सूचना</h5>
                    <p class="small mb-2" style="color: #a8a29e !important;">
                        <i class="fa-solid fa-location-dot text-warning me-2"></i>
                        {{ $siteSettings['contact_address'] ?? 'श्री दिगम्बर जैन मंदिर परिसर, नई दिल्ली' }}
                    </p>
                    <p class="small mb-2" style="color: #a8a29e !important;">
                        <i class="fa-solid fa-phone text-warning me-2"></i>
                        {{ $siteSettings['contact_mobile'] ?? '+91 98765 43210' }}
                    </p>
                    <p class="small mb-3" style="color: #a8a29e !important;">
                        <i class="fa-solid fa-envelope text-warning me-2"></i>
                        {{ $siteSettings['contact_email'] ?? 'info@jindharm.org' }}
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-sm btn-spiritual py-2 px-3 w-100">
                        <i class="fa-solid fa-paper-plane me-1"></i> संदेश भेजें (Inquiry)
                    </a>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2 text-center text-md-start">
                    <span>© {{ date('Y') }} {{ $siteSettings['website_name'] ?? 'सर्वोदय जिनधर्म संघ' }}। सर्वाधिकार सुरक्षित।</span>
                    <span class="d-none d-md-inline text-muted">|</span>
                    <span class="text-secondary">
                        Development by <a href="https://coderpoint.in" target="_blank" rel="noopener noreferrer" class="text-warning fw-semibold text-decoration-none">Coder Point</a>
                    </span>
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
                    <a href="{{ route('pages.show', 'privacy-policy') }}" class="text-secondary small">गोपनीयता नीति</a>
                    <span>•</span>
                    <a href="{{ route('pages.show', 'terms-conditions') }}" class="text-secondary small">नियम व शर्तें</a>
                    <span>•</span>
                    <a href="{{ url('/admin/login') }}" class="text-secondary small"><i class="fa-solid fa-lock me-1"></i>एडमिन लॉगिन</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Universal Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md);">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-spiritual" id="searchModalLabel"><i class="fa-solid fa-magnifying-glass text-danger me-2"></i>धर्म पोर्टल पर खोजें</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2 pb-4">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control form-control-lg border-2" placeholder="उदा: विद्यासागर, तत्त्वार्थ सूत्र, पंचांग..." required autofocus>
                            <button class="btn btn-spiritual" type="submit">खोजें</button>
                        </div>
                        <small class="text-muted d-block mt-2">आप संत, ग्रन्थ, भजन, समाचार या कार्यक्रम खोज सकते हैं।</small>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global App Scripts -->
    <script>
        // Header scroll elevation
        window.addEventListener('scroll', () => {
            const header = document.getElementById('siteHeader');
            if (header) {
                if (window.scrollY > 30) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            }
        });

        // Mobile drawer toggles
        const drawer = document.getElementById('mobileDrawer');
        const overlay = document.getElementById('drawerOverlay');
        const openBtn = document.getElementById('drawerOpenBtn');
        const closeBtn = document.getElementById('drawerCloseBtn');

        function openDrawer() {
            if (drawer && overlay) {
                drawer.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeDrawer() {
            if (drawer && overlay) {
                drawer.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        if (openBtn) openBtn.addEventListener('click', openDrawer);
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        if (overlay) overlay.addEventListener('click', closeDrawer);

        // Custom Accessible HTML5 Audio Player Controller
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.spiritual-audio-player').forEach(player => {
                const audio = player.querySelector('audio');
                const playBtn = player.querySelector('.audio-btn-play');
                const scrubber = player.querySelector('.audio-scrubber');
                const currentTimeSpan = player.querySelector('.audio-current-time');
                const durationSpan = player.querySelector('.audio-duration');

                if (!audio || !playBtn) return;

                function formatTime(seconds) {
                    const mins = Math.floor(seconds / 60);
                    const secs = Math.floor(seconds % 60);
                    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
                }

                audio.addEventListener('loadedmetadata', () => {
                    if (scrubber) scrubber.max = audio.duration;
                    if (durationSpan) durationSpan.textContent = formatTime(audio.duration);
                });

                audio.addEventListener('timeupdate', () => {
                    if (scrubber) scrubber.value = audio.currentTime;
                    if (currentTimeSpan) currentTimeSpan.textContent = formatTime(audio.currentTime);
                });

                playBtn.addEventListener('click', () => {
                    // Pause other playing audio elements
                    document.querySelectorAll('audio').forEach(otherAudio => {
                        if (otherAudio !== audio && !otherAudio.paused) {
                            otherAudio.pause();
                            const otherPlayBtn = otherAudio.closest('.spiritual-audio-player')?.querySelector('.audio-btn-play i');
                            if (otherPlayBtn) otherPlayBtn.className = 'fa-solid fa-play';
                        }
                    });

                    const icon = playBtn.querySelector('i');
                    if (audio.paused) {
                        audio.play();
                        if (icon) icon.className = 'fa-solid fa-pause';
                    } else {
                        audio.pause();
                        if (icon) icon.className = 'fa-solid fa-play';
                    }
                });

                audio.addEventListener('ended', () => {
                    const icon = playBtn.querySelector('i');
                    if (icon) icon.className = 'fa-solid fa-play';
                    if (scrubber) scrubber.value = 0;
                });

                if (scrubber) {
                    scrubber.addEventListener('input', () => {
                        audio.currentTime = scrubber.value;
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
