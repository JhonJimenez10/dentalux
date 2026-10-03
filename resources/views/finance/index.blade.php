@extends('layouts.app')
@section('title', 'Finanzas')

@section('topbar-actions')
    <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="btn-hide-mobile">Nuevo Egreso</span>
    </a>
@endsection

@section('content')

    @php
        $breadcrumbs = [['label' => 'Finanzas', 'url' => route('finance.index')]];

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
        $catLabels = [
            'materiales' => 'Materiales dentales',
            'equipos' => 'Equipos e instrumental',
            'servicios' => 'Servicios',
            'arriendo' => 'Arriendo / local',
            'sueldos' => 'Sueldos y honorarios',
            'laboratorio' => 'Laboratorio dental',
            'publicidad' => 'Publicidad y marketing',
            'mantenimiento' => 'Mantenimiento',
            'otros' => 'Otros',
        ];

        $methodLabels = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta_credito' => 'T. Crédito',
            'tarjeta_debito' => 'T. Débito',
            'cheque' => 'Cheque',
        ];

        $catColors = [
            'materiales' => ['#DBEAFE', '#1D4ED8'],
            'equipos' => ['#EDE9FE', '#7C3AED'],
            'servicios' => ['#FEF3C7', '#D97706'],
            'arriendo' => ['#FCE7F3', '#DB2777'],
            'sueldos' => ['#D1FAE5', '#059669'],
            'publicidad' => ['#FEE2E2', '#DC2626'],
            'laboratorio' => ['#E0F2FE', '#0284C7'],
            'mantenimiento' => ['#F3F4F6', '#6B7280'],
            'otros' => ['#F9FAFB', '#9CA3AF'],
        ];
    @endphp

    {{-- Filtro --}}
    <div class="card no-print" style="margin-bottom:20px;">
        <div class="card-body" style="padding:14px 16px;">
            <form method="GET" action="{{ route('finance.index') }}"
                style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
                <div>
                    <label
                        style="font-size:11px;font-weight:700;color:var(--text-muted);
                               text-transform:uppercase;letter-spacing:0.4px;
                               display:block;margin-bottom:5px;">Mes</label>
                    <select name="month" class="form-control" style="width:140px;">
                        @foreach ($monthNames as $num => $name)
                            <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>
                                {{ $name }}
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
                        @for ($y = now()->year; $y >= now()->year - 4; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
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
                <button type="button" class="btn btn-outline" onclick="window.print()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimir
                </button>

                {{-- Exportar Excel --}}
                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    <a href="{{ route('finance.export.excel', ['month' => $month, 'year' => $year]) }}"
                        style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;
                              background:#217346;color:#fff;border-radius:var(--radius-sm);
                              font-size:13px;font-weight:500;text-decoration:none;
                              border:none;cursor:pointer;">
                        📊 Excel Completo
                    </a>
                    <a href="{{ route('finance.export.ingresos', ['month' => $month, 'year' => $year]) }}"
                        style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;
                              background:#059669;color:#fff;border-radius:var(--radius-sm);
                              font-size:13px;font-weight:500;text-decoration:none;
                              border:none;cursor:pointer;">
                        📥 Solo Ingresos
                    </a>
                    <a href="{{ route('finance.export.egresos', ['month' => $month, 'year' => $year]) }}"
                        style="display:inline-flex;align-items:center;gap:5px;padding:8px 14px;
                              background:#DC2626;color:#fff;border-radius:var(--radius-sm);
                              font-size:13px;font-weight:500;text-decoration:none;
                              border:none;cursor:pointer;">
                        📤 Solo Egresos
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- KPIs principales --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;" class="finance-kpi-grid">

        {{-- Ingresos mes --}}
        <div class="card" style="padding:18px;border-left:4px solid var(--success);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:6px;">
                Ingresos {{ $monthNames[$month] }}
            </div>
            <div
                style="font-size:26px;font-weight:700;color:var(--success);
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                ${{ number_format($totalIncomeMonth, 2) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:6px;">
                {{ $incomesMonth->count() }} pagos recibidos
            </div>
        </div>

        {{-- Egresos mes --}}
        <div class="card" style="padding:18px;border-left:4px solid var(--danger);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:6px;">
                Egresos {{ $monthNames[$month] }}
            </div>
            <div
                style="font-size:26px;font-weight:700;color:var(--danger);
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                ${{ number_format($totalExpenseMonth, 2) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:6px;">
                {{ $expensesMonth->count() }} gastos registrados
            </div>
        </div>

        {{-- Balance mes --}}
        <div class="card"
            style="padding:18px;
         border-left:4px solid {{ $balance >= 0 ? 'var(--success)' : 'var(--danger)' }};">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:6px;">
                Balance {{ $monthNames[$month] }}
            </div>
            <div
                style="font-size:26px;font-weight:700;
                     color:{{ $balance >= 0 ? 'var(--success)' : 'var(--danger)' }};
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                {{ $balance >= 0 ? '+' : '' }}${{ number_format($balance, 2) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:6px;">
                {{ $balance >= 0 ? 'Superávit del mes' : 'Déficit del mes' }}
            </div>
        </div>

        {{-- Ingresos año --}}
        <div class="card" style="padding:18px;border-left:4px solid var(--primary);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:6px;">
                Balance Año {{ $year }}
            </div>
            @php $annualBalance = $totalIncomeYear - $totalExpenseYear; @endphp
            <div
                style="font-size:26px;font-weight:700;color:var(--primary);
                     font-family:'Plus Jakarta Sans',sans-serif;line-height:1;">
                {{ $annualBalance >= 0 ? '+' : '' }}${{ number_format($annualBalance, 2) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:6px;">
                Ing: ${{ number_format($totalIncomeYear, 0) }}
                · Egr: ${{ number_format($totalExpenseYear, 0) }}
            </div>
        </div>

    </div>

    {{-- Gráfica 6 meses + Distribución --}}
    <div style="display:grid;grid-template-columns:1fr 280px;gap:16px;margin-bottom:20px;" class="finance-chart-grid">

        {{-- Gráfica barras --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ingresos vs Egresos — Últimos 6 meses</h3>
            </div>
            <div class="card-body" style="padding:16px 20px;">
                @php $maxVal = $last6->max(fn($m) => max($m['income'], $m['expense'])) ?: 1; @endphp

                {{-- Barras agrupadas --}}
                <div
                    style="display:flex;align-items:flex-end;gap:8px;height:140px;
                         margin-bottom:8px;">
                    @foreach ($last6 as $m)
                        @php
                            $iH = $maxVal > 0 ? max(3, ($m['income'] / $maxVal) * 100) : 3;
                            $eH = $maxVal > 0 ? max(3, ($m['expense'] / $maxVal) * 100) : 3;
                            $isCurrent = $m['month'] == $month && $m['year'] == $year;
                        @endphp
                        <div
                            style="flex:1;display:flex;flex-direction:column;align-items:center;
                             gap:4px;height:100%;">
                            <div
                                style="flex:1;width:100%;display:flex;align-items:flex-end;
                                 gap:2px;">
                                {{-- Barra ingreso --}}
                                <div style="flex:1;height:{{ $iH }}%;background:{{ $isCurrent ? 'var(--success)' : '#A7F3D0' }};
                                     border-radius:4px 4px 0 0;transition:height 0.4s;"
                                    title="Ingreso: ${{ number_format($m['income'], 2) }}">
                                </div>
                                {{-- Barra egreso --}}
                                <div style="flex:1;height:{{ $eH }}%;background:{{ $isCurrent ? 'var(--danger)' : '#FECACA' }};
                                     border-radius:4px 4px 0 0;transition:height 0.4s;"
                                    title="Egreso: ${{ number_format($m['expense'], 2) }}">
                                </div>
                            </div>
                            <div
                                style="font-size:9px;color:var(--text-muted);white-space:nowrap;
                                 font-weight:{{ $isCurrent ? '700' : '400' }};">
                                {{ $m['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Leyenda --}}
                <div
                    style="display:flex;gap:16px;justify-content:center;font-size:11px;
                         color:var(--text-muted);">
                    <div style="display:flex;align-items:center;gap:5px;">
                        <div
                            style="width:12px;height:12px;background:var(--success);
                                 border-radius:3px;">
                        </div>
                        Ingresos
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;">
                        <div
                            style="width:12px;height:12px;background:var(--danger);
                                 border-radius:3px;">
                        </div>
                        Egresos
                    </div>
                </div>

                {{-- Tabla resumen 6 meses --}}
                <div style="margin-top:14px;border-top:1px solid var(--border);padding-top:12px;">
                    <table style="width:100%;font-size:12px;border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th
                                    style="text-align:left;color:var(--text-muted);
                                        font-weight:600;padding:4px 6px;">
                                    Mes</th>
                                <th
                                    style="text-align:right;color:var(--success);
                                        font-weight:600;padding:4px 6px;">
                                    Ingresos</th>
                                <th
                                    style="text-align:right;color:var(--danger);
                                        font-weight:600;padding:4px 6px;">
                                    Egresos</th>
                                <th
                                    style="text-align:right;font-weight:600;
                                        padding:4px 6px;">
                                    Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($last6 as $m)
                                @php $bal = $m['income'] - $m['expense']; @endphp
                                <tr
                                    style="{{ $m['month'] == $month && $m['year'] == $year ? 'background:var(--primary-bg);font-weight:600;' : '' }}">
                                    <td style="padding:5px 6px;border-radius:4px;">
                                        {{ $m['label'] }}
                                    </td>
                                    <td
                                        style="text-align:right;padding:5px 6px;
                                        color:var(--success);">
                                        ${{ number_format($m['income'], 2) }}
                                    </td>
                                    <td
                                        style="text-align:right;padding:5px 6px;
                                        color:var(--danger);">
                                        ${{ number_format($m['expense'], 2) }}
                                    </td>
                                    <td
                                        style="text-align:right;padding:5px 6px;
                                        color:{{ $bal >= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                        {{ $bal >= 0 ? '+' : '' }}${{ number_format($bal, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Distribución --}}
        <div style="display:flex;flex-direction:column;gap:14px;">

            {{-- Por método de ingreso --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-size:13px;">Ingresos por método</h3>
                </div>
                <div style="padding:0;">
                    @if ($incomeByMethod->isEmpty())
                        <div
                            style="padding:16px;text-align:center;font-size:12px;
                             color:var(--text-muted);">
                            Sin ingresos este mes</div>
                    @else
                        @foreach ($incomeByMethod as $method => $total)
                            @php $pct = $totalIncomeMonth>0 ? round(($total/$totalIncomeMonth)*100) : 0; @endphp
                            <div style="padding:10px 14px;border-bottom:1px solid var(--border);">
                                <div
                                    style="display:flex;justify-content:space-between;
                                 align-items:center;margin-bottom:5px;">
                                    <span style="font-size:12px;font-weight:500;">
                                        {{ $methodLabels[$method] ?? ucfirst($method) }}
                                    </span>
                                    <div style="text-align:right;">
                                        <div style="font-size:13px;font-weight:700;color:var(--success);">
                                            ${{ number_format($total, 2) }}
                                        </div>
                                        <div style="font-size:10px;color:var(--text-muted);">{{ $pct }}%</div>
                                    </div>
                                </div>
                                <div style="height:4px;background:var(--border);border-radius:100px;">
                                    <div
                                        style="height:100%;width:{{ $pct }}%;background:var(--success);
                                     border-radius:100px;">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Por categoría de egreso --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-size:13px;">Egresos por categoría</h3>
                </div>
                <div style="padding:0;">
                    @if ($expenseByCategory->isEmpty())
                        <div
                            style="padding:16px;text-align:center;font-size:12px;
                             color:var(--text-muted);">
                            Sin egresos este mes</div>
                    @else
                        @foreach ($expenseByCategory as $cat => $total)
                            @php
                                $pct = $totalExpenseMonth > 0 ? round(($total / $totalExpenseMonth) * 100) : 0;
                                $colors = $catColors[$cat] ?? ['#F3F4F6', '#6B7280'];
                            @endphp
                            <div style="padding:10px 14px;border-bottom:1px solid var(--border);">
                                <div
                                    style="display:flex;justify-content:space-between;
                                 align-items:center;margin-bottom:5px;">
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <div
                                            style="width:8px;height:8px;border-radius:50%;
                                         background:{{ $colors[1] }};flex-shrink:0;">
                                        </div>
                                        <span style="font-size:12px;font-weight:500;">
                                            {{ $catLabels[$cat] ?? ucfirst($cat) }} </span>
                                    </div>
                                    <div style="text-align:right;">
                                        <div style="font-size:13px;font-weight:700;color:var(--danger);">
                                            ${{ number_format($total, 2) }}
                                        </div>
                                        <div style="font-size:10px;color:var(--text-muted);">{{ $pct }}%</div>
                                    </div>
                                </div>
                                <div style="height:4px;background:var(--border);border-radius:100px;">
                                    <div
                                        style="height:100%;width:{{ $pct }}%;
                                     background:{{ $colors[1] }};border-radius:100px;">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Detalle: Ingresos y Egresos del mes --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" class="finance-detail-grid">

        {{-- Ingresos --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Ingresos — {{ $monthNames[$month] }} {{ $year }}
                </h3>
                <span class="badge badge-success">
                    ${{ number_format($totalIncomeMonth, 2) }}
                </span>
            </div>

            @if ($incomesMonth->isEmpty())
                <div style="padding:32px;text-align:center;color:var(--text-muted);">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="margin:0 auto 10px;display:block;opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p style="font-size:13px;">Sin ingresos en este período</p>
                </div>
            @else
                {{-- Desktop --}}
                <div id="inc-table" class="table-wrap">
                    <table class="dtable" style="min-width:auto;">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Paciente</th>
                                <th class="hide-xs">Método</th>
                                <th style="text-align:right;">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($incomesMonth as $pay)
                                <tr>
                                    <td style="font-size:12px;white-space:nowrap;">
                                        {{ $pay->payment_date->format('d/m/Y') }}
                                    </td>
                                    <td style="font-size:13px;">
                                        @if ($pay->paymentControl?->patient)
                                            <a href="{{ route('patients.show', $pay->paymentControl->patient) }}"
                                                style="color:var(--primary);text-decoration:none;">
                                                {{ $pay->paymentControl->patient->full_name }}
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="hide-xs">
                                        <span class="badge badge-gray" style="font-size:11px;">
                                            {{ $pay->method_label }}
                                        </span>
                                    </td>
                                    <td
                                        style="text-align:right;font-weight:700;color:var(--success);
                                    white-space:nowrap;">
                                        ${{ number_format($pay->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"
                                    style="padding:10px 14px;font-weight:700;font-size:13px;
                                   background:var(--bg);">
                                    TOTAL
                                </td>
                                <td
                                    style="text-align:right;padding:10px 14px;font-weight:700;
                                   font-size:15px;color:var(--success);background:var(--bg);">
                                    ${{ number_format($totalIncomeMonth, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- Mobile --}}
                <div id="inc-cards" style="display:none;">
                    @foreach ($incomesMonth as $pay)
                        <div
                            style="padding:12px 14px;border-bottom:1px solid #F3F4F6;
                         display:flex;justify-content:space-between;align-items:center;">
                            <div>
                                @if ($pay->paymentControl?->patient)
                                    <div style="font-size:13px;font-weight:600;">
                                        {{ $pay->paymentControl->patient->full_name }}
                                    </div>
                                @endif
                                <div style="font-size:11px;color:var(--text-muted);">
                                    {{ $pay->payment_date->format('d/m/Y') }}
                                    · {{ $pay->method_label }}
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
                        <span style="color:var(--success);">${{ number_format($totalIncomeMonth, 2) }}</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Egresos --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Egresos — {{ $monthNames[$month] }} {{ $year }}
                </h3>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="badge badge-danger">
                        ${{ number_format($totalExpenseMonth, 2) }}
                    </span>
                    <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary btn-sm">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nuevo
                    </a>
                </div>
            </div>

            @if ($expensesMonth->isEmpty())
                <div style="padding:32px;text-align:center;color:var(--text-muted);">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="margin:0 auto 10px;display:block;opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p style="font-size:13px;margin-bottom:12px;">Sin egresos registrados</p>
                    <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary btn-sm">
                        Registrar egreso
                    </a>
                </div>
            @else
                {{-- Desktop --}}
                <div id="exp-table" class="table-wrap">
                    <table class="dtable" style="min-width:auto;">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th class="hide-xs">Categoría</th>
                                <th style="text-align:right;">Monto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expensesMonth as $exp)
                                @php $cc = $catColors[$exp->category] ?? ['#F3F4F6','#6B7280']; @endphp
                                <tr>
                                    <td style="font-size:12px;white-space:nowrap;">
                                        {{ $exp->expense_date->format('d/m/Y') }}
                                    </td>
                                    <td style="font-size:13px;">
                                        <div style="font-weight:500;">{{ $exp->description }}</div>
                                        @if ($exp->notes)
                                            <div style="font-size:11px;color:var(--text-muted);">
                                                {{ Str::limit($exp->notes, 40) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="hide-xs">
                                        <span
                                            style="font-size:11px;font-weight:600;padding:2px 8px;
                                          border-radius:100px;background:{{ $cc[0] }};
                                          color:{{ $cc[1] }};">
                                            {{ $exp->category_label }}
                                        </span>
                                    </td>
                                    <td
                                        style="text-align:right;font-weight:700;color:var(--danger);
                                    white-space:nowrap;">
                                        ${{ number_format($exp->amount, 2) }}
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:4px;">
                                            <a href="{{ route('finance.expenses.edit', $exp) }}"
                                                class="btn btn-ghost btn-sm">Editar</a>
                                            <form method="POST" action="{{ route('finance.expenses.destroy', $exp) }}"
                                                onsubmit="return confirm('¿Eliminar egreso?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">✕</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"
                                    style="padding:10px 14px;font-weight:700;font-size:13px;
                                   background:var(--bg);">
                                    TOTAL
                                </td>
                                <td
                                    style="text-align:right;padding:10px 14px;font-weight:700;
                                   font-size:15px;color:var(--danger);background:var(--bg);">
                                    ${{ number_format($totalExpenseMonth, 2) }}
                                </td>
                                <td style="background:var(--bg);"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- Mobile --}}
                <div id="exp-cards" style="display:none;">
                    @foreach ($expensesMonth as $exp)
                        @php $cc = $catColors[$exp->category] ?? ['#F3F4F6','#6B7280']; @endphp
                        <div style="padding:12px 14px;border-bottom:1px solid #F3F4F6;">
                            <div
                                style="display:flex;justify-content:space-between;
                             align-items:flex-start;margin-bottom:6px;">
                                <div style="flex:1;min-width:0;">
                                    <div
                                        style="font-size:13px;font-weight:600;
                                     overflow:hidden;text-overflow:ellipsis;
                                     white-space:nowrap;">
                                        {{ $exp->description }}
                                    </div>
                                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
                                        {{ $exp->expense_date->format('d/m/Y') }}
                                    </div>
                                </div>
                                <div
                                    style="font-size:15px;font-weight:700;color:var(--danger);
                                 flex-shrink:0;margin-left:10px;">
                                    ${{ number_format($exp->amount, 2) }}
                                </div>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span
                                    style="font-size:11px;font-weight:600;padding:2px 8px;
                                  border-radius:100px;background:{{ $cc[0] }};
                                  color:{{ $cc[1] }};">
                                    {{ $exp->category_label }}
                                </span>
                                <div style="display:flex;gap:4px;">
                                    <a href="{{ route('finance.expenses.edit', $exp) }}"
                                        class="btn btn-ghost btn-sm">Editar</a>
                                    <form method="POST" action="{{ route('finance.expenses.destroy', $exp) }}"
                                        onsubmit="return confirm('¿Eliminar?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">✕</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div
                        style="padding:10px 14px;display:flex;justify-content:space-between;
                         background:var(--bg);font-weight:700;">
                        <span>TOTAL</span>
                        <span style="color:var(--danger);">${{ number_format($totalExpenseMonth, 2) }}</span>
                    </div>
                </div>
            @endif
        </div>

    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 768px) {
            .finance-kpi-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }

            .finance-chart-grid {
                grid-template-columns: 1fr !important;
            }

            .finance-detail-grid {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 480px) {
            .finance-kpi-grid {
                grid-template-columns: 1fr 1fr !important;
            }
        }

        @media (max-width: 640px) {
            #inc-table {
                display: none !important;
            }

            #inc-cards {
                display: block !important;
            }

            #exp-table {
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
