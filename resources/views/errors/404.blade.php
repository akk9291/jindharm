@extends('layouts.app')

@section('title', '४०४ - यह पृष्ठ उपलब्ध नहीं है | जिनधर्म पोर्टल')

@section('content')
    <div class="container py-5 my-5 text-center">
        <div class="card border-0 shadow-sm rounded-4 p-5 mx-auto bg-white" style="max-width: 600px;">
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 90px; height: 90px; background: var(--primary-light); color: var(--primary); font-size: 40px;">
                    <i class="fa-solid fa-om"></i>
                </div>
            </div>
            <h1 class="font-spiritual text-danger mb-2" style="font-size: 52px; font-weight: 700;">४०४</h1>
            <h2 class="font-spiritual mb-3" style="font-size: 26px;">यह पृष्ठ उपलब्ध नहीं है</h2>
            <p class="text-muted mb-4">
                जिस पृष्ठ को आप खोज रहे हैं, वह स्थानांतरित हो गया है या उपलब्ध नहीं है। कृपया हमारे मुख्य पृष्ठ पर जाकर स्वाध्याय प्रारंभ करें।
            </p>
            <div>
                <a href="{{ route('home') }}" class="btn btn-spiritual py-2 px-4">
                    <i class="fa-solid fa-house me-2"></i> होम पेज पर जाएँ
                </a>
            </div>
        </div>
    </div>
@endsection
