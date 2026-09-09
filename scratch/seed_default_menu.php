<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Menu;
use App\Models\MenuItem;

MenuItem::truncate();
Menu::truncate();

$menu = Menu::create([
    'name' => 'मुख्य हेडर मेनू (Main Header Menu)',
    'location' => 'header',
    'is_active' => true
]);

// 1. Home
MenuItem::create([
    'menu_id' => $menu->id,
    'label' => ['hi' => 'गृह (Home)', 'en' => 'Home'],
    'url' => '/',
    'icon' => 'fa-solid fa-house',
    'type' => 'internal',
    'display_order' => 1
]);

// 2. Saints
MenuItem::create([
    'menu_id' => $menu->id,
    'label' => ['hi' => 'संत परिचय', 'en' => 'Saints'],
    'url' => '/sants',
    'icon' => 'fa-solid fa-user-tie',
    'type' => 'internal',
    'display_order' => 2
]);

// 3. Swadhyay (Parent)
$swadhyay = MenuItem::create([
    'menu_id' => $menu->id,
    'label' => ['hi' => 'ज्ञान भण्डार', 'en' => 'Knowledge'],
    'url' => '/granths',
    'icon' => 'fa-solid fa-book-open',
    'type' => 'internal',
    'display_order' => 3
]);

MenuItem::create([
    'menu_id' => $menu->id,
    'parent_id' => $swadhyay->id,
    'label' => ['hi' => 'अमृत प्रवचन', 'en' => 'Pravachans'],
    'url' => '/pravachans',
    'icon' => 'fa-solid fa-microphone',
    'type' => 'internal',
    'display_order' => 1
]);

MenuItem::create([
    'menu_id' => $menu->id,
    'parent_id' => $swadhyay->id,
    'label' => ['hi' => 'पवित्र ग्रन्थ', 'en' => 'Granths'],
    'url' => '/granths',
    'icon' => 'fa-solid fa-scroll',
    'type' => 'internal',
    'display_order' => 2
]);

MenuItem::create([
    'menu_id' => $menu->id,
    'parent_id' => $swadhyay->id,
    'label' => ['hi' => 'भजन एवं स्तुति', 'en' => 'Bhajans'],
    'url' => '/bhajans',
    'icon' => 'fa-solid fa-music',
    'type' => 'internal',
    'display_order' => 3
]);

// 4. Panchang (Parent)
$panchang = MenuItem::create([
    'menu_id' => $menu->id,
    'label' => ['hi' => 'जैन पंचांग', 'en' => 'Panchang'],
    'url' => '/panchang',
    'icon' => 'fa-solid fa-calendar-days',
    'type' => 'internal',
    'display_order' => 4
]);

MenuItem::create([
    'menu_id' => $menu->id,
    'parent_id' => $panchang->id,
    'label' => ['hi' => 'पर्व एवं त्योहार', 'en' => 'Festivals'],
    'url' => '/festivals',
    'icon' => 'fa-solid fa-award',
    'type' => 'internal',
    'display_order' => 1
]);

MenuItem::create([
    'menu_id' => $menu->id,
    'parent_id' => $panchang->id,
    'label' => ['hi' => 'दैनिक सुविचार', 'en' => 'Daily Quotes'],
    'url' => '/suvichar',
    'icon' => 'fa-solid fa-quote-left',
    'type' => 'internal',
    'display_order' => 2
]);

// 5. Gallery
$gallery = MenuItem::create([
    'menu_id' => $menu->id,
    'label' => ['hi' => 'दीर्घा (Gallery)', 'en' => 'Gallery'],
    'url' => '/gallery/photos',
    'icon' => 'fa-solid fa-images',
    'type' => 'internal',
    'display_order' => 5
]);

MenuItem::create([
    'menu_id' => $menu->id,
    'parent_id' => $gallery->id,
    'label' => ['hi' => 'चित्र दीर्घा (Photos)', 'en' => 'Photos'],
    'url' => '/gallery/photos',
    'icon' => 'fa-solid fa-image',
    'type' => 'internal',
    'display_order' => 1
]);

MenuItem::create([
    'menu_id' => $menu->id,
    'parent_id' => $gallery->id,
    'label' => ['hi' => 'वीडियो दीर्घा (Videos)', 'en' => 'Videos'],
    'url' => '/gallery/videos',
    'icon' => 'fa-solid fa-video',
    'type' => 'internal',
    'display_order' => 2
]);

MenuItem::create([
    'menu_id' => $menu->id,
    'parent_id' => $gallery->id,
    'label' => ['hi' => 'PDF ग्रन्थ व पत्रिका (PDFs)', 'en' => 'PDF Library'],
    'url' => '/gallery/pdfs',
    'icon' => 'fa-solid fa-file-pdf',
    'type' => 'internal',
    'display_order' => 3
]);

// 6. News
MenuItem::create([
    'menu_id' => $menu->id,
    'label' => ['hi' => 'धर्म समाचार', 'en' => 'News'],
    'url' => '/news',
    'icon' => 'fa-solid fa-newspaper',
    'type' => 'internal',
    'display_order' => 6
]);

// 7. Events
MenuItem::create([
    'menu_id' => $menu->id,
    'label' => ['hi' => 'कार्यक्रम', 'en' => 'Events'],
    'url' => '/events',
    'icon' => 'fa-solid fa-clock',
    'type' => 'internal',
    'display_order' => 7
]);

// 8. Sangh & Vihar
MenuItem::create([
    'menu_id' => $menu->id,
    'label' => ['hi' => 'संघ व विहार', 'en' => 'Sangh & Vihar'],
    'url' => '/sangh-vihar',
    'icon' => 'fa-solid fa-location-dot',
    'type' => 'internal',
    'display_order' => 8
]);

// 9. Contact
MenuItem::create([
    'menu_id' => $menu->id,
    'label' => ['hi' => 'संपर्क', 'en' => 'Contact'],
    'url' => '/contact',
    'icon' => 'fa-solid fa-envelope',
    'type' => 'internal',
    'display_order' => 9
]);

echo "Default Header Menu seeded successfully with hierarchical structure!\n";

