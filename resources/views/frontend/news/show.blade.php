@extends('layouts.app')

@php
    $title = is_array($news->title) ? ($news->title['hi'] ?? $news->title['en'] ?? '') : $news->title;
    $author = is_array($news->author) ? ($news->author['hi'] ?? $news->author['en'] ?? '') : $news->author;
    $desc = is_array($news->full_description) ? ($news->full_description['hi'] ?? $news->full_description['en'] ?? '') : $news->full_description;
    $short = is_array($news->short_description) ? ($news->short_description['hi'] ?? $news->short_description['en'] ?? '') : $news->short_description;
    $image = $news->featured_image ?: asset('images/jain/temple_shikhar.jpg');
@endphp

@section('title', $title . ' - धर्म समाचार')
@section('meta_description', strip_tags(Str::limit($short ?: $desc, 150)))
@section('og_image', $image)

@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "{{ $title }}",
    "image": "{{ $image }}",
    "datePublished": "{{ $news->publish_date }}",
    "author": {
        "@type": "Person",
        "name": "{{ $author ?: 'संवाददाता' }}"
    },
    "description": "{{ strip_tags($short ?: $desc) }}"
}
</script>
@endpush

@section('content')
    <x-breadcrumbs :items="[
        ['title' => 'धर्म समाचार', 'url' => route('news.index')],
        ['title' => $title, 'url' => '']
    ]" />

    <div class="container pb-5">
        <div class="row g-4">
            <div class="col-lg-8">
                <article class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                    <img src="{{ $image }}" class="w-100 object-fit-cover" style="max-height: 440px;" alt="{{ $title }}">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-3 border-bottom">
                            <div>
                                <span class="badge bg-danger px-3 py-1 rounded-pill me-2">धर्म प्रभावना</span>
                                <span class="text-muted small"><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($news->publish_date)->format('d F, Y') }}</span>
                            </div>
                            <div class="text-muted small">
                                <i class="fa-solid fa-feather-pointed text-warning me-1"></i> रिपोर्ट: <strong>{{ $author ?: 'विशेष संवाददाता' }}</strong>
                            </div>
                        </div>

                        <h1 class="font-spiritual mb-3" style="font-size: 28px; line-height: 1.35;">{{ $title }}</h1>

                        @if($short)
                            <div class="p-3 rounded-3 mb-4" style="background: var(--bg-warm-tint); border-left: 4px solid var(--primary);">
                                <p class="mb-0 text-dark fw-medium">{{ $short }}</p>
                            </div>
                        @endif

                        <div class="content-body" style="font-size: 16.5px; line-height: 1.8;">
                            {!! $desc !!}
                        </div>

                        <!-- Share Buttons -->
                        <div class="mt-4 pt-3 border-top d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <span class="fw-semibold text-muted small"><i class="fa-solid fa-share-nodes me-1"></i> समाचार साझा करें:</span>
                            <div class="d-flex gap-2">
                                <a href="https://wa.me/?text={{ urlencode($title . ' ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-success rounded-pill px-3"><i class="fa-brands fa-whatsapp me-1"></i> WhatsApp</a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3"><i class="fa-brands fa-facebook-f me-1"></i> Facebook</a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar: Related News -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="font-spiritual border-bottom pb-2 mb-3"><i class="fa-solid fa-newspaper text-danger me-2"></i> अन्य प्रमुख समाचार</h5>
                    <div class="d-flex flex-column gap-3">
                        @foreach($related as $r)
                            @php
                                $rTitle = is_array($r->title) ? ($r->title['hi'] ?? $r->title['en']) : $r->title;
                            @endphp
                            <a href="{{ route('news.show', $r->slug ?: $r->id) }}" class="d-flex align-items-center gap-3 text-dark text-decoration-none p-2 rounded hover-bg-light">
                                <div class="flex-shrink-0 rounded overflow-hidden" style="width: 70px; height: 60px;">
                                    <img src="{{ $r->featured_image ?: asset('images/jain/temple_shikhar.jpg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $rTitle }}">
                                </div>
                                <div>
                                    <h6 class="mb-1 font-spiritual" style="font-size: 14px; line-height: 1.3;">{{ $rTitle }}</h6>
                                    <small class="text-muted d-block" style="font-size: 11px;"><i class="fa-regular fa-clock me-1"></i> {{ \Carbon\Carbon::parse($r->publish_date)->format('d M') }}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
