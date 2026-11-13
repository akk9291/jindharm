<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saint;
use App\Models\Content;
use App\Models\Event;
use App\Models\Panchang;
use App\Models\Festival;
use App\Models\Album;
use App\Models\AlbumPhoto;
use App\Models\DailyQuote;
use App\Models\Category;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show the new homepage (design v1 – search-first, category directory).
     */
    public function index()
    {
        return view('home.index', $this->homeData());
    }

    /**
     * Previous "portal" homepage, kept available at /home-portal.
     */
    public function portalV1()
    {
        return view('home.index_portal_v1', $this->homeData());
    }

    /**
     * Shared data for the homepage views.
     */
    private function homeData(): array
    {
        $today = Carbon::today()->toDateString();

        // 1. Today's Featured Suvichar / Daily Quotes
        $todayQuote = DailyQuote::active()->where('is_featured', true)->orderBy('quote_date', 'desc')->first()
            ?? DailyQuote::active()->whereDate('quote_date', $today)->first()
            ?? DailyQuote::active()->orderBy('quote_date', 'desc')->first();

        $recentQuotes = DailyQuote::active()->orderBy('quote_date', 'desc')->take(6)->get();

        // 2. Today's Jain Panchang
        $todayPanchang = Panchang::whereDate('date', $today)->first() 
            ?? Panchang::orderBy('date', 'desc')->first();

        // 3. Granths (Scriptures)
        $granths = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'granth');
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('display_order', 'asc')
        ->take(8)
        ->get();

        // 4. Latest Pravachans
        $pravachans = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'pravachan');
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('publish_date', 'desc')
        ->take(8)
        ->get();

        // 5. Bhajans, Stutis & Kavitayein
        $bhajans = Content::whereHas('contentType', function($q) {
            $q->whereIn('slug', ['bhajan', 'stuti']);
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('publish_date', 'desc')
        ->take(8)
        ->get();

        // 6. PDF E-Library Items
        $pdfLibrary = Content::whereNotNull('pdfs')
            ->where('status', 'published')
            ->where('is_active', true)
            ->orderBy('publish_date', 'desc')
            ->take(6)
            ->get();

        // 7. Latest News & Samachar
        $news = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'news');
        })
        ->with('category')
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('publish_date', 'desc')
        ->take(6)
        ->get();

        // 8. Featured Saints & Sangh
        $saints = Saint::with(['vihars' => function($q) {
            $q->where('type', 'current')->where('is_active', true);
        }])
        ->where('is_active', true)
        ->orderBy('display_order', 'asc')
        ->take(6)
        ->get();

        // 9. Upcoming Events & Festivals
        $events = Event::where('is_active', true)
            ->where('start_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->take(4)
            ->get();

        if ($events->isEmpty()) {
            $events = Event::where('is_active', true)->orderBy('start_date', 'desc')->take(4)->get();
        }

        $festivals = Festival::where('is_active', true)->take(4)->get();

        // 10. Videos Gallery
        $videos = Content::whereNotNull('videos')
            ->where('status', 'published')
            ->where('is_active', true)
            ->orderBy('publish_date', 'desc')
            ->take(6)
            ->get();

        // 11. Photo Albums (Folders)
        $albums = Album::where('is_active', true)
            ->withCount('photos')
            ->orderBy('display_order', 'asc')
            ->take(4)
            ->get();

        // 12. Tirth Kshetra & Temple Directory Data
        $tirthList = [
            [
                'id' => 1,
                'name' => 'श्री सम्मेद शिखर जी महातीर्थ',
                'state' => 'झारखण्ड (गिरिडीह)',
                'tirthankara' => '२० तीर्थंकरों की निर्वाण भूमि',
                'description' => 'जैन धर्म का सर्वोच्च एवं सर्वोत्कृष्ट सिद्धक्षेत्र जहां से बीस तीर्थंकर मोक्ष पधारे।',
                'phone' => '+91 6558 232235',
                'manager' => 'दिगम्बर जैन संस्थान, मधुबन',
                'image' => '/images/jain/temple_shikhar.jpg',
                'map_query' => 'Shikharji Madhuban Jharkhand',
                'is_featured' => true,
            ],
            [
                'id' => 2,
                'name' => 'श्री कुण्डलपुर महातीर्थ क्षेत्र',
                'state' => 'मध्य प्रदेश (दमोह)',
                'tirthankara' => 'श्री 1008 बड़े बाबा (ऋषभदेव)',
                'description' => 'विश्व प्रसिद्ध अतिशय क्षेत्र जहां बड़े बाबा की अलौकिक पद्मासन प्रतिमा एवं भव्य सहस्रकूट जिनालय स्थित है।',
                'phone' => '+91 7812 284224',
                'manager' => 'कुण्डलपुर क्षेत्र प्रबंधक कार्यालय',
                'image' => '/images/jain/tirthankara_idol.jpg',
                'map_query' => 'Kundalpur Damoh Madhya Pradesh',
                'is_featured' => true,
            ],
            [
                'id' => 3,
                'name' => 'श्री गिरनार जी सिद्धक्षेत्र',
                'state' => 'गुजरात (जूनागढ़)',
                'tirthankara' => 'भगवान नेमिनाथ (२२वें तीर्थंकर)',
                'description' => 'भगवान नेमिनाथ की दीक्षा, केवलज्ञान एवं पावन मोक्ष स्थली (५वीं टोंक)।',
                'phone' => '+91 285 2621008',
                'manager' => 'श्री दिगम्बर जैन धर्मशाला, जूनागढ़',
                'image' => '/images/jain/temple_shikhar.jpg',
                'map_query' => 'Girnarji Junagadh Gujarat',
                'is_featured' => true,
            ],
            [
                'id' => 4,
                'name' => 'श्री पावापुरी जी जलमंदिर',
                'state' => 'बिहार (नालंदा)',
                'tirthankara' => 'भगवान महावीर स्वामी (२४वें तीर्थंकर)',
                'description' => 'अंतिम तीर्थंकर भगवान महावीर स्वामी की पावन निर्वाण भूमि एवं कमल सरोवर के मध्य स्थित भव्य जलमंदिर।',
                'phone' => '+91 6112 274205',
                'manager' => 'श्री दिगम्बर जैन पावापुरी ट्रस्ट',
                'image' => '/images/jain/tirthankara_idol.jpg',
                'map_query' => 'Pawapuri Jalmandir Nalanda Bihar',
                'is_featured' => true,
            ],
            [
                'id' => 5,
                'name' => 'श्री श्रवणबेलगोला तीर्थ',
                'state' => 'कर्नाटक (हासन)',
                'tirthankara' => 'भगवान बाहुबली (गोमटेश्वर)',
                'description' => 'विश्व की अद्वितीय ५७ फीट उत्तुंग एक पाषाण गोमटेश्वर बाहुबली स्वामी की विशाल पद्मासन/खड्गासन प्रतिमा।',
                'phone' => '+91 8176 257226',
                'manager' => 'श्री क्षेत्र सेवा समिति, श्रवणबेलगोला',
                'image' => '/images/jain/temple_shikhar.jpg',
                'map_query' => 'Shravanabelagola Karnataka',
                'is_featured' => false,
            ],
            [
                'id' => 6,
                'name' => 'श्री पार्श्वनाथ तीर्थ भेलूपुर',
                'state' => 'उत्तर प्रदेश (वाराणसी)',
                'tirthankara' => 'भगवान पार्श्वनाथ (२३वें तीर्थंकर)',
                'description' => 'भगवान पार्श्वनाथ की गर्भ, जन्म एवं तप कल्याणक स्थली। वर्तमान चातुर्मास / वर्षा योग केंद्र।',
                'phone' => '+91 9415201372',
                'manager' => 'भेलूपुर दिगम्बर जैन समाज कार्यालय',
                'image' => '/images/jain/temple_shikhar.jpg',
                'map_query' => 'Bhelupur Jain Mandir Varanasi UP',
                'is_featured' => true,
            ]
        ];

        // 13. Portal Counts & Statistics
        $stats = [
            'granths' => Content::whereHas('contentType', fn($q)=>$q->where('slug', 'granth'))->count(),
            'pravachans' => Content::whereHas('contentType', fn($q)=>$q->where('slug', 'pravachan'))->count(),
            'bhajans' => Content::whereHas('contentType', fn($q)=>$q->whereIn('slug', ['bhajan', 'stuti']))->count(),
            'news' => Content::whereHas('contentType', fn($q)=>$q->where('slug', 'news'))->count(),
            'pdfs' => Content::whereNotNull('pdfs')->count(),
            'videos' => Content::whereNotNull('videos')->count(),
            'quotes' => DailyQuote::active()->count(),
            'saints' => Saint::where('is_active', true)->count(),
            'tirth' => count($tirthList),
        ];

        return compact(
            'todayQuote',
            'recentQuotes',
            'todayPanchang',
            'granths',
            'pravachans',
            'bhajans',
            'pdfLibrary',
            'news',
            'saints',
            'events',
            'festivals',
            'videos',
            'albums',
            'tirthList',
            'stats'
        );
    }

    /**
     * Show the classic/legacy version of the homepage.
     */
    public function classic()
    {
        $today = Carbon::today()->toDateString();

        $saints = Saint::with(['vihars' => function($q) {
            $q->where('type', 'current')->where('is_active', true);
        }])
        ->where('is_active', true)
        ->where('is_featured', true)
        ->orderBy('display_order', 'asc')
        ->take(4)
        ->get();

        if ($saints->isEmpty()) {
            $saints = Saint::with('vihars')->where('is_active', true)->take(4)->get();
        }

        $pravachans = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'pravachan');
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('publish_date', 'desc')
        ->take(3)
        ->get();

        $granths = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'granth');
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('display_order', 'asc')
        ->take(3)
        ->get();

        $bhajans = Content::whereHas('contentType', function($q) {
            $q->whereIn('slug', ['bhajan', 'stuti']);
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('publish_date', 'desc')
        ->take(3)
        ->get();

        $news = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'news');
        })
        ->with('category')
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('publish_date', 'desc')
        ->take(3)
        ->get();

        $events = Event::where('is_active', true)
            ->where('start_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        if ($events->isEmpty()) {
            $events = Event::where('is_active', true)->orderBy('start_date', 'desc')->take(3)->get();
        }

        $photos = AlbumPhoto::with('album')
            ->where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->take(6)
            ->get();

        $videos = Content::whereNotNull('videos')
            ->where('status', 'published')
            ->where('is_active', true)
            ->take(3)
            ->get();

        $activeSections = ['hero', 'stats', 'saints', 'pravachans', 'granths', 'bhajans', 'news', 'events', 'photos', 'videos', 'newsletter'];

        return view('home.index_classic', compact(
            'saints',
            'pravachans',
            'granths',
            'bhajans',
            'news',
            'events',
            'photos',
            'videos',
            'activeSections'
        ));
    }
}
