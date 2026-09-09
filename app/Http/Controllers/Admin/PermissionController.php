<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index()
    {
        $permissions = Permission::all();

        // Group permissions by their prefix (e.g. "saint.view" -> group: saint)
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $groupName = count($parts) > 1 ? $parts[0] : 'general';
            
            // Map prefix to friendly Hindi title
            $friendlyGroup = $this->getFriendlyGroupName($groupName);
            $groupedPermissions[$friendlyGroup][] = $permission;
        }

        return view('admin.permissions.index', compact('groupedPermissions'));
    }

    /**
     * Store a newly created or updated permission.
     */
    public function store(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9_\-]+\.[a-zA-Z0-9_\-]+$/', // Format: module.action
                Rule::unique('permissions')->ignore($id),
            ]
        ], [
            'name.regex' => 'अनुमति नाम प्रारूप "module.action" (उदा: saint.view) होना चाहिए। (Must be in module.action format)'
        ]);

        if ($id) {
            $permission = Permission::findOrFail($id);
        } else {
            $permission = new Permission();
            $permission->guard_name = 'web';
        }

        $permission->name = strtolower($request->name);
        $permission->save();

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $msg = $id ? 'अनुमति सफलतापूर्वक अपडेट की गई!' : 'नयी अनुमति सफलतापूर्वक बनाई गई!';
        return redirect()->route('permissions.index')->with('success', $msg);
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        
        // Prevent deleting core system permissions to avoid breaking defaults
        $corePermissions = [
            'saint.view', 'saint.create', 'saint.edit', 'saint.delete',
            'guru.view', 'guru.create', 'guru.edit', 'guru.delete',
            'vihar.view', 'vihar.create', 'vihar.edit', 'vihar.delete',
            'content_type.view', 'content_type.create', 'content_type.edit', 'content_type.delete',
            'category.view', 'category.create', 'category.edit', 'category.delete',
            'content.view', 'content.create', 'content.edit', 'content.delete', 'content.publish',
            'media.view', 'media.upload', 'media.edit', 'media.delete',
            'gallery.view', 'gallery.create', 'gallery.edit', 'gallery.delete',
            'video.view', 'video.create', 'video.edit', 'video.delete',
            'audio.view', 'audio.create', 'audio.edit', 'audio.delete',
            'pdf.view', 'pdf.create', 'pdf.edit', 'pdf.delete',
            'event.view', 'event.create', 'event.edit', 'event.delete',
            'panchang.view', 'panchang.create', 'panchang.edit', 'panchang.delete',
            'festival.view', 'festival.create', 'festival.edit', 'festival.delete',
            'page.view', 'page.create', 'page.edit', 'page.delete',
            'menu.view', 'menu.create', 'menu.edit', 'menu.delete',
            'homepage.view', 'homepage.edit',
            'seo.view', 'seo.manage',
            'settings.view', 'settings.manage',
            'user.view', 'user.create', 'user.edit', 'user.delete',
            'role.view', 'role.create', 'role.edit', 'role.delete',
            'permission.view', 'permission.create', 'permission.edit', 'permission.delete',
            'log.view'
        ];

        if (in_array($permission->name, $corePermissions)) {
            return back()->with('error', 'सिस्टम की मूल अनुमति को हटाया नहीं जा सकता है! (Cannot delete system core permissions)');
        }

        $permission->delete();
        
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('permissions.index')->with('success', 'अनुमति सफलतापूर्वक हटा दी गई!');
    }

    /**
     * Map a permission prefix to a friendly module name.
     */
    private function getFriendlyGroupName(string $prefix): string
    {
        $map = [
            'saint' => 'साधु (Saints)',
            'guru' => 'गुरु परंपरा (Guru Parampara)',
            'vihar' => 'विहार ट्रैकिंग (Vihar)',
            'content_type' => 'कंटेंट प्रकार (Content Types)',
            'category' => 'श्रेणी प्रबंधन (Categories)',
            'content' => 'सामग्री संपादक (Contents)',
            'media' => 'मीडिया लाइब्रेरी (Media)',
            'gallery' => 'चित्र दीर्घा (Gallery)',
            'video' => 'वीडियो लाइब्रेरी (Videos)',
            'audio' => 'ऑडियो लाइब्रेरी (Audio)',
            'pdf' => 'पीडीएफ लाइब्रेरी (PDF)',
            'event' => 'कार्यक्रम (Events)',
            'panchang' => 'पंचांग (Panchang)',
            'festival' => 'त्योहार कैलेंडर (Festivals)',
            'page' => 'डायनामिक पेज (Pages)',
            'menu' => 'मेनू बिल्डर (Menus)',
            'homepage' => 'होमपेज बिल्डर (Homepage)',
            'seo' => 'एसईओ प्रबंधन (SEO)',
            'settings' => 'वेबसाइट सेटिंग्स (Settings)',
            'user' => 'उपयोगकर्ता प्रबंधन (Users)',
            'role' => 'भूमिका प्रबंधन (Roles)',
            'permission' => 'अनुमति प्रबंधन (Permissions)',
            'log' => 'गतिविधि लॉग (Logs)',
        ];

        return $map[$prefix] ?? ucfirst($prefix);
    }
}
