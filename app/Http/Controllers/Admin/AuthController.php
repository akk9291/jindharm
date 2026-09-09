<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/admin/dashboard');
        }
        return view('admin.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Log login event
            ActivityLog::log('Authentication', 'Login', auth()->id(), 'प्रयोक्ता ने लॉग इन किया।');

            return redirect()->intended('/admin/dashboard')
                ->with('success', 'सफलतापूर्वक लॉगिन किया गया!');
        }

        return back()->withErrors([
            'email' => 'प्रदान किए गए क्रेडेंशियल हमारे रिकॉर्ड से मेल नहीं खाते हैं।',
        ])->onlyInput('email');
    }

    /**
     * Log user out.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::log('Authentication', 'Logout', auth()->id(), 'प्रयोक्ता ने लॉग आउट किया।');
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'सफलतापूर्वक लॉग आउट किया गया!');
    }
}
