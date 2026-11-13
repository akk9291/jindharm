{{--
    जिनधर्म संघ – Home Page (Design v1)
    Search-first layout with category directory, today strip, Varsha Yog / live vihar,
    news & events, tirth carousel, compact media tabs, saints and footer.
    Previous homepage is kept at /home-portal (home/index_portal_v1.blade.php).
--}}
@php
    // Safe localized value helper (handles array/json translations or plain strings)
    $tr = function ($v) {
        if (is_string($v)) {
            $d = json_decode($v, true);
            if (is_array($d)) { $v = $d; }
        }
        if (is_array($v)) {
            return $v[app()->getLocale()] ?? $v['hi'] ?? $v['en'] ?? (array_values($v)[0] ?? '');
        }
        return (string) ($v ?? '');
    };

    // Image path helper: works on a domain root and in a subfolder install
    $img = function ($path, $fallback = 'images/jain/temple_shikhar.jpg') {
        $path = $path ?: $fallback;
        return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//', 'data:']) ? $path : asset(ltrim($path, '/'));
    };

    $s = $siteSettings ?? [];
    $siteName  = $s['website_name'] ?? 'जिनधर्म संघ';
    $tagline   = $s['site_tagline'] ?? 'अहिंसा परमो धर्मः · जियो और जीने दो';
    $phone     = $s['contact_mobile'] ?? '+91-9876543210';
    $email     = $s['contact_email'] ?? 'info@jindharm.org';
    $address   = $s['contact_address'] ?? '102, श्री दिगम्बर जैन बड़ा मंदिर परिसर, अहिंसा मार्ग, नई दिल्ली – 110002';
    $about     = $s['footer_about'] ?? 'भगवान महावीर के अहिंसा, अनेकांत और अपरिग्रह के सिद्धांतों पर आधारित डिजिटल मंच — प्रामाणिक ग्रंथ, संत परिचय, विहार स्थिति एवं दैनिक पंचांग।';
    $waNumber  = !empty($s['social_whatsapp']) ? preg_replace('/[^0-9]/', '', $s['social_whatsapp']) : null;
    $waJoinUrl = $s['whatsapp_channel_url'] ?? ($waNumber ? 'https://wa.me/' . $waNumber . '?text=' . urlencode('जय जिनेन्द्र, मुझे दैनिक सुविचार व समाचार से जोड़ें।') : route('contact'));

    $todayLabel = \Carbon\Carbon::now()->locale('hi')->translatedFormat('l, d F Y');

    // Panchang
    $p = $todayPanchang ?? null;
    $pMaas   = $p ? $tr($p->maas) : '';
    $pPaksha = $p ? $tr($p->paksha) : '';
    $pTithi  = $p ? $tr($p->tithi) : '';
    $pNak    = $p ? $tr($p->nakshatra) : '';
    $pRise   = $p->sunrise ?? '';
    $pSet    = $p->sunset ?? '';
    $pNotes  = $p ? $tr($p->notes) : '';
    $festName = isset($todayFestival) && $todayFestival ? $tr($todayFestival->festival_name) : '';

    // Quote
    $qText   = $todayQuote ? $tr($todayQuote->quote_text) : 'क्रोध को शांति से, मान को नम्रता से, माया को सरलता से और लोभ को संतोष से जीतें।';
    $qAuthor = $todayQuote ? ($tr($todayQuote->author) ?: 'भगवान महावीर स्वामी') : 'भगवान महावीर स्वामी';
    $qShare  = 'https://api.whatsapp.com/send?text=' . urlencode('✨ ' . $qText . "\n— " . $qAuthor . "\n\n" . url('/'));

    // Varsha Yog / Sangh vihar (managed in Admin › Sangh & Vihar)
    $vTitle    = $s['vihar_section_title'] ?? '२८ वां अनेक कल्याणक भूमि वर्षा योग २०२६';
    $vTirth    = $s['vihar_tirth_location'] ?? 'श्री पार्श्वनाथ दिगंबर जैन तीर्थ क्षेत्र भेलूपुर वाराणसी (उत्तर प्रदेश)';
    $vSub      = $s['vihar_tirth_subtitle'] ?? 'गर्भ, जन्म, तप कल्याणक भूमि';
    $vPoster   = $img($s['vihar_poster_image'] ?? null, 'images/jain/varsha_yog_poster_cropped.jpg');
    $vAshish   = $s['vihar_ashish_chhav'] ?? 'महासमाधि धारक परम पूज्य आचार्यश्री 108 विद्यासागर जी महामुनिराज';
    $vAashir   = $s['vihar_aashirwad_pradata'] ?? 'परम पूज्य आचार्यश्री 108 समयसागर जी महामुनिराज';
    $vSanidhya = $s['vihar_punyavardhan_sanidhya'] ?? 'परम पूज्य मुनिश्री 108 धर्मसागर जी महाराज, परम पूज्य मुनिश्री 108 भावसागर जी महाराज';
    $vDisha    = $s['vihar_disha'] ?? 'वाराणसी से अयोध्या जी की ओर';
    $vRatri    = $s['vihar_ratri_vishram'] ?? 'दिगम्बर जैन धर्मशाला, जौनपुर';
    $vAahar    = $s['vihar_aahar_charya'] ?? 'प्रातः ०९:३० बजे, श्री आदिनाथ जिनालय परिसर';
    $vStay     = $s['vihar_current_stay'] ?? 'भेलूपुर जैन मन्दिर, वाराणसी';
    $vActive   = ($s['vihar_status_active'] ?? '1') == '1';
    $vWebsite  = $s['vihar_website_url'] ?? 'www.jindharma.com';
    $vYoutube  = $s['vihar_youtube_text'] ?? 'जैन धर्म वाणी, एवं जैन वर्ल्ड विद्या';
    $vMap      = $s['vihar_map_link'] ?? 'https://maps.google.com/?q=' . urlencode($vTirth);
    $vQr       = $s['vihar_qr_link'] ?? 'https://linktr.ee/Jindharmmatter';
    $vWebHref  = \Illuminate\Support\Str::startsWith($vWebsite, ['http://', 'https://']) ? $vWebsite : 'https://' . $vWebsite;
    $rawC = $s['vihar_contacts'] ?? '[]';
    $vContacts = is_array($rawC) ? $rawC : json_decode($rawC, true);
    if (!is_array($vContacts) || empty($vContacts)) {
        $vContacts = [
            ['name' => 'रामू भैया नौहटा', 'phone' => '7879068125'],
            ['name' => 'राघव जैन भोपाल', 'phone' => '6260151350'],
            ['name' => 'श्री आर.सी. जैन', 'phone' => '9415201372'],
            ['name' => 'श्री विनोद कुमार जैन (चांदी वाले)', 'phone' => '7376895750'],
            ['name' => 'सौरभ कुमार जैन', 'phone' => '9415222071'],
            ['name' => 'राहुल कुमार जैन', 'phone' => '9984226644'],
            ['name' => 'संजय जैन', 'phone' => '6388234656'],
        ];
    }
    $vShare = 'https://api.whatsapp.com/send?text=' . urlencode("🪷 {$vTitle} 🪷\n📍 {$vTirth}\n\n• दिशा: {$vDisha}\n• रात्रि विश्राम: {$vRatri}\n• आहार चर्या: {$vAahar}\n• वर्तमान प्रवास: {$vStay}\n\n" . url('/'));

    // Tirth states for filter
    $stateOf = fn($t) => trim(\Illuminate\Support\Str::before($t['state'] ?? '', '('));
    $tirthStates = collect($tirthList ?? [])->map($stateOf)->filter()->unique()->values();

    // Media helpers
    $firstFile = function ($raw) {
        $files = is_array($raw) ? $raw : json_decode($raw ?? '[]', true);
        if (!is_array($files) || empty($files)) return null;
        $f = $files[0];
        return is_array($f) ? ($f['url'] ?? $f['path'] ?? null) : (is_string($f) ? $f : null);
    };
    $mediaTabs = [
        'pravachan' => ['label' => 'प्रवचन', 'more' => 'सभी प्रवचन देखें', 'url' => route('pravachans.index'), 'items' => []],
        'bhajan'    => ['label' => 'भजन व स्तुति', 'more' => 'सभी भजन व आरती', 'url' => route('bhajans.index'), 'items' => []],
        'pdf'       => ['label' => 'PDF', 'more' => 'पूरा PDF पुस्तकालय', 'url' => route('gallery.pdfs'), 'items' => []],
        'video'     => ['label' => 'वीडियो', 'more' => 'सभी वीडियो देखें', 'url' => route('gallery.videos'), 'items' => []],
    ];
    foreach (($pravachans ?? collect()) as $c) {
        $mediaTabs['pravachan']['items'][] = ['title' => $c->getLocalized('title'), 'by' => $c->getLocalized('author') ?: 'पूज्य संत', 'meta' => 'प्रवचन' . ($c->publish_date ? ' · ' . $c->publish_date->format('d M Y') : ''), 'url' => route('pravachans.show', $c->slug ?: $c->id), 'icon' => 'play'];
    }
    foreach (($bhajans ?? collect()) as $c) {
        $mediaTabs['bhajan']['items'][] = ['title' => $c->getLocalized('title'), 'by' => $c->getLocalized('author') ?: 'भक्ति संगीत', 'meta' => $c->audios ? 'ऑडियो' : 'बोल', 'url' => route('bhajans.show', $c->slug ?: $c->id), 'icon' => 'play'];
    }
    foreach (($pdfLibrary ?? collect()) as $c) {
        $mediaTabs['pdf']['items'][] = ['title' => $c->getLocalized('title'), 'by' => $c->getLocalized('author') ?: 'आचार्य परम्परा', 'meta' => 'PDF डाउनलोड', 'url' => $firstFile($c->pdfs) ?: route('gallery.pdfs'), 'icon' => 'down'];
    }
    foreach (($videos ?? collect()) as $c) {
        $mediaTabs['video']['items'][] = ['title' => $c->getLocalized('title'), 'by' => $c->getLocalized('author') ?: 'पूज्य संत', 'meta' => 'वीडियो', 'url' => $firstFile($c->videos) ?: route('gallery.videos'), 'icon' => 'play'];
    }

    // Hero slides
    $slides = [
        ['tag' => 'सत्य · अहिंसा · अपरिग्रह · अनेकांत', 'title' => $siteName . ' पावन पोर्टल', 'text' => 'ग्रंथ, भजन, प्रवचन, तीर्थ क्षेत्र, संत विहार और दैनिक पंचांग — जैन धर्म की सम्पूर्ण जानकारी अब एक ही स्थान पर।', 'cta1' => ['स्वाध्याय प्रारंभ करें', route('granths.index')], 'cta2' => ['संत विहार देखें', route('sangh-vihar')], 'img' => asset('images/jain/muni_vidyasagar.jpg'), 'fit' => 'cover'],
        ['tag' => 'लाइव · वर्षा योग', 'title' => $vTitle, 'text' => $vTirth . ' — ' . $vSub . '।', 'cta1' => ['पूरा पोस्टर देखें', $vPoster], 'cta2' => ['लोकेशन देखें', $vMap], 'img' => $vPoster, 'fit' => 'contain'],
        ['tag' => 'मांगलिक आयोजन', 'title' => 'आगामी धार्मिक कार्यक्रम', 'text' => 'पंचकल्याणक, विधान, शिविर एवं धर्मसभाओं की तिथियाँ और स्थान — एक ही कैलेंडर में।', 'cta1' => ['कार्यक्रम कैलेंडर देखें', route('events.index')], 'cta2' => ['अपना कार्यक्रम भेजें', route('contact')], 'img' => asset('images/jain/temple_shikhar.jpg'), 'fit' => 'cover'],
    ];

    $categories = [
        ['ग्रंथ एवं शास्त्र', 'आगम · सिद्धांत · स्वाध्याय', route('granths.index'), 'book', ($stats['granths'] ?? 0)],
        ['भजन, आरती, स्तोत्र', 'बोल · ऑडियो · पूजा', route('bhajans.index'), 'music', ($stats['bhajans'] ?? 0)],
        ['प्रवचन', 'संत अनुसार · विषय अनुसार', route('pravachans.index'), 'mic', ($stats['pravachans'] ?? 0)],
        ['सुविचार', 'दैनिक संदेश · संग्रह', route('suvichar.index'), 'quote', ($stats['quotes'] ?? 0)],
        ['संत एवं विहार', 'परिचय · लाइव विहार स्थिति', route('sangh-vihar'), 'user', ($stats['saints'] ?? 0)],
        ['तीर्थ क्षेत्र', 'राज्य अनुसार · संपर्क · सुविधाएँ', '#tirth', 'temple', ($stats['tirth'] ?? 0)],
        ['पंचांग एवं पर्व', 'तिथि · व्रत · त्योहार', route('panchang.index'), 'calendar', 0],
        ['समाचार', 'दैनिक अपडेट · तीर्थ समाचार', route('news.index'), 'news', ($stats['news'] ?? 0)],
        ['कार्यक्रम', 'विधान · पंचकल्याणक · शिविर', route('events.index'), 'flag', 0],
        ['वीडियो', 'प्रवचन वीडियो · वृत्तचित्र', route('gallery.videos'), 'video', ($stats['videos'] ?? 0)],
        ['फोटो दीर्घा', 'तीर्थ वंदना · संत दर्शन', route('gallery.photos'), 'image', 0],
        ['PDF पुस्तकालय', 'ग्रंथ · पत्रिकाएँ डाउनलोड', route('gallery.pdfs'), 'pdf', ($stats['pdfs'] ?? 0)],
    ];

    $icons = [
        'book'     => '<path d="M4 19V5a2 2 0 0 1 2-2h14v16H6a2 2 0 0 0-2 2 2 2 0 0 0 2 2h14"/>',
        'music'    => '<path d="M9 18V5l11-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="17" cy="16" r="3"/>',
        'mic'      => '<rect x="9" y="2" width="6" height="12" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v4"/>',
        'quote'    => '<path d="M6 7h4v4c0 3-2 5-4 6M14 7h4v4c0 3-2 5-4 6"/>',
        'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/>',
        'temple'   => '<path d="M12 2l3 5H9z"/><path d="M8 7h8v3H8zM5 10h14v11H5zM10 21v-5h4v5"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'news'     => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h10M7 16h6"/>',
        'flag'     => '<path d="M5 21V4h12l-2 4 2 4H5"/>',
        'video'    => '<rect x="3" y="5" width="14" height="14" rx="2"/><path d="M17 10l4-2v8l-4-2"/>',
        'image'    => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><path d="M21 17l-5-5-9 8"/>',
        'pdf'      => '<path d="M6 2h9l5 5v15H6z"/><path d="M14 2v6h6M9 14h6M9 18h6"/>',
        'pin'      => '<path d="M12 21s-7-6-7-12a7 7 0 0 1 14 0c0 6-7 12-7 12z"/><circle cx="12" cy="9" r="2.5"/>',
        'phone'    => '<path d="M5 4h4l2 5-3 2a11 11 0 0 0 5 5l2-3 5 2v4a2 2 0 0 1-2 2A17 17 0 0 1 3 6a2 2 0 0 1 2-2"/>',
        'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>',
        'left'     => '<path d="M15 6l-6 6 6 6"/>',
        'right'    => '<path d="M9 6l6 6-6 6"/>',
        'lamp'     => '<path d="M12 3c2 3 3 5 3 7a3 3 0 0 1-6 0c0-2 1-4 3-7z"/><path d="M6 21h12M9 17h6l-1 4h-4z"/>',
        'home'     => '<path d="M3 21V10l9-6 9 6v11M9 21v-6h6v6"/>',
        'menu'     => '<path d="M4 6h16M4 12h16M4 18h16"/>',
    ];
    $svg = fn($name, $size = 22, $extra = '') => '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" '.$extra.'>'.($icons[$name] ?? '').'</svg>';
@endphp
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName }} | जैन धर्म — ग्रंथ, भजन, प्रवचन, तीर्थ क्षेत्र एवं दैनिक पंचांग</title>
    <meta name="description" content="{{ $about }}">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ $siteName }}">
    <meta property="og:description" content="{{ $tagline }}">
    <meta property="og:image" content="{{ asset('images/og-share.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icons/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icons/icon-192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/apple-touch-icon.png') }}">
    <meta name="theme-color" content="#C2410C">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/site-v1.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700&family=Noto+Serif+Devanagari:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --ink:#1C1917; --ink-2:#44403C; --muted:#57534E; --line:#E7E5E4; --line-2:#F5F5F4;
            --saffron:#C2410C; --saffron-d:#9A3412; --saffron-dd:#7C2D12; --brown:#431407; --brown-d:#2A0E05;
            --cream:#FFF7ED; --cream-2:#FFEDD5; --peach:#FED7AA; --peach-2:#FDBA74; --green:#15803D;
            --serif:'Noto Serif Devanagari', serif; --sans:'Mukta', system-ui, sans-serif;
            --wrap:1240px; --gut:clamp(16px,4vw,32px);
        }
        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{margin:0;background:#fff;color:var(--ink);font-family:var(--sans);font-size:17px;line-height:1.55}
        a{color:var(--saffron-d);text-decoration:none}
        a:hover{color:var(--saffron-dd);text-decoration:underline}
        img{max-width:100%;display:block}
        h1,h2,h3{margin:0}
        .wrap{max-width:var(--wrap);margin:0 auto;padding-left:var(--gut);padding-right:var(--gut)}
        .sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}
        .serif{font-family:var(--serif)}
        .eyebrow{font-size:14px;font-weight:700;color:var(--saffron-d);letter-spacing:.06em}
        .h2{font-family:var(--serif);font-size:clamp(24px,3vw,30px);line-height:1.3;margin-top:4px}
        .sec-head{display:flex;flex-wrap:wrap;gap:8px 16px;justify-content:space-between;align-items:flex-end;margin-bottom:22px}
        .more{font-weight:600;white-space:nowrap}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:48px;padding:0 24px;border-radius:12px;font-weight:700;font-size:16px;text-decoration:none;border:0;cursor:pointer;font-family:inherit}
        .btn:hover{text-decoration:none}
        .btn-primary{background:var(--saffron);color:#fff}.btn-primary:hover{background:var(--saffron-d);color:#fff}
        .btn-outline{background:#fff;color:var(--saffron-d);border:1.5px solid var(--saffron-d)}
        .btn-green{background:var(--green);color:#fff}.btn-green:hover{color:#fff;background:#166534}
        .pill{min-height:44px;padding:0 18px;border-radius:999px;font:inherit;font-size:16px;font-weight:600;cursor:pointer;border:1px solid #D6D3D1;background:#fff;color:var(--ink)}
        .pill.on{background:var(--saffron);border-color:var(--saffron);color:#fff}
        .icon-btn{width:44px;height:44px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;border:1px solid #D6D3D1;background:#fff;color:var(--ink)}
        .icon-btn.fill{background:var(--saffron);border-color:var(--saffron);color:#fff}
        :focus-visible{outline:3px solid #F59E0B;outline-offset:2px}

        /* Hero */
        .hero{background:var(--cream);border-bottom:1px solid var(--peach)}
        .hero .wrap{display:flex;flex-wrap:wrap;gap:40px;align-items:center;padding-top:clamp(32px,6vw,64px);padding-bottom:clamp(48px,7vw,80px)}
        .hero-text{flex:1 1 460px;min-width:0;display:flex;flex-direction:column;gap:20px}
        .hero-tag{align-self:flex-start;font-size:14px;font-weight:700;color:var(--saffron-d);background:var(--cream-2);padding:6px 14px;border-radius:999px}
        .hero h1{font-family:var(--serif);font-size:clamp(32px,5vw,54px);line-height:1.22;color:var(--ink)}
        .hero p{margin:0;font-size:19px;line-height:1.6;color:var(--ink-2);max-width:560px}
        .hero-ctas{display:flex;flex-wrap:wrap;gap:12px}
        .hero-ctrl{display:flex;align-items:center;gap:14px;margin-top:6px}
        .dots{display:flex;gap:8px}
        .dot{height:12px;width:12px;border:0;border-radius:999px;background:var(--peach-2);cursor:pointer;padding:0;transition:width .25s}
        .dot.on{width:32px;background:var(--saffron)}
        .hero-media{flex:1 1 420px;min-width:0;position:relative}
        .hero-frame{height:clamp(300px,38vw,440px);border-radius:24px;background:var(--peach);border:8px solid #fff;box-shadow:0 20px 50px rgba(124,45,18,.18);overflow:hidden}
        .hero-frame img{width:100%;height:100%;object-fit:cover}
        .hero-frame img.contain{object-fit:contain;background:#FFFBF5}
        .hero-note{position:absolute;left:24px;right:24px;bottom:-22px;background:var(--brown);color:#fff;border-radius:16px;padding:14px 20px;display:flex;gap:14px;align-items:center}
        .hero-note small{display:block;font-size:13px;font-weight:700;color:var(--peach-2)}
        .hero-note span{font-size:16px;font-weight:600;line-height:1.45}
        .slide{display:none}.slide.on{display:contents}

        /* Search band */
        .search-band{background:var(--brown);color:#fff}
        .search-band .wrap{max-width:1000px;display:flex;flex-direction:column;gap:18px;align-items:center;text-align:center;padding-top:clamp(40px,6vw,64px);padding-bottom:clamp(40px,6vw,64px)}
        .search-band h2{font-family:var(--serif);font-size:clamp(26px,4vw,38px);line-height:1.25}
        .search-band p{margin:0;font-size:18px;color:var(--cream-2)}
        .scat{display:flex;flex-wrap:wrap;gap:8px;justify-content:center}
        .scat .pill{background:transparent;border-color:var(--saffron-d);color:var(--cream-2)}
        .scat .pill.on{background:#fff;border-color:#fff;color:var(--brown)}
        .sform{width:100%;display:flex;flex-wrap:wrap;gap:8px;background:#fff;border-radius:14px;padding:8px}
        .sform .in{flex:1 1 280px;display:flex;align-items:center;gap:10px;padding:0 12px;color:var(--muted)}
        .sform input{flex:1;min-width:0;border:0;outline:none;font:inherit;font-size:18px;color:var(--ink);padding:12px 0;background:transparent}
        .popular{display:flex;flex-wrap:wrap;gap:8px 10px;justify-content:center;align-items:center;font-size:15px}
        .popular span{color:var(--peach)}
        .popular a{color:#fff;padding:6px 14px;border:1px solid var(--saffron-d);border-radius:999px}
        .popular a:hover{background:var(--saffron-d);text-decoration:none}

        /* Categories */
        .section{padding-top:56px;padding-bottom:56px}
        .cat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px}
        .cat{display:flex;gap:16px;align-items:center;padding:18px;border:1px solid var(--line);border-radius:14px;color:var(--ink);background:#fff;transition:border-color .2s, box-shadow .2s, transform .2s}
        .cat:hover{text-decoration:none;color:var(--ink);border-color:var(--peach-2);box-shadow:0 10px 24px rgba(124,45,18,.08);transform:translateY(-2px)}
        .cat-ic{flex:none;width:52px;height:52px;border-radius:12px;background:var(--cream);color:var(--saffron-d);display:flex;align-items:center;justify-content:center}
        .cat b{display:block;font-size:19px}
        .cat small{display:block;font-size:15px;color:var(--muted)}
        .cat .count{margin-left:auto;font-size:13px;font-weight:700;color:var(--saffron-d);background:var(--cream);padding:2px 10px;border-radius:999px;white-space:nowrap}

        /* Today */
        .today{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px}
        .card{border:1px solid var(--line);border-radius:16px;padding:26px;display:flex;flex-direction:column;gap:14px;background:#fff}
        .card.warm{background:var(--cream);border-color:transparent}
        .card .lbl{font-size:15px;font-weight:700;color:var(--saffron-d)}
        .quote{margin:0;font-family:var(--serif);font-size:22px;line-height:1.55;font-weight:600}
        .kv{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px 16px;font-size:16px}
        .kv small{display:block;color:var(--muted);font-size:14px}
        .kv b{font-weight:600}
        .note{padding:12px 14px;background:var(--line-2);border-radius:10px;font-size:15px}
        .badge-live{font-size:13px;font-weight:700;color:#166534;background:#DCFCE7;padding:4px 10px;border-radius:999px}
        .mt-auto{margin-top:auto}

        /* Varsha yog */
        .vy{background:#FFFBF5;border-top:1px solid var(--peach)}
        .vy-head{display:flex;flex-direction:column;align-items:center;gap:12px;text-align:center;margin-bottom:32px}
        .live-pill{display:inline-flex;align-items:center;gap:8px;font-size:14px;font-weight:700;color:var(--saffron-d);background:var(--cream-2);border:1px solid var(--peach-2);padding:6px 16px;border-radius:999px}
        .live-dot{width:9px;height:9px;border-radius:50%;background:#DC2626;animation:pulse 1.6s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.35}}
        @media (prefers-reduced-motion: reduce){.live-dot{animation:none}html{scroll-behavior:auto}}
        .vy-head h2{font-family:var(--serif);font-size:clamp(26px,4vw,40px);line-height:1.3;color:var(--saffron-dd)}
        .vy-loc{display:flex;flex-wrap:wrap;gap:8px 12px;justify-content:center;align-items:center;font-size:16px;color:var(--ink-2)}
        .vy-loc .yb{font-size:13px;font-weight:700;color:#713F12;background:#FDE047;padding:4px 12px;border-radius:999px}
        .vy-body{display:flex;flex-wrap:wrap;gap:24px;align-items:flex-start}
        .poster{flex:1 1 360px;min-width:0;background:#fff;border:1px solid var(--peach);border-radius:20px;padding:16px;display:flex;flex-direction:column;gap:14px}
        .poster-img{position:relative;display:block;border-radius:14px;overflow:hidden;background:var(--peach)}
        .poster-img img{width:100%;height:auto;max-height:640px;object-fit:contain;background:#FFFBF5}
        .poster-img .zoom{position:absolute;left:50%;bottom:14px;transform:translateX(-50%);display:inline-flex;gap:6px;align-items:center;white-space:nowrap;background:#FACC15;color:var(--ink);font-size:14px;font-weight:700;padding:8px 14px;border-radius:8px}
        .poster-actions{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:8px}
        .poster-actions a{min-height:44px;display:flex;align-items:center;justify-content:center;border-radius:10px;font-size:14px;font-weight:700;text-align:center}
        .poster-actions a:hover{text-decoration:none;filter:brightness(.97)}
        .pa1{background:var(--cream);border:1px solid var(--peach-2);color:var(--saffron-d)}
        .pa2{background:#EFF6FF;border:1px solid #93C5FD;color:#1E40AF}
        .pa3{background:#F0FDF4;border:1px solid #86EFAC;color:#166534}
        .pa4{background:#FEFCE8;border:1px solid #FDE047;color:#854D0E}
        .vy-right{flex:1.4 1 480px;min-width:0;display:flex;flex-direction:column;gap:20px}
        .box{background:#fff;border:1px solid var(--line);border-radius:18px;padding:22px}
        .box.hl{border:1.5px solid var(--peach-2)}
        .box-h{display:flex;flex-wrap:wrap;gap:8px;justify-content:space-between;align-items:center;padding-bottom:14px;margin-bottom:14px;border-bottom:1px solid var(--line-2)}
        .box-h h3{display:flex;gap:10px;align-items:center;font-size:19px;font-weight:700;color:var(--saffron-dd)}
        .box-ic{width:38px;height:38px;border-radius:50%;background:var(--cream-2);color:var(--saffron-d);display:flex;align-items:center;justify-content:center}
        .row-list{display:flex;flex-direction:column;gap:10px}
        .row-list div{border-radius:10px;padding:12px 16px}
        .row-list small,.vgrid small{display:block;font-size:13px;color:var(--muted);font-weight:600}
        .row-list b,.vgrid b{font-size:17px}
        .vgrid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:10px}
        .vgrid div{border:1px solid var(--peach);border-radius:12px;padding:14px 16px}
        .chips{display:flex;flex-wrap:wrap;gap:8px}
        .chip{display:inline-flex;gap:8px;align-items:center;min-height:44px;padding:4px 6px 4px 12px;border:1px solid var(--line);border-radius:10px;color:var(--ink);font-size:15px;font-weight:600;background:#fff}
        .chip:hover{text-decoration:none;border-color:#86EFAC;color:var(--ink)}
        .chip em{font-style:normal;font-size:14px;background:var(--line-2);color:var(--ink-2);padding:5px 10px;border-radius:7px}
        .linkstrip{display:flex;flex-wrap:wrap;gap:10px 20px;justify-content:space-between;align-items:center;padding:14px 18px;border:1.5px dashed var(--peach-2);border-radius:14px;background:#fff;font-size:15px}

        /* News + events */
        .tint{background:#FAFAF9;border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
        .two{display:flex;flex-wrap:wrap;gap:40px}
        .two .main{flex:999 1 560px;min-width:0}
        .two .side{flex:1 1 320px;min-width:0}
        .stack{display:flex;flex-direction:column;gap:14px}
        .news{display:flex;gap:16px;align-items:center;padding:14px;background:#fff;border:1px solid var(--line);border-radius:14px;color:var(--ink)}
        .news:hover{text-decoration:none;color:var(--ink);border-color:var(--peach-2)}
        .news img,.news .ph{flex:none;width:112px;height:80px;border-radius:10px;object-fit:cover;background:var(--cream-2)}
        .news small{display:block;font-size:14px;color:var(--muted)}
        .news small b{color:var(--saffron-d)}
        .news strong{display:block;font-size:18px;line-height:1.4}
        .event{display:flex;gap:14px;padding:14px;background:#fff;border:1px solid var(--line);border-radius:14px;color:var(--ink)}
        .event:hover{text-decoration:none;color:var(--ink);border-color:var(--peach-2)}
        .edate{flex:none;width:64px;height:64px;border-radius:12px;background:var(--brown);color:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;line-height:1.1;text-align:center}
        .edate b{font-size:22px}.edate small{font-size:12px}
        .event strong{display:block;font-size:17px;line-height:1.4}
        .event span{font-size:15px;color:var(--muted)}
        .empty{padding:18px;border:1px dashed var(--line);border-radius:12px;color:var(--muted);text-align:center}

        /* Tirth carousel */
        .track{display:flex;gap:20px;overflow-x:auto;scroll-snap-type:x mandatory;scroll-behavior:smooth;padding:4px 2px 16px}
        .tcard{flex:0 0 min(340px,85%);scroll-snap-align:start;border:1px solid var(--line);border-radius:16px;overflow:hidden;display:flex;flex-direction:column;background:#fff}
        .tcard[hidden]{display:none}
        .tcard img{height:160px;width:100%;object-fit:cover}
        .tbody{padding:20px;display:flex;flex-direction:column;gap:10px;flex:1}
        .tbody .k{font-size:13px;font-weight:700;color:var(--saffron-d)}
        .tbody h3{font-size:20px;line-height:1.35}
        .tbody p{margin:0;font-size:15.5px;color:var(--ink-2)}
        .tmeta{display:flex;flex-direction:column;gap:6px;font-size:15px;color:var(--ink-2);padding-top:8px;border-top:1px solid var(--line-2)}
        .tmeta div{display:flex;gap:8px;align-items:center}
        .tmeta svg{color:var(--saffron-d);flex:none}
        .tact{display:flex;gap:8px;margin-top:auto;padding-top:8px}
        .tact a{flex:1;text-align:center;padding:11px;border-radius:10px;font-weight:600;font-size:15px}
        .tact a:hover{text-decoration:none}
        .tact .a1{background:var(--saffron);color:#fff}
        .tact .a2{border:1px solid #D6D3D1;color:var(--ink)}

        /* Media */
        .dark{background:var(--ink);color:#fff}
        .dark .eyebrow{color:var(--peach-2)}
        .tabs{display:flex;flex-wrap:wrap;gap:6px;background:#292524;padding:6px;border-radius:12px}
        .tab{min-height:44px;padding:0 18px;border:0;border-radius:8px;font:inherit;font-size:16px;font-weight:600;cursor:pointer;background:transparent;color:#E7E5E4}
        .tab[aria-selected="true"]{background:#fff;color:var(--ink)}
        .mgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px}
        .mitem{display:flex;gap:12px;align-items:center;padding:10px;border-radius:12px;background:#292524;color:#fff;min-width:0}
        .mitem:hover{text-decoration:none;color:#fff;background:#3A3532}
        .mthumb{flex:none;width:84px;height:62px;border-radius:8px;background:#44403C;display:flex;align-items:center;justify-content:center}
        .mthumb span{width:36px;height:36px;border-radius:50%;background:var(--saffron);display:flex;align-items:center;justify-content:center}
        .mtext{min-width:0;display:flex;flex-direction:column;gap:2px}
        .mtext b{font-size:16px;line-height:1.35;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}
        .mtext small{font-size:13px;color:#D6D3D1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .mtext i{font-style:normal;font-size:12px;font-weight:700;color:var(--peach-2)}
        .mpanel[hidden]{display:none}
        .mmore{margin-top:20px;text-align:center}
        .mmore a{display:inline-flex;align-items:center;min-height:44px;padding:0 22px;border-radius:10px;border:1px solid var(--muted);color:#fff;font-weight:600}
        .mmore a:hover{text-decoration:none;background:#292524}

        /* Saints */
        .sgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px}
        .saint{display:flex;gap:14px;align-items:center;padding:16px;border:1px solid var(--line);border-radius:14px;color:var(--ink)}
        .saint:hover{text-decoration:none;color:var(--ink);border-color:var(--peach-2)}
        .saint img{flex:none;width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--peach-2)}
        .saint b{display:block;font-size:18px;line-height:1.35}
        .saint small{display:block;font-size:14px;color:var(--muted)}

    </style>
</head>
<body>


@include('partials.site-header', ['searchMode' => 'anchor'])

<main id="main">

{{-- 1. Hero slider --}}
<section class="hero" aria-label="मुख्य बैनर" aria-roledescription="carousel">
    <div class="wrap" id="hero">
        @foreach($slides as $i => $sl)
            <div class="slide {{ $i === 0 ? 'on' : '' }}" data-slide="{{ $i }}" role="group" aria-roledescription="slide" aria-label="बैनर {{ $i + 1 }} / {{ count($slides) }}">
                <div class="hero-text">
                    <span class="hero-tag">{{ $sl['tag'] }}</span>
                    @if($i === 0)
                        <h1>{{ $sl['title'] }}</h1>
                    @else
                        <h2 class="serif" style="font-size:clamp(32px,5vw,54px);line-height:1.22">{{ $sl['title'] }}</h2>
                    @endif
                    <p>{{ $sl['text'] }}</p>
                    <div class="hero-ctas">
                        <a href="{{ $sl['cta1'][1] }}" class="btn btn-primary" @if(\Illuminate\Support\Str::startsWith($sl['cta1'][1], 'http') && !\Illuminate\Support\Str::startsWith($sl['cta1'][1], url('/'))) target="_blank" rel="noopener" @endif>{{ $sl['cta1'][0] }}</a>
                        <a href="{{ $sl['cta2'][1] }}" class="btn btn-outline" @if(\Illuminate\Support\Str::startsWith($sl['cta2'][1], 'http') && !\Illuminate\Support\Str::startsWith($sl['cta2'][1], url('/'))) target="_blank" rel="noopener" @endif>{{ $sl['cta2'][0] }}</a>
                    </div>
                    <div class="hero-ctrl">
                        <button type="button" class="icon-btn" data-hero="prev" aria-label="पिछला बैनर">{!! $svg('left', 20) !!}</button>
                        <div class="dots">
                            @foreach($slides as $j => $_)
                                <button type="button" class="dot {{ $j === $i ? 'on' : '' }}" data-go="{{ $j }}" aria-label="बैनर {{ $j + 1 }}"></button>
                            @endforeach
                        </div>
                        <button type="button" class="icon-btn" data-hero="next" aria-label="अगला बैनर">{!! $svg('right', 20) !!}</button>
                        <span style="font-size:15px;color:var(--muted);font-weight:600">{{ $i + 1 }} / {{ count($slides) }}</span>
                    </div>
                </div>
                <div class="hero-media">
                    <div class="hero-frame"><img src="{{ $sl['img'] }}" alt="{{ $sl['title'] }}" class="{{ $sl['fit'] === 'contain' ? 'contain' : '' }}" @if($i > 0) loading="lazy" @endif></div>
                    <div class="hero-note">
                        <span style="color:var(--peach-2);display:flex">{!! $svg('lamp', 28) !!}</span>
                        <div><small>दैनिक प्रेरणा</small><span>"अप्प दीपो भव — अपनी आत्मा के स्वयं दीपक बनें।"</span></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- 2. Search --}}
<section class="search-band">
    <div class="wrap">
        <span style="font-size:15px;letter-spacing:.08em;color:var(--peach)">सत्य · अहिंसा · अपरिग्रह · अनेकांत</span>
        <h2>आप क्या खोजना चाहते हैं?</h2>
        <p>ग्रंथ, भजन, प्रवचन, संत, समाचार — सब कुछ एक ही जगह से खोजें।</p>
        <div class="scat" role="group" aria-label="किसमें खोजें">
            @foreach([['all','सब कुछ','ग्रंथ, भजन, संत या कोई भी शब्द लिखें…'],['granth','ग्रंथ','ग्रंथ का नाम या रचयिता — जैसे समयसार'],['bhajan','भजन / आरती','भजन, आरती या स्तोत्र का नाम'],['pravachan','प्रवचन','संत का नाम या प्रवचन का विषय'],['saint','संत','आचार्य / मुनि / आर्यिका का नाम'],['news','समाचार','समाचार खोजें'],['event','कार्यक्रम','कार्यक्रम या स्थान का नाम']] as $k => $c)
                <button type="button" class="pill {{ $k === 0 ? 'on' : '' }}" data-tab="{{ $c[0] }}" data-ph="{{ $c[2] }}" aria-pressed="{{ $k === 0 ? 'true' : 'false' }}">{{ $c[1] }}</button>
            @endforeach
        </div>
        <form class="sform" action="{{ route('search') }}" method="GET" role="search">
            <input type="hidden" name="tab" id="searchTab" value="all">
            <label for="q" class="sr">खोजें</label>
            <div class="in">{!! $svg('search', 22) !!}<input id="q" type="search" name="q" placeholder="ग्रंथ, भजन, संत या कोई भी शब्द लिखें…" required></div>
            <button type="submit" class="btn btn-primary" style="min-height:52px;font-size:18px">खोजें</button>
        </form>
        <div class="popular">
            <span>लोकप्रिय:</span>
            <a href="{{ route('search', ['q' => 'भक्तामर']) }}">भक्तामर स्तोत्र</a>
            <a href="{{ route('search', ['q' => 'तत्त्वार्थ']) }}">तत्त्वार्थ सूत्र</a>
            <a href="{{ route('search', ['q' => 'मेरी भावना']) }}">मेरी भावना</a>
            <a href="#tirth">सम्मेद शिखरजी</a>
            <a href="{{ route('panchang.index') }}">आज का पंचांग</a>
        </div>
    </div>
</section>

{{-- 3. Categories --}}
<section class="wrap section" style="padding-bottom:24px">
    <div class="sec-head"><div><div class="eyebrow">सभी श्रेणियाँ</div><h2 class="h2">किस दिशा में जाना है? एक चुनें</h2></div></div>
    <div class="cat-grid">
        @foreach($categories as $c)
            <a href="{{ $c[2] }}" class="cat">
                <span class="cat-ic">{!! $svg($c[3], 26) !!}</span>
                <span><b>{{ $c[0] }}</b><small>{{ $c[1] }}</small></span>
                @if($c[4] > 0)<span class="count">{{ $c[4] }}</span>@endif
            </a>
        @endforeach
    </div>
</section>

{{-- 4. Today --}}
<section class="wrap section" style="padding-top:32px">
    <div class="eyebrow">आज</div>
    <h2 class="h2" style="margin-bottom:22px">आज का दिन एक नज़र में</h2>
    <div class="today">
        <div class="card warm">
            <div class="lbl">आज का सुविचार</div>
            <p class="quote">"{{ $qText }}"</p>
            <div style="color:var(--muted)">— {{ $qAuthor }}</div>
            <div class="mt-auto" style="display:flex;flex-wrap:wrap;gap:8px">
                <a href="{{ $qShare }}" target="_blank" rel="noopener" class="btn btn-green" style="min-height:44px;padding:0 16px;font-size:15px">WhatsApp पर भेजें</a>
                <a href="{{ route('suvichar.index') }}" class="btn btn-outline" style="min-height:44px;padding:0 16px;font-size:15px;border-color:var(--peach-2)">सभी सुविचार</a>
            </div>
        </div>

        <div class="card">
            <div class="lbl">आज का जैन पंचांग</div>
            @if($p)
                <div class="kv">
                    <div><small>माह / पक्ष</small><b>{{ $pMaas ?: '—' }} · {{ $pPaksha ?: '—' }}</b></div>
                    <div><small>तिथि</small><b>{{ $pTithi ?: '—' }}</b></div>
                    <div><small>नक्षत्र</small><b>{{ $pNak ?: '—' }}</b></div>
                    <div><small>सूर्योदय / सूर्यास्त</small><b>{{ $pRise ?: '—' }} / {{ $pSet ?: '—' }}</b></div>
                </div>
                @if($festName || $pNotes)
                    <div class="note"><strong>पर्व / व्रत:</strong> {{ $festName ?: $pNotes }}</div>
                @endif
            @else
                <div class="empty">आज का पंचांग अभी उपलब्ध नहीं है।</div>
            @endif
            <a href="{{ route('panchang.index') }}" class="more mt-auto">पूरा पंचांग देखें →</a>
        </div>

        <div class="card">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
                <div class="lbl">लाइव संघ विहार</div>
                @if($vActive)<span class="badge-live">अद्यतन</span>@endif
            </div>
            <div style="font-size:19px;font-weight:700;line-height:1.4">{{ $vAashir }}</div>
            <div style="display:flex;flex-direction:column;gap:8px;font-size:16px">
                <div><span style="color:var(--muted)">विहार दिशा:</span> {{ $vDisha }}</div>
                <div><span style="color:var(--muted)">वर्तमान प्रवास:</span> {{ $vStay }}</div>
                <div><span style="color:var(--muted)">आहार चर्या:</span> {{ $vAahar }}</div>
            </div>
            <a href="#varsha-yog" class="more mt-auto">वर्षा योग व पूरी विहार जानकारी →</a>
        </div>
    </div>
</section>

{{-- 5. Varsha Yog / Live Sangh --}}
<section class="vy" id="varsha-yog">
    <div class="wrap section">
        <div class="vy-head">
            <span class="live-pill"><span class="live-dot"></span>लाइव संघ प्रवास एवं वर्षा योग सूचना</span>
            <h2>{{ $vTitle }}</h2>
            <div class="vy-loc">
                <span style="display:inline-flex;gap:6px;align-items:center"><span style="color:#DC2626;display:flex">{!! $svg('pin', 18) !!}</span>{{ $vTirth }}</span>
                @if($vSub)<span class="yb">{{ $vSub }}</span>@endif
            </div>
        </div>
        <div class="vy-body">
            <div class="poster">
                <a href="{{ $vPoster }}" target="_blank" rel="noopener" class="poster-img">
                    <img src="{{ $vPoster }}" alt="{{ $vTitle }} पोस्टर" loading="lazy">
                    <span class="zoom">{!! $svg('search', 16) !!} क्लिक कर पूर्ण पोस्टर देखें</span>
                </a>
                <div class="poster-actions">
                    <a href="{{ $vPoster }}" target="_blank" rel="noopener" class="pa1">बड़ा देखें</a>
                    <a href="{{ $vPoster }}" download class="pa2">डाउनलोड</a>
                    <a href="{{ $vShare }}" target="_blank" rel="noopener" class="pa3">शेयर</a>
                    <a href="{{ $vMap }}" target="_blank" rel="noopener" class="pa4">लोकेशन</a>
                </div>
            </div>
            <div class="vy-right">
                <div class="box">
                    <div class="box-h"><h3><span class="box-ic">{!! $svg('lamp', 20) !!}</span>पूज्य संघ सानिध्य एवं पावन आशीष</h3></div>
                    <div class="row-list">
                        <div style="background:#FFFBEB"><small>आशीष छांव</small><b>{{ $vAshish }}</b></div>
                        <div style="background:var(--cream)"><small>आशीर्वाद प्रदाता</small><b>{{ $vAashir }}</b></div>
                        <div style="background:#FAFAF9"><small>पुण्यवर्धन सानिध्य</small><b>{{ $vSanidhya }}</b></div>
                    </div>
                </div>
                <div class="box hl">
                    <div class="box-h">
                        <h3><span class="live-dot" style="width:10px;height:10px"></span>लाइव विहार स्थिति एवं दैनिक चर्या</h3>
                        @if($vActive)<span style="font-size:13px;font-weight:700;color:#854D0E;background:#FEF9C3;border:1px solid #FDE047;padding:4px 12px;border-radius:999px">अद्यतन स्थिति</span>@endif
                    </div>
                    <div class="vgrid">
                        <div><small>विहार दिशा</small><b>{{ $vDisha }}</b></div>
                        <div><small>रात्रि विश्राम स्थल</small><b>{{ $vRatri }}</b></div>
                        <div><small>आहार चर्या समय व स्थल</small><b>{{ $vAahar }}</b></div>
                        <div style="background:var(--cream)"><small>वर्तमान पड़ाव / प्रवास</small><b style="color:#B91C1C">{{ $vStay }}</b></div>
                    </div>
                </div>
                <div class="box">
                    <div class="box-h">
                        <h3><span class="box-ic" style="background:#DCFCE7;color:#166534">{!! $svg('phone', 18) !!}</span>बहु-संपर्क सूत्र</h3>
                        <span style="font-size:14px;color:var(--muted)">व्यवस्थापक एवं समिति पदाधिकारी · क्लिक कर कॉल करें</span>
                    </div>
                    <div class="chips">
                        @foreach($vContacts as $c)
                            @php $cp = preg_replace('/[^0-9+]/', '', $c['phone'] ?? ''); @endphp
                            @if($cp)
                                <a href="tel:{{ $cp }}" class="chip" aria-label="{{ $c['name'] ?? '' }} को कॉल करें">
                                    <span style="color:var(--green);display:flex">{!! $svg('phone', 16) !!}</span>{{ $c['name'] ?? '' }}<em>{{ $c['phone'] }}</em>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="linkstrip">
                    <div style="display:flex;flex-wrap:wrap;gap:8px 20px;align-items:center">
                        <a href="{{ $vWebHref }}" target="_blank" rel="noopener" style="font-weight:700;color:var(--ink)">{{ $vWebsite }}</a>
                        <span style="color:var(--muted)">YouTube: {{ $vYoutube }}</span>
                    </div>
                    <a href="{{ $vQr }}" target="_blank" rel="noopener" class="btn" style="min-height:40px;padding:0 16px;font-size:14px;background:var(--saffron-dd);color:#fff">सम्पूर्ण डिजिटल सामग्री</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 6. News + Events --}}
<section class="tint">
    <div class="wrap section two">
        <div class="main">
            <div class="sec-head"><div><div class="eyebrow">दैनिक अपडेट</div><h2 class="h2">ताज़ा समाचार</h2></div><a href="{{ route('news.index') }}" class="more">सभी समाचार →</a></div>
            <div class="stack">
                @forelse(($news ?? collect())->take(4) as $n)
                    <a href="{{ route('news.show', $n->slug ?: $n->id) }}" class="news">
                        @if($n->featured_image)
                            <img src="{{ $img($n->featured_image) }}" alt="" loading="lazy">
                        @else
                            <span class="ph"></span>
                        @endif
                        <span>
                            <small><b>{{ $n->category ? $tr($n->category->name) : 'समाचार' }}</b>@if($n->publish_date) · {{ $n->publish_date->format('d M Y') }}@endif</small>
                            <strong>{{ $n->getLocalized('title') }}</strong>
                        </span>
                    </a>
                @empty
                    <div class="empty">अभी कोई समाचार उपलब्ध नहीं है।</div>
                @endforelse
            </div>
        </div>
        <div class="side">
            <div class="sec-head"><div><div class="eyebrow">मांगलिक आयोजन</div><h2 class="h2">आगामी कार्यक्रम</h2></div></div>
            <div class="stack">
                @forelse(($events ?? collect())->take(3) as $e)
                    <a href="{{ route('events.show', $e->slug ?: $e->id) }}" class="event">
                        <span class="edate">@if($e->start_date)<b>{{ $e->start_date->format('d') }}</b><small>{{ $e->start_date->locale('hi')->translatedFormat('M Y') }}</small>@else<small>तिथि</small>@endif</span>
                        <span><strong>{{ $tr($e->event_name) }}</strong><span>{{ $tr($e->venue) }}</span></span>
                    </a>
                @empty
                    <div class="empty">कोई आगामी कार्यक्रम नहीं है।</div>
                @endforelse
            </div>
            <a href="{{ route('events.index') }}" class="more" style="display:inline-block;margin-top:16px">पूरा कैलेंडर देखें →</a>
        </div>
    </div>
</section>

{{-- 7. Tirth carousel --}}
<section class="wrap section" id="tirth">
    <div class="sec-head">
        <div><div class="eyebrow">तीर्थ वंदना</div><h2 class="h2">तीर्थ क्षेत्र खोजें — राज्य चुनें</h2></div>
        <div style="display:flex;align-items:center;gap:12px">
            <button type="button" class="icon-btn" data-track="prev" aria-label="पिछले तीर्थ">{!! $svg('left', 20) !!}</button>
            <button type="button" class="icon-btn fill" data-track="next" aria-label="अगले तीर्थ">{!! $svg('right', 20) !!}</button>
        </div>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px" role="group" aria-label="राज्य चुनें" id="stateFilter">
        <button type="button" class="pill on" data-state="" aria-pressed="true">सभी राज्य</button>
        @foreach($tirthStates as $st)
            <button type="button" class="pill" data-state="{{ $st }}" aria-pressed="false">{{ $st }}</button>
        @endforeach
    </div>
    <div style="font-size:15px;color:var(--muted);margin-bottom:12px"><span id="tirthCount">{{ count($tirthList ?? []) }}</span> तीर्थ क्षेत्र · स्वाइप करें या तीर दबाएँ</div>
    <div class="track" id="tirthTrack">
        @foreach(($tirthList ?? []) as $t)
            <article class="tcard" data-state="{{ $stateOf($t) }}">
                <img src="{{ $img($t['image'] ?? null) }}" alt="{{ $t['name'] }}" loading="lazy">
                <div class="tbody">
                    <div class="k">{{ $t['state'] }}</div>
                    <h3>{{ $t['name'] }}</h3>
                    <p>{{ $t['description'] }}</p>
                    <div class="tmeta">
                        <div>{!! $svg('lamp', 18) !!}{{ $t['tirthankara'] }}</div>
                        <div>{!! $svg('home', 18) !!}{{ $t['manager'] }}</div>
                        @if(!empty($t['phone']))<div>{!! $svg('phone', 18) !!}<a href="tel:{{ preg_replace('/[^0-9+]/', '', $t['phone']) }}" style="color:var(--ink)">{{ $t['phone'] }}</a></div>@endif
                    </div>
                    <div class="tact">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $t['phone'] ?? '') }}" class="a1">कॉल करें</a>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($t['map_query'] ?? $t['name']) }}" target="_blank" rel="noopener" class="a2">रास्ता देखें</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- 8. Media --}}
<section class="dark">
    <div class="wrap section">
        <div class="sec-head">
            <div><div class="eyebrow">सुनें · देखें · पढ़ें</div><h2 class="h2">नवीनतम मीडिया</h2></div>
            <div class="tabs" role="tablist" aria-label="मीडिया प्रकार">
                @foreach($mediaTabs as $key => $mt)
                    <button type="button" class="tab" role="tab" id="mt-{{ $key }}" aria-controls="mp-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" data-mtab="{{ $key }}">{{ $mt['label'] }}</button>
                @endforeach
            </div>
        </div>
        @foreach($mediaTabs as $key => $mt)
            <div class="mpanel" id="mp-{{ $key }}" role="tabpanel" aria-labelledby="mt-{{ $key }}" @unless($loop->first) hidden @endunless>
                @if(count($mt['items']))
                    <div class="mgrid">
                        @foreach(array_slice($mt['items'], 0, 8) as $m)
                            <a href="{{ $m['url'] }}" class="mitem" @if(\Illuminate\Support\Str::startsWith($m['url'], 'http') && !\Illuminate\Support\Str::startsWith($m['url'], url('/'))) target="_blank" rel="noopener" @endif>
                                <span class="mthumb"><span>
                                    @if($m['icon'] === 'down')
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 4v11M7 10l5 5 5-5M5 20h14"/></svg>
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                                    @endif
                                </span></span>
                                <span class="mtext"><b>{{ $m['title'] }}</b><small>{{ $m['by'] }}</small><i>{{ $m['meta'] }}</i></span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty" style="border-color:#44403C;color:#D6D3D1">इस श्रेणी में अभी सामग्री नहीं है।</div>
                @endif
                <div class="mmore"><a href="{{ $mt['url'] }}">{{ $mt['more'] }} →</a></div>
            </div>
        @endforeach
    </div>
</section>

{{-- 9. Saints --}}
<section class="wrap section">
    <div class="sec-head"><div><div class="eyebrow">त्याग एवं तपस्या</div><h2 class="h2">पूज्य साधु संघ</h2></div><a href="{{ route('sants.index') }}" class="more">सभी संत →</a></div>
    <div class="sgrid">
        @forelse(($saints ?? collect())->take(6) as $saint)
            @php $cv = $saint->vihars->first(); @endphp
            <a href="{{ route('sants.show', $saint->slug ?: $saint->id) }}" class="saint">
                <img src="{{ $img($saint->photo, 'images/jain/muni_vidyasagar.jpg') }}" alt="{{ $tr($saint->name) }}" loading="lazy">
                <span>
                    <b>{{ $tr($saint->name) }}</b>
                    <small>{{ $cv ? 'वर्तमान विहार: ' . $cv->current_location : ($tr($saint->title) ?: 'पूज्य संत') }}</small>
                </span>
            </a>
        @empty
            <div class="empty">संत परिचय शीघ्र उपलब्ध होगा।</div>
        @endforelse
    </div>
</section>

</main>

@include('partials.site-footer')

<script>
(function () {
    // Hero slider
    var slides = document.querySelectorAll('#hero .slide'), cur = 0;
    function show(i) {
        cur = (i + slides.length) % slides.length;
        slides.forEach(function (s, k) { s.classList.toggle('on', k === cur); });
    }
    document.querySelectorAll('#hero [data-hero="prev"]').forEach(function (b) { b.addEventListener('click', function () { show(cur - 1); }); });
    document.querySelectorAll('#hero [data-hero="next"]').forEach(function (b) { b.addEventListener('click', function () { show(cur + 1); }); });
    document.querySelectorAll('#hero [data-go]').forEach(function (b) { b.addEventListener('click', function () { show(parseInt(b.getAttribute('data-go'), 10)); }); });

    // Search category pills
    var q = document.getElementById('q'), tabIn = document.getElementById('searchTab');
    document.querySelectorAll('.scat .pill').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('.scat .pill').forEach(function (o) { o.classList.remove('on'); o.setAttribute('aria-pressed', 'false'); });
            b.classList.add('on'); b.setAttribute('aria-pressed', 'true');
            if (tabIn) tabIn.value = b.getAttribute('data-tab');
            if (q) { q.placeholder = b.getAttribute('data-ph'); q.focus(); }
        });
    });

    // Tirth state filter + carousel arrows
    var track = document.getElementById('tirthTrack'), countEl = document.getElementById('tirthCount');
    document.querySelectorAll('#stateFilter .pill').forEach(function (b) {
        b.addEventListener('click', function () {
            var st = b.getAttribute('data-state'), n = 0;
            document.querySelectorAll('#stateFilter .pill').forEach(function (o) { o.classList.remove('on'); o.setAttribute('aria-pressed', 'false'); });
            b.classList.add('on'); b.setAttribute('aria-pressed', 'true');
            track.querySelectorAll('.tcard').forEach(function (c) {
                var ok = !st || c.getAttribute('data-state') === st;
                c.hidden = !ok; if (ok) n++;
            });
            if (countEl) countEl.textContent = n;
            track.scrollLeft = 0;
        });
    });
    document.querySelectorAll('[data-track]').forEach(function (b) {
        b.addEventListener('click', function () {
            track.scrollBy({ left: (b.getAttribute('data-track') === 'next' ? 1 : -1) * 360, behavior: 'smooth' });
        });
    });

    // Media tabs
    var tabs = document.querySelectorAll('[data-mtab]');
    tabs.forEach(function (t) {
        t.addEventListener('click', function () {
            tabs.forEach(function (o) {
                var on = o === t;
                o.setAttribute('aria-selected', on ? 'true' : 'false');
                var p = document.getElementById('mp-' + o.getAttribute('data-mtab'));
                if (p) p.hidden = !on;
            });
        });
    });
})();
</script>
</body>
</html>
