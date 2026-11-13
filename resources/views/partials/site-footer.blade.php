{{-- Shared footer (design v1). Needs $siteSettings. --}}
@php
    $__s = $siteSettings ?? [];
    $__name = $__s['website_name'] ?? 'जिनधर्म संघ';
    $__tag = $__s['site_tagline'] ?? 'अहिंसा परमो धर्मः · जियो और जीने दो';
    $__about = $__s['footer_about'] ?? 'भगवान महावीर के अहिंसा, अनेकांत और अपरिग्रह के सिद्धांतों पर आधारित डिजिटल मंच — प्रामाणिक ग्रंथ, संत परिचय, विहार स्थिति एवं दैनिक पंचांग।';
    $__phone = $__s['contact_mobile'] ?? '+91-9876543210';
    $__email = $__s['contact_email'] ?? 'info@jindharm.org';
    $__addr = $__s['contact_address'] ?? '102, श्री दिगम्बर जैन बड़ा मंदिर परिसर, अहिंसा मार्ग, नई दिल्ली – 110002';
    $__wa = !empty($__s['social_whatsapp']) ? preg_replace('/[^0-9]/', '', $__s['social_whatsapp']) : null;
    $__join = $__s['whatsapp_channel_url'] ?? ($__wa ? 'https://wa.me/' . $__wa . '?text=' . urlencode('जय जिनेन्द्र, मुझे दैनिक सुविचार व समाचार से जोड़ें।') : route('contact'));
    $__ic = [
        'pin'   => '<path d="M12 21s-7-6-7-12a7 7 0 0 1 14 0c0 6-7 12-7 12z"/><circle cx="12" cy="9" r="2.5"/>',
        'phone' => '<path d="M5 4h4l2 5-3 2a11 11 0 0 0 5 5l2-3 5 2v4a2 2 0 0 1-2 2A17 17 0 0 1 3 6a2 2 0 0 1 2-2"/>',
        'mail'  => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
    ];
    $__svg = fn($n) => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $__ic[$n] . '</svg>';
@endphp

<footer class="jd-footer">
    <div class="jd-join">
        <div class="jd-wrap">
            <div style="flex:1 1 420px;min-width:0">
                <h2>रोज़ का सुविचार, पंचांग और समाचार — सीधे WhatsApp पर</h2>
                <p>हर सुबह धर्म संदेश पाने के लिए हमारे चैनल से जुड़ें।</p>
            </div>
            <a href="{{ $__join }}" target="_blank" rel="noopener" class="jd-btn jd-btn-green" style="min-height:52px;font-size:17px">WhatsApp से जुड़ें</a>
        </div>
    </div>

    <div class="jd-wrap jd-fcols">
        <div class="jd-fbrand">
            <a href="{{ route('home') }}" class="jd-brand" style="color:#fff">
                <picture><source srcset="{{ asset('images/logo.webp') }}" type="image/webp"><img src="{{ asset('images/logo.png') }}" alt="{{ $__name }} लोगो" class="jd-logo" width="101" height="88" loading="lazy"></picture>
                <span><span class="jd-brand-name" style="color:#fff;font-size:25px">{{ $__name }}</span><span class="jd-brand-tag" style="color:var(--jd-peach-2)">{{ $__tag }}</span></span>
            </a>
            <p>{{ $__about }}</p>
            <div class="jd-social" aria-label="सोशल मीडिया">
                @if(!empty($__s['social_facebook']))<a href="{{ $__s['social_facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 3h-3a4 4 0 0 0-4 4v3H5v4h3v7h4v-7h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>@endif
                @if(!empty($__s['social_youtube']))<a href="{{ $__s['social_youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="5" width="20" height="14" rx="4"/><path d="M10 9l5 3-5 3z"/></svg></a>@endif
                @if(!empty($__s['social_instagram']))<a href="{{ $__s['social_instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.8"/></svg></a>@endif
                @if($__wa)<a href="https://wa.me/{{ $__wa }}" target="_blank" rel="noopener" aria-label="WhatsApp"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 20l1.3-4A8 8 0 1 1 8 18.7z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 1a3 3 0 0 1-2-2l1-1-1-2z"/></svg></a>@endif
            </div>
        </div>
        <nav class="jd-fcol" aria-label="ज्ञान भण्डार">
            <div class="jd-fh">ज्ञान भण्डार</div>
            <a href="{{ route('granths.index') }}">ग्रंथ एवं शास्त्र</a>
            <a href="{{ route('bhajans.index') }}">भजन, आरती, स्तोत्र</a>
            <a href="{{ route('pravachans.index') }}">प्रवचन</a>
            <a href="{{ route('suvichar.index') }}">सुविचार संग्रह</a>
            <a href="{{ route('gallery.pdfs') }}">PDF पुस्तकालय</a>
        </nav>
        <nav class="jd-fcol" aria-label="उपयोगी लिंक">
            <div class="jd-fh">उपयोगी लिंक</div>
            <a href="{{ route('home') }}#tirth">तीर्थ क्षेत्र</a>
            <a href="{{ route('sangh-vihar') }}">संत एवं विहार</a>
            <a href="{{ route('panchang.index') }}">पंचांग एवं पर्व</a>
            <a href="{{ route('news.index') }}">समाचार</a>
            <a href="{{ route('events.index') }}">कार्यक्रम कैलेंडर</a>
            <a href="{{ route('gallery.photos') }}">फोटो एवं वीडियो</a>
        </nav>
        <div class="jd-fcol is-wide">
            <div class="jd-fh">संपर्क करें</div>
            <div class="jd-fcontact">{!! $__svg('pin') !!}<span>{{ $__addr }}</span></div>
            <a class="jd-fcontact" href="tel:{{ preg_replace('/[^0-9+]/', '', $__phone) }}">{!! $__svg('phone') !!}{{ $__phone }}</a>
            <a class="jd-fcontact" href="mailto:{{ $__email }}">{!! $__svg('mail') !!}{{ $__email }}</a>
            <a href="{{ route('contact') }}" class="jd-btn jd-btn-saffron" style="align-self:flex-start;min-height:44px;padding:0 20px;font-size:15px">संदेश भेजें</a>
        </div>
    </div>

    <div class="jd-wrap">
        <div class="jd-motto"><b>परस्परोपग्रहो जीवानाम्</b><small>समस्त जीव एक-दूसरे के उपकारक हैं</small></div>
    </div>

    <div class="jd-wrap jd-fbottom">
        <span>© {{ date('Y') }} {{ $__name }}। सर्वाधिकार सुरक्षित। · Development by <a href="https://coderpoint.in" target="_blank" rel="noopener">Coder Point</a></span>
        <nav aria-label="नीति लिंक">
            <a href="{{ route('pages.show', 'about-us') }}">हमारे बारे में</a>
            <a href="{{ route('pages.show', 'privacy-policy') }}">गोपनीयता नीति</a>
            <a href="{{ route('pages.show', 'terms-conditions') }}">नियम व शर्तें</a>
            <a href="{{ route('home.portal') }}">पुराना होम पेज</a>
            <a href="{{ url('/admin/login') }}">एडमिन लॉगिन</a>
        </nav>
    </div>
</footer>
