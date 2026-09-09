<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\AlbumPhoto;
use App\Models\Content;

class GalleryController extends Controller
{
    /**
     * Display photo albums (Folders) gallery.
     */
    public function photos(Request $request)
    {
        if ($request->filled('album')) {
            return $this->albumShow($request->album);
        }

        $albums = Album::where('is_active', true)
            ->withCount(['photos' => function($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('display_order', 'asc')
            ->paginate(12);

        return view('frontend.gallery.photos', compact('albums'));
    }

    /**
     * Display photos inside a specific album (Folder) with Lightbox.
     */
    public function albumShow($id)
    {
        $album = Album::with(['photos' => function($q) {
            $q->where('is_active', true)->orderBy('display_order', 'asc');
        }])->where('is_active', true)->findOrFail($id);

        $otherAlbums = Album::where('is_active', true)
            ->where('id', '!=', $id)
            ->withCount(['photos' => function($q) {
                $q->where('is_active', true);
            }])
            ->take(4)
            ->get();

        return view('frontend.gallery.album-photos', compact('album', 'otherAlbums'));
    }

    /**
     * Display video gallery.
     */
    public function videos(Request $request)
    {
        $query = Content::whereNotNull('videos')
            ->where('status', 'published')
            ->where('is_active', true);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title->hi', 'like', "%{$s}%")
                  ->orWhere('author->hi', 'like', "%{$s}%");
            });
        }

        $videos = $query->orderBy('publish_date', 'desc')->paginate(12);

        return view('frontend.gallery.videos', compact('videos'));
    }

    /**
     * Display PDF documents / E-Granth library gallery.
     */
    public function pdfs(Request $request)
    {
        $query = Content::with(['contentType', 'category'])
            ->whereNotNull('pdfs')
            ->where('status', 'published')
            ->where('is_active', true);

        if ($request->filled('type')) {
            $typeSlug = $request->type;
            $query->whereHas('contentType', function($q) use ($typeSlug) {
                $q->where('slug', $typeSlug);
            });
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title->hi', 'like', "%{$s}%")
                  ->orWhere('title->en', 'like', "%{$s}%")
                  ->orWhere('author->hi', 'like', "%{$s}%")
                  ->orWhere('author->en', 'like', "%{$s}%")
                  ->orWhere('short_description->hi', 'like', "%{$s}%")
                  ->orWhere('full_description->hi', 'like', "%{$s}%");
            });
        }

        $pdfs = $query->orderBy('publish_date', 'desc')
                      ->orderBy('id', 'desc')
                      ->paginate(12);

        return view('frontend.gallery.pdfs', compact('pdfs'));
    }
}
