@extends('layouts.app')

@section('title', 'खोज परिणाम: ' . ($q ?: 'समस्त सामग्री') . ' | जिनधर्म पोर्टल')

@section('content')
    <x-breadcrumbs :items="[['title' => 'खोज (Search)', 'url' => '']]" />

    <div class="container pb-5">
        <!-- Search Query Box -->
        <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white mb-4">
            <h1 class="font-spiritual mb-3" style="font-size: 28px;">
                <i class="fa-solid fa-magnifying-glass text-danger me-2"></i> धर्म पोर्टल पर खोजें
            </h1>
            <form action="{{ route('search') }}" method="GET">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="संत, ग्रन्थ, प्रवचन, भजन, समाचार खोजें..." required>
                    <button class="btn btn-spiritual" type="submit">खोजें</button>
                </div>
            </form>

            @if($q)
                <div class="mt-3 text-muted small">
                    "<strong>{{ $q }}</strong>" के लिए <strong>{{ $totalResults }}</strong> परिणाम प्राप्त हुए।
                </div>
            @endif
        </div>

        @if($totalResults > 0)
            <!-- Search Results Tabs -->
            <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3" id="searchTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-3 py-1 btn-sm" id="all-tab" data-bs-toggle="pill" data-bs-target="#all-content" type="button" role="tab">सभी ({{ $totalResults }})</button>
                </li>
                @if($saints->isNotEmpty())
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1 btn-sm" id="saints-tab" data-bs-toggle="pill" data-bs-target="#saints-content" type="button" role="tab">संत संघ ({{ $saints->count() }})</button>
                    </li>
                @endif
                @if($pravachans->isNotEmpty())
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1 btn-sm" id="pravachans-tab" data-bs-toggle="pill" data-bs-target="#pravachans-content" type="button" role="tab">प्रवचन ({{ $pravachans->count() }})</button>
                    </li>
                @endif
                @if($granths->isNotEmpty())
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1 btn-sm" id="granths-tab" data-bs-toggle="pill" data-bs-target="#granths-content" type="button" role="tab">ग्रन्थ ({{ $granths->count() }})</button>
                    </li>
                @endif
                @if($bhajans->isNotEmpty())
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1 btn-sm" id="bhajans-tab" data-bs-toggle="pill" data-bs-target="#bhajans-content" type="button" role="tab">भजन ({{ $bhajans->count() }})</button>
                    </li>
                @endif
                @if($news->isNotEmpty())
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1 btn-sm" id="news-tab" data-bs-toggle="pill" data-bs-target="#news-content" type="button" role="tab">समाचार ({{ $news->count() }})</button>
                    </li>
                @endif
                @if($events->isNotEmpty())
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill px-3 py-1 btn-sm" id="events-tab" data-bs-toggle="pill" data-bs-target="#events-content" type="button" role="tab">कार्यक्रम ({{ $events->count() }})</button>
                    </li>
                @endif
            </ul>

            <div class="tab-content" id="searchTabsContent">
                <!-- All Results Tab -->
                <div class="tab-pane fade show active" id="all-content" role="tabpanel">
                    @if($saints->isNotEmpty())
                        <h4 class="font-spiritual mb-3 text-secondary"><i class="fa-solid fa-user-tie me-2"></i> पूज्य साधु संघ</h4>
                        <div class="row g-4 mb-5">
                            @foreach($saints as $sant)
                                <div class="col-lg-3 col-md-6">
                                    <x-sant-card :sant="$sant" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($pravachans->isNotEmpty())
                        <h4 class="font-spiritual mb-3 text-secondary"><i class="fa-solid fa-microphone me-2"></i> अमृत प्रवचन</h4>
                        <div class="row g-4 mb-5">
                            @foreach($pravachans as $pravachan)
                                <div class="col-lg-4 col-md-6">
                                    <x-pravachan-card :pravachan="$pravachan" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($granths->isNotEmpty())
                        <h4 class="font-spiritual mb-3 text-secondary"><i class="fa-solid fa-book-open me-2"></i> पवित्र ग्रन्थ</h4>
                        <div class="row g-4 mb-5">
                            @foreach($granths as $granth)
                                <div class="col-lg-4 col-md-6">
                                    <x-granth-card :granth="$granth" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($bhajans->isNotEmpty())
                        <h4 class="font-spiritual mb-3 text-secondary"><i class="fa-solid fa-music me-2"></i> भजन एवं स्तुति</h4>
                        <div class="row g-4 mb-5">
                            @foreach($bhajans as $bhajan)
                                <div class="col-lg-4 col-md-6">
                                    <x-bhajan-card :bhajan="$bhajan" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($news->isNotEmpty())
                        <h4 class="font-spiritual mb-3 text-secondary"><i class="fa-solid fa-newspaper me-2"></i> धर्म समाचार</h4>
                        <div class="row g-4 mb-5">
                            @foreach($news as $item)
                                <div class="col-lg-4 col-md-6">
                                    <x-news-card :news="$item" />
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($events->isNotEmpty())
                        <h4 class="font-spiritual mb-3 text-secondary"><i class="fa-solid fa-calendar-check me-2"></i> कार्यक्रम</h4>
                        <div class="row g-4 mb-5">
                            @foreach($events as $ev)
                                <div class="col-lg-4 col-md-6">
                                    <x-event-card :event="$ev" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Specific Category Tabs -->
                @if($saints->isNotEmpty())
                    <div class="tab-pane fade" id="saints-content" role="tabpanel">
                        <div class="row g-4">
                            @foreach($saints as $sant)
                                <div class="col-lg-3 col-md-6">
                                    <x-sant-card :sant="$sant" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($pravachans->isNotEmpty())
                    <div class="tab-pane fade" id="pravachans-content" role="tabpanel">
                        <div class="row g-4">
                            @foreach($pravachans as $pravachan)
                                <div class="col-lg-4 col-md-6">
                                    <x-pravachan-card :pravachan="$pravachan" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($granths->isNotEmpty())
                    <div class="tab-pane fade" id="granths-content" role="tabpanel">
                        <div class="row g-4">
                            @foreach($granths as $granth)
                                <div class="col-lg-4 col-md-6">
                                    <x-granth-card :granth="$granth" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($bhajans->isNotEmpty())
                    <div class="tab-pane fade" id="bhajans-content" role="tabpanel">
                        <div class="row g-4">
                            @foreach($bhajans as $bhajan)
                                <div class="col-lg-4 col-md-6">
                                    <x-bhajan-card :bhajan="$bhajan" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($news->isNotEmpty())
                    <div class="tab-pane fade" id="news-content" role="tabpanel">
                        <div class="row g-4">
                            @foreach($news as $item)
                                <div class="col-lg-4 col-md-6">
                                    <x-news-card :news="$item" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($events->isNotEmpty())
                    <div class="tab-pane fade" id="events-content" role="tabpanel">
                        <div class="row g-4">
                            @foreach($events as $ev)
                                <div class="col-lg-4 col-md-6">
                                    <x-event-card :event="$ev" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <x-empty-state 
                title="कोई परिणाम नहीं मिला" 
                message="आपकी खोज '{{ $q }}' के अनुसार कोई सामग्री प्राप्त नहीं हुई। कृपया अन्य कीवर्ड या सरल शब्दों से खोजें।"
                icon="fa-magnifying-glass"
                actionText="मुख्य पृष्ठ पर लौटें"
                :actionUrl="route('home')"
            />
        @endif
    </div>
@endsection
