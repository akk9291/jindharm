<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyQuote;
use Carbon\Carbon;

class QuoteController extends Controller
{
    /**
     * Display a listing of daily quotes / suvichar archive.
     */
    public function index(Request $request)
    {
        $query = DailyQuote::active();

        // Search Query
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q) {
                $sub->where('quote_text->hi', 'like', "%{$q}%")
                    ->orWhere('quote_text->en', 'like', "%{$q}%")
                    ->orWhere('author->hi', 'like', "%{$q}%")
                    ->orWhere('author->en', 'like', "%{$q}%")
                    ->orWhere('title->hi', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%");
            });
        }

        // Category Filter
        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        // Date Filter
        if ($request->filled('date')) {
            $query->whereDate('quote_date', $request->input('date'));
        }

        // Month Filter
        if ($request->filled('month')) {
            try {
                $m = Carbon::parse($request->input('month'));
                $query->whereYear('quote_date', $m->year)->whereMonth('quote_date', $m->month);
            } catch (\Exception $e) {}
        }

        // Today / Latest Featured for spotlight
        $today = Carbon::today()->toDateString();
        $featuredQuote = DailyQuote::active()->where('is_featured', true)->orderBy('quote_date', 'desc')->first()
            ?? DailyQuote::active()->whereDate('quote_date', $today)->first()
            ?? DailyQuote::active()->orderBy('quote_date', 'desc')->first();

        // Paginated quotes
        $quotes = $query->orderBy('quote_date', 'desc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        // Categories list
        $categories = DailyQuote::active()->whereNotNull('category')->distinct()->pluck('category')->filter()->values();

        return view('frontend.suvichar.index', compact(
            'quotes', 
            'featuredQuote', 
            'categories'
        ));
    }

    /**
     * Display a single daily quote.
     */
    public function show($id)
    {
        $quote = DailyQuote::active()->findOrFail($id);
        
        // Increment view count
        $quote->increment('views_count');

        // Related quotes
        $relatedQuotes = DailyQuote::active()
            ->where('id', '!=', $quote->id)
            ->when($quote->category, function($q) use ($quote) {
                $q->where('category', $quote->category);
            })
            ->orderBy('quote_date', 'desc')
            ->take(3)
            ->get();

        if ($relatedQuotes->isEmpty()) {
            $relatedQuotes = DailyQuote::active()->where('id', '!=', $quote->id)->orderBy('quote_date', 'desc')->take(3)->get();
        }

        return view('frontend.suvichar.show', compact('quote', 'relatedQuotes'));
    }
}
