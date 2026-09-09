@extends('layouts.admin')

@section('title', 'डैशबोर्ड (Dashboard)')
@section('header_title', 'मुख्य नियंत्रण पटल (Admin Dashboard)')

@section('content')
<style>
    /* Dashboard Specific Styles */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 24px 28px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: var(--transition);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
        border-color: rgba(234, 88, 12, 0.15);
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .stat-icon.saints { background: linear-gradient(135deg, #f97316, #ea580c); box-shadow: 0 4px 14px rgba(234, 88, 12, 0.25); }
    .stat-icon.content { background: linear-gradient(135deg, #3b82f6, #2563eb); box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25); }
    .stat-icon.photos { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25); }
    .stat-icon.videos { background: linear-gradient(135deg, #ef4444, #dc2626); box-shadow: 0 4px 14px rgba(220, 38, 38, 0.25); }
    .stat-icon.pdfs { background: linear-gradient(135deg, #8b5cf6, #7c3ded); box-shadow: 0 4px 14px rgba(124, 61, 237, 0.25); }
    .stat-icon.events { background: linear-gradient(135deg, #ec4899, #db2777); box-shadow: 0 4px 14px rgba(219, 39, 119, 0.25); }

    .stat-info h3 {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 4px;
        color: var(--text-dark);
    }

    .stat-info p {
        font-size: 13px;
        color: var(--text-light);
        font-weight: 600;
    }

    .dashboard-layout {
        display: grid;
        grid-template-columns: 1.7fr 1.3fr;
        gap: 32px;
    }

    @media (max-width: 1200px) {
        .dashboard-layout {
            grid-template-columns: 1fr;
        }
    }

    /* Traditional Panchang Card Design */
    .panchang-widget {
        background: linear-gradient(135deg, #fffcf5, #fef6e7);
        border: 2px solid #fed7aa;
        border-radius: var(--border-radius-lg);
        padding: 28px;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .panchang-widget::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(234, 88, 12, 0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .panchang-date {
        font-size: 15px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 16px;
        text-align: center;
        border-bottom: 1.5px dashed #fed7aa;
        padding-bottom: 12px;
    }

    .panchang-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 20px;
    }

    .panchang-item {
        background: white;
        padding: 12px;
        border-radius: var(--border-radius-md);
        border: 1px solid #fed7aa;
        text-align: center;
        transition: var(--transition);
    }

    .panchang-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(234, 88, 12, 0.05);
    }

    .panchang-item span {
        display: block;
        font-size: 11.5px;
        color: var(--text-light);
        margin-bottom: 4px;
        font-weight: 600;
    }

    .panchang-item strong {
        font-size: 14.5px;
        color: #7c2d12;
    }

    .panchang-times {
        display: flex;
        justify-content: space-around;
        background: rgba(255, 255, 255, 0.7);
        padding: 12px;
        border-radius: var(--border-radius-md);
        margin-bottom: 20px;
        font-size: 13.5px;
        font-weight: 600;
        border: 1px solid #fed7aa;
    }

    .panchang-notes {
        font-size: 13px;
        color: #7c2d12;
        line-height: 1.6;
        background: #fff;
        padding: 16px;
        border-radius: var(--border-radius-md);
        border-left: 5px solid var(--primary);
        border-top: 1px solid #fed7aa;
        border-right: 1px solid #fed7aa;
        border-bottom: 1px solid #fed7aa;
    }

    .saint-badge {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .saint-img {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        background: #e2e8f0;
        border: 1.5px solid var(--primary);
    }

    /* Activity list */
    .activity-list {
        list-style: none;
    }

    .activity-item {
        display: flex;
        gap: 14px;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 13px;
        flex-shrink: 0;
        border: 1px solid rgba(234, 88, 12, 0.15);
    }

    .activity-details p {
        margin-bottom: 4px;
        color: var(--text-medium);
        line-height: 1.5;
    }

    .activity-details span {
        font-size: 11.5px;
        color: var(--text-light);
        font-weight: 500;
    }
</style>

<!-- Metrics summary -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon saints"><i class="fa-solid fa-user-tie"></i></div>
        <div class="stat-info">
            <h3>{{ $totalSaints }}</h3>
            <p>कुल साधु संघ (Saints)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon content"><i class="fa-solid fa-file-pen"></i></div>
        <div class="stat-info">
            <h3>{{ $totalContent }}</h3>
            <p>कुल लेख/ग्रन्थ (Content)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon photos"><i class="fa-solid fa-images"></i></div>
        <div class="stat-info">
            <h3>{{ $totalPhotos }}</h3>
            <p>कुल फोटो (Photos)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon videos"><i class="fa-solid fa-circle-play"></i></div>
        <div class="stat-info">
            <h3>{{ $totalVideos }}</h3>
            <p>वीडियो प्रवचन (Videos)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pdfs"><i class="fa-solid fa-file-pdf"></i></div>
        <div class="stat-info">
            <h3>{{ $totalPdfs }}</h3>
            <p>पीडीएफ ग्रन्थ (PDFs)</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon events"><i class="fa-solid fa-calendar-check"></i></div>
        <div class="stat-info">
            <h3>{{ $totalEvents }}</h3>
            <p>मांगलिक कार्यक्रम (Events)</p>
        </div>
    </div>
</div>

<div class="dashboard-layout">
    <!-- Left Column: Vihar and Activities -->
    <div>
        <!-- Vihar Tracker widget -->
        <div class="panel-card">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>वर्तमान विहार ट्रैकिंग (Active Vihar Status)</span>
                </div>
            </div>
            @if($currentVihars->isEmpty())
                <p style="text-align: center; color: var(--text-light); padding: 20px 0;">वर्तमान में कोई विहार दर्ज नहीं है।</p>
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>पूज्य साधु (Saint)</th>
                                <th>विहार स्थान (Location)</th>
                                <th>शहर / राज्य (City & State)</th>
                                <th>प्रारंभ तिथि (Start Date)</th>
                                <th>सम्पर्क सूत्र (Contact)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentVihars as $vihar)
                                <tr>
                                    <td>
                                        <div class="saint-badge">
                                            @if($vihar->saint->photo)
                                                <img class="saint-img" src="{{ asset($vihar->saint->photo) }}" alt="Photo">
                                            @else
                                                <div class="saint-img" style="display:flex; align-items:center; justify-content:center; color:white; font-size:10px; background:linear-gradient(135deg, #f97316, #ea580c);">सा</div>
                                            @endif
                                            <strong>{{ $vihar->saint->getLocalized('name') }}</strong>
                                        </div>
                                    </td>
                                    <td><strong>{{ $vihar->getLocalized('location_title') }}</strong></td>
                                    <td>{{ $vihar->city }}, {{ $vihar->state }}</td>
                                    <td><span style="font-weight: 600;">{{ $vihar->start_date->format('d-M-Y') }}</span></td>
                                    <td>
                                        @if($vihar->contact_person)
                                            <strong>{{ $vihar->contact_person }}</strong> <br> <span style="font-size: 11.5px; color: var(--text-light)">{{ $vihar->contact_number }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Recent Activities Audit widget -->
        <div class="panel-card">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>हालिया गतिविधियां (Recent User Activities)</span>
                </div>
            </div>
            @if($recentActivities->isEmpty())
                <p style="text-align: center; color: var(--text-light); padding: 20px 0;">कोई गतिविधि दर्ज नहीं है।</p>
            @else
                <ul class="activity-list">
                    @foreach($recentActivities as $log)
                        <li class="activity-item">
                            <div class="activity-icon">
                                <i class="fa-solid fa-pen-nib"></i>
                            </div>
                            <div class="activity-details">
                                <p><strong>{{ $log->user->name ?? 'System' }}</strong> ने <strong>{{ $log->module }}</strong> पर <strong>{{ $log->action }}</strong> क्रिया की।</p>
                                @if($log->description)
                                    <p style="font-size: 12px; color: var(--text-light); margin-top: 2px;">{{ $log->description }}</p>
                                @endif
                                <span>{{ $log->created_at->diffForHumans() }} | IP: {{ $log->ip_address }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Right Column: Panchang and Festivals -->
    <div>
        <!-- Panchang Widget -->
        <div class="panel-card" style="padding: 0; border: none; background: transparent;">
            <div class="panchang-widget">
                <div class="panchang-date">
                    <i class="fa-solid fa-calendar-day"></i> आज का पंचांग ({{ now()->format('d F Y') }})
                </div>
                @if($todayPanchang)
                    <div class="panchang-grid">
                        <div class="panchang-item">
                            <span>तिथि (Tithi)</span>
                            <strong>{{ $todayPanchang->tithi }}</strong>
                        </div>
                        <div class="panchang-item">
                            <span>पक्ष (Paksha)</span>
                            <strong>{{ $todayPanchang->paksha }}</strong>
                        </div>
                        <div class="panchang-item">
                            <span>मास (Maas)</span>
                            <strong>{{ $todayPanchang->maas }}</strong>
                        </div>
                        <div class="panchang-item">
                            <span>नक्षत्र (Nakshatra)</span>
                            <strong>{{ $todayPanchang->nakshatra ?? '-' }}</strong>
                        </div>
                    </div>
                    <div class="panchang-times">
                        <div><i class="fa-solid fa-sun" style="color: #ea580c;"></i> सूर्योदय: {{ $todayPanchang->sunrise ?? '-' }}</div>
                        <div><i class="fa-solid fa-moon" style="color: #475569;"></i> सूर्यास्त: {{ $todayPanchang->sunset ?? '-' }}</div>
                    </div>
                    @if($todayPanchang->notes)
                        <div class="panchang-notes">
                            <strong>मांगलिक संदेश/विशेष:</strong><br>
                            {{ $todayPanchang->notes }}
                        </div>
                    @endif
                @else
                    <p style="text-align: center; color: #7c2d12; font-size: 13px;">आज के पंचांग की जानकारी उपलब्ध नहीं है।</p>
                @endif
            </div>
        </div>

        <!-- Upcoming Festivals Widget -->
        <div class="panel-card">
            <div class="panel-header">
                <div class="panel-title">
                    <i class="fa-solid fa-award"></i>
                    <span>आगामी त्योहार / कल्याणक (Festivals)</span>
                </div>
            </div>
            @if($upcomingFestivals->isEmpty())
                <p style="text-align: center; color: var(--text-light); padding: 10px 0;">कोई आगामी त्योहार उपलब्ध नहीं है।</p>
            @else
                <ul class="activity-list">
                    @foreach($upcomingFestivals as $fest)
                        <li class="activity-item" style="padding: 10px 0;">
                            <div class="activity-icon" style="background: #fff7ed; color: var(--primary);">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="activity-details">
                                <p style="font-weight: 600;">{{ $fest->getLocalized('festival_name') }}</p>
                                <span style="color: var(--primary); font-weight: 500;">{{ $fest->festival_date->format('d-M-Y') }} ({{ $fest->festival_date->diffForHumans() }})</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
