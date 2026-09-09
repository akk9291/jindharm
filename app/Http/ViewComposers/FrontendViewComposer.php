<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Panchang;
use App\Models\Festival;
use App\Models\Setting;
use App\Models\HomepageSection;
use App\Models\Menu;
use Carbon\Carbon;

class FrontendViewComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $today = Carbon::today()->toDateString();

        // 1. Today's Panchang
        $todayPanchang = Panchang::where('date', $today)->first() 
            ?? Panchang::where('is_active', true)->orderBy('date', 'desc')->first();

        // 2. Today or Upcoming Festival
        $todayFestival = Festival::where('festival_date', $today)->first()
            ?? Festival::where('festival_date', '>=', $today)->orderBy('festival_date', 'asc')->first()
            ?? Festival::orderBy('festival_date', 'desc')->first();

        // 3. Global Settings map
        $settings = Setting::pluck('value', 'key')->toArray();

        // 4. Active Homepage Sections
        $activeSections = HomepageSection::where('is_active', true)
            ->where('show_on_website', true)
            ->pluck('section_key')
            ->toArray();

        // 5. Header Menu
        $headerMenu = Menu::with(['items' => function($q) {
            $q->whereNull('parent_id')->orderBy('display_order')->with(['children' => function($cq) {
                $cq->orderBy('display_order');
            }]);
        }])->where('location', 'header')->where('is_active', true)->first();

        $view->with([
            'todayPanchang' => $todayPanchang,
            'todayFestival' => $todayFestival,
            'siteSettings' => $settings,
            'activeSections' => $activeSections,
            'headerMenu' => $headerMenu,
        ]);
    }
}
