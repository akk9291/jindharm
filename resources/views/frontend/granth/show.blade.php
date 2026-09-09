@extends('layouts.app')

@php
    $title = is_array($granth->title) ? ($granth->title['hi'] ?? $granth->title['en'] ?? '') : $granth->title;
    $author = is_array($granth->author) ? ($granth->author['hi'] ?? $granth->author['en'] ?? '') : $granth->author;
    $desc = is_array($granth->full_description) ? ($granth->full_description['hi'] ?? $granth->full_description['en'] ?? '') : $granth->full_description;
    $short = is_array($granth->short_description) ? ($granth->short_description['hi'] ?? $granth->short_description['en'] ?? '') : $granth->short_description;
    $image = $granth->featured_image ?: asset('images/jain/granth_manuscript.jpg');

    // Parse all PDFs
    $rawPdfs = !empty($granth->pdfs) ? (array)$granth->pdfs : [];
    $pdfList = [];
    foreach ($rawPdfs as $idx => $p) {
        if (is_array($p) && !empty($p['url'])) {
            $pdfList[] = [
                'url' => $p['url'],
                'title' => !empty($p['title']) ? $p['title'] : 'भाग ' . ($idx + 1) . ' PDF'
            ];
        } elseif (is_string($p) && trim($p) !== '') {
            $pdfList[] = [
                'url' => $p,
                'title' => 'ग्रन्थ भाग ' . ($idx + 1) . ' PDF'
            ];
        }
    }
    $firstPdf = count($pdfList) > 0 ? $pdfList[0]['url'] : null;
@endphp

@section('title', $title . ' - स्वाध्याय एवं PDF डाउनलोड')
@section('meta_description', strip_tags(Str::limit($short ?: $desc, 150)))
@section('og_image', $image)

@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Book",
    "name": "{{ $title }}",
    "author": {
        "@type": "Person",
        "name": "{{ $author ?: 'आचार्य' }}"
    },
    "description": "{{ strip_tags($short ?: $desc) }}",
    "image": "{{ $image }}"
}
</script>
@endpush

@section('content')
    <x-breadcrumbs :items="[
        ['title' => 'ज्ञान भण्डार', 'url' => route('granths.index')],
        ['title' => $title, 'url' => '']
    ]" />

    <div class="container pb-5">
        <div class="row g-4">
            <!-- Book Overview Column -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white position-sticky" style="top: 100px;">
                    <div class="rounded-3 overflow-hidden shadow mb-3 mx-auto" style="max-width: 240px;">
                        <img src="{{ $image }}" class="w-100 h-100 object-fit-cover" alt="{{ $title }}">
                    </div>

                    <h4 class="font-spiritual mb-1">{{ $title }}</h4>
                    @if($author)
                        <p class="text-muted small mb-3"><i class="fa-solid fa-pen-nib text-warning me-1"></i> रचयिता: <strong>{{ $author }}</strong></p>
                    @endif

                    <div class="d-grid gap-2 mb-3">
                        @foreach($pdfList as $pItem)
                            <a href="{{ $pItem['url'] }}" target="_blank" class="btn btn-spiritual">
                                <i class="fa-solid fa-file-pdf me-1"></i> {{ $pItem['title'] }} (डाउनलोड)
                            </a>
                        @endforeach
                        <a href="#reading-section" class="btn btn-spiritual-outline">
                            <i class="fa-solid fa-book-reader me-1"></i> ऑनलाइन स्वाध्याय करें
                        </a>
                    </div>

                    <div class="p-3 rounded text-start small bg-light">
                        <div class="mb-1"><strong>भाषा:</strong> संस्कृत / प्राकृत / हिन्दी टीका</div>
                        <div class="mb-1"><strong>विषय:</strong> जैन सिद्धान्त एवं मोक्षमार्ग</div>
                        <div><strong>प्रकार:</strong> प्रामाणिक मूल शास्त्र</div>
                    </div>
                </div>
            </div>

            <!-- Content & Scripture Text Column -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white mb-4" id="reading-section">
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-4 border-bottom">
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-scroll me-1"></i> प्रामाणिक शास्त्र
                        </span>
                        <div class="text-muted small">
                            <i class="fa-solid fa-eye me-1"></i> निःशुल्क स्वाध्याय
                        </div>
                    </div>

                    <h1 class="font-spiritual mb-3" style="font-size: 30px;">{{ $title }}</h1>

                    @if($short)
                        <div class="p-3 rounded-3 mb-4" style="background: var(--bg-warm-tint); border-left: 4px solid var(--gold);">
                            <p class="mb-0 text-dark fw-medium">{{ $short }}</p>
                        </div>
                    @endif

                    <div class="content-body" style="font-size: 16.5px; line-height: 1.9;">
                        {!! $desc !!}
                    </div>

                    <!-- PDF Viewer embed if available -->
                    @if($pdfUrl)
                        <div class="mt-5 pt-4 border-top">
                            <h4 class="font-spiritual mb-3"><i class="fa-solid fa-file-lines text-danger me-2"></i> ग्रन्थ पीडीएफ पूर्वावलोकन (PDF Viewer)</h4>
                            <div class="ratio ratio-4x3 border rounded-3 overflow-hidden shadow-sm">
                                <iframe src="{{ $pdfUrl }}" title="{{ $title }} PDF"></iframe>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Related Scriptures -->
                @if($related->isNotEmpty())
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h5 class="font-spiritual border-bottom pb-2 mb-3"><i class="fa-solid fa-book text-warning me-2"></i> अन्य पवित्र ग्रन्थ</h5>
                        <div class="row g-3">
                            @foreach($related as $rel)
                                <div class="col-md-4">
                                    <x-granth-card :granth="$rel" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
