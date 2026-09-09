<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Festival;
use Carbon\Carbon;

class FestivalController extends Controller
{
    /**
     * Display a listing of festivals.
     */
    public function index(Request $request)
    {
        $festivals = Festival::where('is_active', true)
            ->orderBy('festival_date', 'asc')
            ->paginate(12);

        return view('frontend.festivals.index', compact('festivals'));
    }

    /**
     * Display festival details.
     */
    public function show($slug)
    {
        $festival = Festival::where('is_active', true)
            ->where(function($q) use ($slug) {
                $q->where('slug', $slug)->orWhere('id', $slug);
            })
            ->firstOrFail();

        $related = Festival::where('is_active', true)
            ->where('id', '!=', $festival->id)
            ->orderBy('festival_date', 'asc')
            ->take(3)
            ->get();

        return view('frontend.festivals.show', compact('festival', 'related'));
    }
}
