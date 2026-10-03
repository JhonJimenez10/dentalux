<header class="topbar">
    <div class="topbar-left">

        {{-- Toggle mobile --}}
        <button class="menu-toggle" id="menu-toggle" onclick="toggleMobile()" aria-label="Menú">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        {{-- Breadcrumb --}}
        @isset($breadcrumbs)
            <nav class="breadcrumb" aria-label="Ruta">
                @foreach ($breadcrumbs as $crumb)
                    @if (!$loop->last)
                        <a href="{{ $crumb['url'] }}"
                            style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
                              max-width:120px;display:inline-block;vertical-align:bottom;">
                            {{ $crumb['label'] }}
                        </a>
                        <span style="color:var(--border);user-select:none;flex-shrink:0;">›</span>
                    @else
                        <span
                            style="color:var(--text);font-weight:500;white-space:nowrap;
                                 overflow:hidden;text-overflow:ellipsis;max-width:140px;
                                 display:inline-block;vertical-align:bottom;">
                            {{ $crumb['label'] }}
                        </span>
                    @endif
                @endforeach
            </nav>
        @else
            <span class="topbar-title">@yield('title', 'Panel')</span>
        @endisset

    </div>

    <div class="topbar-actions">
        @yield('topbar-actions')
    </div>
</header>
