@extends('layouts.app')

@section('title', 'वीडियो दीर्घा (Video Gallery) | प्रवचन एवं वृत्तचित्र')
@section('meta_description', 'पूज्य संतों के दिव्य व्याख्यान, तीर्थ वृत्तचित्र एवं सांस्कृतिक आयोजनों के वीडियो।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'वीडियो दीर्घा', 'url' => '']]" />

    <div class="container pb-5">
        <div class="row align-items-center justify-content-between mb-4 g-3">
            <div class="col-md-7">
                <h1 class="font-spiritual mb-1" style="font-size: 30px;">वीडियो दीर्घा (Video Gallery)</h1>
                <p class="text-muted small mb-0">पूज्य आचार्यों के दुर्लभ वीडियो प्रवचन एवं तीर्थ आयोजनों के दृश्य।</p>
            </div>
            <div class="col-md-5 d-flex flex-wrap justify-content-md-end gap-2">
                <a href="{{ route('gallery.photos') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fa-solid fa-folder-open me-1"></i> चित्र एल्बम</a>
                <a href="{{ route('gallery.videos') }}" class="btn btn-sm btn-spiritual rounded-pill px-3"><i class="fa-solid fa-video me-1"></i> वीडियो</a>
                <a href="{{ route('gallery.pdfs') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fa-solid fa-file-pdf me-1"></i> PDF ग्रन्थ व पत्रिका</a>
            </div>
        </div>

        @if($videos->isNotEmpty())
            <div class="row g-4">
                @foreach($videos as $vid)
                    @php
                        $vUrl = is_array($vid->videos) ? $vid->videos[0] : $vid->videos;
                        $vTitle = is_array($vid->title) ? ($vid->title['hi'] ?? $vid->title['en'] ?? '') : $vid->title;
                        $vAuthor = is_array($vid->author) ? ($vid->author['hi'] ?? $vid->author['en'] ?? '') : $vid->author;
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <x-video-card 
                            :videoUrl="$vUrl"
                            :title="$vTitle"
                            :author="$vAuthor ?: 'पूज्य संत'"
                        />
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$videos" />
        @else
            <x-empty-state 
                title="कोई वीडियो उपलब्ध नहीं है" 
                message="शीघ्र ही नए वीडियो व्याख्यान यहाँ जोड़े जाएंगे।"
                icon="fa-video"
            />
        @endif
    </div>
@endsection
