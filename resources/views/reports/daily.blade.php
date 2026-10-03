@extends('layouts.app')
@section('title', 'Reporte Diario')

@section('topbar-actions')
    <a href="{{ route('reports.daily.export', ['date' => $date->format('Y-m-d')]) }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span class="btn-hide-mobile">Exportar Excel</span>
    </a>
@endsection

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Reportes', 'url' => '#'],
            ['label' => 'Reporte Diario', 'url' => route('reports.daily')],
        ];

        $methodLabels = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta Crédito',
            'tarjeta_debito' => 'Tarjeta Débito',
            'cheque' => 'Cheque',
        ];

        $catLabels = [
            'materiales' => 'Materiales',
            'equipos' => 'Equipos',
            'servicios' => 'Servicios',
            'arriendo' => 'Arriendo',
            'sueldos' => 'Sueldos',
            'laboratorio' => 'Laboratorio',
            'publicidad' => 'Publicidad',
            'mantenimiento' => 'Mantenimiento',
            'otros' => 'Otros',
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Reporte Diario</h1>
            <p>{{ $date->isoFormat('dddd D [de] MMMM [de] YYYY') }}</p>
        </div>
    </div>

    {{-- Selector de fecha --}}
    <div class="card no-print" style="margin-bottom:20px;">
        <div class="card-body" style="padding:14px 16px;">
            <form method="GET" action="{{ route('reports.daily') }}"
                style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                <div>
                    <label
                        style="font-size:11px;font-weight:700;color:var(--text-muted);
                               text-transform:uppercase;letter-spacing:0.4px;
                               display:block;margin-bottom:5px;">Fecha
                        del reporte</label>
                    <input type="date" name="date" class="form-control" value="{{ $date->format('Y-m-d') }}"
                        max="{{ today()->format('Y-m-d') }}">
                </div>
                <button type="submit" class="btn btn-primary">Ver reporte</button>
                <a href="{{ route('reports.daily.export', ['date' => $date->format('Y-m-d')]) }}" class="btn btn-outline"
                    style="display:flex;align-items:center;gap:6px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    📊 Descargar Excel
                </a>
            </form>
        </div>
    </div>

    {{-- KPIs --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;" class="daily-kpi-grid">

        <div class="card" style="padding:18px;border-left:4px solid var(--success);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Ingresos</div>
            <div
                style="font-size:26px;font-weight:700;color:var(--success);
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                ${{ number_format($totalIncome, 2) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:5px;">
                {{ $payments->count() }} pago(s)
            </div>
        </div>

        <div class="card" style="padding:18px;border-left:4px solid var(--danger);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Egresos</div>
            <div
                style="font-size:26px;font-weight:700;color:var(--danger);
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                ${{ number_format($totalExpense, 2) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:5px;">
                {{ $expenses->count() }} gasto(s)
            </div>
        </div>

        <div class="card"
            style="padding:18px;
         border-left:4px solid {{ $balance >= 0 ? 'var(--success)' : 'var(--danger)' }};">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Balance</div>
            <div
                style="font-size:26px;font-weight:700;
                     color:{{ $balance >= 0 ? 'var(--success)' : 'var(--danger)' }};
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                {{ $balance >= 0 ? '+' : '' }}${{ number_format($balance, 2) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:5px;">
                {{ $balance >= 0 ? 'Superávit' : 'Déficit' }} del día
            </div>
        </div>

        <div class="card" style="padding:18px;border-left:4px solid var(--primary);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Pacientes Nuevos</div>
            <div
                style="font-size:26px;font-weight:700;color:var(--primary);
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                {{ $patients->count() }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:5px;">
                registrados hoy
            </div>
        </div>

    </div>

    {{-- Arqueo de caja --}}
    @if ($closing)
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <h3 class="card-title">Arqueo de Caja</h3>
                <span class="badge {{ $closing->status_badge }}">
                    {{ $closing->status_label }}
                </span>
            </div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);text-align:center;
                 border-bottom:1px solid var(--border);"
                class="arqueo-grid">
                @foreach ([['Efectivo esperado', '$' . number_format($closing->expected_cash, 2), ''], ['Efectivo real', '$' . number_format($closing->actual_cash, 2), ''], ['Diferencia', ($closing->difference >= 0 ? '+' : '') . '$' . number_format($closing->difference, 2), $closing->difference == 0 ? 'var(--success)' : ($closing->difference > 0 ? '#0284C7' : 'var(--danger)')], ['Saldo para mañana', '$' . number_format($closing->closing_balance, 2), 'var(--primary)']] as $item)
                    <div style="padding:16px;border-right:1px solid var(--border);">
                        <div style="font-size:11px;color:var(--text-muted);margin-bottom:5px;">
                            {{ $item[0] }}
                        </div>
                        <div
                            style="font-size:18px;font-weight:700;
                         color:{{ $item[2] ?: 'var(--text)' }};">
                            {{ $item[1] }}
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($closing->notes)
                <div style="padding:12px 16px;font-size:13px;color:var(--text-muted);">
                    <strong>Notas:</strong> {{ $closing->notes }}
                </div>
            @endif
        </div>
    @else
        <div
            style="background:#FEF3C7;border:1px solid #D97706;border-radius:var(--radius-sm);
             padding:12px 16px;margin-bottom:20px;font-size:13px;color:#92400E;
             display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <span>⚠️ No hay cierre de caja registrado para este día.</span>
            <a href="{{ route('cash-closing.create', ['date' => $date->format('Y-m-d')]) }}" class="btn btn-sm"
                style="background:#D97706;color:#fff;border:none;">
                Hacer cierre
            </a>
        </div>
    @endif

    {{-- Detalle ingresos + egresos --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;" class="daily-detail-grid">

        {{-- Ingresos --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ingresos del Día</h3>
                <span class="badge badge-success">
                    ${{ number_format($totalIncome, 2) }}
                </span>
            </div>
            @if ($payments->isEmpty())
                <div style="padding:24px;text-align:center;color:var(--text-muted);font-size:13px;">
                    Sin ingresos registrados.
                </div>
            @else
                <div id="inc-tbl" class="table-wrap">
                    <table class="dtable" style="min-width:auto;">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Método</th>
                                <th style="text-align:right;">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $pay)
                                <tr>
                                    <td style="font-size:13px;">
                                        {{ $pay->paymentControl?->patient?->full_name ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-gray" style="font-size:11px;">
                                            {{ $pay->method_label }}
                                        </span>
                                    </td>
                                    <td style="text-align:right;font-weight:700;color:var(--success);">
                                        ${{ number_format($pay->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding:10px 14px;font-weight:700;background:var(--bg);">
                                    TOTAL
                                </td>
                                <td
                                    style="padding:10px 14px;text-align:right;font-weight:700;
                                   font-size:15px;color:var(--success);background:var(--bg);">
                                    ${{ number_format($totalIncome, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- Mobile --}}
                <div id="inc-cards" style="display:none;">
                    @foreach ($payments as $pay)
                        <div
                            style="padding:12px 14px;border-bottom:1px solid #F3F4F6;
                         display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-size:13px;font-weight:600;">
                                    {{ $pay->paymentControl?->patient?->full_name ?? '—' }}
                                </div>
                                <div style="font-size:11px;color:var(--text-muted);">
                                    {{ $pay->method_label }}
                                </div>
                            </div>
                            <div style="font-size:15px;font-weight:700;color:var(--success);">
                                ${{ number_format($pay->amount, 2) }}
                            </div>
                        </div>
                    @endforeach
                    <div
                        style="padding:10px 14px;display:flex;justify-content:space-between;
                         background:var(--bg);font-weight:700;">
                        <span>TOTAL</span>
                        <span style="color:var(--success);">${{ number_format($totalIncome, 2) }}</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Egresos --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Egresos del Día</h3>
                <span class="badge badge-danger">
                    ${{ number_format($totalExpense, 2) }}
                </span>
            </div>
            @if ($expenses->isEmpty())
                <div style="padding:24px;text-align:center;color:var(--text-muted);font-size:13px;">
                    Sin egresos registrados.
                </div>
            @else
                <div id="exp-tbl" class="table-wrap">
                    <table class="dtable" style="min-width:auto;">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th class="hide-xs">Categoría</th>
                                <th style="text-align:right;">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $exp)
                                <tr>
                                    <td style="font-size:13px;font-weight:500;">{{ $exp->description }}</td>
                                    <td class="hide-xs">
                                        <span class="badge badge-gray" style="font-size:11px;">
                                            {{ $catLabels[$exp->category] ?? $exp->category }}
                                        </span>
                                    </td>
                                    <td style="text-align:right;font-weight:700;color:var(--danger);">
                                        ${{ number_format($exp->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" style="padding:10px 14px;font-weight:700;background:var(--bg);">
                                    TOTAL
                                </td>
                                <td
                                    style="padding:10px 14px;text-align:right;font-weight:700;
                                   font-size:15px;color:var(--danger);background:var(--bg);">
                                    ${{ number_format($totalExpense, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- Mobile --}}
                <div id="exp-cards" style="display:none;">
                    @foreach ($expenses as $exp)
                        <div
                            style="padding:12px 14px;border-bottom:1px solid #F3F4F6;
                         display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                <div style="font-size:13px;font-weight:600;">{{ $exp->description }}</div>
                                <div style="font-size:11px;color:var(--text-muted);">
                                    {{ $catLabels[$exp->category] ?? $exp->category }}
                                </div>
                            </div>
                            <div style="font-size:15px;font-weight:700;color:var(--danger);">
                                ${{ number_format($exp->amount, 2) }}
                            </div>
                        </div>
                    @endforeach
                    <div
                        style="padding:10px 14px;display:flex;justify-content:space-between;
                         background:var(--bg);font-weight:700;">
                        <span>TOTAL</span>
                        <span style="color:var(--danger);">${{ number_format($totalExpense, 2) }}</span>
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- Pacientes nuevos --}}
    @if ($patients->isNotEmpty())
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <h3 class="card-title">Pacientes Nuevos del Día</h3>
                <span class="badge badge-info">{{ $patients->count() }}</span>
            </div>
            <div class="table-wrap">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th class="hide-xs">Cédula</th>
                            <th class="hide-xs">Teléfono</th>
                            <th class="hide-xs">Ciudad</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($patients as $p)
                            <tr>
                                <td>
                                    <a href="{{ route('patients.show', $p) }}"
                                        style="font-weight:600;color:var(--primary);
                                  text-decoration:none;font-size:13px;">
                                        {{ $p->full_name }}
                                    </a>
                                </td>
                                <td class="hide-xs" style="font-size:13px;color:var(--text-muted);">
                                    {{ $p->cedula ?? '—' }}
                                </td>
                                <td class="hide-xs" style="font-size:13px;">{{ $p->phone ?? '—' }}</td>
                                <td class="hide-xs" style="font-size:13px;">{{ $p->city ?? '—' }}</td>
                                <td style="font-size:12px;color:var(--text-muted);">
                                    {{ $p->reason_for_consultation ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Botón exportar grande --}}
    <div style="text-align:center;padding:20px 0;">
        <a href="{{ route('reports.daily.export', ['date' => $date->format('Y-m-d')]) }}" class="btn btn-primary"
            style="padding:14px 32px;font-size:15px;gap:10px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            📊 Descargar Reporte Excel
            <span style="font-size:12px;opacity:0.8;font-weight:400;">
                — {{ $date->format('d/m/Y') }}
            </span>
        </a>
        <div style="font-size:12px;color:var(--text-muted);margin-top:8px;">
            El archivo incluye 4 hojas: Resumen, Ingresos, Egresos y Pacientes Nuevos
        </div>
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width:768px) {
            .daily-kpi-grid {
                grid-template-columns: 1fr 1fr !important;
            }

            .daily-detail-grid {
                grid-template-columns: 1fr !important;
            }

            .arqueo-grid {
                grid-template-columns: 1fr 1fr !important;
            }
        }

        @media (max-width:640px) {
            #inc-tbl {
                display: none !important;
            }

            #inc-cards {
                display: block !important;
            }

            #exp-tbl {
                display: none !important;
            }

            #exp-cards {
                display: block !important;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush
