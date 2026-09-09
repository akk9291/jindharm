<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of upcoming and past events.
     */
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();

        $upcomingEvents = Event::where('is_active', true)
            ->where('start_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->paginate(9);

        $pastEvents = Event::where('is_active', true)
            ->where('start_date', '<', $today)
            ->orderBy('start_date', 'desc')
            ->take(6)
            ->get();

        return view('frontend.events.index', compact('upcomingEvents', 'pastEvents'));
    }

    /**
     * Display event details.
     */
    public function show($slug)
    {
        $event = Event::where('is_active', true)
            ->where(function($q) use ($slug) {
                $q->where('slug', $slug)->orWhere('id', $slug);
            })
            ->firstOrFail();

        $related = Event::where('is_active', true)
            ->where('id', '!=', $event->id)
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        return view('frontend.events.show', compact('event', 'related'));
    }
}
