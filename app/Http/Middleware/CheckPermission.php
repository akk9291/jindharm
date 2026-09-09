<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->status) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json(['error' => 'आपका खाता निष्क्रिय कर दिया गया है। (Account is deactivated)'], 403);
            }

            return redirect()->route('login')->with('error', 'आपका खाता निष्क्रिय कर दिया गया है। कृपया व्यवस्थापक से संपर्क करें।');
        }

        // Super Admin has all permissions bypass
        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        // Check permission (comma separated means user needs at least one of the permissions - OR check)
        $perms = explode(',', $permission);
        $hasPermission = false;
        
        foreach ($perms as $p) {
            if ($user->can(trim($p))) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'unauthorized', 'message' => 'इस कार्य के लिए आपके पास अनुमति नहीं है। (Unauthorized access)'], 403);
            }
            abort(403, 'इस कार्य के लिए आपके पास अनुमति नहीं है। (Unauthorized access)');
        }

        return $next($request);
    }
}
