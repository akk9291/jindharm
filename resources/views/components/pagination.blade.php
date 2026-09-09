@props(['paginator'])

@if($paginator->hasPages())
    <nav class="d-flex justify-content-center my-5" aria-label="Page navigation">
        {{ $paginator->links('pagination::bootstrap-5') }}
    </nav>
@endif
