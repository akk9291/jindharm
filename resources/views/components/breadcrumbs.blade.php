@props(['items' => []])

<nav aria-label="breadcrumb" class="py-3 mb-4 border-bottom bg-white">
    <div class="container">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa-solid fa-house me-1"></i> गृह</a></li>
            @foreach($items as $item)
                @if(!$loop->last && !empty($item['url']))
                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                @else
                    <li class="breadcrumb-item active text-truncate" style="max-width: 300px;" aria-current="page">{{ $item['title'] }}</li>
                @endif
            @endforeach
        </ol>
    </div>
</nav>

@push('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "गृह",
            "item": "{{ route('home') }}"
        }
        @foreach($items as $idx => $it)
        ,{
            "@type": "ListItem",
            "position": {{ $idx + 2 }},
            "name": "{{ $it['title'] }}",
            "item": "{{ $it['url'] ?? url()->current() }}"
        }
        @endforeach
    ]
}
</script>
@endpush
