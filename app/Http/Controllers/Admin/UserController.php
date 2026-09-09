<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Store a newly created or updated user in storage.
     */
    public function store(Request $request)
    {
        $id = $request->id;

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
            'mobile' => 'nullable|string|max:20',
            'role' => 'required|string|exists:roles,name',
            'status' => 'required|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Password is only required on creation
        if (!$id) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        $request->validate($rules);

        // Fetch or create user instance
        if ($id) {
            $user = User::findOrFail($id);
            
            // Protect Super Admin role modifications from non-Super Admins
            if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
                return back()->with('error', 'आपके पास सुपर एडमिन को संशोधित करने की अनुमति नहीं है। (Unauthorized)');
            }
        } else {
            $user = new User();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                @unlink(public_path($user->profile_photo));
            }

            $photo = $request->file('profile_photo');
            $fileName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            
            // Ensure directory exists
            $uploadDir = public_path('uploads/profiles');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $photo->move($uploadDir, $fileName);
            $user->profile_photo = 'uploads/profiles/' . $fileName;
        }

        $user->save();

        // Sync Spatie Role (Except if trying to strip Super Admin role by non-Super Admin)
        if ($user->id === 1 || ($user->hasRole('Super Admin') && $request->role !== 'Super Admin' && !auth()->user()->hasRole('Super Admin'))) {
            // Keep Super Admin
        } else {
            $user->syncRoles([$request->role]);
        }

        $msg = $id ? 'उपयोगकर्ता जानकारी सफलतापूर्वक अपडेट की गई!' : 'नया उपयोगकर्ता सफलतापूर्वक बनाया गया!';
        return redirect()->route('users.index')->with('success', $msg);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting ID 1 (Primary Admin) or any Super Admin by non-Super Admins
        if ($user->id === 1 || $user->hasRole('Super Admin')) {
            return back()->with('error', 'सुपर एडमिन को हटाया नहीं जा सकता है! (Cannot delete Super Admin)');
        }

        // Delete photo file if exists
        if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
            @unlink(public_path($user->profile_photo));
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'उपयोगकर्ता को सफलतापूर्वक हटा दिया गया है!');
    }

    /**
     * Reset password helper.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_password' => 'required|string|min:6',
        ]);

        $user = User::findOrFail($request->user_id);
        
        // Prevent password reset of Super Admins by non-Super Admins
        if ($user->hasRole('Super Admin') && !auth()->user()->hasRole('Super Admin')) {
            return back()->with('error', 'आपके पास सुपर एडमिन पासवर्ड बदलने की अनुमति नहीं है। (Unauthorized)');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('users.index')->with('success', 'पासवर्ड सफलतापूर्वक रीसेट किया गया!');
    }
}
