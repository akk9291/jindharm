@extends('layouts.app')

@section('title', 'पूज्य साधु-संत परिचय | दिगम्बर जैन मुनि संघ')
@section('meta_description', 'दिगम्बर जैन परम्परा के परम पूज्य आचार्यों, मुनिराजों एवं आर्यिका संघों का जीवन परिचय, दीक्षा विवरण एवं वर्तमान विहार स्थिति।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'संत परिचय', 'url' => '']]" />

    <div class="container pb-5">
        <div class="row align-items-center justify-content-between mb-4 g-3">
            <div class="col-md-7">
                <h1 class="font-spiritual mb-1" style="font-size: 30px;">पूज्य साधु संघ परिचय</h1>
                <p class="text-muted small mb-0">आत्म-कल्याण और जगत-कल्याण के मार्ग पर अग्रसर तपस्वी साधुओं का विवरण।</p>
            </div>
            <div class="col-md-5">
                <form action="{{ route('sants.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="संत का नाम खोजें..." aria-label="Search">
                    <button type="submit" class="btn btn-spiritual flex-shrink-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                    @if(request('search'))
                        <a href="{{ route('sants.index') }}" class="btn btn-outline-secondary flex-shrink-0">रीसेट</a>
                    @endif
                </form>
            </div>
        </div>

        @if($saints->isNotEmpty())
            <div class="row g-4">
                @foreach($saints as $sant)
                    <div class="col-lg-3 col-md-6">
                        <x-sant-card :sant="$sant" />
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$saints" />
        @else
            <x-empty-state 
                title="कोई संत विवरण नहीं मिला" 
                message="आपकी खोज के अनुसार कोई परिणाम नहीं मिला। कृपया अन्य कीवर्ड से प्रयास करें।"
                icon="fa-user-tie"
                actionText="सभी संत देखें"
                :actionUrl="route('sants.index')"
            />
        @endif
    </div>
@endsection
