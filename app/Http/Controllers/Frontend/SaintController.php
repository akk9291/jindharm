<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Saint;
use App\Models\Vihar;
use App\Models\Setting;
use App\Models\Content;

class SaintController extends Controller
{
    /**
     * Display a listing of revered saints.
     */
    public function index(Request $request)
    {
        $query = Saint::with(['vihars' => function($q) {
            $q->where('type', 'current')->where('is_active', true);
        }])
        ->where('is_active', true);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('name->hi', 'like', "%{$s}%")
                  ->orWhere('name->en', 'like', "%{$s}%")
                  ->orWhere('title->hi', 'like', "%{$s}%");
            });
        }

        $saints = $query->orderBy('display_order', 'asc')->paginate(12);

        return view('frontend.sants.index', compact('saints'));
    }

    /**
     * Display saint details.
     */
    public function show($slug)
    {
        $saint = Saint::with(['vihars' => function($q) {
            $q->where('is_active', true)->orderBy('start_date', 'desc');
        }, 'parent', 'children'])
        ->where('slug', $slug)
        ->orWhere('id', $slug)
        ->firstOrFail();

        // Related Pravachans by or about this saint
        $saintNameHi = is_array($saint->name) ? ($saint->name['hi'] ?? '') : '';
        $pravachans = Content::whereHas('contentType', function($q) {
            $q->where('slug', 'pravachan');
        })
        ->where('status', 'published')
        ->where('is_active', true)
        ->where(function($q) use ($saintNameHi) {
            $q->where('author->hi', 'like', "%{$saintNameHi}%")
              ->orWhere('title->hi', 'like', "%{$saintNameHi}%")
              ->orWhere('tags', 'like', "%{$saintNameHi}%");
        })
        ->take(4)
        ->get();

        $currentVihar = $saint->vihars->where('type', 'current')->first();
        $pastVihars = $saint->vihars->where('type', 'previous');
        $upcomingVihars = $saint->vihars->where('type', 'upcoming');

        return view('frontend.sants.show', compact('saint', 'pravachans', 'currentVihar', 'pastVihars', 'upcomingVihars'));
    }

    /**
     * Display Sangh Information & Live Vihar Status public portal page.
     */
    public function sanghVihar()
    {
        $viharSettings = Setting::where('group', 'vihar')
            ->orWhere('key', 'like', 'vihar_%')
            ->orWhere('key', 'marquee_text')
            ->pluck('value', 'key');

        $saints = Saint::with(['vihars' => function($q) {
            $q->where('is_active', true)->orderBy('start_date', 'desc');
        }])
        ->where('is_active', true)
        ->orderBy('display_order', 'asc')
        ->get();

        $recentVihars = Vihar::with('saint')
            ->where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->take(8)
            ->get();

        return view('frontend.sangh-vihar', compact('viharSettings', 'saints', 'recentVihars'));
    }
}
