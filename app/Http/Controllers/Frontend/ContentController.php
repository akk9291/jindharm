<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\ContentType;
use App\Models\Category;

class ContentController extends Controller
{
    // ==========================================
    // 1. PRAVACHAN
    // ==========================================
    public function pravachanIndex(Request $request)
    {
        $query = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'pravachan');
        })
        ->where('status', 'published')
        ->where('is_active', true);

        // Filter by media type
        if ($request->filter === 'video') {
            $query->whereNotNull('videos');
        } elseif ($request->filter === 'audio') {
            $query->whereNotNull('audios');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title->hi', 'like', "%{$s}%")
                  ->orWhere('author->hi', 'like', "%{$s}%")
                  ->orWhere('short_description->hi', 'like', "%{$s}%");
            });
        }

        $pravachans = $query->orderBy('publish_date', 'desc')->paginate(9);

        $categories = Category::whereHas('contentType', function($q) {
            $q->where('slug', 'pravachan');
        })->get();

        return view('frontend.pravachan.index', compact('pravachans', 'categories'));
    }

    public function pravachanShow($slug)
    {
        $pravachan = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'pravachan');
        })
        ->where(function($q) use ($slug) {
            $q->where('slug', $slug)->orWhere('id', $slug);
        })
        ->firstOrFail();

        $related = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'pravachan');
        })
        ->where('id', '!=', $pravachan->id)
        ->where('status', 'published')
        ->where('is_active', true)
        ->take(3)
        ->get();

        return view('frontend.pravachan.show', compact('pravachan', 'related'));
    }

    // ==========================================
    // 2. GRANTH (SCRIPTURES)
    // ==========================================
    public function granthIndex(Request $request)
    {
        $query = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'granth');
        })
        ->where('status', 'published')
        ->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title->hi', 'like', "%{$s}%")
                  ->orWhere('author->hi', 'like', "%{$s}%");
            });
        }

        $granths = $query->orderBy('display_order', 'asc')->paginate(9);

        $categories = Category::whereHas('contentType', function($q) {
            $q->where('slug', 'granth');
        })->get();

        return view('frontend.granth.index', compact('granths', 'categories'));
    }

    public function granthShow($slug)
    {
        $granth = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'granth');
        })
        ->where(function($q) use ($slug) {
            $q->where('slug', $slug)->orWhere('id', $slug);
        })
        ->firstOrFail();

        $related = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'granth');
        })
        ->where('id', '!=', $granth->id)
        ->where('status', 'published')
        ->where('is_active', true)
        ->take(3)
        ->get();

        return view('frontend.granth.show', compact('granth', 'related'));
    }

    // ==========================================
    // 3. BHAJAN / STUTI
    // ==========================================
    public function bhajanIndex(Request $request)
    {
        $query = Content::whereHas('contentType', function($q) {
            $q->whereIn('slug', ['bhajan', 'stuti']);
        })
        ->where('status', 'published')
        ->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title->hi', 'like', "%{$s}%")
                  ->orWhere('author->hi', 'like', "%{$s}%");
            });
        }

        $bhajans = $query->orderBy('publish_date', 'desc')->paginate(10);

        $categories = Category::whereHas('contentType', function($q) {
            $q->whereIn('slug', ['bhajan', 'stuti']);
        })->get();

        return view('frontend.bhajan.index', compact('bhajans', 'categories'));
    }

    public function bhajanShow($slug)
    {
        $bhajan = Content::whereHas('contentType', function($q) {
            $q->whereIn('slug', ['bhajan', 'stuti']);
        })
        ->where(function($q) use ($slug) {
            $q->where('slug', $slug)->orWhere('id', $slug);
        })
        ->firstOrFail();

        $related = Content::whereHas('contentType', function($q) {
            $q->whereIn('slug', ['bhajan', 'stuti']);
        })
        ->where('id', '!=', $bhajan->id)
        ->where('status', 'published')
        ->where('is_active', true)
        ->take(4)
        ->get();

        return view('frontend.bhajan.show', compact('bhajan', 'related'));
    }

    // ==========================================
    // 4. NEWS / SAMACHAR
    // ==========================================
    public function newsIndex(Request $request)
    {
        $query = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'news');
        })
        ->with('category')
        ->where('status', 'published')
        ->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title->hi', 'like', "%{$s}%")
                  ->orWhere('short_description->hi', 'like', "%{$s}%");
            });
        }

        $newsList = $query->orderBy('publish_date', 'desc')->paginate(9);

        $categories = Category::whereHas('contentType', function($q) {
            $q->where('slug', 'news');
        })->get();

        return view('frontend.news.index', compact('newsList', 'categories'));
    }

    public function newsShow($slug)
    {
        $news = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'news');
        })
        ->with('category')
        ->where(function($q) use ($slug) {
            $q->where('slug', $slug)->orWhere('id', $slug);
        })
        ->firstOrFail();

        $related = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'news');
        })
        ->where('id', '!=', $news->id)
        ->where('status', 'published')
        ->where('is_active', true)
        ->take(3)
        ->get();

        return view('frontend.news.show', compact('news', 'related'));
    }
}
