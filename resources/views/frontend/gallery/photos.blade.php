@extends('layouts.app')

@section('title', 'चित्र दीर्घा एल्बम (Photo Albums) | तीर्थ दर्शन एवं संत संस्मरण')
@section('meta_description', 'सिद्धक्षेत्र, तीर्थ वंदना, पूज्य संत दर्शन, चातुर्मास कलश स्थापना एवं धार्मिक आयोजनों के पावन चित्र एल्बम संग्रह।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'चित्र दीर्घा (एल्बम संग्रह)', 'url' => '']]" />

    <div class="container pb-5">
        <!-- Top Section Header & Tab Controls -->
        <div class="row align-items-center justify-content-between mb-4 g-3">
            <div class="col-md-7">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size: 12px;">
                        <i class="fa-solid fa-folder-open me-1"></i> फ़ोल्डर व एल्बम संग्रह
                    </span>
                </div>
                <h1 class="font-spiritual mb-1" style="font-size: 30px;">चित्र दीर्घा (Photo Albums)</h1>
                <p class="text-muted small mb-0">पावन तीर्थों, जिनालयों एवं पूज्य संत संघ के विहंगम चित्र एल्बम (फ़ोल्डर)। किसी भी एल्बम पर क्लिक कर सभी चित्र देखें।</p>
            </div>
            <div class="col-md-5 d-flex flex-wrap justify-content-md-end gap-2">
                <a href="{{ route('gallery.photos') }}" class="btn btn-sm btn-spiritual rounded-pill px-3"><i class="fa-solid fa-folder-open me-1"></i> चित्र एल्बम ({{ $albums->total() }})</a>
                <a href="{{ route('gallery.videos') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fa-solid fa-video me-1"></i> वीडियो</a>
                <a href="{{ route('gallery.pdfs') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fa-solid fa-file-pdf me-1"></i> PDF ग्रन्थ व पत्रिका</a>
            </div>
        </div>

        @if($albums->isNotEmpty())
            <!-- Album Folders Grid -->
            <div class="row g-4">
                @foreach($albums as $album)
                    @php
                        $albumName = $album->getLocalized('name');
                        $albumCategory = is_array($album->category) ? ($album->category['hi'] ?? $album->category['en'] ?? '') : ($album->category ?: 'चित्र संग्रह');
                        $albumDesc = is_array($album->description) ? ($album->description['hi'] ?? $album->description['en'] ?? '') : $album->description;
                        $cover = $album->cover_image ?: '/images/jain/temple_shikhar.jpg';
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4 overflow-hidden album-folder-card" style="transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);">
                            <!-- Album Cover with Folder Style Badge -->
                            <a href="{{ route('gallery.album.show', $album->id) }}" class="d-block position-relative text-decoration-none overflow-hidden" style="height: 220px;">
                                <img src="{{ $cover }}" alt="{{ $albumName }}" class="w-100 h-100 object-fit-cover album-cover-zoom" style="transition: transform 0.5s ease;">
                                
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.6) 100%);"></div>

                                <!-- Top Badges -->
                                <div class="position-absolute top-0 start-0 p-3 d-flex align-items-center gap-2">
                                    <span class="badge bg-warning text-dark shadow-sm px-2.5 py-1.5 rounded-pill" style="font-size: 11.5px; font-weight: 600;">
                                        <i class="fa-solid fa-folder me-1"></i> {{ $albumCategory }}
                                    </span>
                                </div>

                                <!-- Photo Count Badge Bottom Right -->
                                <div class="position-absolute bottom-0 end-0 p-3">
                                    <span class="badge bg-dark bg-opacity-75 text-white border border-white border-opacity-25 px-2.5 py-1.5 rounded-pill shadow-sm" style="font-size: 12px; backdrop-filter: blur(4px);">
                                        <i class="fa-solid fa-images me-1 text-warning"></i> {{ $album->photos_count }} चित्र
                                    </span>
                                </div>
                            </a>

                            <!-- Album Body Content -->
                            <div class="card-body p-3.5 d-flex flex-column justify-content-between bg-white">
                                <div>
                                    <h5 class="font-spiritual mb-2 line-clamp-1" style="font-size: 17px;">
                                        <a href="{{ route('gallery.album.show', $album->id) }}" class="text-dark text-decoration-none hover-primary">
                                            {{ $albumName }}
                                        </a>
                                    </h5>
                                    @if($albumDesc)
                                        <p class="text-muted small mb-3 line-clamp-2" style="font-size: 13px; line-height: 1.5;">
                                            {{ $albumDesc }}
                                        </p>
                                    @endif
                                </div>

                                <div class="pt-2 border-top d-flex align-items-center justify-content-between">
                                    <span class="text-muted small" style="font-size: 12px;">
                                        <i class="fa-regular fa-clock me-1"></i> {{ $album->created_at ? $album->created_at->format('d M, Y') : 'नवीनतम' }}
                                    </span>
                                    <a href="{{ route('gallery.album.show', $album->id) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold" style="font-size: 12px;">
                                        फ़ोल्डर खोलें <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                <x-pagination :paginator="$albums" />
            </div>
        @else
            <x-empty-state 
                title="कोई एल्बम उपलब्ध नहीं है" 
                message="शीघ्र ही नए चित्र एल्बम यहाँ अपलोड किए जाएंगे।"
                icon="fa-folder-open"
            />
        @endif
    </div>

    <style>
        .album-folder-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(133, 56, 12, 0.12) !important;
        }
        .album-folder-card:hover .album-cover-zoom {
            transform: scale(1.08);
        }
        .hover-primary:hover {
            color: var(--primary) !important;
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
