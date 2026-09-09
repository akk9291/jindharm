@props([
    'src' => '',
    'title' => 'प्रवचन वीडियो',
    'poster' => null,
])

@php
    $youtubeId = null;
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $src, $matches)) {
        $youtubeId = $matches[1];
    }
@endphp

<div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm border">
    @if($youtubeId)
        <iframe 
            src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&modestbranding=1" 
            title="{{ $title }}" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
        </iframe>
    @else
        <video controls poster="{{ $poster }}" preload="metadata" class="w-100 h-100 object-fit-cover">
            <source src="{{ $src }}" type="video/mp4">
            आपका ब्राउज़र वीडियो का समर्थन नहीं करता है।
        </video>
    @endif
</div>
