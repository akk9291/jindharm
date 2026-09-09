@extends('layouts.app')

@section('title', 'संपर्क एवं सूचना केंद्र | सर्वोदय जिनधर्म संघ')
@section('meta_description', 'सर्वोदय जिनधर्म संघ के केंद्रीय कार्यालय, तीर्थ संपर्क केंद्र एवं सूचना हेल्पलाइन से संपर्क करें।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'संपर्क करें', 'url' => '']]" />

    <div class="container pb-5">
        <div class="mb-4 text-center">
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-semibold mb-2">सेवा एवं सहयोग</span>
            <h1 class="font-spiritual mb-2" style="font-size: 32px;">संपर्क एवं सूचना केंद्र</h1>
            <p class="text-muted small mx-auto" style="max-width: 600px;">
                तीर्थ यात्रा, संत विहार स्थिति, आहार चर्या या शास्त्र स्वाध्याय से संबंधित किसी भी जिज्ञासा हेतु संपर्क करें।
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 p-3 mb-4 shadow-sm">
                <i class="fa-solid fa-circle-check me-2 fs-5 text-success"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- Contact Information Column -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white h-100">
                    <h4 class="font-spiritual border-bottom pb-3 mb-4 text-danger"><i class="fa-solid fa-building-columns me-2"></i> केंद्रीय कार्यालय</h4>
                    
                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 text-danger flex-shrink-0"><i class="fa-solid fa-location-dot"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold">पता (Address)</h6>
                            <p class="text-muted small mb-0">{{ $siteSettings['contact_address'] ?? '१०२, श्री दिगम्बर जैन बड़ा मंदिर परिसर, नई दिल्ली' }}</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 text-warning flex-shrink-0"><i class="fa-solid fa-phone-volume"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold">हेल्पलाइन एवं फोन</h6>
                            <p class="text-muted small mb-0">{{ $siteSettings['contact_mobile'] ?? '+91 98765 43210' }}</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 text-success flex-shrink-0"><i class="fa-brands fa-whatsapp"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold">व्हाट्सएप सूचना सेवा</h6>
                            <p class="text-muted small mb-0">{{ $siteSettings['contact_whatsapp'] ?? '+91 98765 43210' }}</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="fs-4 text-primary flex-shrink-0"><i class="fa-solid fa-envelope"></i></div>
                        <div>
                            <h6 class="mb-1 fw-bold">ईमेल (Email)</h6>
                            <p class="text-muted small mb-0">{{ $siteSettings['contact_email'] ?? 'info@jindharm.org' }}</p>
                        </div>
                    </div>

                    <div class="mt-auto pt-3 border-top">
                        <small class="text-muted d-block mb-2 fw-bold">कार्यालय समय:</small>
                        <p class="small text-secondary mb-0">प्रातः ०८:०० से सायं ०७:०० बजे तक (प्रतिदिन)</p>
                    </div>
                </div>
            </div>

            <!-- Inquiry Form Column -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                    <h4 class="font-spiritual border-bottom pb-3 mb-4"><i class="fa-solid fa-paper-plane text-danger me-2"></i> संदेश / जिज्ञासा भेजें</h4>
                    
                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">आपका पूरा नाम <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="उदा. श्रेयांश जैन" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">ईमेल पता <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">मोबाइल नंबर</label>
                                <input type="text" name="mobile" class="form-control" placeholder="+91 98765 43210">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">विषय (Subject) <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" placeholder="उदा. संत विहार जानकारी" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">संदेश / विवरण <span class="text-danger">*</span></label>
                                <textarea name="message" rows="5" class="form-control" placeholder="अपना संदेश यहाँ लिखें..." required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-spiritual py-2 px-4">
                                    <i class="fa-solid fa-paper-plane me-2"></i> संदेश प्रेषित करें
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
