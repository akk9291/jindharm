{{--
    Shared top bar + header (design v1).
    Needs: $siteSettings, $todayPanchang, $todayFestival, $headerMenu (FrontendViewComposer).
    Optional: $searchMode = 'modal' (opens Bootstrap #searchModal) | 'anchor' (jumps to #q on the home page).
--}}
@php
    $__tr = function ($v) {
        if (is_string($v)) { $d = json_decode($v, true); if (is_array($d)) { $v = $d; } }
        if (is_array($v)) { return $v[app()->getLocale()] ?? $v['hi'] ?? $v['en'] ?? (array_values($v)[0] ?? ''); }
        return (string) ($v ?? '');
    };
    $__s = $siteSettings ?? [];
    $__name = $__s['website_name'] ?? 'जिनधर्म संघ';
    $__tag = $__s['site_tagline'] ?? 'अहिंसा परमो धर्मः · जियो और जीने दो';
    $__phone = $__s['contact_mobile'] ?? '+91-9876543210';
    $__p = $todayPanchang ?? null;
    $__pLine = $__p ? trim(implode(' · ', array_filter([$__tr($__p->maas), $__tr($__p->paksha), $__tr($__p->tithi)]))) : '';
    $__fest = isset($todayFestival) && $todayFestival ? $__tr($todayFestival->festival_name) : '';
    $__mode = $searchMode ?? 'modal';
    $__isActive = function ($url) {
        $path = trim(parse_url(url($url), PHP_URL_PATH) ?? '', '/');
        $base = trim(parse_url(url('/'), PHP_URL_PATH) ?? '', '/');
        $rel = trim(\Illuminate\Support\Str::after($path, $base), '/');
        return $rel === '' ? request()->is('/') : request()->is($rel, $rel . '/*');
    };
@endphp

@php $__base = rtrim(parse_url(url('/'), PHP_URL_PATH) ?? '', '/'); @endphp
@if($__base !== '')
    {{-- Site runs from a subfolder (e.g. localhost/jindharm/public): fix root-relative paths saved in the DB, like "/images/..." or "/documents/...pdf" --}}
    <script>
        (function () {
            var base = @json($__base), attrs = { IMG: 'src', IFRAME: 'src', SOURCE: 'src', AUDIO: 'src', VIDEO: 'src', A: 'href' };
            function fix(el) {
                var a = attrs[el.tagName]; if (!a) return;
                var v = el.getAttribute(a);
                if (v && v.charAt(0) === '/' && v.charAt(1) !== '/' && v !== base && v.indexOf(base + '/') !== 0) el.setAttribute(a, base + v);
            }
            function scan(n) { if (n.nodeType !== 1) return; fix(n); if (n.querySelectorAll) n.querySelectorAll('img,iframe,source,audio,video,a').forEach(fix); }
            new MutationObserver(function (list) { list.forEach(function (m) { m.addedNodes.forEach(scan); }); })
                .observe(document.documentElement, { childList: true, subtree: true });
            document.addEventListener('DOMContentLoaded', function () { scan(document.body); });
        })();
    </script>
@endif

<a href="#main" class="jd-sr jd-skip">मुख्य सामग्री पर जाएँ</a>

<div class="jd-topbar">
    <div class="jd-wrap">
        <div class="jd-row">
            <span style="font-weight:600">{{ \Carbon\Carbon::now()->locale('hi')->translatedFormat('l, d F Y') }}</span>
            @if($__pLine)<span class="jd-muted">{{ $__pLine }}</span>@endif
            @if($__fest)<span class="jd-fest">पर्व: {{ $__fest }}</span>@endif
        </div>
        <div class="jd-row">
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $__phone) }}">हेल्पलाइन {{ $__phone }}</a>
        </div>
    </div>
</div>

<header class="jd-header" id="siteHeader">
    <div class="jd-wrap">
        <a href="{{ route('home') }}" class="jd-brand">
            <picture><source srcset="{{ asset('images/logo.webp') }}" type="image/webp"><img src="{{ asset('images/logo.png') }}" alt="{{ $__name }} लोगो" class="jd-logo" width="73" height="64"></picture>
            <span><span class="jd-brand-name">{{ $__name }}</span><span class="jd-brand-tag">{{ $__tag }}</span></span>
        </a>

        <div class="jd-actions">
            @if($__mode === 'modal')
                <button type="button" class="jd-icon-btn" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="खोजें">
            @else
                <a href="#q" class="jd-icon-btn" aria-label="खोजें">
            @endif
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg>
            @if($__mode === 'modal')
                </button>
            @else
                </a>
            @endif
            <button type="button" class="jd-icon-btn jd-menu-toggle" id="jdMenuToggle" aria-label="मेनू खोलें" aria-expanded="false" aria-controls="jdNav">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <ul class="jd-nav" id="jdNav" aria-label="मुख्य मेनू">
            @if(isset($headerMenu) && $headerMenu && $headerMenu->items->count() > 0)
                @foreach($headerMenu->items as $item)
                    @php $kids = $item->children ?? collect(); @endphp
                    <li>
                        <a href="{{ url($item->url) }}" class="{{ $__isActive($item->url) ? 'is-active' : '' }}" target="{{ $item->target ?: '_self' }}" @if($kids->count()) aria-haspopup="true" @endif>
                            {{ $item->getLocalized('label') }}
                            @if($kids->count())<span class="jd-caret" aria-hidden="true">▾</span>@endif
                        </a>
                        @if($kids->count())
                            <ul class="jd-sub">
                                @foreach($kids as $child)
                                    <li><a href="{{ url($child->url) }}" class="{{ $__isActive($child->url) ? 'is-active' : '' }}" target="{{ $child->target ?: '_self' }}">{{ $child->getLocalized('label') }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @else
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'is-active' : '' }}">होम</a></li>
                <li><a href="{{ route('granths.index') }}" class="{{ request()->routeIs('granths.*', 'bhajans.*', 'pravachans.*') ? 'is-active' : '' }}">ज्ञान भण्डार <span class="jd-caret" aria-hidden="true">▾</span></a>
                    <ul class="jd-sub">
                        <li><a href="{{ route('granths.index') }}">ग्रंथ</a></li>
                        <li><a href="{{ route('pravachans.index') }}">प्रवचन</a></li>
                        <li><a href="{{ route('bhajans.index') }}">भजन व स्तुति</a></li>
                        <li><a href="{{ route('suvichar.index') }}">सुविचार</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('sants.index') }}" class="{{ request()->routeIs('sants.*', 'sangh-vihar') ? 'is-active' : '' }}">संत</a></li>
                <li><a href="{{ route('panchang.index') }}" class="{{ request()->routeIs('panchang.*', 'festivals.*') ? 'is-active' : '' }}">पंचांग</a></li>
                <li><a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'is-active' : '' }}">समाचार</a></li>
                <li><a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'is-active' : '' }}">कार्यक्रम</a></li>
                <li><a href="{{ route('gallery.photos') }}" class="{{ request()->routeIs('gallery.*') ? 'is-active' : '' }}">मीडिया</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'is-active' : '' }}">संपर्क</a></li>
            @endif
        </ul>
    </div>
</header>

<script>
(function () {
    var t = document.getElementById('jdMenuToggle'), n = document.getElementById('jdNav'), h = document.getElementById('siteHeader');
    if (t && n) t.addEventListener('click', function () {
        var open = n.classList.toggle('is-open');
        t.setAttribute('aria-expanded', open ? 'true' : 'false');
        t.setAttribute('aria-label', open ? 'मेनू बंद करें' : 'मेनू खोलें');
    });
    if (h) window.addEventListener('scroll', function () { h.classList.toggle('is-scrolled', window.scrollY > 20); }, { passive: true });
})();
</script>
