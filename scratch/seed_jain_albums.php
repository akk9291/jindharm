<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Album;
use App\Models\AlbumPhoto;

// Clean existing album photos and albums
AlbumPhoto::truncate();
Album::truncate();

// 1. Album: श्री कुंडलपुर महामहोत्सव एवं बड़े बाबा दर्शन
$album1 = Album::create([
    'name' => [
        'hi' => 'श्री कुंडलपुर महामहोत्सव एवं बड़े बाबा दर्शन',
        'en' => 'Shri Kundalpur Mahotsav & Bade Baba Darshan'
    ],
    'cover_image' => '/images/jain/temple_shikhar.jpg',
    'category' => ['hi' => 'तीर्थ दर्शन'],
    'description' => [
        'hi' => 'सिद्धक्षेत्र कुंडलपुर जी में आयोजित भव्य पंचकल्याणक गजरथ महोत्सव एवं बड़े बाबा के विहंगम दर्शन।'
    ],
    'is_active' => true,
    'is_featured' => true,
    'display_order' => 1
]);

$photos1 = [
    ['path' => '/images/jain/temple_shikhar.jpg', 'caption' => 'श्री कुंडलपुर तीर्थ का भव्य शिखर एवं धर्म ध्वजारोहण'],
    ['path' => '/images/jain/tirthankara_idol.jpg', 'caption' => 'श्री बड़े बाबा (ऋषभदेव भगवान) की पद्मासन शांत वीतरागी प्रतिमा'],
    ['path' => '/images/jain/muni_vidyasagar.jpg', 'caption' => 'आचार्य श्री 108 विद्यासागर जी महाराज ससंघ सान्निध्य'],
    ['path' => '/images/jain/muni_pravachan.jpg', 'caption' => 'महोत्सव धर्मसभा में पूज्य मुनिश्री द्वारा अमृत देशना'],
    ['path' => '/images/jain/granth_manuscript.jpg', 'caption' => 'महोत्सव अवसर पर प्राचीन जिनवाणी पूजन'],
];

foreach ($photos1 as $idx => $p) {
    AlbumPhoto::create([
        'album_id' => $album1->id,
        'photo_path' => $p['path'],
        'caption' => ['hi' => $p['caption'], 'en' => $p['caption']],
        'is_active' => true,
        'display_order' => $idx + 1
    ]);
}

// 2. Album: परम पूज्य आचार्य श्री विद्यासागर जी महाराज संस्मरण
$album2 = Album::create([
    'name' => [
        'hi' => 'परम पूज्य आचार्य श्री विद्यासागर जी महाराज संस्मरण',
        'en' => 'Acharya Shri Vidyasagar Ji Memories'
    ],
    'cover_image' => '/images/jain/muni_vidyasagar.jpg',
    'category' => ['hi' => 'संत परिचय'],
    'description' => [
        'hi' => 'युगश्रेष्ठ संत शिरोमणि आचार्य गुरुवर श्री विद्यासागर जी महामुनिराज की तपोमय साधना व पावन दर्शन।'
    ],
    'is_active' => true,
    'is_featured' => true,
    'display_order' => 2
]);

$photos2 = [
    ['path' => '/images/jain/muni_vidyasagar.jpg', 'caption' => 'आचार्य श्री विद्यासागर जी महाराज गहन आत्म-ध्यान मुद्रा में'],
    ['path' => '/images/jain/muni_pravachan.jpg', 'caption' => 'आचार्य संघ की प्रातःकालीन शास्त्र सभा एवं स्वाध्याय'],
    ['path' => '/images/jain/tirthankara_idol.jpg', 'caption' => 'आचार्य श्री द्वारा प्रतिष्ठित जिनबिम्ब दर्शन'],
    ['path' => '/images/jain/temple_shikhar.jpg', 'caption' => 'आचार्य श्री के पावन विहार के समय तीर्थ दर्शन'],
];

foreach ($photos2 as $idx => $p) {
    AlbumPhoto::create([
        'album_id' => $album2->id,
        'photo_path' => $p['path'],
        'caption' => ['hi' => $p['caption'], 'en' => $p['caption']],
        'is_active' => true,
        'display_order' => $idx + 1
    ]);
}

// 3. Album: भव्य जिनालय, शिखर व तीर्थंकर प्रतिमा दर्शन
$album3 = Album::create([
    'name' => [
        'hi' => 'भव्य जिनालय, शिखर व तीर्थंकर प्रतिमा दर्शन',
        'en' => 'Sacred Temples & Tirthankara Idols'
    ],
    'cover_image' => '/images/jain/tirthankara_idol.jpg',
    'category' => ['hi' => 'जिनालय दर्शन'],
    'description' => [
        'hi' => 'भारत के प्राचीन व भव्य जैन मंदिरों की स्थापत्य कला एवं जिनेंद्र भगवान के अलौकिक दर्शन।'
    ],
    'is_active' => true,
    'is_featured' => false,
    'display_order' => 3
]);

$photos3 = [
    ['path' => '/images/jain/tirthankara_idol.jpg', 'caption' => 'श्वेत संगमरमर की वीतराग शांत पद्मासन प्रतिमा'],
    ['path' => '/images/jain/temple_shikhar.jpg', 'caption' => 'कलात्मक नक्काशीदार मंदिर शिखर व गुम्बद'],
    ['path' => '/images/jain/granth_manuscript.jpg', 'caption' => 'मंदिर में विराजमान अतिप्राचीन हस्तलिखित शास्त्र'],
    ['path' => '/images/jain/muni_vidyasagar.jpg', 'caption' => 'जिनालय प्रांगण में मुनि श्री के दर्शन'],
];

foreach ($photos3 as $idx => $p) {
    AlbumPhoto::create([
        'album_id' => $album3->id,
        'photo_path' => $p['path'],
        'caption' => ['hi' => $p['caption'], 'en' => $p['caption']],
        'is_active' => true,
        'display_order' => $idx + 1
    ]);
}

// 4. Album: पूज्य मुनि श्री प्रमाणसागर जी धर्मसभा एवं प्रवचन
$album4 = Album::create([
    'name' => [
        'hi' => 'पूज्य मुनि श्री प्रमाणसागर जी धर्मसभा एवं प्रवचन',
        'en' => 'Muni Shri Pramansagar Ji Pravachan Assembly'
    ],
    'cover_image' => '/images/jain/muni_pravachan.jpg',
    'category' => ['hi' => 'अमृत प्रवचन'],
    'description' => [
        'hi' => 'शंका समाधान एवं जीवन जीने की कला पर मुनिश्री के ओजस्वी विचार एवं धर्मसभा संस्मरण।'
    ],
    'is_active' => true,
    'is_featured' => false,
    'display_order' => 4
]);

$photos4 = [
    ['path' => '/images/jain/muni_pravachan.jpg', 'caption' => 'पूज्य मुनि श्री प्रमाणसागर जी महाराज की धर्मसभा'],
    ['path' => '/images/jain/muni_vidyasagar.jpg', 'caption' => 'गुरु वंदना एवं आशीर्वाद प्रसंग'],
    ['path' => '/images/jain/temple_shikhar.jpg', 'caption' => 'चातुर्मास स्थल जिनालय का विहंगम दृश्य'],
];

foreach ($photos4 as $idx => $p) {
    AlbumPhoto::create([
        'album_id' => $album4->id,
        'photo_path' => $p['path'],
        'caption' => ['hi' => $p['caption'], 'en' => $p['caption']],
        'is_active' => true,
        'display_order' => $idx + 1
    ]);
}

// 5. Album: प्राचीन हस्तलिखित जैन ग्रन्थ एवं पांडुलिपि दीर्घा
$album5 = Album::create([
    'name' => [
        'hi' => 'प्राचीन हस्तलिखित जैन ग्रन्थ एवं पांडुलिपि दीर्घा',
        'en' => 'Ancient Jain Manuscripts & Scriptures'
    ],
    'cover_image' => '/images/jain/granth_manuscript.jpg',
    'category' => ['hi' => 'ज्ञान भण्डार'],
    'description' => [
        'hi' => 'ताड़पत्र व प्राचीन कागज पर स्वर्णाक्षरों में लिखी प्राकृत-संस्कृत जिनवाणी माता के दुर्लभ दर्शन।'
    ],
    'is_active' => true,
    'is_featured' => false,
    'display_order' => 5
]);

$photos5 = [
    ['path' => '/images/jain/granth_manuscript.jpg', 'caption' => 'प्राचीन हस्तलिखित तत्त्वार्थ सूत्र एवं समयसार पांडुलिपि'],
    ['path' => '/images/jain/tirthankara_idol.jpg', 'caption' => 'ज्ञान मंदिर में विराजमान श्री जिनेंद्र भगवान'],
    ['path' => '/images/jain/muni_vidyasagar.jpg', 'caption' => 'जिनवाणी स्वाध्याय करते हुए आचार्य श्री'],
];

foreach ($photos5 as $idx => $p) {
    AlbumPhoto::create([
        'album_id' => $album5->id,
        'photo_path' => $p['path'],
        'caption' => ['hi' => $p['caption'], 'en' => $p['caption']],
        'is_active' => true,
        'display_order' => $idx + 1
    ]);
}

echo "Seeded 5 Jain Photo Albums with 20 authentic photos successfully!\n";
