<aside class="sidebar" id="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <svg class="logo-svg" viewBox="0 0 38 38" fill="none">
            <rect width="38" height="38" rx="10" fill="#C8395A" />
            <path
                d="M19 8C14.5 8 11 11.5 11 15.5C11 17.5 11.8 19.3 12.5 21L14 27C14.4 28.6 15.8 29.5 17.2 29.5H20.8C22.2 29.5 23.6 28.6 24 27L25.5 21C26.2 19.3 27 17.5 27 15.5C27 11.5 23.5 8 19 8Z"
                fill="white" opacity="0.9" />
            <path
                d="M19 8C16.5 8 14.3 9.2 13 11C14.2 10.4 15.5 10 17 10C21 10 24.2 13 24.8 17C26.2 16.2 27 14.9 27 13.5C27 10.5 23.5 8 19 8Z"
                fill="white" opacity="0.4" />
        </svg>
        <div class="logo-texts">
            <div class="logo-text-name">dentalux</div>
            <div class="logo-text-sub">Odontología Familiar</div>
        </div>
        <button class="sidebar-collapse-btn" id="collapse-btn" onclick="toggleCollapse()" title="Colapsar menú"
            aria-label="Colapsar menú">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav" id="sidebar-nav">

        {{-- Inicio --}}
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            data-tooltip="Inicio">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="nav-text">Inicio</span>
        </a>

        {{-- ── GESTIÓN ── --}}
        <div class="nav-label"><span class="nav-text">Gestión</span></div>

        <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
            data-tooltip="Pacientes">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="nav-text">Pacientes</span>
        </a>

        {{-- ── FINANZAS (Dentista y Admin) ── --}}
        @if (auth()->user()->isDentist() || auth()->user()->isAdmin())
            <div class="nav-label"><span class="nav-text">Finanzas</span></div>

            <a href="{{ route('finance.index') }}"
                class="nav-link {{ request()->routeIs('finance.*') ? 'active' : '' }}" data-tooltip="Finanzas">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="nav-text">Finanzas</span>
            </a>
        @endif

        {{-- ── REPORTES (Dentista y Admin) ── --}}
        @if (auth()->user()->isDentist() || auth()->user()->isAdmin())
            <div class="nav-label"><span class="nav-text">Reportes</span></div>

            {{-- Ingresos — activo SOLO en reports.income --}}
            <a href="{{ route('reports.income') }}"
                class="nav-link {{ request()->routeIs('reports.income') ? 'active' : '' }}" data-tooltip="Ingresos">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="nav-text">Ingresos</span>
            </a>
            <a href="{{ route('reports.pending') }}"
                class="nav-link {{ request()->routeIs('reports.pending*') ? 'active' : '' }}" data-tooltip="Pendientes">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="nav-text">Pendientes</span>
            </a>

            {{-- Cierre de Caja — activo SOLO en cash-closing.* --}}
            <a href="{{ route('cash-closing.index') }}"
                class="nav-link {{ request()->routeIs('cash-closing.*') ? 'active' : '' }}"
                data-tooltip="Cierre de Caja">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="nav-text">Cierre de Caja</span>
            </a>

            {{-- Reporte Diario — activo SOLO en reports.daily* --}}
            <a href="{{ route('reports.daily') }}"
                class="nav-link {{ request()->routeIs('reports.daily') || request()->routeIs('reports.daily.*') ? 'active' : '' }}"
                data-tooltip="Reporte Diario">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="nav-text">Reporte Diario</span>
            </a>
        @endif

        {{-- ── ADMIN (solo Admin) ── --}}
        @if (auth()->user()->isAdmin())
            <div class="nav-label"><span class="nav-text">Admin</span></div>

            <a href="{{ route('users.index') }}"
                class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" data-tooltip="Usuarios">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="nav-text">Usuarios</span>
            </a>

            <a href="{{ route('bank-accounts.index') }}"
                class="nav-link {{ request()->routeIs('bank-accounts.*') ? 'active' : '' }}"
                data-tooltip="Cuentas Bancarias">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="nav-text">Cuentas Bancarias</span>
            </a>
        @endif

    </nav>

    {{-- Footer usuario --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout" title="Cerrar sesión">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

</aside>
