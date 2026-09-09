@props([
    'badge' => 'सत्य • अहिंसा • अपरिग्रह • अनेकांत',
    'title' => 'अहिंसा और आत्म-कल्याण का',
    'titleHighlight' => 'दिव्य आलोक',
    'description' => 'भगवान महावीर के कालजयी दर्शन और पूज्य दिगम्बर मुनि संघ की पावन परम्परा से जुड़ें। प्रामाणिक ग्रन्थ, दैनिक पंचांग एवं संत विहार की सजीव स्थिति।',
    'primaryBtnText' => 'संत विहार देखें',
    'primaryBtnUrl' => url('/sant'),
    'secondaryBtnText' => 'पवित्र ग्रन्थ पढ़ें',
    'secondaryBtnUrl' => url('/granth'),
    'image' => asset('images/jain/muni_vidyasagar.jpg'),
    'saintHighlight' => 'परम पूज्य आचार्य श्री १०८ विद्यासागर जी महाराज',
])

<section class="hero-spiritual">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Left Text Content -->
            <div class="col-lg-7">
                @if($badge)
                    <div class="hero-badge">
                        <i class="fa-solid fa-sparkles text-warning"></i>
                        <span>{{ $badge }}</span>
                    </div>
                @endif
                <h1 class="hero-title font-spiritual">
                    {{ $title }} <span>{{ $titleHighlight }}</span>
                </h1>
                <p class="hero-description">
                    {{ $description }}
                </p>
                <div class="d-flex flex-wrap align-items-center gap-3">
                    @if($primaryBtnText)
                        <a href="{{ $primaryBtnUrl }}" class="btn-spiritual">
                            <i class="fa-solid fa-map-location-dot"></i> {{ $primaryBtnText }}
                        </a>
                    @endif
                    @if($secondaryBtnText)
                        <a href="{{ $secondaryBtnUrl }}" class="btn-spiritual-outline">
                            <i class="fa-solid fa-book-open"></i> {{ $secondaryBtnText }}
                        </a>
                    @endif
                </div>

                <!-- Daily Inspiration Mini Bar -->
                <div class="d-flex align-items-center gap-3 mt-4 pt-3 border-top border-light">
                    <div class="text-secondary small">
                        <i class="fa-solid fa-feather-pointed text-warning me-1"></i>
                        <strong>दैनिक प्रेरणा:</strong> "अप्प दीपो भव — अपनी आत्मा के स्वयं दीपक बनें।"
                    </div>
                </div>
            </div>

            <!-- Right Visual / Saint Portrait -->
            <div class="col-lg-5">
                <div class="hero-image-card">
                    <img src="{{ $image }}" class="img-fluid w-100" style="max-height: 480px; object-fit: cover;" alt="{{ $saintHighlight }}">
                    @if($saintHighlight)
                        <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-white backdrop-blur">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-hands-praying text-warning fs-5"></i>
                                <div>
                                    <h6 class="mb-0 text-white font-spiritual" style="font-size: 15px;">{{ $saintHighlight }}</h6>
                                    <small class="text-light" style="font-size: 11px;">दर्शन • प्रेरणा • संयम</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
