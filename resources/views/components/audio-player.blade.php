@props([
    'src' => '',
    'title' => 'भक्ति संगीत',
])

<div class="spiritual-audio-player">
    <audio preload="none">
        <source src="{{ $src }}" type="audio/mpeg">
        आपका ब्राउज़र ऑडियो प्लेयर का समर्थन नहीं करता है।
    </audio>

    <button type="button" class="audio-btn-play" aria-label="ऑडियो चलाएँ / रोकें">
        <i class="fa-solid fa-play"></i>
    </button>

    <div class="audio-progress-wrap">
        <input type="range" class="audio-scrubber" min="0" value="0" step="1" aria-label="ऑडियो प्रगति">
        <div class="audio-time">
            <span class="audio-current-time">0:00</span>
            <span class="audio-duration">--:--</span>
        </div>
    </div>
</div>
