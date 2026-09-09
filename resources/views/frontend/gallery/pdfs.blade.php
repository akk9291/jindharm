@extends('layouts.app')

@section('title', 'PDF ग्रन्थ, स्तोत्र एवं पत्रिका दीर्घा | जिनधर्म ई-लाइब्रेरी')
@section('meta_description', 'जैन धर्म के पावन ग्रन्थ, स्तोत्र, पाठ, पंचांग, चातुर्मास पत्रिका एवं धार्मिक पत्रिकाओं का डिजिटल PDF संग्रह। डाउनलोड करें अथवा ऑनलाइन पढ़ें।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'PDF ग्रन्थ व पत्रिकाएं', 'url' => '']]" />

    <div class="container pb-5">
        <!-- Top Section Header & Tab Controls -->
        <div class="row align-items-center justify-content-between mb-4 g-3">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill fw-semibold" style="font-size: 12px;">
                        <i class="fa-solid fa-file-pdf me-1"></i> डिजिटल ई-पुस्तकालय (PDF E-Library)
                    </span>
                </div>
                <h1 class="font-spiritual mb-1" style="font-size: 30px;">PDF ग्रन्थ व पत्रिका दीर्घा</h1>
                <p class="text-muted small mb-0">जिनवाणी के मूल ग्रन्थ, स्तोत्र संग्रह, पंचांग तालिकाएं एवं संघ पत्रिकाएं PDF रूप में प्राप्त करें।</p>
            </div>
            <div class="col-md-6 d-flex flex-wrap justify-content-md-end gap-2">
                <a href="{{ route('gallery.photos') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa-solid fa-folder-open me-1"></i> चित्र एल्बम
                </a>
                <a href="{{ route('gallery.videos') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa-solid fa-video me-1"></i> वीडियो
                </a>
                <a href="{{ route('gallery.pdfs') }}" class="btn btn-sm btn-spiritual rounded-pill px-3">
                    <i class="fa-solid fa-file-pdf me-1"></i> PDF ग्रन्थ व पत्रिका ({{ $pdfs->total() }})
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="card border-0 shadow-sm rounded-4 p-3.5 mb-4 bg-white">
            <form action="{{ route('gallery.pdfs') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 text-muted ps-3">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="ग्रन्थ, स्तोत्र, लेखक या पत्रिका का नाम खोजें..." value="{{ request('search') }}">
                        @if(request('search') || request('type'))
                            <a href="{{ route('gallery.pdfs') }}" class="btn btn-light border-0 text-muted" title="फ़िल्टर हटाएं">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <select name="type" class="form-select bg-light border-0" onchange="this.form.submit()">
                        <option value="">सभी श्रेणियां (All Types)</option>
                        <option value="granth" {{ request('type') == 'granth' ? 'selected' : '' }}>📖 ग्रन्थ (Scriptures)</option>
                        <option value="bhajan" {{ request('type') == 'bhajan' ? 'selected' : '' }}>🎶 स्तोत्र व पाठ (Stotras)</option>
                        <option value="pravachan" {{ request('type') == 'pravachan' ? 'selected' : '' }}>🎙️ प्रवचन साहित्य (Pravachan)</option>
                        <option value="news" {{ request('type') == 'news' ? 'selected' : '' }}>📰 पत्रिका व समाचार (Magazines)</option>
                    </select>
                    <button type="submit" class="btn btn-spiritual px-4 text-nowrap">
                        खोजें
                    </button>
                </div>
            </form>
        </div>

        @if($pdfs->isNotEmpty())
            <!-- PDF Items Grid -->
            <div class="row g-4">
                @foreach($pdfs as $item)
                    @php
                        $title = $item->getLocalized('title');
                        $author = $item->getLocalized('author');
                        $desc = $item->getLocalized('short_description') ?: $item->getLocalized('full_description');
                        
                        $rawFiles = $item->pdfs ?: $item->pdf_files;
                        $files = is_array($rawFiles) ? $rawFiles : json_decode($rawFiles, true);
                        if (!is_array($files) || empty($files)) {
                            $files = [['title' => $title, 'url' => is_string($rawFiles) ? $rawFiles : '#', 'size' => 'PDF']];
                        }
                        
                        $primaryFile = $files[0] ?? null;
                        if (is_string($primaryFile)) {
                            $primaryFile = ['title' => $title, 'url' => $primaryFile, 'size' => 'PDF'];
                        }
                        
                        $fileUrl = $primaryFile['url'] ?? '#';
                        $fileTitle = $primaryFile['title'] ?? $title;
                        $fileSize = $primaryFile['size'] ?? 'PDF Document';
                        $typeName = $item->contentType ? $item->contentType->getLocalized('name') : 'ई-पुस्तिका';
                    @endphp

                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden pdf-item-card" style="transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
                            <!-- Top Decorative PDF Header -->
                            <div class="p-4 position-relative text-white" style="background: linear-gradient(135deg, #c2410c 0%, #85380c 100%);">
                                <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                    <span class="badge bg-white text-dark shadow-sm rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11.5px;">
                                        <i class="fa-solid fa-book text-danger me-1"></i> {{ $typeName }}
                                    </span>
                                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1.5 small fw-bold shadow-sm" style="font-size: 11.5px;">
                                        <i class="fa-solid fa-circle-down me-1"></i> {{ $fileSize }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-3 mt-3">
                                    <div class="pdf-icon-circle shadow-sm">
                                        <i class="fa-solid fa-file-pdf fa-2x text-danger"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-spiritual text-white mb-1 line-clamp-1" title="{{ $title }}" style="font-size: 17px;">
                                            {{ $title }}
                                        </h5>
                                        <p class="text-white text-opacity-80 small mb-0 line-clamp-1">
                                            <i class="fa-solid fa-feather-pointed me-1"></i> {{ $author ?: 'जिनवाणी प्रकाशन' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- PDF Details Body -->
                            <div class="card-body p-3.5 d-flex flex-column justify-content-between bg-white">
                                <div>
                                    @if($desc)
                                        <p class="text-muted small mb-3 line-clamp-2" style="font-size: 13px; line-height: 1.55;">
                                            {{ Str::limit(strip_tags($desc), 120) }}
                                        </p>
                                    @else
                                        <p class="text-muted small mb-3" style="font-size: 13px;">
                                            पावन जिनवाणी का शुद्ध एवं प्रामाणिक PDF संस्करण अध्ययन एवं स्वाध्याय हेतु उपलब्ध है।
                                        </p>
                                    @endif

                                    @if(count($files) > 1)
                                        <div class="mb-3 p-2 rounded-3 bg-light border">
                                            <span class="badge bg-secondary rounded-pill me-1">{{ count($files) }} संचिकाएं</span>
                                            <span class="small text-muted">इस ग्रन्थ में एकाधिक PDF संलग्न हैं।</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                                    <!-- Read Online Modal Trigger -->
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold flex-fill btn-read-pdf"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#pdfPreviewModal" 
                                            data-pdf-url="{{ $fileUrl }}" 
                                            data-pdf-title="{{ $title }}">
                                        <i class="fa-solid fa-book-open me-1"></i> पढ़ें (Read)
                                    </button>

                                    <!-- Download Button -->
                                    <a href="{{ $fileUrl }}" 
                                       download 
                                       class="btn btn-sm btn-spiritual rounded-pill px-3 fw-semibold flex-fill text-center">
                                        <i class="fa-solid fa-download me-1"></i> डाउनलोड
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <x-pagination :paginator="$pdfs" />
            </div>
        @else
            <x-empty-state 
                title="कोई PDF पुस्तिका उपलब्ध नहीं है" 
                message="आपकी खोज के अनुसार कोई PDF प्राप्त नहीं हुआ। कृपया अन्य शब्द खोजें।"
                icon="fa-file-pdf"
            />
        @endif
    </div>

    <!-- PDF Online Reader Modal -->
    <div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 90vw;">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <div class="modal-header bg-dark text-white border-0 py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-file-pdf text-danger fa-lg"></i>
                        <h5 class="modal-title font-spiritual mb-0" id="pdfModalLabel" style="font-size: 18px;">
                            PDF वाचक (PDF Reader)
                        </h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a id="modalDownloadBtn" href="#" download class="btn btn-sm btn-warning fw-semibold rounded-pill px-3">
                            <i class="fa-solid fa-download me-1"></i> डाउनलोड
                        </a>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-0" style="height: 80vh; background: #525659;">
                    <iframe id="pdfIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const previewModal = document.getElementById('pdfPreviewModal');
            const iframe = document.getElementById('pdfIframe');
            const modalTitle = document.getElementById('pdfModalLabel');
            const downloadBtn = document.getElementById('modalDownloadBtn');

            if (previewModal) {
                previewModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const pdfUrl = button.getAttribute('data-pdf-url');
                    const pdfTitle = button.getAttribute('data-pdf-title');

                    modalTitle.textContent = pdfTitle || 'PDF वाचक';
                    downloadBtn.setAttribute('href', pdfUrl);
                    iframe.src = pdfUrl;
                });

                previewModal.addEventListener('hidden.bs.modal', function() {
                    iframe.src = '';
                });
            }
        });
    </script>
    @endpush

    <style>
        .pdf-item-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(133, 56, 12, 0.12) !important;
        }
        .pdf-icon-circle {
            width: 52px;
            height: 52px;
            background: #ffffff;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
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
    </style>
@endsection
