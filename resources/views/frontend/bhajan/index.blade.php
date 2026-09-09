@extends('layouts.app')

@section('title', 'भक्ति संगीत | जैन भजन, आरती एवं स्तुति संग्रह')
@section('meta_description', 'मेरी भावना, भक्तामर स्तोत्र, णमोकार महामंत्र धुन एवं लोकप्रिय जैन भक्ति गीतों का मधुर संकलन।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'भजन एवं स्तुति', 'url' => '']]" />

    <div class="container pb-5">
        <div class="row align-items-center justify-content-between mb-4 g-3">
            <div class="col-md-7">
                <h1 class="font-spiritual mb-1" style="font-size: 30px;">भक्ति संगीत, आरती एवं स्तुति</h1>
                <p class="text-muted small mb-0">आत्मिक शांति और जिनेंद्र भक्ति में लीन करने वाले पवित्र भक्ति पद।</p>
            </div>
            <div class="col-md-5">
                <form action="{{ route('bhajans.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="भजन या रचयिता खोजें..." aria-label="Search">
                    <button type="submit" class="btn btn-spiritual flex-shrink-0"><i class="fa-solid fa-magnifying-glass"></i></button>
                    @if(request('search'))
                        <a href="{{ route('bhajans.index') }}" class="btn btn-outline-secondary flex-shrink-0">रीसेट</a>
                    @endif
                </form>
            </div>
        </div>

        @if($bhajans->isNotEmpty())
            <div class="row g-4">
                @foreach($bhajans as $bhajan)
                    <div class="col-lg-4 col-md-6">
                        <x-bhajan-card :bhajan="$bhajan" />
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$bhajans" />
        @else
            <x-empty-state 
                title="कोई भजन उपलब्ध नहीं है" 
                message="आपकी खोज के अनुसार कोई भजन प्राप्त नहीं हुआ।"
                icon="fa-music"
                actionText="सभी भजन देखें"
                :actionUrl="route('bhajans.index')"
            />
        @endif
    </div>
@endsection
