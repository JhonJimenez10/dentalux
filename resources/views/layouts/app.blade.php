<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dentalux') — Odontología Familiar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=DM+Sans:ital,wght@0,400;0,500;1,400&display=swap"
        rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════════
   VARIABLES
════════════════════════════════════════════════ */
        :root {
            --primary: #C8395A;
            --primary-dark: #A62A48;
            --primary-light: #F4D0D8;
            --primary-bg: #FDF4F6;
            --text: #1A1A2E;
            --text-muted: #6B7280;
            --border: #E5E7EB;
            --surface: #FFFFFF;
            --bg: #F8F7F5;
            --success: #059669;
            --warning: #D97706;
            --danger: #DC2626;
            --info: #0284C7;
            --radius: 12px;
            --radius-sm: 8px;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.10);

            /* Sidebar */
            --sb-w: 260px;
            --sb-w-collapsed: 68px;
            --topbar-h: 60px;
            --sb-transition: 0.25s cubic-bezier(.4, 0, .2, 1);
        }

        /* ═══════════════════════════════════════════════
   RESET
════════════════════════════════════════════════ */
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            line-height: 1.3;
        }

        a {
            color: inherit;
        }

        img {
            max-width: 100%;
        }

        /* ═══════════════════════════════════════════════
   SIDEBAR
════════════════════════════════════════════════ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sb-w);
            height: 100vh;
            background: var(--text);
            display: flex;
            flex-direction: column;
            z-index: 200;
            transition: width var(--sb-transition), transform var(--sb-transition);
            overflow: hidden;
        }

        /* Estado colapsado (desktop) */
        .sidebar.collapsed {
            width: var(--sb-w-collapsed);
        }

        /* ── Logo ── */
        .sidebar-logo {
            padding: 18px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            min-height: 70px;
            position: relative;
        }

        .logo-svg {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
        }

        .logo-texts {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            transition: opacity var(--sb-transition), width var(--sb-transition);
        }

        .logo-text-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 17px;
            color: #fff;
            letter-spacing: -0.3px;
            line-height: 1.1;
            white-space: nowrap;
        }

        .logo-text-sub {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.38);
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }

        /* Botón colapsar */
        .sidebar-collapse-btn {
            width: 26px;
            height: 26px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.45);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .sidebar-collapse-btn:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.25);
        }

        .sidebar-collapse-btn svg {
            transition: transform var(--sb-transition);
        }

        .sidebar.collapsed .sidebar-collapse-btn svg {
            transform: rotate(180deg);
        }

        /* Ocultar textos al colapsar */
        .sidebar.collapsed .logo-texts,
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .nav-label,
        .sidebar.collapsed .user-info,
        .sidebar.collapsed .logout-form {
            display: none;
        }

        /* ── Nav ── */
        .sidebar-nav {
            flex: 1;
            padding: 10px 8px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: none;
        }

        .sidebar-nav::-webkit-scrollbar {
            display: none;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.25);
            padding: 14px 10px 4px;
            white-space: nowrap;
            transition: opacity var(--sb-transition);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-sm);
            color: rgba(255, 255, 255, 0.58);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.15s;
            margin-bottom: 2px;
            white-space: nowrap;
            position: relative;
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            transition: transform 0.15s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .nav-link.active {
            background: var(--primary);
            color: #fff;
        }

        .nav-link.active svg {
            transform: scale(1.05);
        }

        /* Tooltip en modo colapsado */
        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 10px;
        }

        .sidebar.collapsed .nav-link::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(var(--sb-w-collapsed) + 8px);
            top: 50%;
            transform: translateY(-50%);
            background: var(--text);
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.15s;
            z-index: 300;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar.collapsed .nav-link::before {
            content: '';
            position: absolute;
            left: calc(var(--sb-w-collapsed) + 2px);
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: var(--text);
            opacity: 0;
            transition: opacity 0.15s;
            z-index: 301;
        }

        .sidebar.collapsed .nav-link:hover::after,
        .sidebar.collapsed .nav-link:hover::before {
            opacity: 1;
        }

        /* ── Footer ── */
        .sidebar-footer {
            padding: 10px 8px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            flex-shrink: 0;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 8px;
            border-radius: var(--radius-sm);
            transition: background 0.15s;
        }

        .sidebar.collapsed .sidebar-user {
            justify-content: center;
            padding: 8px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 130px;
        }

        .user-role {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.36);
        }

        .btn-logout {
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.32);
            display: flex;
            align-items: center;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.15s;
            flex-shrink: 0;
        }

        .btn-logout:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }

        /* Overlay mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 199;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.open {
            display: block;
        }

        /* ═══════════════════════════════════════════════
   MAIN
════════════════════════════════════════════════ */
        .main {
            margin-left: var(--sb-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left var(--sb-transition);
        }

        .main.collapsed {
            margin-left: var(--sb-w-collapsed);
        }

        /* ═══════════════════════════════════════════════
   TOPBAR
════════════════════════════════════════════════ */
        .topbar {
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            gap: 12px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .topbar-title {
            font-size: 16px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: var(--text-muted);
            min-width: 0;
            overflow: hidden;
            flex-shrink: 1;
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        /* Toggle mobile */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            color: var(--text);
            border-radius: var(--radius-sm);
            transition: background 0.15s;
            flex-shrink: 0;
        }

        .menu-toggle:hover {
            background: var(--bg);
        }

        /* ═══════════════════════════════════════════════
   CONTENT
════════════════════════════════════════════════ */
        .content {
            padding: 24px;
            flex: 1;
        }

        /* ═══════════════════════════════════════════════
   CARDS
════════════════════════════════════════════════ */
        .card {
            background: var(--surface);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .card-title {
            font-size: 15px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* ═══════════════════════════════════════════════
   BUTTONS
════════════════════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            border: none;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
            line-height: 1;
        }

        .btn svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-outline:hover {
            background: var(--bg);
            border-color: #ccc;
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
        }

        .btn-ghost:hover {
            background: var(--bg);
            color: var(--text);
        }

        .btn-danger {
            background: #FEE2E2;
            color: var(--danger);
        }

        .btn-danger:hover {
            background: #FECACA;
        }

        .btn-success {
            background: #D1FAE5;
            color: var(--success);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-sm svg {
            width: 13px;
            height: 13px;
        }

        .btn-lg {
            padding: 12px 22px;
            font-size: 15px;
        }

        /* ═══════════════════════════════════════════════
   FORMS
════════════════════════════════════════════════ */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .required {
            color: var(--primary);
        }

        .form-control {
            width: 100%;
            padding: 10px 13px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: var(--surface);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            -webkit-appearance: none;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .form-control::placeholder {
            color: #B0B7C3;
        }

        select.form-control {
            cursor: pointer;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .form-hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .form-error {
            font-size: 12px;
            color: var(--danger);
            margin-top: 4px;
        }

        .form-grid {
            display: grid;
            gap: 16px;
        }

        .cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        /* ═══════════════════════════════════════════════
   TABLES
════════════════════════════════════════════════ */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .dtable {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 480px;
        }

        .dtable thead th {
            padding: 10px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: var(--text-muted);
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .dtable tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid #F3F4F6;
            color: var(--text);
            vertical-align: middle;
        }

        .dtable tbody tr:last-child td {
            border-bottom: none;
        }

        .dtable tbody tr:hover td {
            background: #FAFAFA;
        }

        /* ═══════════════════════════════════════════════
   BADGES
════════════════════════════════════════════════ */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-primary {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .badge-success {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-warning {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-danger {
            background: #FEE2E2;
            color: #991B1B;
        }

        .badge-gray {
            background: #F3F4F6;
            color: #374151;
        }

        .badge-info {
            background: #DBEAFE;
            color: #1E40AF;
        }

        /* ═══════════════════════════════════════════════
   ALERTS
════════════════════════════════════════════════ */
        .alert {
            padding: 13px 16px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }

        .alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: #FEE2E2;
            color: #991B1B;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: #FEF3C7;
            color: #92400E;
            border-left: 4px solid var(--warning);
        }

        /* ═══════════════════════════════════════════════
   PAGE HEADER
════════════════════════════════════════════════ */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header h1 {
            font-size: 20px;
        }

        .page-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ═══════════════════════════════════════════════
   SEARCH
════════════════════════════════════════════════ */
        .search-wrap {
            position: relative;
        }

        .search-wrap svg {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 15px;
            color: var(--text-muted);
            pointer-events: none;
        }

        .search-wrap .form-control {
            padding-left: 35px;
        }

        /* ═══════════════════════════════════════════════
   UTILITIES
════════════════════════════════════════════════ */
        .text-muted {
            color: var(--text-muted);
        }

        .text-primary {
            color: var(--primary);
        }

        .text-success {
            color: var(--success);
        }

        .text-danger {
            color: var(--danger);
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-semibold {
            font-weight: 600;
        }

        .w-full {
            width: 100%;
        }

        .flex {
            display: flex;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .gap-2 {
            gap: 8px;
        }

        .gap-3 {
            gap: 12px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mt-6 {
            margin-top: 24px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .hidden {
            display: none;
        }

        /* ═══════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════════ */

        /* Tablet */
        @media (max-width:1024px) {
            .cols-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Mobile/Tablet */
        @media (max-width:768px) {

            /* Ocultar sidebar y botón colapsar */
            .sidebar {
                transform: translateX(-100%);
                width: var(--sb-w) !important;
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: var(--shadow-md);
            }

            .sidebar-collapse-btn {
                display: none;
            }

            /* Main sin margen */
            .main,
            .main.collapsed {
                margin-left: 0;
            }

            /* Toggle visible */
            .menu-toggle {
                display: flex;
            }

            .content {
                padding: 16px;
            }

            .topbar {
                padding: 0 16px;
            }

            .cols-2,
            .cols-3 {
                grid-template-columns: 1fr;
            }

            .card-header {
                padding: 13px 16px;
            }

            .card-body {
                padding: 16px;
            }

            .page-header h1 {
                font-size: 18px;
            }

            .btn-hide-mobile {
                display: none;
            }
        }

        @media (max-width:480px) {
            .content {
                padding: 12px;
            }

            .topbar {
                padding: 0 12px;
            }

            .btn {
                padding: 8px 12px;
                font-size: 13px;
            }

            .btn-sm {
                padding: 5px 10px;
                font-size: 12px;
            }

            .stat-grid-mobile {
                grid-template-columns: 1fr 1fr !important;
            }

            .dtable .hide-xs {
                display: none;
            }
        }

        /* Print */
        @media print {

            .sidebar,
            .topbar,
            .no-print {
                display: none !important;
            }

            .main {
                margin-left: 0;
            }

            .content {
                padding: 0;
            }
        }
    </style>

    @stack('styles')
    @stack('head_scripts')
</head>

<body>

    {{-- Overlay mobile --}}
    <div class="sidebar-overlay" id="overlay" onclick="closeMobileSidebar()"></div>

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Main --}}
    <div class="main" id="main">

        {{-- Topbar --}}
        @include('layouts.partials.topbar')

        {{-- Content --}}
        <div class="content">

            @if (session('success'))
                <div class="alert alert-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <strong>Corrige los errores:</strong>
                        <ul style="margin-top:6px;padding-left:16px;">
                            @foreach ($errors->all() as $error)
                                <li style="margin-top:3px;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')

        </div>
    </div>

    <script>
        /* ══════════════════════════════════════════════
       SIDEBAR — Colapsar (desktop) y abrir (mobile)
    ══════════════════════════════════════════════ */
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main');
        const colBtn = document.getElementById('collapse-btn');
        const STORAGE_KEY = 'dentalux_sidebar_collapsed';

        // ── Colapsar/expandir en desktop ──
        function toggleCollapse() {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            main.classList.toggle('collapsed', isCollapsed);
            localStorage.setItem(STORAGE_KEY, isCollapsed ? '1' : '0');
        }

        // ── Abrir/cerrar en mobile ──
        function toggleMobile() {
            const open = sidebar.classList.toggle('open');
            document.getElementById('overlay').classList.toggle('open', open);
            document.body.style.overflow = open ? 'hidden' : '';
        }

        function closeMobileSidebar() {
            sidebar.classList.remove('open');
            document.getElementById('overlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        // ── Restaurar estado guardado al cargar ──
        (function() {
            if (window.innerWidth > 768 && localStorage.getItem(STORAGE_KEY) === '1') {
                sidebar.classList.add('collapsed');
                main.classList.add('collapsed');
            }
        })();

        // ── Cerrar sidebar mobile al hacer clic en un link ──
        document.querySelectorAll('.nav-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) closeMobileSidebar();
            });
        });

        // ── Cerrar al redimensionar a desktop ──
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) closeMobileSidebar();
        });
    </script>

    @stack('scripts')
</body>

</html>
