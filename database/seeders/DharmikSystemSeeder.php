<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ContentType;
use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\Setting;
use App\Models\Panchang;
use App\Models\Festival;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DharmikSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles and Permissions
        $permissions = [
            'manage-users',
            'manage-settings',
            'manage-saints',
            'manage-vihar',
            'manage-content',
            'manage-media',
            'manage-events',
            'manage-panchang',
            'manage-seo',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $superAdminRole = Role::findOrCreate('Super Admin');
        $superAdminRole->givePermissionTo($permissions);

        $adminRole = Role::findOrCreate('Admin');
        $adminRole->givePermissionTo([
            'manage-saints',
            'manage-vihar',
            'manage-content',
            'manage-media',
            'manage-events',
            'manage-panchang',
            'manage-seo',
        ]);

        $contentManagerRole = Role::findOrCreate('Content Manager');
        $contentManagerRole->givePermissionTo(['manage-content', 'manage-events']);

        $mediaManagerRole = Role::findOrCreate('Media Manager');
        $mediaManagerRole->givePermissionTo(['manage-media']);

        $locationManagerRole = Role::findOrCreate('Location Manager');
        $locationManagerRole->givePermissionTo(['manage-saints', 'manage-vihar']);

        $seoManagerRole = Role::findOrCreate('SEO Manager');
        $seoManagerRole->givePermissionTo(['manage-seo']);

        // 2. Default Administrative User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@jindharm.com'],
            [
                'name' => 'धर्म व्यवस्थापक (Admin)',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->assignRole($superAdminRole);

        // 3. Default Content Types
        $contentTypes = [
            [
                'name' => ['hi' => 'ग्रन्थ', 'en' => 'Granths', 'sa' => 'ग्रन्थाः'],
                'slug' => 'granth',
                'icon' => 'book-open',
                'display_order' => 1,
            ],
            [
                'name' => ['hi' => 'भजन', 'en' => 'Bhajans', 'sa' => 'भजनानि'],
                'slug' => 'bhajan',
                'icon' => 'music',
                'display_order' => 2,
            ],
            [
                'name' => ['hi' => 'प्रवचन', 'en' => 'Pravachans', 'sa' => 'प्रवचनानि'],
                'slug' => 'pravachan',
                'icon' => 'mic',
                'display_order' => 3,
            ],
            [
                'name' => ['hi' => 'समाचार', 'en' => 'News', 'sa' => 'वार्ताः'],
                'slug' => 'news',
                'icon' => 'newspaper',
                'display_order' => 4,
            ],
            [
                'name' => ['hi' => 'स्तुति / आरती', 'en' => 'Stuti & Aarti', 'sa' => 'स्तुतिः'],
                'slug' => 'stuti',
                'icon' => 'award',
                'display_order' => 5,
            ]
        ];

        foreach ($contentTypes as $typeData) {
            $contentType = ContentType::updateOrCreate(
                ['slug' => $typeData['slug']],
                [
                    'name' => $typeData['name'],
                    'icon' => $typeData['icon'],
                    'display_order' => $typeData['display_order'],
                    'is_active' => true,
                    'show_on_website' => true,
                    'show_on_app' => true,
                ]
            );

            // Add default categories for Bhajan and Stuti
            if ($typeData['slug'] === 'bhajan') {
                Category::updateOrCreate(
                    ['content_type_id' => $contentType->id, 'slug' => 'jin-bhakti'],
                    [
                        'name' => ['hi' => 'जिन भक्ति', 'en' => 'Jin Bhakti', 'sa' => 'जिन भक्तिः'],
                        'description' => ['hi' => 'जिनेन्द्र देव के भक्ति गीत', 'en' => 'Devotional songs of Jinendra Dev'],
                        'display_order' => 1,
                    ]
                );

                Category::updateOrCreate(
                    ['content_type_id' => $contentType->id, 'slug' => 'guru-bhakti'],
                    [
                        'name' => ['hi' => 'गुरु भक्ति', 'en' => 'Guru Bhakti', 'sa' => 'गुरु भक्तिः'],
                        'description' => ['hi' => 'आचार्य और मुनि संघ के भक्ति गीत', 'en' => 'Devotional songs for Gurudev'],
                        'display_order' => 2,
                    ]
                );
            }
        }

        // 4. Homepage builder default sections
        $sections = [
            'hero_slider' => ['hi' => 'मुख्य स्लाइडर', 'en' => 'Hero Slider'],
            'current_vihar' => ['hi' => 'वर्तमान विहार', 'en' => 'Current Vihar'],
            'saints' => ['hi' => 'पूज्य साधु संघ', 'en' => 'Revered Saints'],
            'latest_news' => ['hi' => 'नवीनतम समाचार', 'en' => 'Latest News'],
            'latest_granth' => ['hi' => 'नवीनतम ग्रन्थ', 'en' => 'Latest Granths'],
            'latest_bhajan' => ['hi' => 'लोकप्रिय भजन', 'en' => 'Popular Bhajans'],
            'gallery' => ['hi' => 'चित्र दीर्घा', 'en' => 'Photo Gallery'],
            'events' => ['hi' => 'आगामी मांगलिक कार्यक्रम', 'en' => 'Upcoming Events'],
            'panchang' => ['hi' => 'दैनिक पंचांग', 'en' => 'Daily Panchang'],
            'festivals' => ['hi' => 'त्योहार एवं व्रत', 'en' => 'Festivals & Vrats'],
        ];

        $order = 1;
        foreach ($sections as $key => $label) {
            HomepageSection::updateOrCreate(
                ['section_key' => $key],
                [
                    'label' => $label,
                    'is_active' => true,
                    'display_order' => $order++,
                    'show_on_website' => true,
                    'show_on_app' => true,
                    'settings' => []
                ]
            );
        }

        // 5. Global Settings
        $defaultSettings = [
            // General Settings
            ['key' => 'website_name', 'value' => 'सर्वोदय जैन धर्म संघ', 'type' => 'text', 'group' => 'general'],
            ['key' => 'logo', 'value' => '/images/default_logo.png', 'type' => 'image', 'group' => 'general'],
            ['key' => 'favicon', 'value' => '/favicon.ico', 'type' => 'image', 'group' => 'general'],
            
            // Contact Settings
            ['key' => 'contact_mobile', 'value' => '+91-9876543210', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'info@jindharm.org', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => 'https://wa.me/919876543210', 'type' => 'text', 'group' => 'contact'],
            
            // Social Links
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/jindharm', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/jindharm', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/jindharm', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_telegram', 'value' => 'https://t.me/jindharm', 'type' => 'text', 'group' => 'social'],
            
            // SMTP Config
            ['key' => 'smtp_host', 'value' => 'smtp.mailtrap.io', 'type' => 'text', 'group' => 'smtp'],
            ['key' => 'smtp_port', 'value' => '2525', 'type' => 'text', 'group' => 'smtp'],
            ['key' => 'smtp_username', 'value' => 'smtp_user', 'type' => 'text', 'group' => 'smtp'],
            ['key' => 'smtp_password', 'value' => 'smtp_password', 'type' => 'text', 'group' => 'smtp'],
            
            // Multiple Offices
            ['key' => 'offices', 'value' => json_encode([
                [
                    'name' => 'प्रधान कार्यालय (Head Office) - दिल्ली',
                    'address' => '१०२, धर्म भवन, चांदनी चौक, दिल्ली - ११०००६',
                    'mobile' => '+91-11-23456789',
                    'email' => 'delhi@jindharm.org',
                    'google_map' => 'https://maps.google.com'
                ],
                [
                    'name' => 'आश्रम कार्यालय - इंदौर',
                    'address' => 'जैन तपोभूमि आश्रम, इंदौर, मध्य प्रदेश - ४५२००१',
                    'mobile' => '+91-731-9876543',
                    'email' => 'indore@jindharm.org',
                    'google_map' => 'https://maps.google.com'
                ]
            ], JSON_UNESCAPED_UNICODE), 'type' => 'json', 'group' => 'offices']
        ];

        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group']
                ]
            );
        }

        // 6. Sample Panchang
        Panchang::updateOrCreate(
            ['date' => now()->toDateString()],
            [
                'tithi' => 'दशमी / एकादशी',
                'paksha' => 'शुक्ल पक्ष',
                'maas' => 'आषाढ़',
                'nakshatra' => 'हस्त / चित्रा',
                'sunrise' => '०५:३५ AM',
                'sunset' => '०७:१२ PM',
                'notes' => 'आज संयम दिवस है। पूज्य मुनि श्री के प्रवचन प्रात: ८ बजे होंगे।',
                'is_active' => true,
            ]
        );

        // 7. Sample Festivals
        Festival::updateOrCreate(
            ['festival_date' => now()->toDateString()],
            [
                'festival_name' => ['hi' => 'महावीर जयंती', 'en' => 'Mahavir Jayanti', 'sa' => 'महावीर जयन्ती'],
                'description' => ['hi' => '२४वें तीर्थंकर भगवान महावीर स्वामी का जन्म कल्याणक महोत्सव।', 'en' => 'Birth Anniversary celebration of Lord Mahavira.'],
                'is_active' => true,
                'is_featured' => true,
                'show_on_website' => true,
                'show_on_app' => true,
            ]
        );
    }
}
