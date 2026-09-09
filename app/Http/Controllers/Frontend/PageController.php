<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Setting;

class PageController extends Controller
{
    /**
     * Display a dynamic CMS page.
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('frontend.pages.show', compact('page'));
    }

    /**
     * Display contact page.
     */
    public function contact()
    {
        $officesJson = Setting::where('key', 'offices')->value('value');
        $offices = $officesJson ? json_decode($officesJson, true) : [];

        return view('frontend.pages.contact', compact('offices'));
    }

    /**
     * Handle contact form inquiry submission.
     */
    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // In production: store inquiry or send notification email
        return back()->with('success', 'धन्यवाद! आपका संदेश सफलतापूर्वक प्राप्त हो गया है। हमारी समिति शीघ्र ही आपसे संपर्क करेगी।');
    }
}
