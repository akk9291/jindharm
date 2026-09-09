@extends('layouts.app')

@section('title', 'मांगलिक कार्यक्रम एवं उत्सव | जैन धर्म सभाएं')
@section('meta_description', 'आगामी पंचकल्याणक प्रतिष्ठा महोत्सव, विधान, चातुर्मास कलश स्थापना एवं राष्ट्रीय सम्मेलनों की तिथियां।')

@section('content')
    <x-breadcrumbs :items="[['title' => 'मांगलिक कार्यक्रम', 'url' => '']]" />

    <div class="container pb-5">
        <div class="mb-4">
            <h1 class="font-spiritual mb-1" style="font-size: 30px;">आगामी धार्मिक कार्यक्रम एवं उत्सव</h1>
            <p class="text-muted small mb-0">पंचकल्याणक, विधान, चातुर्मास और गुरु पूर्णिमा जैसे मांगलिक महोत्सवों का विवरण।</p>
        </div>

        @if($upcomingEvents->isNotEmpty())
            <div class="row g-4 mb-5">
                @foreach($upcomingEvents as $event)
                    <div class="col-lg-4 col-md-6">
                        <x-event-card :event="$event" />
                    </div>
                @endforeach
            </div>

            <x-pagination :paginator="$upcomingEvents" />
        @else
            <x-empty-state 
                title="कोई आगामी कार्यक्रम नहीं है" 
                message="वर्तमान में कोई नया कार्यक्रम निर्धारित नहीं है। शीघ्र ही नए आयोजनों की सूचना यहाँ दी जाएगी।"
                icon="fa-calendar-days"
            />
        @endif

        @if($pastEvents->isNotEmpty())
            <div class="mt-5 pt-4 border-top">
                <h3 class="font-spiritual mb-4 text-muted"><i class="fa-solid fa-clock-rotate-left me-2"></i> विगत संपन्न मांगलिक कार्यक्रम</h3>
                <div class="row g-3">
                    @foreach($pastEvents as $pe)
                        <div class="col-lg-4 col-md-6">
                            <x-event-card :event="$pe" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
