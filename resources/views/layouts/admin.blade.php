<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'व्यवस्थापक डैशबोर्ड') - Universal Dharmik CMS</title>
    
    <!-- Google Fonts: Inter & Noto Sans Devanagari -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #ea580c; /* Spiritual Saffron */
            --primary-hover: #c2410c;
            --primary-light: #fff7ed;
            --bg-dark: #0f172a;
            --bg-light: #f8fafc;
            --text-dark: #0f172a;
            --text-light: #64748b;
            --text-medium: #334155;
            --sidebar-width: 280px;
            --glass-bg: rgba(15, 23, 42, 0.96);
            --border-radius-lg: 16px;
            --border-radius-md: 10px;
            --border-radius-sm: 6px;
            --card-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.04), 0 8px 10px -6px rgba(15, 23, 42, 0.04), 0 0 0 1px rgba(15, 23, 42, 0.02);
            --card-shadow-hover: 0 20px 35px -10px rgba(234, 88, 12, 0.08), 0 10px 15px -8px rgba(234, 88, 12, 0.04), 0 0 0 1px rgba(234, 88, 12, 0.05);
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Noto Sans Devanagari', sans-serif;
        }

        body {
            background-color: #faf9f6; /* Warm espiritual tint */
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Sidebar Glassmorphic Design */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 100;
            transition: var(--transition);
        }

        .sidebar-brand {
            padding: 26px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand i {
            color: var(--primary);
            font-size: 26px;
            animation: pulse 2s infinite;
        }

        .sidebar-brand h2 {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #fff;
        }

        .sidebar-menu {
            padding: 20px 14px;
            flex-grow: 1;
            overflow-y: auto;
            list-style: none;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 2px;
        }

        .menu-item {
            margin-bottom: 6px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            border-radius: var(--border-radius-md);
            font-size: 13.5px;
            font-weight: 500;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }

        .menu-link:hover, .menu-item.active .menu-link {
            color: white;
            background: rgba(234, 88, 12, 0.12);
            border-left-color: var(--primary);
            box-shadow: inset 4px 0 0 rgba(234, 88, 12, 0.05);
        }

        .menu-link i {
            width: 20px;
            font-size: 16px;
            text-align: center;
            transition: var(--transition);
        }

        .menu-link:hover i, .menu-item.active .menu-link i {
            color: var(--primary);
            transform: scale(1.1);
        }

        /* Main Content Area */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-width));
            transition: var(--transition);
        }

        /* Header */
        header {
            height: 75px;
            background: white;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 4px 20px -10px rgba(15, 23, 42, 0.03);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-light);
            cursor: pointer;
            display: none;
            padding: 8px;
            border-radius: 50%;
            transition: var(--transition);
        }

        .menu-toggle:hover {
            background: var(--bg-light);
            color: var(--text-dark);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .lang-selector {
            background: var(--bg-light);
            border: 1.5px solid #e2e8f0;
            border-radius: var(--border-radius-sm);
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-medium);
            outline: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .lang-selector:focus {
            border-color: var(--primary);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: var(--border-radius-md);
            transition: var(--transition);
        }

        .user-profile:hover {
            background: var(--bg-light);
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(234, 88, 12, 0.25);
        }

        .user-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .user-info p {
            font-size: 12px;
            color: var(--text-light);
            font-weight: 500;
        }

        /* Main Content Container */
        .content-container {
            padding: 40px;
            flex-grow: 1;
        }

        /* Alerts and Toast Messages */
        .alert {
            padding: 18px 24px;
            border-radius: var(--border-radius-md);
            margin-bottom: 30px;
            font-size: 14.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 5px solid;
            box-shadow: var(--card-shadow);
            animation: slideIn 0.3s ease-out;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-color: #22c55e;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-color: #ef4444;
        }

        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.06); }
            100% { transform: scale(1); }
        }

        @keyframes slideIn {
            from { transform: translateY(-12px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Responsive Styles */
        @media (max-width: 1024px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
            .sidebar.active {
                left: 0;
            }
            .menu-toggle {
                display: block;
            }
            .content-container {
                padding: 24px;
            }
            header {
                padding: 0 24px;
            }
        }

        /* --- Global Premium Form, Button, and Table Design System --- */
        .form-section {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 32px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: var(--transition);
        }

        .form-section:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-medium);
            margin-bottom: 10px;
            letter-spacing: 0.3px;
        }

        .form-control-custom {
            width: 100%;
            padding: 13px 18px;
            border: 1.5px solid #cbd5e1;
            border-radius: var(--border-radius-md);
            outline: none;
            font-size: 14.5px;
            color: var(--text-dark);
            background: #ffffff;
            transition: var(--transition);
        }

        .form-control-custom:hover:not(:focus) {
            border-color: #94a3b8;
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.12);
            background-color: #ffffff;
        }

        /* Responsive row flex that auto-wraps cleanly without squishing fields */
        .row-flex {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }

        .row-flex > div {
            display: flex;
            flex-direction: column;
        }

        /* Checkbox switch wrapper styling */
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 24px;
            padding: 10px 0;
        }

        .checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-medium);
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        /* Buttons styles */
        .btn-primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(234, 88, 12, 0.35);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: var(--text-medium);
            border: 1.5px solid #e2e8f0;
            padding: 14px 28px;
            border-radius: var(--border-radius-md);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: var(--text-dark);
            transform: translateY(-1px);
        }

        .panel-card {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 32px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: var(--transition);
        }

        .panel-card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            border-bottom: 1.5px solid #f1f5f9;
            padding-bottom: 16px;
        }

        .panel-title {
            font-size: 16.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-dark);
        }

        .panel-title i {
            color: var(--primary);
            font-size: 18px;
        }

        /* Beautiful styled table container wrapper */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: var(--border-radius-md);
            border: 1.5px solid #f1f5f9;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table th, .data-table td {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: var(--text-medium);
            vertical-align: middle;
        }

        .data-table th {
            background: #fafaf9;
            color: var(--text-light);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .data-table tbody tr {
            transition: var(--transition);
        }

        .data-table tbody tr:hover {
            background: #faf9f6; /* Warm highlight on row hover */
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-success {
            background: #dcfce7;
            color: #15803d;
            border: 1.5px solid #bbf7d0;
        }

        .badge-primary {
            background: #dbeafe;
            color: #1d4ed8;
            border: 1.5px solid #bfdbfe;
        }

        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
            border: 1.5px solid #fecaca;
        }

        .badge-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1.5px solid #e2e8f0;
        }

        /* Action buttons in tables */
        .btn-action {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 50%;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-action-edit {
            color: #2563eb;
        }

        .btn-action-edit:hover {
            background: #eff6ff;
            transform: scale(1.1);
        }

        .btn-action-delete {
            color: #dc2626;
        }

        .btn-action-delete:hover {
            background: #fef2f2;
            transform: scale(1.1);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Menu -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-om"></i>
            <h2>धर्म CMS (Dharmik CMS)</h2>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ url('admin/dashboard') }}" class="menu-link">
                    <i class="fa-solid fa-gauge"></i>
                    <span>डैशबोर्ड (Dashboard)</span>
                </a>
            </li>
            @can('saint.view')
            <li class="menu-item {{ Request::is('admin/saints*') ? 'active' : '' }}">
                <a href="{{ url('admin/saints') }}" class="menu-link">
                    <i class="fa-solid fa-user-tie"></i>
                    <span>साधु प्रबंधन (Saints)</span>
                </a>
            </li>
            @endcan
            @can('vihar.view')
            <li class="menu-item {{ Request::is('admin/vihar*') ? 'active' : '' }}">
                <a href="{{ url('admin/vihar') }}" class="menu-link">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>विहार ट्रैकिंग (Vihar)</span>
                </a>
            </li>
            @endcan
            @can('content_type.view')
            <li class="menu-item {{ Request::is('admin/content-types*') ? 'active' : '' }}">
                <a href="{{ url('admin/content-types') }}" class="menu-link">
                    <i class="fa-solid fa-sliders"></i>
                    <span>कंटेंट प्रकार (Content Types)</span>
                </a>
            </li>
            @endcan
            @can('category.view')
            <li class="menu-item {{ Request::is('admin/categories*') ? 'active' : '' }}">
                <a href="{{ url('admin/categories') }}" class="menu-link">
                    <i class="fa-solid fa-tags"></i>
                    <span>श्रेणी प्रबंधन (Categories)</span>
                </a>
            </li>
            @endcan
            @can('content.view')
            <li class="menu-item {{ Request::is('admin/contents*') ? 'active' : '' }}">
                <a href="{{ url('admin/contents') }}" class="menu-link">
                    <i class="fa-solid fa-file-pen"></i>
                    <span>कंटेंट संपादक (Editor)</span>
                </a>
            </li>
            @endcan
            @can('media.view')
            <li class="menu-item {{ Request::is('admin/media*') ? 'active' : '' }}">
                <a href="{{ url('admin/media') }}" class="menu-link">
                    <i class="fa-solid fa-photo-film"></i>
                    <span>मीडिया लाइब्रेरी (Library)</span>
                </a>
            </li>
            @endcan
            @can('gallery.view')
            <li class="menu-item {{ Request::is('admin/albums*') ? 'active' : '' }}">
                <a href="{{ url('admin/albums') }}" class="menu-link">
                    <i class="fa-solid fa-images"></i>
                    <span>चित्र दीर्घा (Gallery)</span>
                </a>
            </li>
            @endcan
            @can('event.view')
            <li class="menu-item {{ Request::is('admin/events*') ? 'active' : '' }}">
                <a href="{{ url('admin/events') }}" class="menu-link">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>कार्यक्रम प्रबंधन (Events)</span>
                </a>
            </li>
            @endcan
            @can('panchang.view')
            <li class="menu-item {{ Request::is('admin/panchang*') ? 'active' : '' }}">
                <a href="{{ url('admin/panchang') }}" class="menu-link">
                    <i class="fa-solid fa-clock"></i>
                    <span>दैनिक पंचांग (Panchang)</span>
                </a>
            </li>
            @endcan
            @can('festival.view')
            <li class="menu-item {{ Request::is('admin/festivals*') ? 'active' : '' }}">
                <a href="{{ url('admin/festivals') }}" class="menu-link">
                    <i class="fa-solid fa-award"></i>
                    <span>त्योहार कैलेंडर (Festivals)</span>
                </a>
            </li>
            @endcan
            @can('page.view')
            <li class="menu-item {{ Request::is('admin/pages*') ? 'active' : '' }}">
                <a href="{{ url('admin/pages') }}" class="menu-link">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>डायनामिक पेज (Pages)</span>
                </a>
            </li>
            @endcan
            @can('menu.view')
            <li class="menu-item {{ Request::is('admin/menus*') ? 'active' : '' }}">
                <a href="{{ url('admin/menus') }}" class="menu-link">
                    <i class="fa-solid fa-bars"></i>
                    <span>मेनू बिल्डर (Menu Builder)</span>
                </a>
            </li>
            @endcan
            @can('homepage.view')
            <li class="menu-item {{ Request::is('admin/homepage*') ? 'active' : '' }}">
                <a href="{{ url('admin/homepage') }}" class="menu-link">
                    <i class="fa-solid fa-house-laptop"></i>
                    <span>होमपेज बिल्डर (Homepage)</span>
                </a>
            </li>
            @endcan
            @can('user.view')
            <li class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                <a href="{{ url('admin/users') }}" class="menu-link">
                    <i class="fa-solid fa-users-gear"></i>
                    <span>उपयोगकर्ता (Users)</span>
                </a>
            </li>
            @endcan
            @can('role.view')
            <li class="menu-item {{ Request::is('admin/roles*') ? 'active' : '' }}">
                <a href="{{ url('admin/roles') }}" class="menu-link">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>भूमिकाएं (Roles)</span>
                </a>
            </li>
            @endcan
            @can('permission.view')
            <li class="menu-item {{ Request::is('admin/permissions*') ? 'active' : '' }}">
                <a href="{{ url('admin/permissions') }}" class="menu-link">
                    <i class="fa-solid fa-key"></i>
                    <span>अनुमतियां (Permissions)</span>
                </a>
            </li>
            @endcan
            @can('settings.view')
            <li class="menu-item {{ Request::is('admin/sangh-vihar*') ? 'active' : '' }}">
                <a href="{{ url('admin/sangh-vihar') }}" class="menu-link">
                    <i class="fa-solid fa-person-walking"></i>
                    <span>संघ व लाइव विहार (Sangh & Vihar)</span>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/quotes*') ? 'active' : '' }}">
                <a href="{{ url('admin/quotes') }}" class="menu-link">
                    <i class="fa-solid fa-quote-left"></i>
                    <span>दैनिक सुविचार (Quotes)</span>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/settings*') ? 'active' : '' }}">
                <a href="{{ url('admin/settings') }}" class="menu-link">
                    <i class="fa-solid fa-gears"></i>
                    <span>वेबसाइट सेटिंग्स (Settings)</span>
                </a>
            </li>
            @endcan
            @can('log.view')
            <li class="menu-item {{ Request::is('admin/logs*') ? 'active' : '' }}">
                <a href="{{ url('admin/logs') }}" class="menu-link">
                    <i class="fa-solid fa-receipt"></i>
                    <span>गतिविधि लॉग (Logs)</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>

    <!-- Main Section -->
    <div class="main-wrapper">
        <header>
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h3 style="font-weight: 600; font-size: 18px; color: var(--text-dark);">
                    @yield('header_title', 'प्रवेश द्वार (Management Portal)')
                </h3>
            </div>
            <div class="header-right">
                <!-- Lang selection -->
                <select class="lang-selector" onchange="window.location.href='?lang='+this.value">
                    <option value="hi" {{ app()->getLocale() == 'hi' ? 'selected' : '' }}>हिन्दी (Hindi)</option>
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English (अंग्रेजी)</option>
                    <option value="sa" {{ app()->getLocale() == 'sa' ? 'selected' : '' }}>संस्कृतम् (Sanskrit)</option>
                </select>

                <!-- Profile summary -->
                <div class="user-profile" onclick="window.location.href='{{ url('admin/users') }}'">
                    <div class="user-avatar" style="overflow: hidden; padding: 0;">
                        @if(auth()->user()->profile_photo && file_exists(public_path(auth()->user()->profile_photo)))
                            <img src="{{ asset(auth()->user()->profile_photo) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Avatar">
                        @else
                            {{ mb_substr(auth()->user()->name ?? 'प्र', 0, 1) }}
                        @endif
                    </div>
                    <div class="user-info">
                        <h4>{{ auth()->user()->name ?? 'व्यवस्थापक (Admin)' }}</h4>
                        <p>{{ auth()->user()->roles->first()->name ?? 'Super Admin' }}</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="content-container">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        // Sidebar Toggle for Mobile
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');

        if(menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 1024 && !sidebar.contains(e.target) && e.target !== menuToggle && !menuToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
