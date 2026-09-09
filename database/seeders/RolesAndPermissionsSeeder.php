<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define Permissions by Module
        $modules = [
            'Saints' => ['saint.view', 'saint.create', 'saint.edit', 'saint.delete'],
            'Guru Parampara' => ['guru.view', 'guru.create', 'guru.edit', 'guru.delete'],
            'Vihar Management' => ['vihar.view', 'vihar.create', 'vihar.edit', 'vihar.delete'],
            'Content Types' => ['content_type.view', 'content_type.create', 'content_type.edit', 'content_type.delete'],
            'Categories' => ['category.view', 'category.create', 'category.edit', 'category.delete'],
            'Universal Content' => ['content.view', 'content.create', 'content.edit', 'content.delete', 'content.publish'],
            'Media Library' => ['media.view', 'media.upload', 'media.edit', 'media.delete'],
            'Gallery' => ['gallery.view', 'gallery.create', 'gallery.edit', 'gallery.delete'],
            'Videos' => ['video.view', 'video.create', 'video.edit', 'video.delete'],
            'Audio' => ['audio.view', 'audio.create', 'audio.edit', 'audio.delete'],
            'PDF Library' => ['pdf.view', 'pdf.create', 'pdf.edit', 'pdf.delete'],
            'Events' => ['event.view', 'event.create', 'event.edit', 'event.delete'],
            'Jain Panchang' => ['panchang.view', 'panchang.create', 'panchang.edit', 'panchang.delete'],
            'Festival Calendar' => ['festival.view', 'festival.create', 'festival.edit', 'festival.delete'],
            'Pages' => ['page.view', 'page.create', 'page.edit', 'page.delete'],
            'Menu Builder' => ['menu.view', 'menu.create', 'menu.edit', 'menu.delete'],
            'Homepage Builder' => ['homepage.view', 'homepage.edit'],
            'SEO' => ['seo.view', 'seo.manage'],
            'Website Settings' => ['settings.view', 'settings.manage'],
            'Users' => ['user.view', 'user.create', 'user.edit', 'user.delete'],
            'Roles' => ['role.view', 'role.create', 'role.edit', 'role.delete'],
            'Permissions' => ['permission.view', 'permission.create', 'permission.edit', 'permission.delete'],
            'Activity Logs' => ['log.view'],
        ];

        $allPermissions = [];
        foreach ($modules as $moduleName => $perms) {
            foreach ($perms as $perm) {
                Permission::findOrCreate($perm, 'web');
                $allPermissions[] = $perm;
            }
        }

        // 2. Create Roles and Assign Permissions
        
        // Super Admin
        $superAdmin = Role::findOrCreate('Super Admin', 'web');
        $superAdmin->syncPermissions($allPermissions);

        // Admin
        $admin = Role::findOrCreate('Admin', 'web');
        $adminPermissions = array_merge(
            $modules['Saints'],
            $modules['Guru Parampara'],
            $modules['Vihar Management'],
            $modules['Content Types'],
            $modules['Categories'],
            $modules['Universal Content'],
            $modules['Media Library'],
            $modules['Gallery'],
            $modules['Videos'],
            $modules['Audio'],
            $modules['PDF Library'],
            $modules['Events'],
            $modules['Jain Panchang'],
            $modules['Festival Calendar'],
            $modules['Pages'],
            $modules['Menu Builder'],
            $modules['Homepage Builder'],
            $modules['SEO'],
            $modules['Website Settings'],
            $modules['Activity Logs']
        );
        $admin->syncPermissions($adminPermissions);

        // Content Manager
        $contentManager = Role::findOrCreate('Content Manager', 'web');
        $contentManagerPermissions = array_merge(
            $modules['Content Types'],
            $modules['Categories'],
            $modules['Universal Content'],
            $modules['Pages']
        );
        $contentManager->syncPermissions($contentManagerPermissions);

        // Media Manager
        $mediaManager = Role::findOrCreate('Media Manager', 'web');
        $mediaManagerPermissions = array_merge(
            $modules['Media Library'],
            $modules['Gallery'],
            $modules['Videos'],
            $modules['Audio'],
            $modules['PDF Library']
        );
        $mediaManager->syncPermissions($mediaManagerPermissions);

        // Location Manager
        $locationManager = Role::findOrCreate('Location Manager', 'web');
        $locationManagerPermissions = array_merge(
            $modules['Vihar Management'],
            ['saint.view', 'guru.view']
        );
        $locationManager->syncPermissions($locationManagerPermissions);

        // SEO Manager
        $seoManager = Role::findOrCreate('SEO Manager', 'web');
        $seoManagerPermissions = array_merge(
            $modules['SEO'],
            ['settings.view']
        );
        $seoManager->syncPermissions($seoManagerPermissions);

        // Viewer
        $viewer = Role::findOrCreate('Viewer', 'web');
        $viewerPermissions = [
            'saint.view',
            'guru.view',
            'vihar.view',
            'content_type.view',
            'category.view',
            'content.view',
            'media.view',
            'gallery.view',
            'video.view',
            'audio.view',
            'pdf.view',
            'event.view',
            'panchang.view',
            'festival.view',
            'page.view',
            'menu.view',
            'homepage.view',
            'seo.view',
            'settings.view',
            'log.view'
        ];
        $viewer->syncPermissions($viewerPermissions);

        // 3. Create or Update Default Super Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@jindharm.com'],
            [
                'name' => 'धर्म व्यवस्थापक (Admin)',
                'password' => Hash::make('password'),
                'mobile' => '+91-9876543210',
                'status' => true,
            ]
        );
        $adminUser->syncRoles([$superAdmin]);

        // 4. Create some test users for each role to facilitate verification
        $testUsers = [
            [
                'name' => 'श्रेणी प्रबंधक (Content User)',
                'email' => 'content@jindharm.com',
                'role' => 'Content Manager',
                'mobile' => '+91-9876543211'
            ],
            [
                'name' => 'विहार प्रबंधक (Vihar User)',
                'email' => 'vihar@jindharm.com',
                'role' => 'Location Manager',
                'mobile' => '+91-9876543212'
            ],
            [
                'name' => 'एसईओ प्रबंधक (SEO User)',
                'email' => 'seo@jindharm.com',
                'role' => 'SEO Manager',
                'mobile' => '+91-9876543213'
            ],
            [
                'name' => 'दर्शक (Viewer User)',
                'email' => 'viewer@jindharm.com',
                'role' => 'Viewer',
                'mobile' => '+91-9876543214'
            ],
        ];

        foreach ($testUsers as $uData) {
            $user = User::updateOrCreate(
                ['email' => $uData['email']],
                [
                    'name' => $uData['name'],
                    'password' => Hash::make('password'),
                    'mobile' => $uData['mobile'],
                    'status' => true,
                ]
            );
            $user->syncRoles([$uData['role']]);
        }
    }
}
