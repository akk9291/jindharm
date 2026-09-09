<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Saint;
use App\Models\Vihar;
use App\Models\ContentType;
use App\Models\Category;
use App\Models\Content;
use App\Models\Event;
use App\Models\Panchang;
use App\Models\Festival;
use App\Models\Album;
use App\Models\AlbumPhoto;
use App\Models\Page;
use App\Models\Setting;
use App\Models\HomepageSection;
use App\Models\DailyQuote;
use Carbon\Carbon;

class FrontendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Homepage Sections
        $sections = [
            'hero' => ['label' => ['hi' => 'मुख्य बैनर', 'en' => 'Hero Banner'], 'order' => 1],
            'message' => ['label' => ['hi' => 'आज का आध्यात्मिक संदेश', 'en' => 'Spiritual Message'], 'order' => 2],
            'panchang' => ['label' => ['hi' => 'आज का जैन पंचांग', 'en' => 'Today\'s Panchang'], 'order' => 3],
            'saints' => ['label' => ['hi' => 'पूज्य साधु संघ', 'en' => 'Revered Saints'], 'order' => 4],
            'pravachan' => ['label' => ['hi' => 'नवीनतम प्रवचन', 'en' => 'Latest Pravachan'], 'order' => 5],
            'granth' => ['label' => ['hi' => 'ज्ञान भण्डार - ग्रन्थ', 'en' => 'Jain Granths'], 'order' => 6],
            'bhajan' => ['label' => ['hi' => 'भजन एवं स्तुति', 'en' => 'Bhajan & Stuti'], 'order' => 7],
            'news' => ['label' => ['hi' => 'धर्म प्रभावना समाचार', 'en' => 'Latest News'], 'order' => 8],
            'events' => ['label' => ['hi' => 'आगामी कार्यक्रम', 'en' => 'Upcoming Events'], 'order' => 9],
            'gallery' => ['label' => ['hi' => 'चित्र दीर्घा', 'en' => 'Photo Gallery'], 'order' => 10],
            'video_gallery' => ['label' => ['hi' => 'वीडियो दीर्घा', 'en' => 'Video Gallery'], 'order' => 11],
            'quote_banner' => ['label' => ['hi' => 'अमृत वचन बैनर', 'en' => 'Spiritual Quote Banner'], 'order' => 12],
        ];

        foreach ($sections as $key => $data) {
            HomepageSection::updateOrCreate(
                ['section_key' => $key],
                [
                    'label' => $data['label'],
                    'is_active' => true,
                    'display_order' => $data['order'],
                    'show_on_website' => true,
                    'show_on_app' => true,
                    'settings' => []
                ]
            );
        }

        // 2. Settings
        $settings = [
            ['key' => 'website_name', 'value' => 'सर्वोदय जिनधर्म संघ', 'type' => 'text', 'group' => 'general'],
            ['key' => 'website_name_en', 'value' => 'Sarvodaya Jin Dharm Portal', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'अहिंसा परमो धर्मः | जीयो और जीने दो', 'type' => 'text', 'group' => 'general'],
            ['key' => 'spiritual_message_quote', 'value' => 'क्रोध को शांति से, मान को नम्रता से, माया को सरलता से और लोभ को संतोष से जीतें।', 'type' => 'text', 'group' => 'quotation'],
            ['key' => 'spiritual_message_author', 'value' => 'भगवान महावीर स्वामी (24वें तीर्थंकर)', 'type' => 'text', 'group' => 'quotation'],
            ['key' => 'daily_quote_title', 'value' => 'आज का आध्यात्मिक संदेश', 'type' => 'text', 'group' => 'quotation'],
            ['key' => 'daily_quote_image', 'value' => '/images/jain/muni_vidyasagar.jpg', 'type' => 'text', 'group' => 'quotation'],
            ['key' => 'daily_quote_layout', 'value' => 'split', 'type' => 'text', 'group' => 'quotation'],
            ['key' => 'daily_quote_show_share', 'value' => '1', 'type' => 'text', 'group' => 'quotation'],
            ['key' => 'daily_quote_show_download', 'value' => '1', 'type' => 'text', 'group' => 'quotation'],
            ['key' => 'daily_quote_show_copy', 'value' => '1', 'type' => 'text', 'group' => 'quotation'],
            ['key' => 'contact_mobile', 'value' => '+91-9876543210', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'info@jindharm.org', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => '+919876543210', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => '१०२, श्री दिगम्बर जैन बड़ा मंदिर परिसर, अहिंसा मार्ग, नई दिल्ली - ११०००२', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_telegram', 'value' => 'https://telegram.org', 'type' => 'text', 'group' => 'social'],
            ['key' => 'footer_about', 'value' => 'सर्वोदय जिनधर्म पोर्टल भगवान महावीर के अहिंसा, अनेकांत और अपरिग्रह के सिद्धांतों पर आधारित एक विश्वस्तरीय डिजिटल मंच है। यहाँ प्रामाणिक जैन ग्रन्थ, साधु परिचय, विहार स्थिति एवं दैनिक पंचांग सुलभ हैं।', 'type' => 'text', 'group' => 'general'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }

        // 3. Content Types
        $typesData = [
            ['slug' => 'pravachan', 'name' => ['hi' => 'प्रवचन', 'en' => 'Pravachans'], 'icon' => 'fa-microphone'],
            ['slug' => 'granth', 'name' => ['hi' => 'ग्रन्थ', 'en' => 'Granths'], 'icon' => 'fa-book-open'],
            ['slug' => 'bhajan', 'name' => ['hi' => 'भजन एवं स्तुति', 'en' => 'Bhajans'], 'icon' => 'fa-music'],
            ['slug' => 'news', 'name' => ['hi' => 'समाचार', 'en' => 'News'], 'icon' => 'fa-newspaper'],
            ['slug' => 'stuti', 'name' => ['hi' => 'आरती / स्तोत्र', 'en' => 'Stutis'], 'icon' => 'fa-award'],
        ];

        $contentTypes = [];
        foreach ($typesData as $td) {
            $contentTypes[$td['slug']] = ContentType::updateOrCreate(
                ['slug' => $td['slug']],
                [
                    'name' => $td['name'],
                    'icon' => $td['icon'],
                    'is_active' => true,
                    'show_on_website' => true,
                    'show_on_app' => true
                ]
            );
        }

        // Categories
        $catData = [
            'pravachan' => [
                ['slug' => 'adhyatma', 'name' => ['hi' => 'अध्यात्म एवं दर्शन', 'en' => 'Spirituality & Philosophy']],
                ['slug' => 'charitra', 'name' => ['hi' => 'सदाचार एवं चरित्र', 'en' => 'Ethics & Character']],
                ['slug' => 'shanka-samadhan', 'name' => ['hi' => 'जिज्ञासा समाधान', 'en' => 'Q&A Solutions']],
            ],
            'granth' => [
                ['slug' => 'siddhanta', 'name' => ['hi' => 'सिद्धान्त ग्रन्थ', 'en' => 'Doctrine Books']],
                ['slug' => 'adhyatma-granth', 'name' => ['hi' => 'अध्यात्म ग्रन्थ', 'en' => 'Spiritual Texts']],
                ['slug' => 'charananiyoga', 'name' => ['hi' => 'चरणाद्ययोग ग्रन्थ', 'en' => 'Conduct Scriptures']],
            ],
            'bhajan' => [
                ['slug' => 'jin-bhakti', 'name' => ['hi' => 'जिन भक्ति', 'en' => 'Jin Bhakti']],
                ['slug' => 'guru-bhakti', 'name' => ['hi' => 'गुरु भक्ति', 'en' => 'Guru Bhakti']],
                ['slug' => 'stotra', 'name' => ['hi' => 'स्तोत्र एवं पाठ', 'en' => 'Stotras & Chants']],
            ],
            'news' => [
                ['slug' => 'tirth-news', 'name' => ['hi' => 'तीर्थ क्षेत्र समाचार', 'en' => 'Pilgrimage News']],
                ['slug' => 'sant-news', 'name' => ['hi' => 'साधु संघ समाचार', 'en' => 'Saint News']],
                ['slug' => 'samaj-news', 'name' => ['hi' => 'सामाजिक एवं सांस्कृतिक', 'en' => 'Community News']],
            ],
        ];

        $categories = [];
        foreach ($catData as $typeSlug => $cats) {
            $cType = $contentTypes[$typeSlug];
            foreach ($cats as $c) {
                $categories[$c['slug']] = Category::updateOrCreate(
                    ['content_type_id' => $cType->id, 'slug' => $c['slug']],
                    [
                        'name' => $c['name'],
                        'is_active' => true,
                        'show_on_website' => true,
                        'show_on_app' => true,
                        'display_order' => 1
                    ]
                );
            }
        }

        // 4. Saints
        $s1 = Saint::updateOrCreate(
            ['slug' => 'acharya-vidyasagar-ji'],
            [
                'name' => ['hi' => 'आचार्य श्री १०८ विद्यासागर जी महाराज', 'en' => 'Acharya Shri 108 Vidyasagar Ji Maharaj'],
                'title' => ['hi' => 'राष्ट्रसंत, तपोमूर्ति, युगदृष्टा', 'en' => 'Rashtrasant, Spiritual Giant'],
                'photo' => '/images/jain/muni_vidyasagar.jpg',
                'guru_name' => ['hi' => 'आचार्य श्री १०८ ज्ञानसागर जी महाराज', 'en' => 'Acharya Shri 108 Gyansagar Ji Maharaj'],
                'diksha_date' => '1968-06-30',
                'introduction' => [
                    'hi' => 'परम पूज्य आचार्य श्री १०८ विद्यासागर जी महाराज आधुनिक युग के सबसे महान दिगम्बर जैन आचार्य हैं जिन्होंने देश भर में अहिंसा, स्वावलंबन और हथकरघा क्रांति की अलख जगाई।',
                    'en' => 'Revered Acharya Shri 108 Vidyasagar Ji Maharaj is one of the most prominent Jain ascetics of the modern era, renowned for intense penance and social welfare.'
                ],
                'biography' => [
                    'hi' => "आचार्य श्री १०८ विद्यासागर जी महाराज का जन्म १० अक्टूबर १९४६ को कर्नाटक के सदलगा गाँव में हुआ था। आपका गृहस्थ नाम विद्याधर था। आपने आचार्य श्री ज्ञानसागर जी महाराज से क्षुल्लक, ऐलक एवं मुनि दीक्षा प्राप्त की।\n\nआचार्य श्री ने न केवल घोर तपस्या और संयम का मार्ग प्रशस्त किया, अपितु मूकमाटी महाकाव्य जैसी अनुपम साहित्यिक रचना की। आपने प्रतिभास्थली बालिका विद्यालयों, हथकरघा श्रमदान केन्द्रों एवं पूर्णायु आयुर्वेद चिकित्सालयों की स्थापना की प्रेरणा दी।",
                    'en' => "Acharya Shri 108 Vidyasagar Ji Maharaj was born on October 10, 1946, in Sadalga, Karnataka. He received Muni initiation from Acharya Gyansagar Ji. His deep contemplation and literary contribution like Mukamati are celebrated nationwide."
                ],
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true,
                'display_order' => 1,
            ]
        );

        $s2 = Saint::updateOrCreate(
            ['slug' => 'acharya-kundkund-swami'],
            [
                'name' => ['hi' => 'आचार्य श्री कुन्दकुन्द स्वामी', 'en' => 'Acharya Kundkund Swami'],
                'title' => ['hi' => 'कलिकाल सर्वज्ञ, समयसार प्रणेता', 'en' => 'Kalikala Sarvajna'],
                'photo' => '/images/jain/tirthankara_idol.jpg',
                'guru_name' => ['hi' => 'आचार्य जिनचन्द्र', 'en' => 'Acharya Jinchandra'],
                'introduction' => [
                    'hi' => 'दिगम्बर परम्परा के महानतम दार्शनिक आचार्य जिन्होंने समयसार, प्रवचनसार एवं नियमसार जैसे अध्यात्म के अमर ग्रन्थों की रचना की।',
                    'en' => 'The foremost philosopher saint of the Digambara tradition, author of immortal scriptures including Samayasara and Pravachanasara.'
                ],
                'biography' => [
                    'hi' => "आचार्य कुन्दकुन्द स्वामी ईसा पूर्व प्रथम शताब्दी के अद्वितीय अध्यात्मवादी आचार्य हैं। मंगलं भगवान वीरो मंगलं गौतमो गणी, मंगलं कुन्दकुन्दाद्यो जैनधर्मोस्तु मंगलम् - इस प्रामाणिक श्लोक में तीर्थंकर और गणधर के तत्काल पश्चात् आपका स्मरण किया जाता है।",
                    'en' => "Acharya Kundkund Swami is remembered immediately after Tirthankara Mahavira and Ganadhara Gautama in the sacred daily Mangalacharan."
                ],
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true,
                'display_order' => 2,
            ]
        );

        $s3 = Saint::updateOrCreate(
            ['slug' => 'muni-pramansagar-ji'],
            [
                'parent_id' => $s1->id,
                'name' => ['hi' => 'मुनि श्री १०८ प्रमाणसागर जी महाराज', 'en' => 'Muni Shri 108 Pramansagar Ji Maharaj'],
                'title' => ['hi' => 'प्रवचन केसरी, शंका समाधान प्रणेता', 'en' => 'Pravachan Kesari'],
                'photo' => '/images/jain/muni_pravachan.jpg',
                'guru_name' => ['hi' => 'आचार्य श्री १०८ विद्यासागर जी महाराज', 'en' => 'Acharya Shri 108 Vidyasagar Ji Maharaj'],
                'diksha_date' => '1988-03-31',
                'introduction' => [
                    'hi' => 'आचार्य विद्यासागर जी महाराज के सुयोग्य शिष्य, जिनके द्वारा संचालित "शंका समाधान" कार्यक्रम दुनिया भर में लोकप्रिय है।',
                    'en' => 'Eminent disciple of Acharya Vidyasagar Ji, widely acclaimed for his pragmatic spiritual Q&A program "Shanka Samadhan".'
                ],
                'biography' => [
                    'hi' => 'मुनि श्री प्रमाणसागर जी महाराज ने जन-जन के मन में धर्म की वैज्ञानिक समझ विकसित की है। आपके ओजस्वी प्रवचन और भावना योग की विधि से लाखों लोगों को तनाव मुक्ति और आत्मिक शांति मिली है।',
                    'en' => 'Muni Shri Pramansagar Ji has inspired millions through scientific discourse on Jain tenets and Bhavna Yoga meditation.'
                ],
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true,
                'display_order' => 3,
            ]
        );

        // Vihars
        Vihar::updateOrCreate(
            ['saint_id' => $s1->id, 'type' => 'current'],
            [
                'location_title' => ['hi' => 'श्री दिगम्बर जैन अतिशय क्षेत्र कुंडलपुर धाम', 'en' => 'Kundalpur Tirth Dham'],
                'city' => 'दमोह (Damoh)',
                'state' => 'मध्य प्रदेश (Madhya Pradesh)',
                'country' => 'India',
                'latitude' => 23.9582,
                'longitude' => 79.7042,
                'start_date' => Carbon::now()->subMonths(6)->toDateString(),
                'contact_person' => 'ट्रस्ट कार्यालय (Trust Office)',
                'contact_number' => '+91-7812-245200',
                'is_active' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        Vihar::updateOrCreate(
            ['saint_id' => $s3->id, 'type' => 'current'],
            [
                'location_title' => ['hi' => 'श्री सम्मेद शिखरजी सिद्ध क्षेत्र', 'en' => 'Shri Sammed Shikharji Siddha Kshetra'],
                'city' => 'मधुबन (Madhuban, Giridih)',
                'state' => 'झारखंड (Jharkhand)',
                'country' => 'India',
                'latitude' => 23.9634,
                'longitude' => 86.1432,
                'start_date' => Carbon::now()->subMonths(1)->toDateString(),
                'contact_person' => 'समिति प्रबंधक (Committee Manager)',
                'contact_number' => '+91-6558-223344',
                'is_active' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        // 5. Contents: Pravachans
        Content::updateOrCreate(
            ['slug' => 'aatma-shanti-aur-moksha-marg'],
            [
                'content_type_id' => $contentTypes['pravachan']->id,
                'category_id' => $categories['adhyatma']->id,
                'title' => ['hi' => 'आत्म शांति और मोक्ष का वास्तविक मार्ग', 'en' => 'The True Path to Peace of Soul and Liberation'],
                'author' => ['hi' => 'आचार्य श्री विद्यासागर जी महाराज', 'en' => 'Acharya Vidyasagar Ji'],
                'publish_date' => Carbon::now()->subDays(2)->toDateString(),
                'short_description' => [
                    'hi' => 'सच्चा सुख बाह्य वस्तुओं में नहीं, अपनी आत्मा के स्वरूप को पहचानने और तृष्णा घटाने में है। पूज्य आचार्य श्री के अमृत वचन।',
                    'en' => 'True bliss resides not in worldly accumulation, but in tranquil contemplation of the inner self.'
                ],
                'full_description' => [
                    'hi' => "<p>आचार्य श्री ने धर्मसभा को संबोधित करते हुए कहा कि मानव जीवन का सच्चा ध्येय केवल भौतिक संपदा एकत्र करना नहीं है। संसार में जितनी भी वस्तुएं हमें प्रिय लगती हैं, वे सभी अनित्य हैं। जब तक आत्मा में संतोष का भाव जाग्रत नहीं होता, तब तक शांति संभव नहीं है।</p><p>सम्यग्दर्शन, सम्यग्ज्ञान और सम्यक्चारित्र - इन तीनों की एकता ही मोक्ष का मार्ग है। अपने आचरण को शुद्ध रखें और प्रतिदिन कुछ समय मौन व आत्मचिंतन में व्यतीत करें।</p>",
                    'en' => "<p>Acharya Shri emphasized that true fulfillment is found when consciousness awakens to universal peace and detachment from desires.</p>"
                ],
                'featured_image' => '/images/jain/muni_vidyasagar.jpg',
                'videos' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                'audios' => ['https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3'],
                'tags' => ['आध्यात्म', 'मोक्ष', 'प्रवचन', 'आत्मशांति'],
                'status' => 'published',
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        Content::updateOrCreate(
            ['slug' => 'karma-siddhanta-scientific-view'],
            [
                'content_type_id' => $contentTypes['pravachan']->id,
                'category_id' => $categories['shanka-samadhan']->id,
                'title' => ['hi' => 'कर्म सिद्धांत और उसके भेदों की वैज्ञानिक विवेचना', 'en' => 'Scientific Exploration of the Karma Doctrine'],
                'author' => ['hi' => 'मुनि श्री प्रमाणसागर जी महाराज', 'en' => 'Muni Shri Pramansagar Ji'],
                'publish_date' => Carbon::now()->subDays(5)->toDateString(),
                'short_description' => [
                    'hi' => 'जैसा बीज बोयेंगे वैसा फल मिलेगा। कर्म सिद्धांत न्यूटन के क्रिया-प्रतिक्रिया नियम से भी अधिक सूक्ष्म एवं अचूक है।',
                    'en' => 'The unfailing cosmic law of cause and effect governed by Jain Karma philosophy explained lucidly.'
                ],
                'full_description' => [
                    'hi' => "<p>शंका समाधान के इस सत्र में मुनि श्री ने कर्म के आठ मूल भेदों - ज्ञानावरणीय, दर्शनावरणीय, वेदनीय, मोहनीय, आयु, नाम, गोत्र और अंतराय की विस्तृत व्याख्या की। हमारे परिणाम और विचार ही कर्मबंध का मुख्य कारण हैं।</p>",
                    'en' => "<p>Detailed explanation of the eight classifications of karmic bondage and how conscious right conduct leads to nirjara (shedding of karma).</p>"
                ],
                'featured_image' => '/images/jain/muni_pravachan.jpg',
                'videos' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ'],
                'audios' => ['https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3'],
                'tags' => ['कर्म सिद्धांत', 'शंका समाधान', 'जैन दर्शन'],
                'status' => 'published',
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        // 6. Contents: Granths (Scriptures)
        Content::updateOrCreate(
            ['slug' => 'tattvartha-sutra'],
            [
                'content_type_id' => $contentTypes['granth']->id,
                'category_id' => $categories['siddhanta']->id,
                'title' => ['hi' => 'तत्त्वार्थ सूत्र (मोक्षशास्त्र)', 'en' => 'Tattvartha Sutra (Moksha Shastra)'],
                'author' => ['hi' => 'आचार्य उमास्वामी / उमास्वाति', 'en' => 'Acharya Umaswami'],
                'publish_date' => '2026-01-01',
                'short_description' => [
                    'hi' => 'समस्त जैन दर्शन का सार सूत्र रूप में संकलित करने वाला अद्वितीय और सर्वमान्य ग्रन्थराज। दस अध्यायों में मोक्षमार्ग का निरूपण।',
                    'en' => 'The timeless universal scripture encapsulating complete Jain philosophy across ten profound aphoristic chapters.'
                ],
                'full_description' => [
                    'hi' => "<h3>सम्यग्दर्शनज्ञानचारित्राणि मोक्षमार्गः</h3><p>तत्त्वार्थ सूत्र जैन धर्म का अत्यंत प्रतिष्ठित ग्रन्थ है। इसमें कुल १० अध्याय और ३५७ सूत्र हैं। प्रथम अध्याय में सम्यग्दर्शन, ज्ञान और चारित्र तथा जीवादि सात तत्त्वों का परिचय दिया गया है। द्वितीय से चतुर्थ अध्याय में जीव, पुद्गल और लोक का भूगोल है। पंचम अध्याय में अजीव तत्त्व तथा अंतिम अध्यायों में आस्रव, बंध, संवर, निर्जरा एवं मोक्ष का गहन विवेचन है।</p>",
                    'en' => "<p>Composed in Sanskrit aphorisms, Tattvartha Sutra explains the seven fundamentals (tattvas) and the tripartite path to enlightenment.</p>"
                ],
                'featured_image' => '/images/jain/granth_manuscript.jpg',
                'pdfs' => ['https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'],
                'tags' => ['तत्त्वार्थ सूत्र', 'उमास्वामी', 'ग्रन्थ', 'मोक्षशास्त्र'],
                'status' => 'published',
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        Content::updateOrCreate(
            ['slug' => 'samayasara-granth'],
            [
                'content_type_id' => $contentTypes['granth']->id,
                'category_id' => $categories['adhyatma-granth']->id,
                'title' => ['hi' => 'समयसार (परमात्म प्रकाश)', 'en' => 'Samayasara (The Nature of the Self)'],
                'author' => ['hi' => 'आचार्य श्री कुन्दकुन्द स्वामी', 'en' => 'Acharya Kundkund Swami'],
                'publish_date' => '2026-01-01',
                'short_description' => [
                    'hi' => 'शुद्ध चैतन्य रूप आत्मा का साक्षात्कार कराने वाला अध्यात्म जगत का मुकुटमणि ग्रन्थ। प्राकृत गाथाओं में निबद्ध।',
                    'en' => 'The crown jewel of spiritual texts delineating pure transcendental soul consciousness.'
                ],
                'full_description' => [
                    'hi' => "<p>समयसार ग्रन्थराज में आचार्य कुन्दकुन्द स्वामी ने निश्चय नय की प्रधानता से शुद्ध आत्मा का वर्णन किया है। आत्मा स्वभाव से ज्ञान और दर्शनमय है, वह रागादि विकारी भावों से सर्वथा भिन्न है। यह ग्रन्थ अंतर्दृष्टि खोलने वाला प्रकाशस्तंभ है।</p>",
                    'en' => "<p>A masterclass in non-dual spiritual insight revealing the uncontaminated eternal purity of consciousness.</p>"
                ],
                'featured_image' => '/images/jain/granth_manuscript.jpg',
                'pdfs' => ['https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'],
                'tags' => ['समयसार', 'कुन्दकुन्द', 'अध्यात्म'],
                'status' => 'published',
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        // 7. Contents: Bhajans & Stutis
        Content::updateOrCreate(
            ['slug' => 'meri-bhavna-bhajan'],
            [
                'content_type_id' => $contentTypes['bhajan']->id,
                'category_id' => $categories['jin-bhakti']->id,
                'title' => ['hi' => 'मेरी भावना (जिसने राग-द्वेष कामादिक जीते)', 'en' => 'Meri Bhavna (Universal Prayer for Peace)'],
                'author' => ['hi' => 'पं. जुगलकिशोर जी ‘युगल’', 'en' => 'Pt. Jugal Kishore Ji'],
                'publish_date' => '2026-01-10',
                'short_description' => [
                    'hi' => 'समस्त जीवों के प्रति मैत्री, गुणीजनों में प्रमोद और दीन-दुखियों के प्रति करुणा की अमर प्रार्थना।',
                    'en' => 'The quintessential Jain hymn inspiring universal friendship, compassion, and equanimity.'
                ],
                'full_description' => [
                    'hi' => "<p>जिसने राग-द्वेष कामादिक जीते सब जग जान लिया।<br>सब जीवों को मोक्ष मार्ग का निस्पृह हो उपदेश दिया॥<br>बुद्ध, वीर, जिन, हरि, हर, ब्रह्मा या उसको स्वाधीन कहो।<br>भक्ति-भाव से मत्त बने यह चित्त उसी में लीन रहो॥</p><p>रहे भावना ऐसी मेरी सब जीवों से नित्य रहे।<br>गुणी जनों को देख हृदय में मेरे प्रेम-उमड़ पड़े॥</p>",
                    'en' => "<p>A soulful hymn recited in households for century-old moral elevation and inner balance.</p>"
                ],
                'featured_image' => '/images/jain/tirthankara_idol.jpg',
                'audios' => ['https://www.soundhelix.com/examples/mp3/SoundHelix-Song-3.mp3'],
                'tags' => ['मेरी भावना', 'भजन', 'प्रार्थना'],
                'status' => 'published',
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        Content::updateOrCreate(
            ['slug' => 'bhaktamara-stotra-audio'],
            [
                'content_type_id' => $contentTypes['bhajan']->id,
                'category_id' => $categories['stotra']->id,
                'title' => ['hi' => 'भक्तामर स्तोत्र (संस्कृत पाठ एवं मधुर धुन)', 'en' => 'Bhaktamara Stotra (Devotional Recitation)'],
                'author' => ['hi' => 'आचार्य मानतुंग स्वामी', 'en' => 'Acharya Manatunga'],
                'publish_date' => '2026-01-15',
                'short_description' => [
                    'hi' => 'प्रथम तीर्थंकर भगवान आदिनाथ की स्तुति में रचा गया अलौकिक ४८ काव्यों का प्रभावक स्तोत्र।',
                    'en' => 'Sacred 48-verse Sanskrit hymn in praise of First Tirthankara Lord Adinatha, renowned for miraculous spiritual vibrations.'
                ],
                'full_description' => [
                    'hi' => "<p>भक्तामर-प्रणत-मौलि-मणि-प्रभाणा-मुद्योतकं दलित-पाप-तमो-वितानम्।<br>सम्यक् प्रणम्य जिन-पाद-युगं युगादा-वालम्बनं भव-जले पततां जनानाम्॥१॥</p><p>आचार्य मानतुंग स्वामी द्वारा रचित यह स्तोत्र प्रत्येक श्लोक में अद्भुत मंत्र शक्ति समाहित किए हुए है।</p>",
                    'en' => "<p>Recitation of Bhaktamara Stotra brings mental clarity, protection, and boundless devotion.</p>"
                ],
                'featured_image' => '/images/jain/tirthankara_idol.jpg',
                'audios' => ['https://www.soundhelix.com/examples/mp3/SoundHelix-Song-4.mp3'],
                'tags' => ['भक्तामर', 'स्तोत्र', 'आदिनाथ', 'मंत्र'],
                'status' => 'published',
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        // 8. Contents: News
        Content::updateOrCreate(
            ['slug' => 'vishwa-shanti-mahayagya-shivir'],
            [
                'content_type_id' => $contentTypes['news']->id,
                'category_id' => $categories['tirth-news']->id,
                'title' => ['hi' => 'कुंडलपुर धाम में 1008 सिद्धचक्र महामंडल विधान का भव्य शुभारंभ', 'en' => 'Grand Inauguration of Siddhachakra Vidhan at Kundalpur'],
                'author' => ['hi' => 'विशेष संवाददाता', 'en' => 'Press Correspondent'],
                'publish_date' => Carbon::now()->subDays(1)->toDateString(),
                'short_description' => [
                    'hi' => 'बड़े बाबा के सानिध्य में विश्व शांति एवं प्राणी मात्र के कल्याण हेतु हजारो श्रावक-श्राविकाओं ने की धर्म आराधना।',
                    'en' => 'Thousands of devotees gather at Kundalpur Kshetra for sacred world peace rituals and spiritual recitations.'
                ],
                'full_description' => [
                    'hi' => "<p>कुंडलपुर। सिद्ध क्षेत्र कुंडलपुर में आज प्रात: मंत्रोच्चार और कलश यात्रा के साथ सिद्धचक्र महामंडल विधान प्रारंभ हुआ। देश भर से पधारे श्रद्धालुओं ने पूज्य मुनि संघ के सानिध्य में अष्ट द्रव्यों से जिनेंद्र प्रभु का अभिषेक एवं पूजन किया।</p><p>समिति पदाधिकारियों ने बताया कि आगामी सात दिनों तक नियमित प्रवचन, तत्व चर्चा एवं सांस्कृतिक संध्या का आयोजन किया जाएगा।</p>",
                    'en' => "<p>Extensive dharmik rituals accompanied by philosophical discourses and communal harmony celebrations marked the opening day.</p>"
                ],
                'featured_image' => '/images/jain/temple_shikhar.jpg',
                'tags' => ['समाचार', 'कुंडलपुर', 'विधान', 'धर्म प्रभावना'],
                'status' => 'published',
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        Content::updateOrCreate(
            ['slug' => 'yuva-sanskar-shivir-samachar'],
            [
                'content_type_id' => $contentTypes['news']->id,
                'category_id' => $categories['samaj-news']->id,
                'title' => ['hi' => 'ग्रीष्मकालीन युवा संस्कार शिविर में युवाओं ने लिया व्यसन मुक्ति का संकल्प', 'en' => 'Youth Moral Camp Inspires Hundreds Towards Right Living'],
                'author' => ['hi' => 'संस्कार मंच', 'en' => 'Youth Cell'],
                'publish_date' => Carbon::now()->subDays(3)->toDateString(),
                'short_description' => [
                    'hi' => 'युवा पीढ़ी में सदाचार, माता-पिता की सेवा और जैन जीवन शैली के मूल्यों को स्थापित करने का सफल प्रयास।',
                    'en' => 'A workshop focusing on ethical living, daily meditation, vegetarian diet, and cultural roots.'
                ],
                'full_description' => [
                    'hi' => "<p>शिविर में ३०० से अधिक छात्र-छात्राओं ने भाग लिया। विद्वानों ने दैनिक दिनचर्या, खानपान की शुद्धता और मोबाइल के संतुलित उपयोग पर व्यावहारिक मार्गदर्शन दिया।</p>",
                    'en' => "<p>Interactive workshops provided insights on balance between modern professional careers and core non-violent moral values.</p>"
                ],
                'featured_image' => '/images/jain/muni_pravachan.jpg',
                'tags' => ['युवा शिविर', 'सदाचार', 'संस्कार'],
                'status' => 'published',
                'is_active' => true,
                'is_featured' => false,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        // 9. Events
        Event::updateOrCreate(
            ['slug' => 'siddhachakra-mahamandal-vidhan-2026'],
            [
                'event_name' => ['hi' => 'श्री १०0८ कल्पद्रुम महामंडल विधान एवं विश्व शांति अनुष्ठान', 'en' => 'Shri Kalpadrum Mahamandal Vidhan & World Peace Ritual'],
                'banner' => '/images/jain/temple_shikhar.jpg',
                'description' => [
                    'hi' => 'परम पूज्य मुनि संघ के पावन सानिध्य में विश्व कल्याण, पर्यावरण संवर्धन और आत्म शुद्धि हेतु भव्य कल्पद्रुम विधान का आयोजन किया जा रहा है। प्रतिदिन प्रात: शांतिधारा, अभिषेक एवं संध्या को महाआरती होगी।',
                    'en' => 'Seven days of sublime devotion and scriptural recitations invoking blessings of universal goodwill and peace.'
                ],
                'venue' => ['hi' => 'सर्वोदय तीर्थ महापरिसर, रिंग रोड, इंदौर (म.प्र.)', 'en' => 'Sarvodaya Tirth Complex, Ring Road, Indore'],
                'start_date' => Carbon::now()->addDays(12)->setTime(8, 0),
                'end_date' => Carbon::now()->addDays(19)->setTime(18, 0),
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        Event::updateOrCreate(
            ['slug' => 'mahavir-jayanti-mahotsav-2026'],
            [
                'event_name' => ['hi' => '२६२५वां भगवान महावीर जन्म कल्याणक महोत्सव', 'en' => 'Lord Mahavira Janma Kalyanak Mahotsav'],
                'banner' => '/images/jain/tirthankara_idol.jpg',
                'description' => [
                    'hi' => 'अहिंसा के अग्रदूत तीर्थंकर महावीर स्वामी के जन्म कल्याणक पर प्रभात फेरी, रथयात्रा एवं विशाल धर्मसभा का आयोजन।',
                    'en' => 'Celebration of Lord Mahavira’s auspicious birth anniversary with grand peace march and spiritual symposium.'
                ],
                'venue' => ['hi' => 'लाल किला मैदान से चांदनी चौक, नई दिल्ली', 'en' => 'Red Fort Grounds to Chandni Chowk, New Delhi'],
                'start_date' => Carbon::now()->addMonths(1)->setTime(6, 30),
                'end_date' => Carbon::now()->addMonths(1)->setTime(14, 0),
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        // 10. Panchang Records for current month
        Panchang::whereNotNull('id')->delete();
        $currentMonth = Carbon::now()->startOfMonth();
        $tithis = ['प्रतिपदा', 'द्वितीया', 'तृतीया', 'चतुर्थी', 'पंचमी', 'षष्ठी', 'सप्तमी', 'अष्टमी', 'नवमी', 'दशमी', 'एकादशी', 'द्वादशी', 'त्रयोदशी', 'चतुर्दशी', 'पूर्णिमा / अमावस्या'];
        $nakshatras = ['अश्विनी', 'भरणी', 'कृत्तिका', 'रोहिणी', 'मृगशिरा', 'पुनर्वसु', 'पुष्य', 'हस्त', 'चित्रा', 'स्वाति', 'विशाखा', 'अनुराधा', 'श्रवण', 'धनिष्ठा', 'रेवती'];
        
        for ($i = 0; $i < 30; $i++) {
            $pDate = (clone $currentMonth)->addDays($i);
            $dayNum = $i + 1;
            $paksha = $dayNum <= 15 ? 'शुक्ल पक्ष' : 'कृष्ण पक्ष';
            $tithi = $tithis[$i % 15];
            $isVrat = in_array($tithi, ['अष्टमी', 'चतुर्दशी', 'एकादशी', 'पूर्णिमा / अमावस्या']);

            Panchang::updateOrCreate(
                ['date' => $pDate->toDateString()],
                [
                    'tithi' => $tithi,
                    'paksha' => $paksha,
                    'maas' => 'भाद्रपद (Bhadrapada)',
                    'nakshatra' => $nakshatras[$i % 15],
                    'sunrise' => '०५:५४ AM',
                    'sunset' => '०६:४२ PM',
                    'notes' => $isVrat ? 'पर्व तिथि: एकासन / उपवास धर्माराधना दिवस।' : 'दैनिक स्वाध्याय एवं शांतिधारा हेतु शुभ काल।',
                    'is_active' => true
                ]
            );
        }

        // 11. Festivals
        Festival::updateOrCreate(
            ['slug' => 'paryushan-parva-daslakshan'],
            [
                'festival_name' => ['hi' => 'दशलक्षण महापर्व (पर्युषण)', 'en' => 'Daslakshan Mahaparva (Paryushan)'],
                'festival_date' => Carbon::now()->addDays(14)->toDateString(),
                'image' => '/images/jain/tirthankara_idol.jpg',
                'description' => [
                    'hi' => 'आत्मा की शुद्धि का १० दिवसीय महापर्व। उत्तम क्षमा, उत्तम मार्दव, उत्तम आर्जव, उत्तम शौच, उत्तम सत्य, उत्तम संयम, उत्तम तप, उत्तम त्याग, उत्तम आकिंचन्य एवं उत्तम ब्रह्मचर्य इन दस धर्मों की साधना का पावन अवसर।',
                    'en' => 'The ultimate 10-day period of spiritual introspection, repentance, forgiveness, and contemplation of the ten sacred virtues of the soul.'
                ],
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        Festival::updateOrCreate(
            ['slug' => 'mahavir-moksha-kalyanak-diwali'],
            [
                'festival_name' => ['hi' => 'भगवान महावीर मोक्ष कल्याणक (दीपावली)', 'en' => 'Lord Mahavira Nirvana Kalyanak (Diwali)'],
                'festival_date' => Carbon::now()->addMonths(2)->toDateString(),
                'image' => '/images/jain/temple_shikhar.jpg',
                'description' => [
                    'hi' => 'कार्तिक कृष्ण अमावस्या के पावन प्रभात पर पावापुरी तीर्थ से भगवान महावीर स्वामी ने निर्वाण (मोक्ष) प्राप्त किया। इस अवसर पर निर्वाण लाडू अर्पित किया जाता है और ज्ञान के प्रकाश का प्रतीक दीप जलाया जाता है।',
                    'en' => 'The auspicious day of the final liberation of 24th Tirthankara Lord Mahavira at Pawapuri, commemorated with pure dedication and lamps of wisdom.'
                ],
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        // 12. Photo Album & Photos
        $album = Album::updateOrCreate(
            ['name->hi' => 'श्री कुंडलपुर तीर्थ महोत्सव एवं संत दर्शन'],
            [
                'name' => ['hi' => 'श्री कुंडलपुर तीर्थ महोत्सव एवं संत दर्शन', 'en' => 'Kundalpur Tirth Mahotsav & Saints Gallery'],
                'cover_image' => '/images/jain/temple_shikhar.jpg',
                'category' => ['hi' => 'संत दर्शन', 'en' => 'Sant Darshan'],
                'description' => ['hi' => 'अतिशय क्षेत्र कुंडलपुर में आयोजित भव्य धार्मिक आयोजनों एवं पूज्य गुरुवर के मंगल दर्शन के विहंगम दृश्य।', 'en' => 'Moments of sublime spirituality from Kundalpur Tirth Dham.'],
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true,
                'display_order' => 1
            ]
        );

        $photos = [
            ['path' => '/images/jain/muni_vidyasagar.jpg', 'caption' => 'आचार्य श्री विद्यासागर जी महाराज ध्यान मुद्रा में'],
            ['path' => '/images/jain/temple_shikhar.jpg', 'caption' => 'भव्य जिन मंदिर शिखर एवं धर्म ध्वजारोहण'],
            ['path' => '/images/jain/tirthankara_idol.jpg', 'caption' => 'श्री जिनेंद्र भगवान की शांत वीतरागी प्रतिमा'],
            ['path' => '/images/jain/muni_pravachan.jpg', 'caption' => 'पूज्य मुनि श्री प्रमाणसागर जी महाराज की धर्मसभा एवं प्रवचन'],
        ];

        foreach ($photos as $idx => $p) {
            AlbumPhoto::updateOrCreate(
                ['album_id' => $album->id, 'photo_path' => $p['path']],
                [
                    'caption' => ['hi' => $p['caption'], 'en' => $p['caption']],
                    'is_active' => true,
                    'display_order' => $idx + 1
                ]
            );
        }

        // 13. Dynamic CMS Pages
        Page::updateOrCreate(
            ['slug' => 'about-us'],
            [
                'title' => ['hi' => 'हमारे बारे में (About Us)', 'en' => 'About Us'],
                'featured_image' => '/images/jain/temple_shikhar.jpg',
                'content' => [
                    'hi' => "<h2>सर्वोदय जिनधर्म पोर्टल का परिचय</h2><p>सर्वोदय जिनधर्म पोर्टल भगवान महावीर के 'जीयो और जीने दो' तथा अनेकांतवाद और अपरिग्रह के शाश्वत सिद्धांतों के प्रचार-प्रसार को समर्पित एक अत्याधुनिक डिजिटल संस्थान है। हमारा उद्देश्य प्राचीन प्रामाणिक जैन ग्रन्थों, आचार्यों की गौरवमयी परम्परा, पूज्य साधु संघों की विहार स्थिति एवं दैनिक पंचांग को विश्व भर के जिज्ञासुओं तक सहजता से पहुँचाना है।</p><h3>हमारे मुख्य उद्देश्य:</h3><ul><li>जैन धर्म ग्रन्थों (तत्त्वार्थ सूत्र, समयसार आदि) का डिजिटल संरक्षण एवं निःशुल्क स्वाध्याय सुविधा।</li><li>पूज्य साधु-संतों के विहार की सटीक एवं अद्यतन जानकारी उपलब्ध कराना।</li><li>दैनिक जैन पंचांग, तिथि, नक्षत्र एवं पर्व-त्योहारों की प्रामाणिक गणना प्रस्तुत करना।</li><li>युवा पीढ़ी को सदाचार, शाकाहार एवं नैतिक जीवन मूल्यों से जोड़ना।</li></ul>",
                    'en' => "<h2>About Sarvodaya Jin Dharm Portal</h2><p>Dedicated to universal peace, non-violence, and authentic Jain spiritual heritage.</p>"
                ],
                'is_active' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => ['hi' => 'गोपनीयता नीति (Privacy Policy)', 'en' => 'Privacy Policy'],
                'content' => [
                    'hi' => "<h2>गोपनीयता नीति</h2><p>सर्वोदय जिनधर्म पोर्टल पर आपकी व्यक्तिगत गोपनीयता का पूरा सम्मान किया जाता है। हम किसी भी उपयोगकर्ता की व्यक्तिगत जानकारी को किसी तीसरे पक्ष के साथ साझा या विक्रय नहीं करते हैं। हमारी वेबसाइट पर एकत्रित डेटा केवल सेवाओं को बेहतर बनाने के उद्देश्य से उपयोग किया जाता है।</p>",
                    'en' => "<h2>Privacy Policy</h2><p>Your privacy is important to us. We do not sell or trade your personal information.</p>"
                ],
                'is_active' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'terms-conditions'],
            [
                'title' => ['hi' => 'नियम एवं शर्तें (Terms & Conditions)', 'en' => 'Terms & Conditions'],
                'content' => [
                    'hi' => "<h2>नियम एवं शर्तें</h2><p>इस पोर्टल पर उपलब्ध समस्त सामग्री (ग्रन्थ, भजन, प्रवचन, चित्र) धार्मिक एवं स्वाध्याय उद्देश्य हेतु उपलब्ध कराई गई है। इसका किसी भी प्रकार का अनैतिक या व्यावसायिक दुरुपयोग पूर्णतः वर्जित है।</p>",
                    'en' => "<h2>Terms & Conditions</h2><p>All content is offered solely for spiritual and educational enlightenment.</p>"
                ],
                'is_active' => true,
                'show_on_website' => true,
                'show_on_app' => true
            ]
        );

        // 14. Daily Quotes / Suvichar Archive
        $dailyQuotesData = [
            [
                'title' => ['hi' => 'आज का आध्यात्मिक संदेश', 'en' => 'Spiritual Message of the Day'],
                'quote_text' => [
                    'hi' => 'क्रोध को शांति से, मान को नम्रता से, माया को सरलता से और लोभ को संतोष से जीतें।',
                    'en' => 'Conquer anger by calm, pride by humility, deceit by straight-forwardness, and greed by contentment.'
                ],
                'author' => ['hi' => 'भगवान महावीर स्वामी (24वें तीर्थंकर)', 'en' => 'Bhagwan Mahavira (24th Tirthankara)'],
                'image' => '/images/jain/muni_vidyasagar.jpg',
                'category' => 'संयम',
                'quote_date' => Carbon::today()->toDateString(),
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => ['hi' => 'अहिंसा एवं मैत्री संदेश', 'en' => 'Universal Friendship & Peace'],
                'quote_text' => [
                    'hi' => 'खामेमि सव्वजीवे सव्वे जीवा खमंतु मे। मित्ती मे सव्वभूएसु वेरं मज्झं न केणइ॥ (मैं संसार के समस्त जीवों को क्षमा करता हूँ, सब जीव मुझे क्षमा करें। मेरी सब जीवों से मैत्री है, किसी से वैर नहीं।)',
                    'en' => 'I forgive all beings, may all beings forgive me. I have friendship with all beings and enmity with none.'
                ],
                'author' => ['hi' => 'आगम वाणी (जैन प्राकृत सूत्र)', 'en' => 'Agama Vani (Sacred Prakrit Verse)'],
                'image' => '/images/jain/tirthankara_idol.jpg',
                'category' => 'उत्तम क्षमा',
                'quote_date' => Carbon::yesterday()->toDateString(),
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => ['hi' => 'आत्म-ज्ञान अमृत', 'en' => 'Nectar of Soul Awakening'],
                'quote_text' => [
                    'hi' => 'जो आत्मा को जानता है, वही सर्व को जानता है। जिसने अपने मन को जीत लिया, उसने समस्त संसार को जीत लिया।',
                    'en' => 'He who knows the pure Soul knows all. He who has conquered his inner tendencies has conquered the cosmos.'
                ],
                'author' => ['hi' => 'आचार्य श्री कुन्दकुन्द स्वामी (समयसार)', 'en' => 'Acharya Kundkund Swami (Samayasara)'],
                'image' => '/images/jain/granth_manuscript.jpg',
                'category' => 'स्वाध्याय',
                'quote_date' => Carbon::today()->subDays(2)->toDateString(),
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => ['hi' => 'अहिंसा महामंत्र', 'en' => 'Supreme Ethic of Non-Violence'],
                'quote_text' => [
                    'hi' => 'अहिंसा परमो धर्मः — संसार के छोटे से छोटे जीव को भी अपने समान सुख प्रिय है और दुःख अप्रिय। किसी को कष्ट न पहुँचाना ही सबसे बड़ा धर्म है।',
                    'en' => 'Non-violence is the supreme virtue. Every sentient soul yearns for joy and fears suffering; cause no injury to any life.'
                ],
                'author' => ['hi' => 'भगवान महावीर स्वामी', 'en' => 'Lord Mahavira'],
                'image' => '/images/jain/temple_shikhar.jpg',
                'category' => 'अहिंसा',
                'quote_date' => Carbon::today()->subDays(3)->toDateString(),
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => ['hi' => 'आचार्य वाणी', 'en' => 'Words of the Master'],
                'quote_text' => [
                    'hi' => 'सच्ची स्वतंत्रता बाह्य वस्तुओं के संचय में नहीं, अपनी इच्छाओं के त्याग और संयम में है। तृष्णा ही सब दुखों की जननी है।',
                    'en' => 'True spiritual freedom is found not in accumulating worldly objects, but in renouncing cravings through voluntary restraint.'
                ],
                'author' => ['hi' => 'आचार्य श्री १०८ विद्यासागर जी महाराज', 'en' => 'Acharya Shri 108 Vidyasagar Ji Maharaj'],
                'image' => '/images/jain/muni_vidyasagar.jpg',
                'category' => 'तप एवं त्याग',
                'quote_date' => Carbon::today()->subDays(4)->toDateString(),
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => ['hi' => 'शंका समाधान प्रेरणा', 'en' => 'Clarity of Right Vision'],
                'quote_text' => [
                    'hi' => 'जैसे दर्पण पर धूल जमने से चेहरा साफ नहीं दिखता, वैसे ही कषाय और मोह रूपी मैल से आत्मा का निज स्वरूप ढँक जाता है। स्वाध्याय से अंतःकरण निर्मल बनता है।',
                    'en' => 'Just as dust on a mirror obscures reflection, passion and delusion obscure soul nature. Self-study purifies consciousness.'
                ],
                'author' => ['hi' => 'मुनि श्री १०८ प्रमाणसागर जी महाराज', 'en' => 'Muni Shri 108 Pramansagar Ji Maharaj'],
                'image' => '/images/jain/muni_pravachan.jpg',
                'category' => 'सदाचार',
                'quote_date' => Carbon::today()->subDays(5)->toDateString(),
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => ['hi' => 'तत्त्वार्थ अमृत सूत्र', 'en' => 'Aphorism on Cosmic Harmony'],
                'quote_text' => [
                    'hi' => 'परस्परोपग्रहो जीवानाम् — समस्त प्राणी एक-दूसरे के जीवन और कल्याण के उपकारक हैं। परस्पर सहयोग और करुणा ही सृष्टि का आधार है।',
                    'en' => 'All souls render service and mutual assistance to one another in cosmic interdependence.'
                ],
                'author' => ['hi' => 'आचार्य उमास्वामी (तत्त्वार्थ सूत्र)', 'en' => 'Acharya Umaswami (Tattvartha Sutra)'],
                'image' => '/images/jain/granth_manuscript.jpg',
                'category' => 'अनेकांतवाद',
                'quote_date' => Carbon::today()->subDays(6)->toDateString(),
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => ['hi' => 'सत्य वाणी', 'en' => 'Virtue of Truthfulness'],
                'quote_text' => [
                    'hi' => 'सत्य वही है जो प्रिय हो और समस्त जीवों के हित में हो। मौन रहना उस असत्य से श्रेष्ठ है जो किसी के हृदय को आहत करे।',
                    'en' => 'Truth is that which is noble, pleasant, and conducive to universal welfare.'
                ],
                'author' => ['hi' => 'आचार्य समंतभद्र स्वामी (रत्नकरण्ड श्रावकाचार)', 'en' => 'Acharya Samantabhadra'],
                'image' => '/images/jain/tirthankara_idol.jpg',
                'category' => 'सत्य',
                'quote_date' => Carbon::today()->subDays(7)->toDateString(),
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => ['hi' => 'भक्ति अमृत', 'en' => 'Devotional Surrender'],
                'quote_text' => [
                    'hi' => 'हे वीतराग प्रभु! मुझे संसार का कोई वैभव नहीं चाहिए, बस इतनी कृपा रहे कि जब तक मोक्ष न मिले, तब तक आपके चरणों में मेरी अनन्य भक्ति बनी रहे।',
                    'en' => 'O Lord! I desire no ephemeral worldly possessions, only that unswerving pure devotion stays in my heart until salvation.'
                ],
                'author' => ['hi' => 'आचार्य मानतुंग स्वामी (भक्तामर स्तोत्र)', 'en' => 'Acharya Manatunga (Bhaktamara)'],
                'image' => '/images/jain/temple_shikhar.jpg',
                'category' => 'जिन भक्ति',
                'quote_date' => Carbon::today()->subDays(8)->toDateString(),
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($dailyQuotesData as $dq) {
            DailyQuote::updateOrCreate(
                ['quote_date' => $dq['quote_date']],
                $dq
            );
        }
    }
}
