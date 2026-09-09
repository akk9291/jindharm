@extends('layouts.app')

@php
    $title = is_array($pravachan->title) ? ($pravachan->title['hi'] ?? $pravachan->title['en'] ?? '') : $pravachan->title;
    $author = is_array($pravachan->author) ? ($pravachan->author['hi'] ?? $pravachan->author['en'] ?? '') : $pravachan->author;
    $desc = is_array($pravachan->full_description) ? ($pravachan->full_description['hi'] ?? $pravachan->full_description['en'] ?? '') : $pravachan->full_description;
    $short = is_array($pravachan->short_description) ? ($pravachan->short_description['hi'] ?? $pravachan->short_description['en'] ?? '') : $pravachan->short_description;
    $image = $pravachan->featured_image ?: asset('images/jain/muni_pravachan.jpg');

    // Parse all videos
    $rawVideos = !empty($pravachan->videos) ? (array)$pravachan->videos : [];
    $videoList = [];
    foreach ($rawVideos as $idx => $v) {
        if (is_array($v) && !empty($v['url'])) {
            $videoList[] = [
                'type' => $v['type'] ?? (preg_match('/(?:youtube|youtu\.be)/', $v['url']) ? 'youtube' : 'self_hosted'),
                'url' => $v['url'],
                'title' => !empty($v['title']) ? $v['title'] : 'भाग ' . ($idx + 1)
            ];
        } elseif (is_string($v) && trim($v) !== '') {
            $videoList[] = [
                'type' => preg_match('/(?:youtube|youtu\.be)/', $v) ? 'youtube' : 'self_hosted',
                'url' => $v,
                'title' => 'भाग ' . ($idx + 1)
            ];
        }
    }

    // Parse all audios
    $rawAudios = !empty($pravachan->audios) ? (array)$pravachan->audios : [];
    $audioList = [];
    foreach ($rawAudios as $idx => $a) {
        if (is_array($a) && !empty($a['url'])) {
            $audioList[] = [
                'url' => $a['url'],
                'title' => !empty($a['title']) ? $a['title'] : 'ऑडियो भाग ' . ($idx + 1)
            ];
        } elseif (is_string($a) && trim($a) !== '') {
            $audioList[] = [
                'url' => $a,
                'title' => 'ऑडियो भाग ' . ($idx + 1)
            ];
        }
    }

    // Parse all PDFs
    $rawPdfs = !empty($pravachan->pdfs) ? (array)$pravachan->pdfs : [];
    $pdfList = [];
    foreach ($rawPdfs as $idx => $p) {
        if (is_array($p) && !empty($p['url'])) {
            $pdfList[] = [
                'url' => $p['url'],
                'title' => !empty($p['title']) ? $p['title'] : 'संबंधित दस्तावेज़ ' . ($idx + 1) . '.pdf'
            ];
        } elseif (is_string($p) && trim($p) !== '') {
            $pdfList[] = [
                'url' => $p,
                'title' => 'संबंधित दस्तावेज़ ' . ($idx + 1) . '.pdf'
            ];
        }
    }

    // Parse gallery
    $rawGallery = !empty($pravachan->media_gallery) ? (array)$pravachan->media_gallery : [];
    $galleryList = [];
    foreach ($rawGallery as $g) {
        if (is_array($g) && !empty($g['url'])) {
            $galleryList[] = [
                'url' => $g['url'],
                'caption' => $g['caption'] ?? ''
            ];
        } elseif (is_string($g) && trim($g) !== '') {
            $galleryList[] = [
                'url' => $g,
                'caption' => ''
            ];
        }
    }

    $firstVideo = count($videoList) > 0 ? $videoList[0]['url'] : null;
    $firstAudio = count($audioList) > 0 ? $audioList[0]['url'] : null;
@endphp

@section('title', $title . ' - पूज्य ' . ($author ?: 'संत') . ' के प्रवचन')
@section('meta_description', strip_tags(Str::limit($short ?: $desc, 150)))
@section('og_image', $image)

@push('schema')
@if($firstVideo)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "VideoObject",
    "name": "{{ $title }}",
    "description": "{{ strip_tags($short ?: $desc) }}",
    "thumbnailUrl": "{{ $image }}",
    "uploadDate": "{{ $pravachan->publish_date }}",
    "contentUrl": "{{ $firstVideo }}"
}
</script>
@endif
@endpush

@section('content')
    <x-breadcrumbs :items="[
        ['title' => 'अमृत प्रवचन', 'url' => route('pravachans.index')],
        ['title' => $title, 'url' => '']
    ]" />

    <div class="container pb-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- 1. Multiple Videos Player / Switcher -->
                @if(count($videoList) > 0)
                    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 bg-white mb-4">
                        <div id="mainVideoPlayerWrapper">
                            <x-video-player :src="$videoList[0]['url']" :title="$videoList[0]['title']" :poster="$image" />
                        </div>
                        
                        @if(count($videoList) > 1)
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small fw-bold text-dark"><i class="fa-solid fa-list-ol text-danger me-1"></i> इस प्रवचन के अन्य भाग (All Video Parts):</span>
                                    <span class="badge bg-secondary">{{ count($videoList) }} भाग उपलब्ध</span>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($videoList as $vIdx => $vItem)
                                        <button type="button" class="btn btn-sm {{ $vIdx === 0 ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill video-switch-btn px-3" onclick="switchMainVideo('{{ $vItem['url'] }}', '{{ addslashes($vItem['title']) }}', this)">
                                            <i class="fa-solid fa-play me-1"></i> {{ $vItem['title'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif(count($audioList) > 0)
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center mb-4 bg-white">
                        <img src="{{ $image }}" class="rounded-4 mx-auto mb-3 shadow" style="max-width: 260px; height: 180px; object-fit: cover;" alt="{{ $title }}">
                        <h4 class="font-spiritual mb-1">{{ $title }}</h4>
                        <p class="text-muted small mb-3"><i class="fa-solid fa-user-tie text-warning me-1"></i> {{ $author ?: 'पूज्य संत' }}</p>
                        <x-audio-player :src="$audioList[0]['url']" :title="$audioList[0]['title']" />
                    </div>
                @endif

                    <!-- Tags -->
                    @if(!empty($pravachan->tags) && is_array($pravachan->tags))
                        <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2 align-items-center">
                            <span class="small text-muted"><i class="fa-solid fa-tags me-1"></i> संबंधित विषय:</span>
                            @foreach($pravachan->tags as $t)
                                <span class="badge bg-light text-dark border">{{ $t }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar: Related Pravachans -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="font-spiritual border-bottom pb-2 mb-3"><i class="fa-solid fa-microphone-lines text-danger me-2"></i> अन्य अमृत प्रवचन</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($related as $rel)
                            @php
                                $rTitle = is_array($rel->title) ? ($rel->title['hi'] ?? $rel->title['en']) : $rel->title;
                                $rAuthor = is_array($rel->author) ? ($rel->author['hi'] ?? $rel->author['en']) : $rel->author;
                            @endphp
                            <a href="{{ route('pravachans.show', $rel->slug ?: $rel->id) }}" class="d-flex align-items-center gap-3 text-dark text-decoration-none p-2 rounded hover-bg-light">
                                <div class="flex-shrink-0 rounded overflow-hidden" style="width: 70px; height: 65px;">
                                    <img src="{{ $rel->featured_image ?: asset('images/jain/muni_pravachan.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $rTitle }}">
                                </div>
                                <div>
                                    <h6 class="mb-1 font-spiritual" style="font-size: 14px; line-height: 1.3;">{{ $rTitle }}</h6>
                                    <small class="text-muted d-block" style="font-size: 11px;">{{ $rAuthor }}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Granth CTA -->
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center" style="background: var(--bg-warm-tint); border: 1.5px solid var(--gold-border) !important;">
                    <i class="fa-solid fa-book-open text-warning fs-2 mb-2"></i>
                    <h5 class="font-spiritual">शास्त्र स्वाध्याय करें</h5>
                    <p class="small text-muted mb-3">तत्त्वार्थ सूत्र एवं समयसार जैसे मूल आगम ग्रन्थों का प्रामाणिक अध्ययन करें।</p>
                    <a href="{{ route('granths.index') }}" class="btn btn-sm btn-spiritual">ज्ञान भण्डार खोलें</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function switchMainVideo(url, title, btn) {
            document.querySelectorAll('.video-switch-btn').forEach(b => {
                b.classList.remove('btn-danger');
                b.classList.add('btn-outline-secondary');
            });
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-danger');

            var wrapper = document.getElementById('mainVideoPlayerWrapper');
            if (!wrapper) return;

            // Check if YouTube
            var ytMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
            if (ytMatch && ytMatch[1]) {
                wrapper.innerHTML = `
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm border">
                        <iframe src="https://www.youtube.com/embed/${ytMatch[1]}?autoplay=1&rel=0&modestbranding=1" title="${title}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                `;
            } else {
                wrapper.innerHTML = `
                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm border">
                        <video controls autoplay class="w-100 h-100 object-fit-cover">
                            <source src="${url}" type="video/mp4">
                            आपका ब्राउज़र वीडियो का समर्थन नहीं करता है।
                        </video>
                    </div>
                `;
            }
        }
    </script>
    @endpush
@endsection
