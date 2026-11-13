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
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icons/icon-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/apple-touch-icon.png') }}">
    <meta name="theme-color" content="#C2410C">

    <!-- Google Fonts: Mukta (body) + Noto Serif Devanagari (headings) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700&family=Noto+Serif+Devanagari:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom Spiritual Frontend CSS -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">

    <!-- Design v1: shared header/footer + inner page theme -->
    <link rel="stylesheet" href="{{ asset('css/site-v1.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme-v1.css') }}">

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

    @include('partials.site-header', ['searchMode' => 'modal'])

    <!-- 4. Main Body Content Slot -->
    <main id="main">
        @yield('content')
    </main>

    @include('partials.site-footer')

    <!-- Universal Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: var(--radius-md);">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-spiritual" id="searchModalLabel">धर्म पोर्टल पर खोजें</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2 pb-4">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control form-control-lg" placeholder="उदा: विद्यासागर, तत्त्वार्थ सूत्र, पंचांग..." required autofocus>
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
