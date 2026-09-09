@props(['granth'])

@php
    $title = is_array($granth->title) ? ($granth->title['hi'] ?? $granth->title['en'] ?? '') : $granth->title;
    $author = is_array($granth->author) ? ($granth->author['hi'] ?? $granth->author['en'] ?? '') : $granth->author;
    $excerpt = is_array($granth->short_description) ? ($granth->short_description['hi'] ?? $granth->short_description['en'] ?? '') : $granth->short_description;
    $image = $granth->featured_image ?: asset('images/jain/granth_manuscript.jpg');
    $slug = $granth->slug ?: $granth->id;
    $rawPdfs = !empty($granth->pdfs) ? (array)$granth->pdfs : [];
    $rawFirstPdf = count($rawPdfs) > 0 ? $rawPdfs[0] : null;
    $pdfUrl = is_array($rawFirstPdf) ? ($rawFirstPdf['url'] ?? null) : $rawFirstPdf;
    $hasPdf = !empty($pdfUrl);
@endphp

<div class="spiritual-card">
    <div class="card-img-wrap" style="height: 220px;">
        <img src="{{ $image }}" alt="{{ $title }}" loading="lazy">
        <span class="card-badge-top">
            <i class="fa-solid fa-book-open text-warning me-1"></i> पवित्र शास्त्र
        </span>
        @if($hasPdf)
            <span class="card-badge-right" style="background: #b91c1c;">
                <i class="fa-solid fa-file-pdf me-1"></i> PDF उपलब्ध
            </span>
        @endif
    </div>
    <div class="card-content">
        @if($author)
            <div class="text-muted small mb-1">
                <i class="fa-solid fa-pen-nib text-warning me-1"></i> रचयिता: <strong>{{ $author }}</strong>
            </div>
        @endif
        <h4 class="mb-2 font-spiritual" style="font-size: 18px; line-height: 1.35;">
            <a href="{{ route('granths.show', $slug) }}" class="text-dark">{{ $title }}</a>
        </h4>
        @if($excerpt)
            <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                {{ $excerpt }}
            </p>
        @endif
        <div class="mt-auto pt-2 border-top d-flex align-items-center justify-content-between gap-2">
            <a href="{{ route('granths.show', $slug) }}" class="btn btn-sm btn-spiritual-outline py-1 px-3 flex-grow-1 text-center" style="font-size: 13px;">
                <i class="fa-solid fa-book-reader me-1"></i> स्वाध्याय करें
            </a>
            @if($pdfUrl)
                <a href="{{ $pdfUrl }}" target="_blank" class="btn btn-sm btn-outline-danger py-1 px-2" title="PDF डाउनलोड करें">
                    <i class="fa-solid fa-download"></i>
                </a>
            @endif
        </div>
    </div>
</div>
