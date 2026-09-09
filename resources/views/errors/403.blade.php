@extends('layouts.app')

@section('title', '४०३ - अनाधिकृत प्रवेश | जिनधर्म पोर्टल')

@section('content')
    <div class="container py-5 my-5 text-center">
        <div class="card border-0 shadow-sm rounded-4 p-5 mx-auto bg-white" style="max-width: 600px;">
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 90px; height: 90px; background: rgba(220, 38, 38, 0.1); color: #dc2626; font-size: 40px;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
            <h1 class="font-spiritual text-danger mb-2" style="font-size: 52px; font-weight: 700;">४०३</h1>
            <h2 class="font-spiritual mb-3" style="font-size: 26px;">अनाधिकृत प्रवेश (Access Denied)</h2>
            <p class="text-muted mb-4">
                आपके पास इस पृष्ठ या अनुभाग को देखने की आवश्यक अनुमति नहीं है।
            </p>
            <div>
                <a href="{{ route('home') }}" class="btn btn-spiritual py-2 px-4">
                    <i class="fa-solid fa-house me-2"></i> होम पेज पर जाएँ
                </a>
            </div>
        </div>
    </div>
@endsection
