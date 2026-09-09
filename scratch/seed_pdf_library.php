<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Content;
use App\Models\ContentType;

// Ensure PDF sample files exist
$docsDir = public_path('documents');
if (!file_exists($docsDir)) {
    mkdir($docsDir, 0777, true);
}

// Generate a dummy valid PDF byte content for each granth so downloads work directly
$simplePdfContent = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Count 1/Kids[3 0 R]>>endobj\n3 0 obj<</Type/Page/MediaBox[0 0 612 792]/Parent 2 0 R/Resources<<>>>>endobj\nxref\n0 4\n0000000000 65535 f\n0000000010 00000 n\n0000000053 00000 n\n0000000099 00000 n\ntrailer<</Size 4/Root 1 0 R>>\nstartxref\n168\n%%EOF";

$pdfFiles = [
    'tattvartha_sutra.pdf' => 'तत्त्वार्थ सूत्र (मोक्षशास्त्र) - आचार्य उमास्वामी',
    'samayasara_hindi.pdf' => 'समयसार परमागम - कुन्दकुन्दाचार्य देव',
    'bhaktamar_stotra_path.pdf' => 'भक्तामर स्तोत्र अर्थ सहित - आचार्य मानतुंग',
    'meri_bhavna_prarthana.pdf' => 'मेरी भावना (दैनिक प्रार्थना) - पण्डित जुगलकिशोर जी',
    'jain_panchang_2026.pdf' => 'जैन पंचांग एवं व्रत पर्व निर्णय २०२६',
    'varsha_yog_sangh_patrika.pdf' => 'वर्षा योग एवं विहार संस्मरण विशेषांक',
];

foreach ($pdfFiles as $filename => $label) {
    $filePath = $docsDir . '/' . $filename;
    if (!file_exists($filePath)) {
        file_put_contents($filePath, $simplePdfContent);
    }
}

// Update or create PDF Grantha contents
$granthType = ContentType::firstOrCreate(['slug' => 'granth'], [
    'name' => ['hi' => 'ज्ञान भण्डार', 'en' => 'Granths'],
    'is_active' => true,
    'has_pdf' => true
]);

// 1. तत्त्वार्थ सूत्र
Content::updateOrCreate(
    ['slug' => 'tattvartha-sutra-pdf'],
    [
        'content_type_id' => $granthType->id,
        'title' => ['hi' => 'तत्त्वार्थ सूत्र (मोक्षशास्त्र)', 'en' => 'Tattvartha Sutra (Moksha Shastra)'],
        'author' => ['hi' => 'आचार्य उमास्वामी विरचित', 'en' => 'Acharya Umaswami'],
        'description' => ['hi' => 'जैन दर्शन का सर्वमान्य एवं प्रामाणिक मूल ग्रन्थ, १० अध्यायों में संपूर्ण तत्त्व ज्ञान।'],
        'featured_image' => '/images/jain/granth_manuscript.jpg',
        'pdf_files' => [
            [
                'title' => 'तत्त्वार्थ सूत्र संपूर्ण PDF',
                'url' => '/documents/tattvartha_sutra.pdf',
                'size' => '2.4 MB'
            ]
        ],
        'status' => 'published',
        'publish_date' => now(),
        'is_active' => true,
        'is_featured' => true,
        'display_order' => 1
    ]
);

// 2. समयसार परमागम
Content::updateOrCreate(
    ['slug' => 'samayasara-pdf'],
    [
        'content_type_id' => $granthType->id,
        'title' => ['hi' => 'समयसार परमागम (आत्मख्याति टीका)', 'en' => 'Samayasara Paramagam'],
        'author' => ['hi' => 'श्रीमद् कुन्दकुन्दाचार्य देव', 'en' => 'Acharya Kundakunda'],
        'description' => ['hi' => 'शुद्ध आत्मस्वरूप का निरूपण करने वाला आध्यात्मिक महाग्रंथ।'],
        'featured_image' => '/images/jain/granth_manuscript.jpg',
        'pdf_files' => [
            [
                'title' => 'समयसार मूल एवं सरल हिंदी अर्थ PDF',
                'url' => '/documents/samayasara_hindi.pdf',
                'size' => '4.8 MB'
            ]
        ],
        'status' => 'published',
        'publish_date' => now(),
        'is_active' => true,
        'is_featured' => true,
        'display_order' => 2
    ]
);

// 3. भक्तामर स्तोत्र
Content::updateOrCreate(
    ['slug' => 'bhaktamar-stotra-pdf'],
    [
        'content_type_id' => $granthType->id,
        'title' => ['hi' => 'भक्तामर स्तोत्र (सचित्र व अर्थ सहित)', 'en' => 'Bhaktamara Stotra with Meaning'],
        'author' => ['hi' => 'आचार्य मानतुंग स्वामी', 'en' => 'Acharya Manatunga'],
        'description' => ['hi' => 'आदिनाथ भगवान की महिमा में ४८ काव्यों का चमत्कारी स्तोत्र।'],
        'featured_image' => '/images/jain/tirthankara_idol.jpg',
        'pdf_files' => [
            [
                'title' => 'भक्तामर स्तोत्र ४८ काव्य एवं ऋद्धि-सिद्धि मन्त्र PDF',
                'url' => '/documents/bhaktamar_stotra_path.pdf',
                'size' => '3.1 MB'
            ]
        ],
        'status' => 'published',
        'publish_date' => now(),
        'is_active' => true,
        'is_featured' => false,
        'display_order' => 3
    ]
);

// 4. मेरी भावना
Content::updateOrCreate(
    ['slug' => 'meri-bhavna-pdf'],
    [
        'content_type_id' => $granthType->id,
        'title' => ['hi' => 'मेरी भावना (विश्वमैत्री प्रार्थना)', 'en' => 'Meri Bhavna Universal Prayer'],
        'author' => ['hi' => 'पण्डित जुगलकिशोर जी ‘युगल’', 'en' => 'Pt. Jugal Kishore'],
        'description' => ['hi' => 'जिसने राग द्वेष कामादिक जीते सब जग को जीत लिया - नित्य पठनीय प्रार्थना।'],
        'featured_image' => '/images/jain/muni_vidyasagar.jpg',
        'pdf_files' => [
            [
                'title' => 'मेरी भावना प्रार्थना PDF',
                'url' => '/documents/meri_bhavna_prarthana.pdf',
                'size' => '1.1 MB'
            ]
        ],
        'status' => 'published',
        'publish_date' => now(),
        'is_active' => true,
        'is_featured' => false,
        'display_order' => 4
    ]
);

// 5. जैन पंचांग २०२६
Content::updateOrCreate(
    ['slug' => 'jain-panchang-2026-pdf'],
    [
        'content_type_id' => $granthType->id,
        'title' => ['hi' => 'जैन पंचांग एवं व्रत पर्व तालिका २०२६', 'en' => 'Jain Panchang & Festivals Calendar 2026'],
        'author' => ['hi' => 'जिनधर्म पंचांग समिति', 'en' => 'Jindharm Panchang Committee'],
        'description' => ['hi' => '१२ महीनों की सभी तिथियां, दशलक्षण पर्व, अष्टान्हिका, निर्वाण लाडू एवं सूर्योदय समय।'],
        'featured_image' => '/images/jain/temple_shikhar.jpg',
        'pdf_files' => [
            [
                'title' => 'जैन पंचांग २०२६ वार्षिक PDF',
                'url' => '/documents/jain_panchang_2026.pdf',
                'size' => '5.6 MB'
            ]
        ],
        'status' => 'published',
        'publish_date' => now(),
        'is_active' => true,
        'is_featured' => true,
        'display_order' => 5
    ]
);

// 6. संघ विहार विशेषांक पत्रिका
Content::updateOrCreate(
    ['slug' => 'sangh-vihar-patrika-pdf'],
    [
        'content_type_id' => $granthType->id,
        'title' => ['hi' => 'वर्षा योग एवं विहार संस्मरण विशेषांक', 'en' => 'Sangh Vihar & Varsha Yog Special Issue'],
        'author' => ['hi' => 'सर्वोदय जिनधर्म संघ', 'en' => 'Sarvodaya Jindharm'],
        'description' => ['hi' => 'पूज्य आचार्य श्री विद्यासागर जी महाराज ससंघ वर्षा योग, प्रवचन एवं संस्मरण विशेषांक।'],
        'featured_image' => '/images/jain/muni_pravachan.jpg',
        'pdf_files' => [
            [
                'title' => 'विहार संस्मरण विशेषांक ई-पुस्तिका PDF',
                'url' => '/documents/varsha_yog_sangh_patrika.pdf',
                'size' => '8.2 MB'
            ]
        ],
        'status' => 'published',
        'publish_date' => now(),
        'is_active' => true,
        'is_featured' => false,
        'display_order' => 6
    ]
);

echo "Seeded 6 authentic Jain PDF Granths & Documents successfully!\n";
