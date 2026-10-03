@extends('layouts.app')
@section('title', 'Cierre de Caja')

@section('topbar-actions')
    <a href="{{ route('cash-closing.create') }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="btn-hide-mobile">Nuevo Cierre</span>
    </a>
@endsection

@section('content')

    @php
        $breadcrumbs = [['label' => 'Cierre de Caja', 'url' => route('cash-closing.index')]];
        $monthNames = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Cierre de Caja</h1>
            <p>{{ $monthNames[$month] }} {{ $year }}</p>
        </div>
    </div>

    {{-- Alerta si hoy no tiene cierre --}}
    @if (!$todayClosing)
        <div
            style="background:#FEF3C7;border-left:4px solid #D97706;border-radius:var(--radius-sm);
             padding:14px 16px;margin-bottom:20px;display:flex;align-items:center;
             justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <svg width="20" height="20" fill="none" stroke="#D97706" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span style="font-size:14px;color:#92400E;font-weight:500;">
                    Hoy <strong>{{ today()->format('d/m/Y') }}</strong> no tiene cierre de caja registrado.
                </span>
            </div>
            <a href="{{ route('cash-closing.create') }}" class="btn btn-sm"
                style="background:#D97706;color:#fff;border:none;">
                Hacer cierre de hoy
            </a>
        </div>
    @elseif($todayClosing->status === 'open')
        <div
            style="background:#DBEAFE;border-left:4px solid #0284C7;border-radius:var(--radius-sm);
             padding:14px 16px;margin-bottom:20px;display:flex;align-items:center;
             justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <span style="font-size:14px;color:#1E40AF;font-weight:500;">
                El cierre de hoy está como <strong>borrador</strong>. Puedes finalizarlo.
            </span>
            <a href="{{ route('cash-closing.show', $todayClosing) }}" class="btn btn-sm"
                style="background:#0284C7;color:#fff;border:none;">
                Ver borrador
            </a>
        </div>
    @endif

    {{-- Filtro --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="padding:14px 16px;">
            <form method="GET" action="{{ route('cash-closing.index') }}"
                style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                <div>
                    <label
                        style="font-size:11px;font-weight:700;color:var(--text-muted);
                               text-transform:uppercase;letter-spacing:0.4px;
                               display:block;margin-bottom:5px;">Mes</label>
                    <select name="month" class="form-control" style="width:140px;">
                        @foreach ($monthNames as $num => $name)
                            <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        style="font-size:11px;font-weight:700;color:var(--text-muted);
                               text-transform:uppercase;letter-spacing:0.4px;
                               display:block;margin-bottom:5px;">Año</label>
                    <select name="year" class="form-control" style="width:100px;">
                        @for ($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>
    </div>

    {{-- KPIs del mes --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;" class="cc-kpi-grid">

        <div class="card" style="padding:16px;border-left:4px solid var(--success);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Total Ingresos</div>
            <div
                style="font-size:22px;font-weight:700;color:var(--success);
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                ${{ number_format($totalIncome, 2) }}
            </div>
        </div>

        <div class="card" style="padding:16px;border-left:4px solid var(--danger);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Total Egresos</div>
            <div
                style="font-size:22px;font-weight:700;color:var(--danger);
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                ${{ number_format($totalExpenses, 2) }}
            </div>
        </div>

        <div class="card"
            style="padding:16px;
         border-left:4px solid {{ $totalBalance >= 0 ? 'var(--success)' : 'var(--danger)' }};">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Balance del Mes</div>
            <div
                style="font-size:22px;font-weight:700;
                     color:{{ $totalBalance >= 0 ? 'var(--success)' : 'var(--danger)' }};
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                {{ $totalBalance >= 0 ? '+' : '' }}${{ number_format($totalBalance, 2) }}
            </div>
        </div>

        <div class="card" style="padding:16px;border-left:4px solid var(--primary);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Días con Cierre</div>
            <div
                style="font-size:22px;font-weight:700;color:var(--primary);
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                {{ $daysWithData }}
            </div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:4px;">
                días registrados
            </div>
        </div>

    </div>

    {{-- Lista de cierres --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Cierres de {{ $monthNames[$month] }} {{ $year }}
            </h3>
            <a href="{{ route('cash-closing.create') }}" class="btn btn-primary btn-sm">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Cierre
            </a>
        </div>

        @if ($closings->isEmpty())
            <div style="padding:48px;text-align:center;color:var(--text-muted);">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    style="margin:0 auto 14px;display:block;opacity:0.25;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <p style="font-size:15px;font-weight:600;margin-bottom:6px;">
                    Sin cierres en este período
                </p>
                <p style="font-size:13px;margin-bottom:16px;">
                    Registra el primer cierre de caja del día.
                </p>
                <a href="{{ route('cash-closing.create') }}" class="btn btn-primary">
                    Hacer cierre de hoy
                </a>
            </div>
        @else
            {{-- Desktop --}}
            <div class="table-wrap" id="cc-table">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th style="text-align:right;">Ingresos</th>
                            <th style="text-align:right;">Egresos</th>
                            <th style="text-align:right;">Balance</th>
                            <th class="hide-xs" style="text-align:right;">Efectivo Real</th>
                            <th class="hide-xs" style="text-align:right;">Diferencia</th>
                            <th class="hide-xs">Cerrado por</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($closings as $c)
                            @php $bal = $c->total_income - $c->total_expenses; @endphp
                            <tr>
                                <td style="white-space:nowrap;font-weight:600;">
                                    {{ $c->closing_date->format('d/m/Y') }}
                                    @if ($c->closing_date->isToday())
                                        <span class="badge badge-info" style="font-size:10px;margin-left:4px;">
                                            Hoy
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $c->status_badge }}">
                                        {{ $c->status_label }}
                                    </span>
                                </td>
                                <td style="text-align:right;color:var(--success);font-weight:600;">
                                    ${{ number_format($c->total_income, 2) }}
                                </td>
                                <td style="text-align:right;color:var(--danger);font-weight:600;">
                                    ${{ number_format($c->total_expenses, 2) }}
                                </td>
                                <td
                                    style="text-align:right;font-weight:700;
                                color:{{ $bal >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                    {{ $bal >= 0 ? '+' : '' }}${{ number_format($bal, 2) }}
                                </td>
                                <td class="hide-xs" style="text-align:right;">
                                    ${{ number_format($c->actual_cash, 2) }}
                                </td>
                                <td class="hide-xs" style="text-align:right;">
                                    @php $diff = $c->difference; @endphp
                                    <span
                                        style="font-weight:600;
                                      color:{{ $diff == 0 ? 'var(--success)' : ($diff > 0 ? 'var(--info)' : 'var(--danger)') }};">
                                        {{ $diff >= 0 ? '+' : '' }}${{ number_format($diff, 2) }}
                                    </span>
                                </td>
                                <td class="hide-xs" style="font-size:12px;color:var(--text-muted);">
                                    {{ $c->closedBy->name }}
                                </td>
                                <td>
                                    <a href="{{ route('cash-closing.show', $c) }}" class="btn btn-ghost btn-sm">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile --}}
            <div id="cc-cards" style="display:none;">
                @foreach ($closings as $c)
                    @php $bal = $c->total_income - $c->total_expenses; @endphp
                    <a href="{{ route('cash-closing.show', $c) }}"
                        style="display:block;padding:14px 16px;border-bottom:1px solid #F3F4F6;
                  text-decoration:none;color:var(--text);">
                        <div
                            style="display:flex;justify-content:space-between;
                         align-items:flex-start;margin-bottom:8px;">
                            <div>
                                <div style="font-size:14px;font-weight:700;">
                                    {{ $c->closing_date->format('d/m/Y') }}
                                    @if ($c->closing_date->isToday())
                                        <span class="badge badge-info" style="font-size:10px;">Hoy</span>
                                    @endif
                                </div>
                                <div style="margin-top:3px;">
                                    <span class="badge {{ $c->status_badge }}" style="font-size:11px;">
                                        {{ $c->status_label }}
                                    </span>
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div
                                    style="font-size:16px;font-weight:700;
                                 color:{{ $bal >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                    {{ $bal >= 0 ? '+' : '' }}${{ number_format($bal, 2) }}
                                </div>
                                <div style="font-size:11px;color:var(--text-muted);">balance</div>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:12px;">
                            <div style="background:var(--bg);border-radius:6px;padding:6px 10px;">
                                <div style="color:var(--text-muted);">Ingresos</div>
                                <div style="font-weight:700;color:var(--success);">
                                    ${{ number_format($c->total_income, 2) }}
                                </div>
                            </div>
                            <div style="background:var(--bg);border-radius:6px;padding:6px 10px;">
                                <div style="color:var(--text-muted);">Egresos</div>
                                <div style="font-weight:700;color:var(--danger);">
                                    ${{ number_format($c->total_expenses, 2) }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        @endif
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 768px) {
            .cc-kpi-grid {
                grid-template-columns: 1fr 1fr !important;
            }
        }

        @media (max-width: 640px) {
            #cc-table {
                display: none !important;
            }

            #cc-cards {
                display: block !important;
            }
        }
    </style>
@endpush
