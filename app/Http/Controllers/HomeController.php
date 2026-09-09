<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saint;
use App\Models\Content;
use App\Models\Event;
use App\Models\Panchang;
use App\Models\Festival;
use App\Models\Album;
use App\Models\AlbumPhoto;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     */
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // 1. Featured Saints
        $saints = Saint::with(['vihars' => function($q) {
            $q->where('type', 'current')->where('is_active', true);
        }])
        ->where('is_active', true)
        ->where('is_featured', true)
        ->orderBy('display_order', 'asc')
        ->take(4)
        ->get();

        // If no featured, get first 4
        if ($saints->isEmpty()) {
            $saints = Saint::with('vihars')->where('is_active', true)->take(4)->get();
        }

        // 2. Latest Pravachans
        $pravachans = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'pravachan');
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('publish_date', 'desc')
        ->take(3)
        ->get();

        // 3. Granths (Scriptures)
        $granths = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'granth');
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('display_order', 'asc')
        ->take(3)
        ->get();

        // 4. Bhajans & Stutis
        $bhajans = Content::whereHas('contentType', function($q) {
            $q->whereIn('slug', ['bhajan', 'stuti']);
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('publish_date', 'desc')
        ->take(3)
        ->get();

        // 5. Latest News
        $news = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'news');
        })
        ->with('category')
        ->where('status', 'published')
        ->where('is_active', true)
        ->orderBy('publish_date', 'desc')
        ->take(3)
        ->get();

        // 6. Upcoming Events
        $events = Event::where('is_active', true)
            ->where('start_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        // If no upcoming, take latest
        if ($events->isEmpty()) {
            $events = Event::where('is_active', true)->orderBy('start_date', 'desc')->take(3)->get();
        }

        // 7. Photo Gallery
        $photos = AlbumPhoto::with('album')
            ->where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->take(6)
            ->get();

        // 8. Video Gallery (extract from pravachans / contents)
        $videos = Content::whereNotNull('videos')
            ->where('status', 'published')
            ->where('is_active', true)
            ->take(3)
            ->get();

        return view('home.index', compact(
            'saints',
            'pravachans',
            'granths',
            'bhajans',
            'news',
            'events',
            'photos',
            'videos'
        ));
    }
}
