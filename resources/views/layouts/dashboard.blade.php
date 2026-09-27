<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Perpustakaan') - Sistem Perpustakaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1d4ed8;
            --primary-hover: #1e40af;
            --primary-light: #eff6ff;
            --primary-border: #bfdbfe;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --bg-page: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --border-focus: #3b82f6;
            --sidebar-width: 260px;
            --topbar-height: 70px;
            
            --success: #15803d;
            --success-bg: #f0fdf4;
            --success-border: #bbf7d0;

            --warning: #b45309;
            --warning-bg: #fffbeb;
            --warning-border: #fde68a;

            --danger: #b91c1c;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;

            --info: #0369a1;
            --info-bg: #f0f9ff;
            --info-border: #bae6fd;

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --shadow-xs: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.06), 0 2px 4px -2px rgba(0,0,0,0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--bg-card);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 40;
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            height: var(--topbar-height);
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            color: #ffffff;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }

        .brand-text h2 {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.3px;
            color: #0f172a;
            line-height: 1.2;
        }

        .brand-text span {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 20px 14px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .menu-category {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 10px 12px 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            color: #475569;
            text-decoration: none;
            transition: all 0.15s ease;
            position: relative;
        }

        .nav-item:hover {
            color: var(--primary);
            background-color: #f1f5f9;
        }

        .nav-item.active {
            color: var(--primary);
            background-color: var(--primary-light);
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 3.5px;
            background-color: var(--primary);
            border-radius: 0 4px 4px 0;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            background-color: #fcfcfd;
        }

        .user-quick-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 10px;
            border-radius: var(--radius-md);
            background-color: #f8fafc;
            border: 1px solid var(--border-color);
            margin-bottom: 10px;
        }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .avatar-circle.petugas {
            background-color: #6366f1;
        }

        .user-quick-info {
            flex: 1;
            overflow: hidden;
        }

        .user-quick-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-quick-role {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: capitalize;
        }

        /* ===== MAIN CONTENT WRAPPER ===== */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-width));
        }

        /* ===== TOPBAR ===== */
        .topbar {
            height: var(--topbar-height);
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 30;
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            max-width: 480px;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            padding: 6px;
            cursor: pointer;
            color: var(--text-main);
        }

        .search-container {
            width: 100%;
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 9px 14px 9px 38px;
            font-size: 13px;
            font-family: inherit;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background-color: #f8fafc;
            color: var(--text-main);
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            background-color: #ffffff;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-pill-text {
            text-align: right;
            display: flex;
            flex-direction: column;
        }

        .user-pill-name {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.2;
        }

        .user-pill-role {
            font-size: 11px;
            font-weight: 500;
        }

        .badge-role-peminjam {
            color: var(--primary);
        }

        .badge-role-petugas {
            color: #6366f1;
        }

        .btn-topbar-logout {
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            color: #dc2626;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: var(--radius-md);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }

        .btn-topbar-logout:hover {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }

        /* ===== CONTENT AREA ===== */
        .content-area {
            flex: 1;
            padding: 28px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 22px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-danger {
            background-color: var(--danger-bg);
            color: var(--danger);
            border: 1px solid var(--danger-border);
        }

        .alert-success {
            background-color: var(--success-bg);
            color: var(--success);
            border: 1px solid var(--success-border);
        }

        /* Greeting Banner */
        .greeting-banner {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px 28px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-xs);
            gap: 16px;
            flex-wrap: wrap;
        }

        .greeting-text h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.3px;
            margin-bottom: 4px;
        }

        .greeting-text p {
            font-size: 14px;
            color: var(--text-muted);
        }

        .greeting-date {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--text-muted);
            background-color: #f8fafc;
            padding: 8px 14px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            font-weight: 500;
        }

        /* Summary Cards Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-xs);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.blue { background-color: #eff6ff; color: #2563eb; }
        .stat-icon.amber { background-color: #fffbeb; color: #d97706; }
        .stat-icon.emerald { background-color: #f0fdf4; color: #16a34a; }
        .stat-icon.purple { background-color: #faf5ff; color: #9333ea; }
        .stat-icon.rose { background-color: #fff1f2; color: #e11d48; }

        .stat-info h3 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.1;
            margin-bottom: 4px;
        }

        .stat-info p {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text-muted);
        }

        /* Section Containers */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
        }

        .section-link:hover {
            text-decoration: underline;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-primary { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .badge-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

        /* Tables */
        .table-responsive {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            overflow-x: auto;
            box-shadow: var(--shadow-xs);
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13.5px;
        }

        .custom-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            padding: 13px 18px;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .custom-table td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            vertical-align: middle;
        }

        .custom-table tr:last-child td {
            border-bottom: none;
        }

        .custom-table tr:hover td {
            background-color: #fbfcfe;
        }

        /* Action Buttons */
        .btn-sm-action {
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-color);
            background-color: #ffffff;
            color: var(--text-main);
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-sm-action:hover {
            border-color: var(--primary);
            color: var(--primary);
            background-color: var(--primary-light);
        }

        /* Footer */
        .dashboard-footer {
            margin-top: auto;
            padding: 24px 28px;
            border-top: 1px solid var(--border-color);
            background: #ffffff;
            font-size: 13px;
            color: var(--text-muted);
            text-align: center;
        }

        /* ===== RESPONSIVE MEDIA QUERIES ===== */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
            .mobile-toggle {
                display: block;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .user-pill-text {
                display: none;
            }
            .content-area {
                padding: 18px 16px;
            }
            .topbar {
                padding: 0 16px;
            }
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(15, 23, 42, 0.4);
            z-index: 35;
        }

        .sidebar-backdrop.active {
            display: block;
        }
    </style>
</head>
<body>

    {{-- Backdrop for mobile drawer --}}
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    {{-- Sidebar --}}
    <aside class="sidebar" id="appSidebar">
        <a href="{{ Auth::user()->role === 'petugas' ? route('petugas.dashboard') : route('peminjam.dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="brand-text">
                <h2>Perpustakaan</h2>
                <span>Portal {{ Auth::user()->role === 'petugas' ? 'Petugas' : 'Peminjam' }}</span>
            </div>
        </a>

        <nav class="sidebar-menu">
            <div class="menu-category">Menu Utama</div>

            @if (Auth::user()->role === 'peminjam')
                {{-- Navigasi Peminjam --}}
                <a href="{{ route('peminjam.dashboard') }}" class="nav-item {{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('peminjam.katalog.index') }}" class="nav-item {{ request()->routeIs('peminjam.katalog.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Katalog Buku
                </a>
                <a href="{{ route('peminjam.peminjaman.index') }}" class="nav-item {{ request()->routeIs('peminjam.peminjaman.*') && request('tab') !== 'riwayat' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Peminjaman Saya
                </a>
                <a href="{{ route('peminjam.peminjaman.index', ['tab' => 'riwayat']) }}" class="nav-item {{ request()->routeIs('peminjam.peminjaman.*') && request('tab') === 'riwayat' ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Riwayat
                </a>
                <a href="#profil" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil
                </a>
            @else
                {{-- Navigasi Petugas --}}
                <a href="{{ route('petugas.dashboard') }}" class="nav-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('petugas.buku.index') }}" class="nav-item {{ request()->routeIs('petugas.buku.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Data Buku
                </a>
                <a href="{{ route('petugas.peminjam.index') }}" class="nav-item {{ request()->routeIs('petugas.peminjam.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Data Peminjam
                </a>
                <a href="{{ route('petugas.peminjaman.index') }}" class="nav-item {{ request()->routeIs('petugas.peminjaman.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Peminjaman
                </a>
                <a href="#pengembalian" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pengembalian
                </a>
                <a href="#laporan" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Laporan
                </a>
                <a href="#profil" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil
                </a>
            @endif

            <div class="menu-category" style="margin-top: 10px;">Sesi</div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer; color: #dc2626;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </nav>

        {{-- Profil Singkat di Bawah Sidebar --}}
        <div class="sidebar-footer">
            <div class="user-quick-profile">
                <div class="avatar-circle {{ Auth::user()->role === 'petugas' ? 'petugas' : '' }}">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-quick-info">
                    <div class="user-quick-name">{{ Auth::user()->name }}</div>
                    <div class="user-quick-role">{{ Auth::user()->role === 'petugas' ? 'Petugas Perpustakaan' : 'Peminjam' }}</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Wrapper --}}
    <div class="main-wrapper">
        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-toggle" onclick="toggleSidebar()" aria-label="Toggle Navigation">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="search-container">
                    <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" class="search-input" placeholder="{{ Auth::user()->role === 'petugas' ? 'Cari data transaksi, judul buku, atau nama peminjam...' : 'Cari judul buku, penulis, atau kategori...' }}">
                </div>
            </div>

            <div class="topbar-right">
                <div class="user-pill">
                    <div class="avatar-circle {{ Auth::user()->role === 'petugas' ? 'petugas' : '' }}" style="width: 34px; height: 34px; font-size: 13px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="user-pill-text">
                        <span class="user-pill-name">{{ Auth::user()->name }}</span>
                        <span class="user-pill-role {{ Auth::user()->role === 'petugas' ? 'badge-role-petugas' : 'badge-role-peminjam' }}">
                            {{ Auth::user()->role === 'petugas' ? 'Petugas Perpustakaan' : 'Peminjam' }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-topbar-logout" title="Keluar dari sistem">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- Main Page Content --}}
        <main class="content-area">
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="dashboard-footer">
            <p>&copy; 2026 Sistem Informasi Perpustakaan. Dikembangkan untuk kemudahan peminjaman &amp; sirkulasi buku.</p>
        </footer>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('active');
        }
    </script>
</body>
</html>
