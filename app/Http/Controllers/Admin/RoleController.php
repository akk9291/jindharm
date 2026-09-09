<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        // Get all roles
        $roles = Role::with('permissions')->get();

        // Get permissions grouped by module
        $modules = [
            'साधु संघ (Saints)' => ['saint.view', 'saint.create', 'saint.edit', 'saint.delete'],
            'गुरु परम्परा (Guru Parampara)' => ['guru.view', 'guru.create', 'guru.edit', 'guru.delete'],
            'विहार प्रबंधन (Vihar Management)' => ['vihar.view', 'vihar.create', 'vihar.edit', 'vihar.delete'],
            'कंटेंट प्रकार (Content Types)' => ['content_type.view', 'content_type.create', 'content_type.edit', 'content_type.delete'],
            'श्रेणी प्रबंधन (Categories)' => ['category.view', 'category.create', 'category.edit', 'category.delete'],
            'सामग्री (Universal Content)' => ['content.view', 'content.create', 'content.edit', 'content.delete', 'content.publish'],
            'मीडिया लाइब्रेरी (Media Library)' => ['media.view', 'media.upload', 'media.edit', 'media.delete'],
            'चित्र दीर्घा (Gallery)' => ['gallery.view', 'gallery.create', 'gallery.edit', 'gallery.delete'],
            'वीडियो (Videos)' => ['video.view', 'video.create', 'video.edit', 'video.delete'],
            'ऑडियो (Audio)' => ['audio.view', 'audio.create', 'audio.edit', 'audio.delete'],
            'पीडीएफ लाइब्रेरी (PDF Library)' => ['pdf.view', 'pdf.create', 'pdf.edit', 'pdf.delete'],
            'कार्यक्रम प्रबंधन (Events)' => ['event.view', 'event.create', 'event.edit', 'event.delete'],
            'जैन पंचांग (Jain Panchang)' => ['panchang.view', 'panchang.create', 'panchang.edit', 'panchang.delete'],
            'त्योहार कैलेंडर (Festival Calendar)' => ['festival.view', 'festival.create', 'festival.edit', 'festival.delete'],
            'डायनामिक पेज (Pages)' => ['page.view', 'page.create', 'page.edit', 'page.delete'],
            'मेनू बिल्डर (Menu Builder)' => ['menu.view', 'menu.create', 'menu.edit', 'menu.delete'],
            'होमपेज बिल्डर (Homepage Builder)' => ['homepage.view', 'homepage.edit'],
            'एसईओ प्रबंधन (SEO)' => ['seo.view', 'seo.manage'],
            'वेबसाइट सेटिंग्स (Website Settings)' => ['settings.view', 'settings.manage'],
            'उपयोगकर्ता (Users)' => ['user.view', 'user.create', 'user.edit', 'user.delete'],
            'भूमिकाएं (Roles)' => ['role.view', 'role.create', 'role.edit', 'role.delete'],
            'अनुमतियां (Permissions)' => ['permission.view', 'permission.create', 'permission.edit', 'permission.delete'],
            'गतिविधि लॉग (Activity Logs)' => ['log.view'],
        ];

        return view('admin.roles.index', compact('roles', 'modules'));
    }

    /**
     * Store a newly created or updated role.
     */
    public function store(Request $request)
    {
        $id = $request->id;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($id),
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if ($id) {
            $role = Role::findOrFail($id);
            // Protect Super Admin name edit
            if ($role->name === 'Super Admin' && $request->name !== 'Super Admin') {
                return back()->with('error', 'सुपर एडमिन का नाम बदला नहीं जा सकता है! (Cannot rename Super Admin)');
            }
        } else {
            $role = new Role();
            $role->guard_name = 'web';
        }

        $role->name = $request->name;
        $role->save();

        // Sync permissions
        $perms = $request->input('permissions', []);
        
        // Super Admin must always retain all permissions
        if ($role->name === 'Super Admin') {
            $role->syncPermissions(Permission::all());
        } else {
            $role->syncPermissions($perms);
        }

        $msg = $id ? 'भूमिका सफलतापूर्वक अपडेट की गई!' : 'नयी भूमिका सफलतापूर्वक बनाई गई!';
        return redirect()->route('roles.index')->with('success', $msg);
    }

    /**
     * Remove the specified role.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Protect system roles
        $systemRoles = ['Super Admin', 'Admin', 'Viewer', 'Content Manager', 'Media Manager', 'Location Manager', 'SEO Manager'];
        if (in_array($role->name, $systemRoles)) {
            return back()->with('error', 'सिस्टम की डिफ़ॉल्ट भूमिकाओं को मिटाया नहीं जा सकता है! (Cannot delete system default roles)');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'भूमिका को सफलतापूर्वक हटा दिया गया है!');
    }

    /**
     * Clone a role.
     */
    public function clone(Request $request, $id)
    {
        $sourceRole = Role::findOrFail($id);

        $request->validate([
            'new_name' => 'required|string|max:255|unique:roles,name',
        ]);

        // Create new role
        $newRole = Role::create([
            'name' => $request->new_name,
            'guard_name' => 'web'
        ]);

        // Copy all permissions from source role
        $permissions = $sourceRole->permissions->pluck('name')->toArray();
        $newRole->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', "भूमिका '{$sourceRole->name}' को '{$newRole->name}' के रूप में सफलतापूर्वक क्लोन किया गया!");
    }
}
