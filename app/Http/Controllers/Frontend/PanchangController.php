<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Panchang;
use App\Models\Festival;
use Carbon\Carbon;

class PanchangController extends Controller
{
    /**
     * Display the monthly Jain Panchang calendar.
     */
    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $currentDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $currentDate->daysInMonth;
        $startOfMonth = (clone $currentDate)->startOfMonth();
        $endOfMonth = (clone $currentDate)->endOfMonth();

        // Fetch panchang records for the month
        $panchangRecords = Panchang::whereBetween('date', [
            $startOfMonth->toDateString(),
            $endOfMonth->toDateString()
        ])->get()->keyBy('date');

        // Fetch festivals for the month
        $festivals = Festival::whereBetween('festival_date', [
            $startOfMonth->toDateString(),
            $endOfMonth->toDateString()
        ])->get()->keyBy('festival_date');

        // Selected Date
        $selectedDateStr = $request->input('date', Carbon::today()->toDateString());
        $selectedPanchang = Panchang::where('date', $selectedDateStr)->first() 
            ?? $panchangRecords->first();
        $selectedFestival = Festival::where('festival_date', $selectedDateStr)->first();

        return view('frontend.panchang.index', compact(
            'currentDate',
            'daysInMonth',
            'panchangRecords',
            'festivals',
            'selectedDateStr',
            'selectedPanchang',
            'selectedFestival',
            'year',
            'month'
        ));
    }
}
