@extends('layouts.app')
@section('title', 'Pacientes')

@section('topbar-actions')
    <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="btn-hide-mobile">Nuevo Paciente</span>
    </a>
@endsection

@section('content')

    @php
        $breadcrumbs = [['label' => 'Pacientes', 'url' => route('patients.index')]];

        $hasFilters = request()->hasAny(['search', 'city', 'gender', 'age_min', 'age_max', 'date_from', 'date_to']);
    @endphp

    <div class="page-header">
        <div>
            <h1>Pacientes</h1>
            <p>
                {{ $patients->total() }} resultado(s)
                @if ($hasFilters)
                    <span style="color:var(--primary);font-weight:600;">filtrados</span>
                @endif
                de {{ $totalCount }} total
            </p>
        </div>
    </div>

    <div class="card" style="margin-bottom:16px;">
        <div class="card-body" style="padding:14px 16px;">
            <form method="GET" action="{{ route('patients.index') }}" id="search-form">

                {{-- Búsqueda principal --}}
                <div style="display:flex;gap:8px;margin-bottom:12px;flex-wrap:wrap;">
                    <div class="search-wrap" style="flex:1;min-width:200px;max-width:500px;position:relative;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            style="position:absolute;left:11px;top:50%;
                                transform:translateY(-50%);width:15px;height:15px;
                                color:var(--text-muted);pointer-events:none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" id="search-input" class="form-control"
                            style="padding-left:35px;" placeholder="Buscar por nombre, apellido, cédula, teléfono…"
                            value="{{ request('search') }}" autocomplete="off" oninput="updateSuggestions(this.value)">

                        {{-- Sugerencias de búsqueda --}}
                        <div id="search-suggestions"
                            style="display:none;position:absolute;top:100%;left:0;right:0;
                                background:var(--surface);border:1px solid var(--border);
                                border-top:none;border-radius:0 0 var(--radius-sm) var(--radius-sm);
                                box-shadow:0 8px 24px rgba(0,0,0,0.1);z-index:100;
                                max-height:220px;overflow-y:auto;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Buscar
                    </button>

                    {{-- Toggle filtros avanzados --}}
                    <button type="button" id="toggle-filters" class="btn btn-outline" onclick="toggleFilters()"
                        style="{{ ($hasFilters && !request('search')) || request()->hasAny(['city', 'gender', 'age_min', 'age_max', 'date_from', 'date_to']) ? 'background:var(--primary-bg);border-color:var(--primary);color:var(--primary);' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                        </svg>
                        Filtros
                        @if (request()->hasAny(['city', 'gender', 'age_min', 'age_max', 'date_from', 'date_to']))
                            <span
                                style="background:var(--primary);color:#fff;border-radius:100px;
                                  padding:0 6px;font-size:11px;font-weight:700;">
                                {{ collect(['city', 'gender', 'age_min', 'age_max', 'date_from', 'date_to'])->filter(fn($k) => request($k))->count() }}
                            </span>
                        @endif
                    </button>

                    @if ($hasFilters)
                        <a href="{{ route('patients.index') }}" class="btn btn-ghost" title="Limpiar todos los filtros">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </a>
                    @endif
                </div>

                {{-- Filtros avanzados --}}
                <div id="advanced-filters"
                    style="display:{{ request()->hasAny(['city', 'gender', 'age_min', 'age_max', 'date_from', 'date_to']) ? 'block' : 'none' }};">
                    <div style="border-top:1px solid var(--border);padding-top:12px;">
                        <div
                            style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
                                 gap:10px;align-items:end;">

                            {{-- Ciudad --}}
                            <div>
                                <label
                                    style="font-size:11px;font-weight:700;
                                           color:var(--text-muted);text-transform:uppercase;
                                           letter-spacing:0.4px;display:block;
                                           margin-bottom:5px;">
                                    Ciudad
                                </label>
                                <select name="city" class="form-control" style="font-size:13px;">
                                    <option value="">Todas las ciudades</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city }}"
                                            {{ request('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Género --}}
                            <div>
                                <label
                                    style="font-size:11px;font-weight:700;
                                           color:var(--text-muted);text-transform:uppercase;
                                           letter-spacing:0.4px;display:block;
                                           margin-bottom:5px;">
                                    Género
                                </label>
                                <select name="gender" class="form-control" style="font-size:13px;">
                                    <option value="">Todos</option>
                                    <option value="masculino" {{ request('gender') == 'masculino' ? 'selected' : '' }}>
                                        Masculino
                                    </option>
                                    <option value="femenino" {{ request('gender') == 'femenino' ? 'selected' : '' }}>
                                        Femenino
                                    </option>
                                    <option value="otro" {{ request('gender') == 'otro' ? 'selected' : '' }}>
                                        Otro
                                    </option>
                                </select>
                            </div>

                            {{-- Edad mínima --}}
                            <div>
                                <label
                                    style="font-size:11px;font-weight:700;
                                           color:var(--text-muted);text-transform:uppercase;
                                           letter-spacing:0.4px;display:block;
                                           margin-bottom:5px;">
                                    Edad mínima
                                </label>
                                <input type="number" name="age_min" class="form-control" style="font-size:13px;"
                                    min="0" max="150" placeholder="Ej: 18" value="{{ request('age_min') }}">
                            </div>

                            {{-- Edad máxima --}}
                            <div>
                                <label
                                    style="font-size:11px;font-weight:700;
                                           color:var(--text-muted);text-transform:uppercase;
                                           letter-spacing:0.4px;display:block;
                                           margin-bottom:5px;">
                                    Edad máxima
                                </label>
                                <input type="number" name="age_max" class="form-control" style="font-size:13px;"
                                    min="0" max="150" placeholder="Ej: 60"
                                    value="{{ request('age_max') }}">
                            </div>

                            {{-- Registrado desde --}}
                            <div>
                                <label
                                    style="font-size:11px;font-weight:700;
                                           color:var(--text-muted);text-transform:uppercase;
                                           letter-spacing:0.4px;display:block;
                                           margin-bottom:5px;">
                                    Registrado desde
                                </label>
                                <input type="date" name="date_from" class="form-control" style="font-size:13px;"
                                    value="{{ request('date_from') }}">
                            </div>

                            {{-- Registrado hasta --}}
                            <div>
                                <label
                                    style="font-size:11px;font-weight:700;
                                           color:var(--text-muted);text-transform:uppercase;
                                           letter-spacing:0.4px;display:block;
                                           margin-bottom:5px;">
                                    Registrado hasta
                                </label>
                                <input type="date" name="date_to" class="form-control" style="font-size:13px;"
                                    value="{{ request('date_to') }}">
                            </div>

                            {{-- Ordenar por --}}
                            <div>
                                <label
                                    style="font-size:11px;font-weight:700;
                                           color:var(--text-muted);text-transform:uppercase;
                                           letter-spacing:0.4px;display:block;
                                           margin-bottom:5px;">
                                    Ordenar por
                                </label>
                                <div style="display:flex;gap:4px;">
                                    <select name="order_by" class="form-control" style="font-size:12px;flex:1;">
                                        <option value="created_at"
                                            {{ request('order_by', 'created_at') == 'created_at' ? 'selected' : '' }}>
                                            Fecha registro
                                        </option>
                                        <option value="first_name"
                                            {{ request('order_by') == 'first_name' ? 'selected' : '' }}>
                                            Nombre
                                        </option>
                                        <option value="city" {{ request('order_by') == 'city' ? 'selected' : '' }}>
                                            Ciudad
                                        </option>
                                    </select>
                                    <select name="order_dir" class="form-control" style="font-size:12px;width:80px;">
                                        <option value="desc"
                                            {{ request('order_dir', 'desc') == 'desc' ? 'selected' : '' }}>
                                            ↓ Desc
                                        </option>
                                        <option value="asc" {{ request('order_dir') == 'asc' ? 'selected' : '' }}>
                                            ↑ Asc
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div style="margin-top:10px;display:flex;gap:8px;">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Aplicar filtros
                            </button>
                            <a href="{{ route('patients.index') }}" class="btn btn-ghost btn-sm">
                                Limpiar todo
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Mantener ordenamiento en búsqueda simple --}}
                @if (request('order_by'))
                    <input type="hidden" name="order_by" value="{{ request('order_by') }}">
                    <input type="hidden" name="order_dir" value="{{ request('order_dir') }}">
                @endif

            </form>
        </div>
    </div>

    {{-- Tags de filtros activos --}}
    @if ($hasFilters)
        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px;
             align-items:center;">
            <span style="font-size:12px;color:var(--text-muted);">Filtros activos:</span>

            @if (request('search'))
                <span
                    style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;
                  background:var(--primary-light);color:var(--primary);
                  border-radius:100px;font-size:12px;font-weight:600;">
                    🔍 "{{ request('search') }}"
                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['search', 'page'])) }}"
                        style="color:var(--primary);text-decoration:none;font-size:14px;
                  line-height:1;">×</a>
                </span>
            @endif

            @if (request('city'))
                <span
                    style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;
                  background:#DBEAFE;color:#1D4ED8;
                  border-radius:100px;font-size:12px;font-weight:600;">
                    📍 {{ request('city') }}
                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['city', 'page'])) }}"
                        style="color:#1D4ED8;text-decoration:none;font-size:14px;line-height:1;">×</a>
                </span>
            @endif

            @if (request('gender'))
                <span
                    style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;
                  background:#D1FAE5;color:#059669;
                  border-radius:100px;font-size:12px;font-weight:600;">
                    👤 {{ ucfirst(request('gender')) }}
                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['gender', 'page'])) }}"
                        style="color:#059669;text-decoration:none;font-size:14px;line-height:1;">×</a>
                </span>
            @endif

            @if (request('age_min') || request('age_max'))
                <span
                    style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;
                  background:#FEF3C7;color:#D97706;
                  border-radius:100px;font-size:12px;font-weight:600;">
                    🎂 {{ request('age_min') ?: '0' }}–{{ request('age_max') ?: '∞' }} años
                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['age_min', 'age_max', 'page'])) }}"
                        style="color:#D97706;text-decoration:none;font-size:14px;line-height:1;">×</a>
                </span>
            @endif

            @if (request('date_from') || request('date_to'))
                <span
                    style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;
                  background:#EDE9FE;color:#7C3AED;
                  border-radius:100px;font-size:12px;font-weight:600;">
                    📅
                    {{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : '' }}
                    –
                    {{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : '' }}
                    <a href="{{ url()->current() . '?' . http_build_query(request()->except(['date_from', 'date_to', 'page'])) }}"
                        style="color:#7C3AED;text-decoration:none;font-size:14px;line-height:1;">×</a>
                </span>
            @endif
        </div>
    @endif

    {{-- Resultados --}}
    <div class="card">

        @if ($patients->isEmpty())
            <div style="padding:48px 20px;text-align:center;color:var(--text-muted);">
                <div
                    style="width:56px;height:56px;background:var(--bg);border-radius:50%;
                     display:flex;align-items:center;justify-content:center;
                     margin:0 auto 14px;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                @if ($hasFilters)
                    <p style="font-size:15px;font-weight:600;margin-bottom:6px;">
                        Sin resultados
                    </p>
                    <p style="font-size:13px;margin-bottom:16px;">
                        No hay pacientes que coincidan con los filtros aplicados.
                    </p>
                    <a href="{{ route('patients.index') }}" class="btn btn-primary">
                        Limpiar filtros
                    </a>
                @else
                    <p style="font-size:15px;font-weight:600;margin-bottom:6px;">
                        Sin pacientes registrados
                    </p>
                    <a href="{{ route('patients.create') }}" class="btn btn-primary">
                        Registrar primer paciente
                    </a>
                @endif
            </div>
        @else
            {{-- Desktop: tabla --}}
            <div class="table-wrap" id="desktop-list">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>
                                @php
                                    $nextDir =
                                        request('order_by') == 'first_name' && request('order_dir', 'desc') == 'asc'
                                            ? 'desc'
                                            : 'asc';
                                    $sortUrl =
                                        url()->current() .
                                        '?' .
                                        http_build_query(
                                            array_merge(request()->all(), [
                                                'order_by' => 'first_name',
                                                'order_dir' => $nextDir,
                                                'page' => 1,
                                            ]),
                                        );
                                @endphp
                                <a href="{{ $sortUrl }}"
                                    style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:4px;">
                                    Paciente
                                    @if (request('order_by') == 'first_name')
                                        <span>{{ request('order_dir') == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="hide-xs">Cédula</th>
                            <th class="hide-xs">Teléfono</th>
                            <th>Edad</th>
                            <th class="hide-xs">
                                @php
                                    $nextDir =
                                        request('order_by') == 'city' && request('order_dir', 'desc') == 'asc'
                                            ? 'desc'
                                            : 'asc';
                                    $sortUrl =
                                        url()->current() .
                                        '?' .
                                        http_build_query(
                                            array_merge(request()->all(), [
                                                'order_by' => 'city',
                                                'order_dir' => $nextDir,
                                                'page' => 1,
                                            ]),
                                        );
                                @endphp
                                <a href="{{ $sortUrl }}"
                                    style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:4px;">
                                    Ciudad
                                    @if (request('order_by') == 'city')
                                        <span>{{ request('order_dir') == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th class="hide-xs">
                                @php
                                    $nextDir =
                                        request('order_by', 'created_at') == 'created_at' &&
                                        request('order_dir', 'desc') == 'asc'
                                            ? 'desc'
                                            : 'asc';
                                    $sortUrl =
                                        url()->current() .
                                        '?' .
                                        http_build_query(
                                            array_merge(request()->all(), [
                                                'order_by' => 'created_at',
                                                'order_dir' => $nextDir,
                                                'page' => 1,
                                            ]),
                                        );
                                @endphp
                                <a href="{{ $sortUrl }}"
                                    style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:4px;">
                                    Registrado
                                    @if (request('order_by', 'created_at') == 'created_at')
                                        <span>{{ request('order_dir', 'desc') == 'asc' ? '↑' : '↓' }}</span>
                                    @endif
                                </a>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $patient)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div
                                            style="width:36px;height:36px;border-radius:50%;
                                         background:var(--primary-light);flex-shrink:0;
                                         display:flex;align-items:center;
                                         justify-content:center;font-size:12px;
                                         font-weight:700;color:var(--primary);">
                                            {{ $patient->initials }}
                                        </div>
                                        <div style="min-width:0;">
                                            <div
                                                style="font-weight:600;white-space:nowrap;
                                             overflow:hidden;text-overflow:ellipsis;
                                             max-width:200px;">
                                                @if (request('search'))
                                                    {!! highlightSearch($patient->full_name, request('search')) !!}
                                                @else
                                                    {{ $patient->full_name }}
                                                @endif
                                            </div>
                                            @if ($patient->email)
                                                <div
                                                    style="font-size:12px;color:var(--text-muted);
                                             white-space:nowrap;overflow:hidden;
                                             text-overflow:ellipsis;max-width:200px;">
                                                    {{ $patient->email }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="hide-xs" style="color:var(--text-muted);font-size:13px;">
                                    @if (request('search') && $patient->cedula)
                                        {!! highlightSearch($patient->cedula, request('search')) !!}
                                    @else
                                        {{ $patient->cedula ?? '—' }}
                                    @endif
                                </td>
                                <td class="hide-xs" style="font-size:13px;">
                                    {{ $patient->phone ?? '—' }}
                                </td>
                                <td style="font-size:13px;">
                                    {{ $patient->age_calculated ? $patient->age_calculated . ' a.' : '—' }}
                                </td>
                                <td class="hide-xs" style="color:var(--text-muted);font-size:13px;">
                                    {{ $patient->city ?? '—' }}
                                </td>
                                <td class="hide-xs" style="font-size:12px;color:var(--text-muted);white-space:nowrap;">
                                    {{ $patient->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <div style="display:flex;gap:4px;">
                                        <a href="{{ route('patients.show', $patient) }}"
                                            class="btn btn-ghost btn-sm">Ver</a>
                                        <a href="{{ route('patients.edit', $patient) }}"
                                            class="btn btn-ghost btn-sm hide-xs">Editar</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile: tarjetas --}}
            <div id="mobile-list" style="display:none;">
                @foreach ($patients as $patient)
                    <a href="{{ route('patients.show', $patient) }}"
                        style="display:flex;align-items:center;gap:12px;padding:14px 16px;
                  border-bottom:1px solid #F3F4F6;text-decoration:none;
                  color:var(--text);transition:background 0.1s;"
                        onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                        <div
                            style="width:42px;height:42px;border-radius:50%;
                         background:var(--primary-light);
                         display:flex;align-items:center;justify-content:center;
                         font-size:14px;font-weight:700;color:var(--primary);
                         flex-shrink:0;">
                            {{ $patient->initials }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div
                                style="font-weight:600;font-size:14px;
                             white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $patient->full_name }}
                            </div>
                            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                                {{ $patient->cedula ?? ($patient->phone ?? ($patient->city ?? 'Sin datos adicionales')) }}
                            </div>
                        </div>
                        <div
                            style="display:flex;flex-direction:column;align-items:flex-end;
                         gap:3px;flex-shrink:0;">
                            @if ($patient->age_calculated)
                                <span style="font-size:12px;font-weight:600;">
                                    {{ $patient->age_calculated }} años
                                </span>
                            @endif
                            <span style="font-size:11px;color:var(--text-muted);">
                                {{ $patient->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Paginación --}}
            @if ($patients->hasPages())
                <div style="padding:14px 16px;border-top:1px solid var(--border);">
                    {{ $patients->links() }}
                </div>
            @endif

        @endif
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 640px) {
            #desktop-list {
                display: none !important;
            }

            #mobile-list {
                display: block !important;
            }
        }

        mark.search-highlight {
            background: #FEF3C7;
            color: #92400E;
            border-radius: 2px;
            padding: 0 1px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // ── Toggle filtros avanzados ──────────────────────────────────
        function toggleFilters() {
            const box = document.getElementById('advanced-filters');
            const btn = document.getElementById('toggle-filters');
            const open = box.style.display === 'none';
            box.style.display = open ? 'block' : 'none';
        }

        // ── Autocompletado / sugerencias ──────────────────────────────
        let searchTimeout = null;

        function updateSuggestions(value) {
            clearTimeout(searchTimeout);
            const box = document.getElementById('search-suggestions');

            if (value.length < 2) {
                box.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('patients.index') }}?search=${encodeURIComponent(value)}&per_page=5&ajax=1`)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.length) {
                            box.style.display = 'none';
                            return;
                        }

                        box.innerHTML = data.map(p => `
                    <a href="/patients/${p.id}"
                       style="display:flex;align-items:center;gap:10px;padding:10px 14px;
                              text-decoration:none;color:var(--text);
                              border-bottom:1px solid var(--border);
                              transition:background 0.1s;"
                       onmouseover="this.style.background='var(--bg)'"
                       onmouseout="this.style.background='transparent'">
                        <div style="width:30px;height:30px;border-radius:50%;
                                     background:var(--primary-light);flex-shrink:0;
                                     display:flex;align-items:center;justify-content:center;
                                     font-size:11px;font-weight:700;color:var(--primary);">
                            ${p.initials}
                        </div>
                        <div style="min-width:0;flex:1;">
                            <div style="font-size:13px;font-weight:600;">${p.full_name}</div>
                            <div style="font-size:11px;color:var(--text-muted);">
                                ${p.cedula || p.phone || p.city || ''}
                            </div>
                        </div>
                    </a>
                `).join('');

                        box.style.display = 'block';
                    })
                    .catch(() => {
                        box.style.display = 'none';
                    });
            }, 280);
        }

        // Cerrar sugerencias al hacer clic fuera
        document.addEventListener('click', e => {
            if (!e.target.closest('#search-input') && !e.target.closest('#search-suggestions')) {
                document.getElementById('search-suggestions').style.display = 'none';
            }
        });

        // Enter en búsqueda envía el form
        document.getElementById('search-input').addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                document.getElementById('search-suggestions').style.display = 'none';
                document.getElementById('search-form').submit();
            }
        });
    </script>
@endpush
