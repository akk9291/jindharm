@extends('layouts.app')

@php
    $albumName = $album->getLocalized('name');
    $albumCategory = is_array($album->category) ? ($album->category['hi'] ?? $album->category['en'] ?? '') : ($album->category ?: 'तीर्थ दर्शन');
    $albumDesc = is_array($album->description) ? ($album->description['hi'] ?? $album->description['en'] ?? '') : $album->description;
@endphp

@section('title', $albumName . ' | चित्र दीर्घा (Photo Album)')
@section('meta_description', $albumDesc ?: $albumName . ' के पावन चित्र एवं संस्मरण।')

@push('styles')
    <!-- GLightbox CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <style>
        .photo-card-item {
            position: relative;
            border-radius: var(--radius-md);
            overflow: hidden;
            background: #ffffff;
            border: 1px solid var(--border-subtle);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .photo-card-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 36px rgba(133, 56, 12, 0.14);
            border-color: rgba(194, 132, 31, 0.5);
        }
        .photo-img-wrapper {
            position: relative;
            height: 250px;
            overflow: hidden;
            cursor: pointer;
        }
        .photo-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .photo-card-item:hover .photo-img-wrapper img {
            transform: scale(1.08);
        }
        .photo-hover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.65) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .photo-card-item:hover .photo-hover-overlay {
            opacity: 1;
        }
        .btn-view-lightbox {
            width: 48px;
            height: 48px;
            background: #ffffff;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
            transform: scale(0.8);
            transition: transform 0.3s ease, background-color 0.2s ease;
        }
        .photo-card-item:hover .btn-view-lightbox {
            transform: scale(1);
        }
        .btn-view-lightbox:hover {
            background: var(--primary);
            color: #ffffff;
        }
        /* Custom GLightbox Styling */
        .gslide-title {
            font-family: var(--font-serif);
            font-size: 18px !important;
            font-weight: 700 !important;
            color: #fef08a !important;
        }
        .gslide-desc {
            font-size: 14px !important;
            color: #e2e8f0 !important;
        }
    </style>
@endpush

@section('content')
    <x-breadcrumbs :items="[
        ['title' => 'चित्र दीर्घा (एल्बम)', 'url' => route('gallery.photos')],
        ['title' => $albumName, 'url' => '']
    ]" />

    <div class="container pb-5">
        <!-- Album Header Box -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: linear-gradient(135deg, #fffaf5 0%, #ffffff 100%); border-left: 5px solid var(--primary) !important;">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <a href="{{ route('gallery.photos') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa-solid fa-arrow-left me-1"></i> सभी एल्बम / फ़ोल्डर (All Albums)
                </a>
                <div class="d-flex align-items-center gap-2">
                    @if($albumCategory)
                        <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill fw-semibold">
                            <i class="fa-solid fa-folder me-1"></i> {{ $albumCategory }}
                        </span>
                    @endif
                    <span class="badge bg-dark text-white px-2.5 py-1.5 rounded-pill">
                        <i class="fa-solid fa-images me-1 text-warning"></i> {{ $album->photos->count() }} चित्र
                    </span>
                </div>
            </div>

            <h1 class="font-spiritual mb-2" style="font-size: 28px; color: var(--primary);">{{ $albumName }}</h1>
            @if($albumDesc)
                <p class="text-muted mb-0" style="font-size: 15px; max-width: 900px; line-height: 1.6;">
                    {{ $albumDesc }}
                </p>
            @endif
        </div>

        <!-- Photos Grid with Lightbox Trigger -->
        @if($album->photos->isNotEmpty())
            <div class="row g-4 mb-5">
                @foreach($album->photos as $photo)
                    @php
                        $caption = is_array($photo->caption) ? ($photo->caption['hi'] ?? $photo->caption['en'] ?? '') : ($photo->caption ?: $albumName);
                        $photoUrl = $photo->photo_path;
                    @endphp
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="photo-card-item">
                            <!-- GLightbox Link Anchor -->
                            <a href="{{ $photoUrl }}" class="glightbox photo-img-wrapper d-block text-decoration-none" data-gallery="album-{{ $album->id }}" data-title="{{ $caption }}" data-description="{{ $albumName }}">
                                <img src="{{ $photoUrl }}" alt="{{ $caption }}" loading="lazy">
                                <div class="photo-hover-overlay">
                                    <div class="btn-view-lightbox" title="बड़ा चित्र देखें (Lightbox)">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </div>
                                </div>
                            </a>
                            @if($caption)
                                <div class="p-3 bg-white border-top">
                                    <p class="mb-0 text-dark small fw-semibold text-truncate" title="{{ $caption }}">
                                        <i class="fa-solid fa-image text-warning me-1"></i> {{ $caption }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-empty-state 
                title="इस एल्बम में कोई चित्र उपलब्ध नहीं है" 
                message="शीघ्र ही इस फ़ोल्डर में चित्र अपलोड किए जाएंगे।"
                icon="fa-images"
            />
        @endif

        <!-- Other Albums Section -->
        @if($otherAlbums->isNotEmpty())
            <div class="pt-4 border-top">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h3 class="font-spiritual mb-0" style="font-size: 20px;">
                        <i class="fa-solid fa-folder-open text-warning me-2"></i> अन्य चित्र एल्बम (Other Folders)
                    </h3>
                    <a href="{{ route('gallery.photos') }}" class="btn btn-sm btn-link text-primary text-decoration-none fw-semibold">
                        सभी देखें <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-3">
                    @foreach($otherAlbums as $other)
                        @php
                            $oName = $other->getLocalized('name');
                            $oCover = $other->cover_image ?: '/images/jain/temple_shikhar.jpg';
                        @endphp
                        <div class="col-lg-3 col-md-6 col-6">
                            <a href="{{ route('gallery.album.show', $other->id) }}" class="d-block card border-0 shadow-sm rounded-3 overflow-hidden text-decoration-none text-dark h-100" style="transition: transform 0.2s ease;">
                                <div style="height: 120px; overflow: hidden; position: relative;">
                                    <img src="{{ $oCover }}" alt="{{ $oName }}" class="w-100 h-100 object-fit-cover">
                                    <span class="position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75 text-white" style="font-size: 10px;">
                                        <i class="fa-solid fa-images me-1 text-warning"></i> {{ $other->photos_count }} चित्र
                                    </span>
                                </div>
                                <div class="p-2.5 bg-white">
                                    <h6 class="font-spiritual mb-0 line-clamp-1 small" style="font-size: 13px;">{{ $oName }}</h6>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- GLightbox JS Bundle & Initialization -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                autoplayVideos: true,
                zoomable: true,
                openEffect: 'zoom',
                closeEffect: 'fade',
                slideEffect: 'slide'
            });
        });
    </script>
@endsection
