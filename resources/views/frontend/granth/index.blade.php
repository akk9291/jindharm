@extends('layouts.app')

@section('title', 'ज्ञान भण्डार | पवित्र जैन आगम एवं ग्रन्थ संग्रह')
@section('meta_description', 'तत्त्वार्थ सूत्र, समयसार, प्रवचनसार, नियमसार आदि प्रामाणिक दिगम्बर जैन आगम ग्रन्थों का ऑनलाइन स्वाध्याय एवं PDF डाउनलोड।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'ज्ञान भण्डार (ग्रन्थ)', 'url' => '']]" />

    <div class="container pb-5">
        <div class="row align-items-center justify-content-between mb-4 g-3">
            <div class="col-md-7">
                <h1 class="font-spiritual mb-1" style="font-size: 30px;">ज्ञान भण्डार - पवित्र जैन ग्रन्थ</h1>
                <p class="text-muted small mb-0">आचार्यों द्वारा रचित शाश्वत सिद्धान्त एवं अध्यात्म के अमर ग्रन्थों का संकलन।</p>
            </div>
            <div class="col-md-5">
                <form action="{{ route('granths.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="ग्रन्थ या आचार्य का नाम खोजें..." aria-label="Search">
                    <button type="submit" class="btn btn-spiritual flex-shrink-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                    @if(request('search'))
                        <a href="{{ route('granths.index') }}" class="btn btn-outline-secondary flex-shrink-0">रीसेट</a>
                    @endif
                </form>
            </div>
        </div>

        @if($granths->isNotEmpty())
            <div class="row g-4">
                @foreach($granths as $granth)
                    <div class="col-lg-4 col-md-6">
                        <x-granth-card :granth="$granth" />
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$granths" />
        @else
            <x-empty-state 
                title="कोई ग्रन्थ नहीं मिला" 
                message="आपकी खोज के अनुसार कोई ग्रन्थ उपलब्ध नहीं है। कृपया अन्य शब्द से खोजें।"
                icon="fa-book-open"
                actionText="सभी ग्रन्थ देखें"
                :actionUrl="route('granths.index')"
            />
        @endif
    </div>
@endsection
