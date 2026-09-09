@props([
    'title' => 'कोई सामग्री उपलब्ध नहीं है',
    'message' => 'वर्तमान में इस श्रेणी में कोई सामग्री उपलब्ध नहीं है। कृपया बाद में पुनः प्रयास करें।',
    'icon' => 'fa-om',
    'actionText' => 'मुख्य पृष्ठ पर जाएँ',
    'actionUrl' => url('/'),
])

<div class="text-center py-5 my-4 bg-white rounded-4 border p-4 shadow-sm">
    <div class="mb-3">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 72px; height: 72px; background: var(--primary-light); color: var(--primary); font-size: 32px;">
            <i class="fa-solid {{ $icon }}"></i>
        </div>
    </div>
    <h4 class="font-spiritual mb-2 text-dark">{{ $title }}</h4>
    <p class="text-muted small mx-auto mb-4" style="max-width: 480px;">
        {{ $message }}
    </p>
    @if($actionUrl && $actionText)
        <a href="{{ $actionUrl }}" class="btn btn-sm btn-spiritual">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ $actionText }}
        </a>
    @endif
</div>
