<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Saint;
use App\Models\Content;
use App\Models\Event;

class SearchController extends Controller
{
    /**
     * Universal portal search.
     */
    public function index(Request $request)
    {
        $q = trim($request->input('q', ''));
        $tab = $request->input('tab', 'all');

        $saints = collect();
        $pravachans = collect();
        $granths = collect();
        $bhajans = collect();
        $news = collect();
        $events = collect();

        if ($q) {
            // 1. Saints
            $saints = Saint::where('is_active', true)
                ->where(function($query) use ($q) {
                    $query->where('name->hi', 'like', "%{$q}%")
                          ->orWhere('name->en', 'like', "%{$q}%")
                          ->orWhere('title->hi', 'like', "%{$q}%");
                })->get();

            // 2. Pravachan
            $pravachans = Content::whereHas('contentType', fn($t) => $t->where('slug', 'pravachan'))
                ->where('status', 'published')
                ->where('is_active', true)
                ->where(function($query) use ($q) {
                    $query->where('title->hi', 'like', "%{$q}%")
                          ->orWhere('author->hi', 'like', "%{$q}%")
                          ->orWhere('short_description->hi', 'like', "%{$q}%");
                })->get();

            // 3. Granth
            $granths = Content::whereHas('contentType', fn($t) => $t->where('slug', 'granth'))
                ->where('status', 'published')
                ->where('is_active', true)
                ->where(function($query) use ($q) {
                    $query->where('title->hi', 'like', "%{$q}%")
                          ->orWhere('author->hi', 'like', "%{$q}%")
                          ->orWhere('short_description->hi', 'like', "%{$q}%");
                })->get();

            // 4. Bhajan
            $bhajans = Content::whereHas('contentType', fn($t) => $t->whereIn('slug', ['bhajan', 'stuti']))
                ->where('status', 'published')
                ->where('is_active', true)
                ->where(function($query) use ($q) {
                    $query->where('title->hi', 'like', "%{$q}%")
                          ->orWhere('author->hi', 'like', "%{$q}%");
                })->get();

            // 5. News
            $news = Content::whereHas('contentType', fn($t) => $t->where('slug', 'news'))
                ->where('status', 'published')
                ->where('is_active', true)
                ->where(function($query) use ($q) {
                    $query->where('title->hi', 'like', "%{$q}%")
                          ->orWhere('short_description->hi', 'like', "%{$q}%");
                })->get();

            // 6. Events
            $events = Event::where('is_active', true)
                ->where(function($query) use ($q) {
                    $query->where('event_name->hi', 'like', "%{$q}%")
                          ->orWhere('venue->hi', 'like', "%{$q}%");
                })->get();
        }

        $totalResults = $saints->count() + $pravachans->count() + $granths->count() + $bhajans->count() + $news->count() + $events->count();

        return view('frontend.search.index', compact(
            'q',
            'tab',
            'saints',
            'pravachans',
            'granths',
            'bhajans',
            'news',
            'events',
            'totalResults'
        ));
    }
}
