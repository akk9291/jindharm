@props([
    'pretitle' => null,
    'title' => '',
    'subtitle' => null,
])

<div class="section-header-wrapper">
    @if($pretitle)
        <span class="section-pretitle">{{ $pretitle }}</span>
    @endif
    <h2 class="section-maintitle font-spiritual">{{ $title }}</h2>
    <div class="spiritual-divider">
        <span class="line"></span>
        <i class="fa-solid fa-om"></i>
        <span class="line right"></span>
    </div>
    @if($subtitle)
        <p class="section-subtitle">{{ $subtitle }}</p>
    @endif
</div>
