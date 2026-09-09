@extends('layouts.app')

@php
    $title = $quote->display_title;
    $text = $quote->display_text;
    $author = $quote->display_author;
    $image = $quote->image;
    $date = $quote->quote_date ? $quote->quote_date->format('d M, Y') : '';
    $waText = urlencode("✨ " . $title . " ✨\n\n\"" . $text . "\"\n— " . $author . "\n\nपूरा सुविचार देखें: " . url()->current());
@endphp

@section('title', $title . ' - ' . Str::limit($text, 50))
@section('meta_description', Str::limit($text, 150))
@section('og_image', asset($image))

@section('content')

<!-- 1. Breadcrumbs -->
<x-breadcrumbs :items="[
    ['title' => 'दैनिक सुविचार', 'url' => route('suvichar.index')],
    ['title' => Str::limit($text, 35), 'url' => '']
]" />

<!-- 2. Main Content Section -->
<section class="py-5" style="background: var(--bg-warm-tint);">
    <div class="container">
        <div class="row justify-content-center g-4">
            <div class="col-lg-10">
                <div class="bg-white rounded-4 shadow-sm border p-4 p-md-5" style="border-color: var(--gold-border) !important;">
                    
                    <!-- Header Meta -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-4 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background: rgba(194, 132, 31, 0.15); color: var(--terracotta); border: 1px solid var(--gold-border); font-size: 13px; padding: 6px 14px; border-radius: 20px;">
                                <i class="fa-solid fa-sparkles text-warning me-1"></i> {{ $title }}
                            </span>
                            @if($quote->category)
                                <span class="badge bg-secondary text-light font-size: 12px; padding: 6px 12px; border-radius: 20px;">
                                    <i class="fa-solid fa-tag me-1"></i> {{ $quote->category }}
                                </span>
                            @endif
                        </div>
                        @if($date)
                            <div class="text-muted small">
                                <i class="fa-regular fa-calendar text-warning me-1"></i> {{ $date }}
                            </div>
                        @endif
                    </div>

                    <div class="row g-4 align-items-center">
                        <!-- Left: Large Sacred Image with Lightbox Zoom -->
                        <div class="col-md-5">
                            <div class="position-relative rounded-4 overflow-hidden shadow" style="border: 2px solid var(--gold); max-height: 440px; background: #2b1104;">
                                <img src="{{ $image }}" class="w-100 h-100 object-fit-cover" alt="{{ $author }}" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageZoomModal">
                                <div class="position-absolute bottom-0 start-0 end-0 p-3 bg-dark bg-opacity-75 text-center text-white small backdrop-blur">
                                    <i class="fa-solid fa-magnifying-glass-plus me-1"></i> क्लिक कर बड़ा चित्र देखें
                                </div>
                            </div>
                        </div>

                        <!-- Right: Quote Text and Author -->
                        <div class="col-md-7">
                            <div class="ps-md-3">
                                <div class="mb-3 text-warning opacity-75">
                                    <i class="fa-solid fa-quote-left display-4" style="color: var(--gold);"></i>
                                </div>
                                
                                <h2 class="font-spiritual text-primary mb-4" style="font-size: 26px; line-height: 1.6; font-weight: 600;">
                                    "{{ $text }}"
                                </h2>

                                <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: rgba(133, 56, 12, 0.1); color: var(--terracotta); font-size: 18px;">
                                        <i class="fa-solid fa-hands-praying"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 font-spiritual text-terracotta" style="font-size: 18px;">{{ $author }}</h5>
                                        <small class="text-muted">परम पूज्य जिनवाणी अमृत वचन</small>
                                    </div>
                                </div>

                                <!-- Actions Toolbar -->
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="https://api.whatsapp.com/send?text={{ $waText }}" target="_blank" class="suvichar-btn-whatsapp" title="WhatsApp पर शेयर करें">
                                        <i class="fa-brands fa-whatsapp fs-5"></i> WhatsApp शेयर
                                    </a>

                                    <a href="{{ $image }}" download="jain_suvichar_{{ $quote->id }}.jpg" class="suvichar-btn-action" title="सुविचार चित्र डाउनलोड करें">
                                        <i class="fa-solid fa-download text-primary"></i> चित्र डाउनलोड
                                    </a>

                                    <button type="button" class="suvichar-btn-action" onclick="copyQuoteToClipboard('{{ addslashes($text) }}', '{{ addslashes($author) }}', this)">
                                        <i class="fa-regular fa-copy text-secondary"></i> <span>वचन कॉपी करें</span>
                                    </button>

                                    <a href="{{ route('suvichar.index') }}" class="suvichar-btn-action">
                                        <i class="fa-solid fa-layer-group text-warning"></i> सभी सुविचार
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Social Sharing Bar -->
                    <div class="mt-5 pt-4 border-top text-center">
                        <span class="text-muted small fw-bold d-block mb-3">
                            <i class="fa-solid fa-share-nodes text-warning me-1"></i> इस पावन संदेश को अन्य धर्मप्रेमियों तक पहुँचाएं:
                        </span>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <a href="https://api.whatsapp.com/send?text={{ $waText }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3">
                                <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="fa-brands fa-facebook me-1"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ $waText }}" target="_blank" class="btn btn-sm btn-dark rounded-pill px-3">
                                <i class="fa-brands fa-x-twitter me-1"></i> Twitter
                            </a>
                            <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ $waText }}" target="_blank" class="btn btn-sm btn-info text-white rounded-pill px-3">
                                <i class="fa-brands fa-telegram me-1"></i> Telegram
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Zoom -->
<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #1c0d02;">
            <div class="modal-header border-0 pb-0 text-white">
                <h6 class="modal-title font-spiritual text-white">
                    <i class="fa-solid fa-hands-praying text-warning me-2"></i> {{ $title }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="{{ $image }}" class="img-fluid rounded-3 shadow mb-3" style="max-height: 75vh; object-fit: contain;" alt="{{ $author }}">
                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.08); color: #fff;">
                    <p class="font-spiritual fs-5 mb-1 text-warning">"{{ $text }}"</p>
                    <small class="text-light opacity-75">— {{ $author }}</small>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                <a href="{{ $image }}" download="jain_suvichar.jpg" class="btn btn-sm btn-spiritual">
                    <i class="fa-solid fa-download me-1"></i> चित्र डाउनलोड करें
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 3. Related / More Quotes -->
@if(isset($relatedQuotes) && count($relatedQuotes) > 0)
    <section class="py-5 bg-white border-top">
        <div class="container">
            <x-section-title 
                pretitle="आध्यात्मिक संग्रह" 
                title="अन्य पावन सुविचार" 
                subtitle="जिनवाणी एवं पूज्य मुनि संघ के अन्य प्रेरणादायी विचार।" 
            />

            <div class="row g-4 justify-content-center">
                @foreach($relatedQuotes as $rQuote)
                    <div class="col-lg-4 col-md-6">
                        <div class="spiritual-card h-100 p-4 border" style="border-radius: 12px; border-color: var(--gold-border) !important;">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img src="{{ $rQuote->image }}" style="width: 55px; height: 55px; object-fit: cover; border-radius: 8px; border: 1.5px solid var(--gold);">
                                <div>
                                    <h6 class="mb-0 font-spiritual" style="font-size: 14px;">{{ $rQuote->display_author }}</h6>
                                    <small class="text-muted">{{ $rQuote->quote_date ? $rQuote->quote_date->format('d M, Y') : '' }}</small>
                                </div>
                            </div>
                            <p class="font-spiritual text-dark mb-3" style="font-size: 16px; line-height: 1.5;">
                                "{{ Str::limit($rQuote->display_text, 80) }}"
                            </p>
                            <div class="mt-auto pt-2 border-top">
                                <a href="{{ route('suvichar.show', $rQuote->id) }}" class="text-terracotta fw-bold small text-decoration-none">
                                    विस्तार से पढ़ें <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('suvichar.index') }}" class="btn-spiritual">
                    संपूर्ण सुविचार आर्काइव देखें <i class="fa-solid fa-quote-left ms-1"></i>
                </a>
            </div>
        </div>
    </section>
@endif

@endsection

@push('scripts')
<script>
    function copyQuoteToClipboard(quote, author, btnElement) {
        const textToCopy = `"${quote}"\n— ${author}\n\nसर्वोदय जिनधर्म: ${window.location.href}`;
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
