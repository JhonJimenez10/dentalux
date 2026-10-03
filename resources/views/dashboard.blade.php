@extends('layouts.app')
@section('title', 'Inicio')

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
        use App\Models\Patient;
        $totalPatients = Patient::count();
        $monthPatients = Patient::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $todayPatients = Patient::whereDate('created_at', today())->count();
        $recientes = Patient::latest()->take(8)->get();
    @endphp

    {{-- Bienvenida --}}
    <div style="margin-bottom:24px;">
        <h1 style="font-size:22px; margin-bottom:4px;">
            Bienvenido, {{ explode(' ', auth()->user()->name)[0] }} 👋
        </h1>
        <p style="font-size:14px; color:var(--text-muted);">
            {{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </p>
    </div>

    {{-- Stats --}}
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px;" class="stat-grid-mobile">

        <div class="card" style="padding:20px; display:flex; align-items:center; gap:14px;">
            <div
                style="width:48px;height:48px;border-radius:14px;background:var(--primary-light);
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="22" height="22" fill="none" stroke="var(--primary)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:2px;">Total Pacientes</div>
                <div
                    style="font-size:30px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;
                        line-height:1;color:var(--text);">
                    {{ number_format($totalPatients) }}
                </div>
            </div>
        </div>

        <div class="card" style="padding:20px; display:flex; align-items:center; gap:14px;">
            <div
                style="width:48px;height:48px;border-radius:14px;background:#D1FAE5;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="22" height="22" fill="none" stroke="#059669" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:2px;">Nuevos este mes</div>
                <div
                    style="font-size:30px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;
                        line-height:1;color:var(--text);">
                    {{ $monthPatients }}
                </div>
            </div>
        </div>

        <div class="card" style="padding:20px; display:flex; align-items:center; gap:14px;">
            <div
                style="width:48px;height:48px;border-radius:14px;background:#DBEAFE;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="22" height="22" fill="none" stroke="#0284C7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:2px;">Registros hoy</div>
                <div
                    style="font-size:30px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;
                        line-height:1;color:var(--text);">
                    {{ $todayPatients }}
                </div>
            </div>
        </div>

    </div>

    {{-- Accesos rápidos --}}
    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin-bottom:24px;">

        <a href="{{ route('patients.create') }}"
            style="display:flex;align-items:center;gap:14px;padding:18px 20px;background:var(--primary);
              border-radius:var(--radius);text-decoration:none;transition:all 0.2s;color:#fff;"
            onmouseover="this.style.background='var(--primary-dark)';this.style.transform='translateY(-1px)'"
            onmouseout="this.style.background='var(--primary)';this.style.transform='translateY(0)'">
            <div
                style="width:42px;height:42px;background:rgba(255,255,255,0.18);border-radius:10px;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div>
                <div style="font-weight:600;font-size:15px;">Nuevo Paciente</div>
                <div style="font-size:12px;opacity:0.72;margin-top:1px;">Registrar historial</div>
            </div>
        </a>

        <a href="{{ route('patients.index') }}"
            style="display:flex;align-items:center;gap:14px;padding:18px 20px;background:var(--surface);
              border:1px solid var(--border);border-radius:var(--radius);text-decoration:none;
              transition:all 0.2s;color:var(--text);"
            onmouseover="this.style.borderColor='var(--primary)';this.style.transform='translateY(-1px)'"
            onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
            <div
                style="width:42px;height:42px;background:var(--primary-bg);border-radius:10px;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="var(--primary)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <div style="font-weight:600;font-size:15px;">Ver Pacientes</div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:1px;">
                    {{ $totalPatients }} registrados
                </div>
            </div>
        </a>

    </div>

    {{-- Pacientes recientes --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pacientes Recientes</h3>
            <a href="{{ route('patients.index') }}" class="btn btn-ghost btn-sm">
                Ver todos
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if ($recientes->isEmpty())
            <div style="padding:48px 20px; text-align:center; color:var(--text-muted);">
                <div
                    style="width:56px;height:56px;background:var(--bg);border-radius:50%;
                    display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <p style="font-size:15px;font-weight:600;margin-bottom:6px;">Sin pacientes aún</p>
                <p style="font-size:13px;margin-bottom:16px;">Registra tu primer paciente para empezar.</p>
                <a href="{{ route('patients.create') }}" class="btn btn-primary">Registrar paciente</a>
            </div>
        @else
            {{-- Mobile: tarjetas --}}
            <div style="display:none;" id="patients-cards">
                @foreach ($recientes as $p)
                    <a href="{{ route('patients.show', $p) }}"
                        style="display:flex;align-items:center;gap:12px;padding:14px 16px;
                  border-bottom:1px solid #F3F4F6;text-decoration:none;
                  transition:background 0.1s;"
                        onmouseover="this.style.background='#FAFAFA'" onmouseout="this.style.background='transparent'">
                        <div
                            style="width:38px;height:38px;border-radius:50%;background:var(--primary-light);
                        display:flex;align-items:center;justify-content:center;
                        font-size:13px;font-weight:700;color:var(--primary);flex-shrink:0;">
                            {{ $p->initials }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div
                                style="font-weight:600;font-size:14px;color:var(--text);
                            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $p->full_name }}
                            </div>
                            <div style="font-size:12px;color:var(--text-muted);">
                                {{ $p->phone ?? ($p->email ?? 'Sin contacto') }}
                            </div>
                        </div>
                        <div style="font-size:11px;color:var(--text-muted);flex-shrink:0;">
                            {{ $p->created_at->diffForHumans() }}
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Desktop: tabla --}}
            <div class="table-wrap" id="patients-table">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th class="hide-xs">Cédula</th>
                            <th class="hide-xs">Teléfono</th>
                            <th>Ciudad</th>
                            <th>Registrado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recientes as $p)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div
                                            style="width:36px;height:36px;border-radius:50%;
                                        background:var(--primary-light);
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:12px;font-weight:700;color:var(--primary);
                                        flex-shrink:0;">
                                            {{ $p->initials }}
                                        </div>
                                        <div>
                                            <div style="font-weight:600;">{{ $p->full_name }}</div>
                                            @if ($p->email)
                                                <div style="font-size:12px;color:var(--text-muted);">{{ $p->email }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="hide-xs" style="color:var(--text-muted);">{{ $p->cedula ?? '—' }}</td>
                                <td class="hide-xs">{{ $p->phone ?? '—' }}</td>
                                <td>{{ $p->city ?? '—' }}</td>
                                <td style="font-size:13px;color:var(--text-muted);white-space:nowrap;">
                                    {{ $p->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('patients.show', $p) }}" class="btn btn-ghost btn-sm">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width:640px) {
            #patients-table {
                display: none;
            }

            #patients-cards {
                display: block !important;
            }
        }
    </style>
@endpush
