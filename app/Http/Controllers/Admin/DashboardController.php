<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Saint;
use App\Models\Content;
use App\Models\AlbumPhoto;
use App\Models\Event;
use App\Models\Vihar;
use App\Models\Panchang;
use App\Models\Festival;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $totalSaints = Saint::active()->count();
        $totalContent = Content::published()->count();
        $totalPhotos = AlbumPhoto::where('is_active', true)->count();
        
        // Count videos, PDFs, audios stored in contents JSON columns
        $contents = Content::published()->get();
        $totalVideos = 0;
        $totalPdfs = 0;
        
        foreach ($contents as $content) {
            if (is_array($content->videos)) {
                $totalVideos += count($content->videos);
            }
            if (is_array($content->pdfs)) {
                $totalPdfs += count($content->pdfs);
            }
        }

        $totalEvents = Event::active()->count();
        
        $currentVihars = Vihar::current()->with('saint')->get();
        
        $todayPanchang = Panchang::forDate(now()->toDateString());
        
        $upcomingFestivals = Festival::active()
            ->where('festival_date', '>=', now()->toDateString())
            ->orderBy('festival_date', 'asc')
            ->limit(5)
            ->get();
            
        $recentActivities = ActivityLog::with('user')
            ->latest('id')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalSaints',
            'totalContent',
            'totalPhotos',
            'totalVideos',
            'totalPdfs',
            'totalEvents',
            'currentVihars',
            'todayPanchang',
            'upcomingFestivals',
            'recentActivities'
        ));
    }
}
