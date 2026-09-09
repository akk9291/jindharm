@extends('layouts.app')

@section('title', 'अमृत प्रवचन | पूज्य संतों के दिव्य व्याख्यान')
@section('meta_description', 'जैन आचार्यों एवं मुनिराजों के दिव्य प्रवचन, आत्म-शांति, कर्म सिद्धान्त और मोक्षमार्ग का ऑडियो-वीडियो स्वाध्याय।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'अमृत प्रवचन', 'url' => '']]" />

    <div class="container pb-5">
        <!-- Page Header & Filters -->
        <div class="row align-items-center justify-content-between mb-4 g-3">
            <div class="col-lg-6">
                <h1 class="font-spiritual mb-1" style="font-size: 30px;">अमृत प्रवचन एवं व्याख्यान</h1>
                <p class="text-muted small mb-0">आत्म-कल्याण एवं सम्यग्दर्शन की प्रेरणा देने वाली पूज्य संतों की दिव्य देशना।</p>
            </div>
            <div class="col-lg-6">
                <form action="{{ route('pravachans.index') }}" method="GET" class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <!-- Filter Pills -->
                    <div class="btn-group" role="group">
                        <a href="{{ route('pravachans.index') }}" class="btn btn-sm {{ !request('filter') ? 'btn-spiritual' : 'btn-outline-secondary' }}">सभी</a>
                        <a href="{{ route('pravachans.index', ['filter' => 'video']) }}" class="btn btn-sm {{ request('filter') == 'video' ? 'btn-spiritual' : 'btn-outline-secondary' }}"><i class="fa-solid fa-video me-1"></i> वीडियो</a>
                        <a href="{{ route('pravachans.index', ['filter' => 'audio']) }}" class="btn btn-sm {{ request('filter') == 'audio' ? 'btn-spiritual' : 'btn-outline-secondary' }}"><i class="fa-solid fa-headphones me-1"></i> ऑडियो</a>
                    </div>
                    
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" style="max-width: 180px;" placeholder="खोजें...">
                    <button type="submit" class="btn btn-sm btn-spiritual"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </div>

        @if($pravachans->isNotEmpty())
            <div class="row g-4">
                @foreach($pravachans as $pravachan)
                    <div class="col-lg-4 col-md-6">
                        <x-pravachan-card :pravachan="$pravachan" />
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$pravachans" />
        @else
            <x-empty-state 
                title="कोई प्रवचन नहीं मिला" 
                message="आपके द्वारा चयनित फ़िल्टर या खोज के अनुसार कोई प्रवचन उपलब्ध नहीं है।"
                icon="fa-microphone"
                actionText="सभी प्रवचन देखें"
                :actionUrl="route('pravachans.index')"
            />
        @endif
    </div>
@endsection
