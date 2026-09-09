@extends('layouts.app')

@section('title', 'दैनिक जैन सुविचार एवं अमृत वचन आर्काइव (Daily Jain Quotes)')
@section('meta_description', 'पूज्य दिगम्बर मुनि संघ, तीर्थंकर वाणी एवं आचार्यों के पावन आध्यात्मिक अमृत वचन व दैनिक सुविचार संग्रह।')

@section('content')

<!-- 1. Breadcrumbs -->
<x-breadcrumbs :items="[
    ['title' => 'दैनिक सुविचार आर्काइव', 'url' => '']
]" />

<!-- 2. Hero Header Section -->
<section class="py-5 text-center text-white position-relative" style="background: linear-gradient(135deg, #3d1704 0%, #1a0801 100%); border-bottom: 3px solid var(--gold);">
    <div class="container py-3">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow" style="width: 65px; height: 65px; background: rgba(194, 132, 31, 0.25); color: #fef08a; font-size: 28px; border: 1.5px solid rgba(194, 132, 31, 0.5);">
            <i class="fa-solid fa-quote-left"></i>
        </div>
        <h1 class="font-spiritual text-white mb-2" style="font-size: 36px;">
            दैनिक सुविचार एवं अमृत वचन
        </h1>
        <p class="mx-auto text-light opacity-85 mb-0" style="max-width: 680px; font-size: 16.5px; line-height: 1.6;">
            जिनवाणी और पूज्य आचार्यों के दिव्य वचनों का प्रामाणिक डिजिटल संग्रह। प्रतिदिन आत्मचिंतन, सदाचार एवं धर्म लाभ प्राप्त करें।
        </p>
    </div>
</section>

<!-- 3. Featured / Today's Spotlight Quote -->
@if($featuredQuote)
    @php
        $fTitle = $featuredQuote->display_title;
        $fText = $featuredQuote->display_text;
        $fAuthor = $featuredQuote->display_author;
        $fImg = $featuredQuote->image;
        $fWaText = urlencode("✨ " . $fTitle . " ✨\n\n\"" . $fText . "\"\n— " . $fAuthor . "\n\nपूरा सुविचार संग्रह देखें: " . route('suvichar.show', $featuredQuote->id));
    @endphp
    <section class="py-5" style="background: var(--bg-warm-tint); border-bottom: 1px solid var(--gold-border);">
        <div class="container">
            <div class="text-center mb-4">
                <span class="badge" style="background: rgba(194, 132, 31, 0.15); color: var(--terracotta); border: 1px solid var(--gold-border); font-size: 13px; padding: 6px 16px; border-radius: 20px;">
                    <i class="fa-solid fa-star text-warning me-1"></i> आज का प्रमुख आध्यात्मिक संदेश (Today's Spotlight)
                </span>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="daily-suvichar-card">
                        <div class="row g-0 align-items-stretch">
                            <!-- Left: Image -->
                            <div class="col-md-5 col-lg-4">
                                <div class="suvichar-img-wrap" data-bs-toggle="modal" data-bs-target="#featuredModal" title="बड़ा चित्र देखें">
                                    <img src="{{ $fImg }}" alt="{{ $fAuthor }}">
                                    <div class="suvichar-img-overlay">
                                        <span class="badge bg-dark bg-opacity-75 text-light small">
                                            <i class="fa-solid fa-expand me-1"></i> बड़ा चित्र देखें
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Text & Actions -->
                            <div class="col-md-7 col-lg-8">
                                <div class="suvichar-body h-100">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                        <div class="suvichar-badge mb-0">
                                            <i class="fa-solid fa-sparkles"></i> {{ $fTitle }}
                                        </div>
                                        <span class="text-muted small">
                                            <i class="fa-regular fa-calendar me-1"></i> {{ $featuredQuote->quote_date ? $featuredQuote->quote_date->format('d M, Y') : 'आज का संदेश' }}
                                        </span>
                                    </div>

                                    <div class="suvichar-quote-text" style="font-size: 24px;">
                                        "{{ $fText }}"
                                    </div>

                                    <div class="suvichar-author">
                                        {{ $fAuthor }}
                                    </div>

                                    <div class="suvichar-actions mt-auto">
                                        <a href="https://api.whatsapp.com/send?text={{ $fWaText }}" target="_blank" class="suvichar-btn-whatsapp" title="WhatsApp पर शेयर करें">
                                            <i class="fa-brands fa-whatsapp fs-6"></i> WhatsApp शेयर
                                        </a>

                                        <a href="{{ $fImg }}" download="jain_suvichar_{{ $featuredQuote->id }}.jpg" class="suvichar-btn-action" title="सुविचार चित्र डाउनलोड करें">
                                            <i class="fa-solid fa-download text-primary"></i> चित्र डाउनलोड
                                        </a>

                                        <button type="button" class="suvichar-btn-action" onclick="copySuvicharText('{{ addslashes($fText) }}', '{{ addslashes($fAuthor) }}', this)" title="वचन कॉपी करें">
                                            <i class="fa-regular fa-copy text-secondary"></i> <span>कॉपी करें</span>
                                        </button>

                                        <a href="{{ route('suvichar.show', $featuredQuote->id) }}" class="suvichar-btn-action" title="विस्तार से देखें">
                                            <i class="fa-solid fa-arrow-right text-warning"></i> पूर्ण विवरण
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for Featured Quote -->
    <div class="modal fade" id="featuredModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #1c0d02;">
                <div class="modal-header border-0 pb-0 text-white">
                    <h6 class="modal-title font-spiritual text-white">
                        <i class="fa-solid fa-hands-praying text-warning me-2"></i> {{ $fTitle }}
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-3">
                    <img src="{{ $fImg }}" class="img-fluid rounded-3 shadow mb-3" style="max-height: 70vh; object-fit: contain;" alt="{{ $fAuthor }}">
                    <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.08); color: #fff;">
                        <p class="font-spiritual fs-5 mb-1 text-warning">"{{ $fText }}"</p>
                        <small class="text-light opacity-75">— {{ $fAuthor }}</small>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <a href="{{ $fImg }}" download="jain_suvichar.jpg" class="btn btn-sm btn-spiritual">
                        <i class="fa-solid fa-download me-1"></i> चित्र डाउनलोड करें
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ $fWaText }}" target="_blank" class="btn btn-sm suvichar-btn-whatsapp">
                        <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp शेयर
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- 4. Filter & Search Toolbar -->
<section class="py-4 bg-white border-bottom shadow-sm sticky-top" style="top: 70px; z-index: 99;">
    <div class="container">
        <form action="{{ route('suvichar.index') }}" method="GET" class="row g-3 align-items-center justify-content-between">
            <!-- Category Pills -->
            <div class="col-lg-7 col-md-12">
                <div class="d-flex align-items-center gap-2 overflow-auto pb-1" style="white-space: nowrap;">
                    <a href="{{ route('suvichar.index') }}" class="btn btn-sm {{ !request('category') ? 'btn-spiritual' : 'btn-spiritual-outline' }}">
                        सभी सुविचार (All)
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('suvichar.index', ['category' => $cat]) }}" class="btn btn-sm {{ request('category') == $cat ? 'btn-spiritual' : 'btn-spiritual-outline' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Search & Date Filter -->
            <div class="col-lg-5 col-md-12">
                <div class="d-flex gap-2">
                    <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm" style="max-width: 140px; border-color: var(--gold-border);">
                    <div class="input-group input-group-sm flex-grow-1">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="सुविचार या संत खोजें..." style="border-color: var(--gold-border);">
                        <button class="btn btn-spiritual" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    @if(request()->hasAny(['category', 'date', 'q']))
                        <a href="{{ route('suvichar.index') }}" class="btn btn-sm btn-outline-secondary" title="फ़िल्टर हटाएं">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>

<!-- 5. Quotes Grid Archive -->
<section class="py-5" style="background: var(--bg-warm-tint);">
    <div class="container">
        <div class="row g-4">
            @forelse($quotes as $quote)
                @php
                    $qTitle = $quote->display_title;
                    $qText = $quote->display_text;
                    $qAuthor = $quote->display_author;
                    $qImg = $quote->image;
                    $qDate = $quote->quote_date ? $quote->quote_date->format('d M, Y') : '';
                    $qWaText = urlencode("✨ " . $qTitle . " ✨\n\n\"" . $qText . "\"\n— " . $qAuthor . "\n\nधर्म लाभ हेतु देखें: " . route('suvichar.show', $quote->id));
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="spiritual-card h-100 d-flex flex-column" style="border-radius: var(--radius-lg); overflow: hidden; border: 1.5px solid var(--gold-border); background: #ffffff; box-shadow: var(--shadow-soft);">
                        <!-- Card Top Image -->
                        <div class="card-img-wrap position-relative" style="height: 220px; overflow: hidden; background: #2b1104;">
                            <img src="{{ $qImg }}" alt="{{ $qAuthor }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover;">
                            
                            @if($quote->category)
                                <span class="card-badge-top" style="background: var(--terracotta); color: #fff; font-weight: 600;">
                                    <i class="fa-solid fa-tag me-1"></i> {{ $quote->category }}
                                </span>
                            @endif

                            @if($qDate)
                                <span class="card-badge-right" style="background: rgba(0,0,0,0.7); color: #fff; font-size: 11.5px;">
                                    <i class="fa-regular fa-calendar me-1"></i> {{ $qDate }}
                                </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="card-content d-flex flex-column flex-grow-1 p-4">
                            <h5 class="font-spiritual text-terracotta mb-2" style="font-size: 18px; line-height: 1.5; min-height: 54px;">
                                <a href="{{ route('suvichar.show', $quote->id) }}" class="text-dark text-decoration-none">
                                    "{{ Str::limit($qText, 90) }}"
                                </a>
                            </h5>

                            <div class="d-flex align-items-center gap-2 mb-3 mt-1">
                                <i class="fa-solid fa-feather-pointed text-warning"></i>
                                <span class="fw-bold small text-muted">{{ $qAuthor }}</span>
                            </div>

                            <!-- Actions Row -->
                            <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between gap-1 flex-wrap">
                                <a href="https://api.whatsapp.com/send?text={{ $qWaText }}" target="_blank" class="btn btn-sm btn-success py-1 px-2" style="font-size: 12px; background: #25D366; border: none;" title="WhatsApp शेयर">
                                    <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                </a>

                                <a href="{{ $qImg }}" download="jain_suvichar_{{ $quote->id }}.jpg" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 12px;" title="चित्र डाउनलोड करें">
                                    <i class="fa-solid fa-download text-primary"></i> डाउनलोड
                                </a>

                                <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 12px;" onclick="copySuvicharText('{{ addslashes($qText) }}', '{{ addslashes($qAuthor) }}', this)" title="वचन कॉपी करें">
                                    <i class="fa-regular fa-copy"></i>
                                </button>

                                <a href="{{ route('suvichar.show', $quote->id) }}" class="btn btn-sm btn-spiritual-outline py-1 px-2" style="font-size: 12px;" title="पूरा पढ़ें">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <x-empty-state 
                        title="कोई सुविचार नहीं मिला" 
                        message="चयनित श्रेणी या खोज के लिए कोई सुविचार उपलब्ध नहीं है। कृपया दूसरा फ़िल्टर चुनें।"
                    />
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($quotes->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    function copySuvicharText(quote, author, btnElement) {
        const textToCopy = `"${quote}"\n— ${author}\n\nसर्वोदय जिनधर्म: ${window.location.origin}`;
        navigator.clipboard.writeText(textToCopy).then(() => {
            const originalHTML = btnElement.innerHTML;
            btnElement.innerHTML = `<i class="fa-solid fa-check text-success"></i>`;
            setTimeout(() => {
                btnElement.innerHTML = originalHTML;
            }, 2000);
        }).catch(err => {
            alert('वचन कॉपी किया गया!');
        });
    }
</script>
@endpush
