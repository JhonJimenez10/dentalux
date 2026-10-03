@extends('layouts.app')
@section('title', 'Reporte de Ingresos')

@section('topbar-actions')
    <button onclick="window.print()" class="btn btn-outline btn-sm no-print">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        <span class="btn-hide-mobile">Imprimir</span>
    </button>
@endsection

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Reportes', 'url' => '#'],
            ['label' => 'Ingresos', 'url' => route('reports.income')],
        ];

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

        $methodLabels = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta Crédito',
            'tarjeta_debito' => 'Tarjeta Débito',
            'cheque' => 'Cheque',
        ];

        $methodColors = [
            'efectivo' => ['#D1FAE5', '#059669'],
            'transferencia' => ['#DBEAFE', '#0284C7'],
            'tarjeta_credito' => ['#F4D0D8', '#C8395A'],
            'tarjeta_debito' => ['#FEF3C7', '#D4A843'],
            'cheque' => ['#F3F4F6', '#6B7280'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Reporte de Ingresos</h1>
            <p>{{ $monthNames[$month] }} {{ $year }}</p>
        </div>
    </div>

    {{-- Filtro de mes/año --}}
    <div class="card no-print" style="margin-bottom:20px;">
        <div class="card-body" style="padding:14px 16px;">
            <form method="GET" action="{{ route('reports.income') }}"
                style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                <div>
                    <label
                        style="font-size:12px;font-weight:600;color:var(--text-muted);
                               display:block;margin-bottom:5px;text-transform:uppercase;
                               letter-spacing:0.4px;">
                        Mes
                    </label>
                    <select name="month" class="form-control" style="width:150px;">
                        @foreach ($monthNames as $num => $name)
                            <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        style="font-size:12px;font-weight:600;color:var(--text-muted);
                               display:block;margin-bottom:5px;text-transform:uppercase;
                               letter-spacing:0.4px;">
                        Año
                    </label>
                    <select name="year" class="form-control" style="width:100px;">
                        @for ($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filtrar
                </button>
            </form>
        </div>
    </div>

    {{-- Stats principales --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;" class="stats-main-grid">

        <div class="card" style="padding:18px;display:flex;align-items:center;gap:12px;">
            <div
                style="width:44px;height:44px;border-radius:12px;background:var(--primary-light);
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="var(--primary)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <div style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">
                    Ingresos este mes
                </div>
                <div
                    style="font-size:22px;font-weight:700;
                        font-family:'Plus Jakarta Sans',sans-serif;line-height:1;
                        color:var(--primary);">
                    ${{ number_format($totalMonth, 2) }}
                </div>
            </div>
        </div>

        <div class="card" style="padding:18px;display:flex;align-items:center;gap:12px;">
            <div
                style="width:44px;height:44px;border-radius:12px;background:#D1FAE5;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="#059669" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <div style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">
                    Ingresos año {{ $year }}
                </div>
                <div
                    style="font-size:22px;font-weight:700;
                        font-family:'Plus Jakarta Sans',sans-serif;line-height:1;
                        color:#059669;">
                    ${{ number_format($stats['total_income'], 2) }}
                </div>
            </div>
        </div>

        <div class="card" style="padding:18px;display:flex;align-items:center;gap:12px;">
            <div
                style="width:44px;height:44px;border-radius:12px;background:#DBEAFE;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="#0284C7" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <div style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">
                    Total Pacientes
                </div>
                <div
                    style="font-size:22px;font-weight:700;
                        font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                    {{ $stats['total_patients'] }}
                </div>
            </div>
        </div>

        <div class="card" style="padding:18px;display:flex;align-items:center;gap:12px;">
            <div
                style="width:44px;height:44px;border-radius:12px;background:#FEF3C7;
                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="#D4A843" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <div style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">
                    Presupuestos Activos
                </div>
                <div
                    style="font-size:22px;font-weight:700;
                        font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                    {{ $stats['active_budgets'] }}
                </div>
            </div>
        </div>

    </div>

    {{-- Gráfica últimos 6 meses + Métodos de pago --}}
    <div style="display:grid;grid-template-columns:1fr 280px;gap:16px;margin-bottom:20px;" class="income-chart-grid">

        {{-- Últimos 6 meses --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ingresos — Últimos 6 Meses</h3>
            </div>
            <div class="card-body" style="padding:20px;">
                @php
                    $maxVal = $last6Months->max('total') ?: 1;
                @endphp
                <div
                    style="display:flex;align-items:flex-end;gap:10px;height:160px;
                         padding-bottom:4px;">
                    @foreach ($last6Months as $m)
                        @php
                            $heightPct = $maxVal > 0 ? ($m['total'] / $maxVal) * 100 : 0;
                            $isCurrentMonth = $m['month'] == $month && $m['year'] == $year;
                        @endphp
                        <div
                            style="flex:1;display:flex;flex-direction:column;
                             align-items:center;gap:6px;height:100%;">
                            <div style="font-size:11px;font-weight:600;color:var(--primary);">
                                @if ($m['total'] > 0)
                                    ${{ number_format($m['total'], 0) }}
                                @endif
                            </div>
                            <div style="flex:1;width:100%;display:flex;align-items:flex-end;">
                                <a href="{{ route('reports.income', ['month' => $m['month'], 'year' => $m['year']]) }}"
                                    style="width:100%;height:{{ max(4, $heightPct) }}%;
                                  background:{{ $isCurrentMonth ? 'var(--primary)' : 'var(--primary-light)' }};
                                  border-radius:6px 6px 0 0;display:block;
                                  transition:background 0.2s;min-height:4px;"
                                    onmouseover="this.style.background='var(--primary-dark)'"
                                    onmouseout="this.style.background='{{ $isCurrentMonth ? 'var(--primary)' : 'var(--primary-light)' }}'">
                                </a>
                            </div>
                            <div
                                style="font-size:10px;color:var(--text-muted);text-align:center;
                                 white-space:nowrap;">
                                {{ $m['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($last6Months->sum('total') == 0)
                    <div
                        style="text-align:center;padding:20px;color:var(--text-muted);
                         font-size:13px;">
                        Sin ingresos en los últimos 6 meses
                    </div>
                @endif
            </div>
        </div>

        {{-- Por método de pago --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Por Método</h3>
            </div>
            <div style="padding:0;">
                @if ($byMethod->isEmpty())
                    <div
                        style="padding:32px 16px;text-align:center;color:var(--text-muted);
                         font-size:13px;">
                        Sin pagos en {{ $monthNames[$month] }}
                    </div>
                @else
                    @foreach ($byMethod as $method => $total)
                        @php
                            $colors = $methodColors[$method] ?? ['#F3F4F6', '#6B7280'];
                            $label = $methodLabels[$method] ?? ucfirst($method);
                            $pct = $totalMonth > 0 ? round(($total / $totalMonth) * 100) : 0;
                        @endphp
                        <div style="padding:12px 16px;border-bottom:1px solid var(--border);">
                            <div
                                style="display:flex;justify-content:space-between;
                             align-items:center;margin-bottom:6px;">
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <div
                                        style="width:10px;height:10px;border-radius:50%;
                                    background:{{ $colors[1] }};flex-shrink:0;">
                                    </div>
                                    <span style="font-size:13px;font-weight:500;">{{ $label }}</span>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:13px;font-weight:700;">
                                        ${{ number_format($total, 2) }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-muted);">
                                        {{ $pct }}%
                                    </div>
                                </div>
                            </div>
                            <div
                                style="height:5px;background:var(--border);border-radius:100px;
                             overflow:hidden;">
                                <div
                                    style="height:100%;width:{{ $pct }}%;
                                background:{{ $colors[1] }};border-radius:100px;">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div style="padding:12px 16px;display:flex;justify-content:space-between;">
                        <span style="font-size:13px;font-weight:700;">Total</span>
                        <span style="font-size:15px;font-weight:700;color:var(--primary);">
                            ${{ number_format($totalMonth, 2) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Últimos pagos --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Pagos de {{ $monthNames[$month] }} {{ $year }}
            </h3>
            <span class="badge badge-gray">
                {{ $recentPayments->count() }} registros
            </span>
        </div>

        @if ($recentPayments->isEmpty())
            <div style="padding:40px;text-align:center;color:var(--text-muted);">
                <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    style="margin:0 auto 12px;display:block;opacity:0.3;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p style="font-size:14px;">Sin pagos registrados en este período.</p>
            </div>
        @else
            {{-- Desktop --}}
            <div class="table-wrap" id="income-table">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th class="hide-xs">Método</th>
                            <th class="hide-xs">Registrado por</th>
                            <th style="text-align:right;">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentPayments as $payment)
                            <tr>
                                <td style="white-space:nowrap;font-size:13px;">
                                    {{ $payment->payment_date->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if ($payment->paymentControl?->patient)
                                        <a href="{{ route('patients.show', $payment->paymentControl->patient) }}"
                                            style="font-weight:500;color:var(--primary);
                                  text-decoration:none;font-size:13px;">
                                            {{ $payment->paymentControl->patient->full_name }}
                                        </a>
                                    @else
                                        <span style="color:var(--text-muted);font-size:13px;">—</span>
                                    @endif
                                </td>
                                <td class="hide-xs">
                                    <span class="badge badge-gray" style="font-size:11px;">
                                        {{ $payment->method_label }}
                                    </span>
                                </td>
                                <td class="hide-xs" style="font-size:12px;color:var(--text-muted);">
                                    {{ $payment->registeredBy->name }}
                                </td>
                                <td
                                    style="text-align:right;font-weight:700;color:var(--success);
                               font-size:15px;white-space:nowrap;">
                                    ${{ number_format($payment->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"
                                style="padding:12px 14px;font-weight:700;font-size:14px;
                               background:var(--bg);">
                                TOTAL DEL MES
                            </td>
                            <td
                                style="padding:12px 14px;text-align:right;font-weight:700;
                               font-size:18px;color:var(--primary);background:var(--bg);">
                                ${{ number_format($totalMonth, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Mobile: tarjetas --}}
            <div id="income-cards" style="display:none;">
                @foreach ($recentPayments as $payment)
                    <div
                        style="padding:12px 14px;border-bottom:1px solid #F3F4F6;
                    display:flex;align-items:center;gap:10px;">
                        <div style="flex:1;min-width:0;">
                            @if ($payment->paymentControl?->patient)
                                <div
                                    style="font-weight:600;font-size:13px;overflow:hidden;
                             text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $payment->paymentControl->patient->full_name }}
                                </div>
                            @endif
                            <div
                                style="font-size:11px;color:var(--text-muted);margin-top:2px;
                             display:flex;gap:8px;">
                                <span>{{ $payment->payment_date->format('d/m/Y') }}</span>
                                <span>{{ $payment->method_label }}</span>
                            </div>
                        </div>
                        <div
                            style="font-size:16px;font-weight:700;color:var(--success);
                        flex-shrink:0;">
                            ${{ number_format($payment->amount, 2) }}
                        </div>
                    </div>
                @endforeach
                <div
                    style="padding:12px 14px;display:flex;justify-content:space-between;
                    background:var(--bg);border-top:2px solid var(--border);">
                    <span style="font-weight:700;font-size:13px;">TOTAL DEL MES</span>
                    <span style="font-weight:700;font-size:16px;color:var(--primary);">
                        ${{ number_format($totalMonth, 2) }}
                    </span>
                </div>
            </div>

        @endif
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 768px) {
            .stats-main-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .income-chart-grid {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 480px) {
            .stats-main-grid {
                grid-template-columns: 1fr 1fr !important;
            }
        }

        @media (max-width: 640px) {
            #income-table {
                display: none !important;
            }

            #income-cards {
                display: block !important;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #E5E7EB !important;
            }
        }
    </style>
@endpush
