@extends('layouts.app')
@section('title', 'Cierre #' . $cashClosing->id)

@section('topbar-actions')
    <button onclick="window.print()" class="btn btn-outline btn-sm no-print">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        <span class="btn-hide-mobile">Imprimir</span>
    </button>
    @if ($cashClosing->status === 'open')
        <a href="{{ route('cash-closing.create', ['date' => $cashClosing->closing_date->format('Y-m-d')]) }}"
            class="btn btn-primary btn-sm">
            Editar / Cerrar
        </a>
    @elseif(auth()->user()->isAdmin())
        <form method="POST" action="{{ route('cash-closing.reopen', $cashClosing) }}"
            onsubmit="return confirm('¿Reabrir este cierre?')" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm">
                Reabrir
            </button>
        </form>
    @endif
@endsection

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Cierre de Caja', 'url' => route('cash-closing.index')],
            ['label' => $cashClosing->closing_date->format('d/m/Y'), 'url' => '#'],
        ];
        $balance = $cashClosing->total_income - $cashClosing->total_expenses;
    @endphp

    {{-- Cabecera --}}
    <div
        style="background:var(--text);border-radius:var(--radius);padding:18px 22px;
            margin-bottom:20px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
        <div
            style="width:48px;height:48px;border-radius:12px;background:var(--primary);
                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="24" height="24" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <div style="flex:1;min-width:0;">
            <div
                style="font-family:'Plus Jakarta Sans',sans-serif;font-size:18px;
                    font-weight:700;color:#fff;">
                Cierre de Caja — {{ $cashClosing->closing_date->isoFormat('dddd D [de] MMMM [de] YYYY') }}
            </div>
            <div
                style="font-size:12px;color:rgba(255,255,255,0.45);margin-top:3px;
                     display:flex;gap:12px;flex-wrap:wrap;">
                <span>Cerrado por: {{ $cashClosing->closedBy->name }}</span>
                @if ($cashClosing->closed_at)
                    <span>{{ $cashClosing->closed_at->format('H:i') }}</span>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:16px;flex-shrink:0;flex-wrap:wrap;align-items:center;">
            <span class="badge {{ $cashClosing->status_badge }}" style="font-size:13px;padding:6px 14px;">
                {{ $cashClosing->status_label }}
            </span>
            <div style="text-align:right;">
                <div
                    style="font-size:9px;color:rgba(255,255,255,0.38);
                         text-transform:uppercase;letter-spacing:0.5px;margin-bottom:3px;">
                    Balance
                </div>
                <div
                    style="font-size:22px;font-weight:700;
                         color:{{ $balance >= 0 ? '#6EE7B7' : '#FCA5A5' }};
                         font-family:'Plus Jakarta Sans',sans-serif;">
                    {{ $balance >= 0 ? '+' : '' }}${{ number_format($balance, 2) }}
                </div>
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;" class="show-kpi-grid">

        <div class="card" style="padding:16px;text-align:center;">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:6px;">
                Total Ingresos</div>
            <div style="font-size:22px;font-weight:700;color:var(--success);">
                ${{ number_format($cashClosing->total_income, 2) }}
            </div>
        </div>

        <div class="card" style="padding:16px;text-align:center;">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:6px;">
                Total Egresos</div>
            <div style="font-size:22px;font-weight:700;color:var(--danger);">
                ${{ number_format($cashClosing->total_expenses, 2) }}
            </div>
        </div>

        <div class="card" style="padding:16px;text-align:center;">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:6px;">
                Efectivo Real</div>
            <div style="font-size:22px;font-weight:700;color:var(--primary);">
                ${{ number_format($cashClosing->actual_cash, 2) }}
            </div>
        </div>

        <div class="card"
            style="padding:16px;text-align:center;
         background:{{ $cashClosing->difference == 0 ? '#F0FDF4' : ($cashClosing->difference > 0 ? '#EFF6FF' : '#FFF5F5') }};
         border-color:{{ $cashClosing->difference == 0 ? 'var(--success)' : ($cashClosing->difference > 0 ? '#0284C7' : 'var(--danger)') }};">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:6px;">
                Diferencia</div>
            <div
                style="font-size:22px;font-weight:700;
                     color:{{ $cashClosing->difference == 0 ? 'var(--success)' : ($cashClosing->difference > 0 ? '#0284C7' : 'var(--danger)') }};">
                {{ $cashClosing->difference >= 0 ? '+' : '' }}${{ number_format($cashClosing->difference, 2) }}
            </div>
            <div
                style="font-size:11px;margin-top:3px;
                     color:{{ $cashClosing->difference == 0 ? 'var(--success)' : ($cashClosing->difference > 0 ? '#0284C7' : 'var(--danger)') }};">
                @if ($cashClosing->difference == 0)
                    ✓ Cuadra
                @elseif($cashClosing->difference > 0)
                    Sobrante
                @else
                    Faltante
                @endif
            </div>
        </div>

    </div>

    <div style="display:grid;grid-template-columns:1fr 300px;gap:18px;align-items:start;" class="show-detail-grid">

        {{-- Izquierda --}}
        <div>

            {{-- Desglose de ingresos --}}
            <div class="card" style="margin-bottom:18px;">
                <div class="card-header">
                    <h3 class="card-title">Desglose de Ingresos</h3>
                </div>
                <div style="padding:0;">
                    @foreach ([['Efectivo', $cashClosing->income_cash, '#059669'], ['Transferencia', $cashClosing->income_transfer, '#0284C7'], ['Tarjeta', $cashClosing->income_card, '#7C3AED'], ['Otros (cheque)', $cashClosing->income_other, '#D97706']] as $row)
                        <div
                            style="display:flex;justify-content:space-between;align-items:center;
                             padding:12px 18px;border-bottom:1px solid var(--border);">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div
                                    style="width:8px;height:8px;border-radius:50%;
                                     background:{{ $row[2] }};flex-shrink:0;">
                                </div>
                                <span style="font-size:14px;">{{ $row[0] }}</span>
                            </div>
                            <span style="font-weight:700;color:{{ $row[2] }};">
                                ${{ number_format($row[1], 2) }}
                            </span>
                        </div>
                    @endforeach
                    <div
                        style="display:flex;justify-content:space-between;padding:14px 18px;
                             background:var(--bg);">
                        <span style="font-weight:700;">TOTAL</span>
                        <span style="font-weight:700;font-size:16px;color:var(--success);">
                            ${{ number_format($cashClosing->total_income, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Pagos del día --}}
            @if ($payments->isNotEmpty())
                <div class="card" style="margin-bottom:18px;">
                    <div class="card-header">
                        <h3 class="card-title">Pagos Recibidos</h3>
                        <span class="badge badge-gray">{{ $payments->count() }}</span>
                    </div>
                    <div class="table-wrap">
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
                        </table>
                    </div>
                </div>
            @endif

            {{-- Egresos del día --}}
            @if ($expenses->isNotEmpty())
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Egresos del Día</h3>
                        <span class="badge badge-danger">
                            ${{ number_format($cashClosing->total_expenses, 2) }}
                        </span>
                    </div>
                    <div class="table-wrap">
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
                                                {{ $exp->category_label }}
                                            </span>
                                        </td>
                                        <td style="text-align:right;font-weight:700;color:var(--danger);">
                                            ${{ number_format($exp->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>

        {{-- Derecha --}}
        <div>

            {{-- Resumen del arqueo --}}
            <div class="card" style="margin-bottom:14px;">
                <div class="card-header">
                    <h3 class="card-title">Arqueo de Caja</h3>
                </div>
                <div style="padding:0;">
                    @foreach ([['Saldo inicial', '$' . number_format($cashClosing->opening_balance, 2), ''], ['+ Ingresos total', '$' . number_format($cashClosing->total_income, 2), 'var(--success)'], ['- Egresos total', '$' . number_format($cashClosing->total_expenses, 2), 'var(--danger)'], ['= Efectivo esperado', '$' . number_format($cashClosing->expected_cash, 2), 'var(--primary)'], ['Efectivo real', '$' . number_format($cashClosing->actual_cash, 2), '#1A1A2E']] as $row)
                        <div
                            style="display:flex;justify-content:space-between;align-items:center;
                             padding:11px 16px;border-bottom:1px solid var(--border);
                             font-size:13px;">
                            <span style="color:var(--text-muted);">{{ $row[0] }}</span>
                            <span style="font-weight:600;color:{{ $row[2] ?: 'var(--text)' }};">
                                {{ $row[1] }}
                            </span>
                        </div>
                    @endforeach
                    {{-- Diferencia --}}
                    <div
                        style="padding:14px 16px;background:{{ $cashClosing->difference == 0 ? '#F0FDF4' : ($cashClosing->difference > 0 ? '#EFF6FF' : '#FFF5F5') }};">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-weight:700;">Diferencia</span>
                            <span
                                style="font-size:18px;font-weight:700;
                                      color:{{ $cashClosing->difference == 0 ? 'var(--success)' : ($cashClosing->difference > 0 ? '#0284C7' : 'var(--danger)') }};">
                                {{ $cashClosing->difference >= 0 ? '+' : '' }}${{ number_format($cashClosing->difference, 2) }}
                            </span>
                        </div>
                        @if ($cashClosing->difference != 0)
                            <div
                                style="font-size:12px;margin-top:4px;
                                 color:{{ $cashClosing->difference > 0 ? '#0284C7' : 'var(--danger)' }};">
                                {{ $cashClosing->difference > 0 ? 'Hay más efectivo del esperado (sobrante).' : 'Hay menos efectivo del esperado (faltante).' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Notas --}}
            @if ($cashClosing->notes)
                <div class="card" style="margin-bottom:14px;">
                    <div class="card-header">
                        <h3 class="card-title">Notas</h3>
                    </div>
                    <div class="card-body"
                        style="font-size:14px;color:var(--text-muted);
                                           line-height:1.6;">
                        {{ $cashClosing->notes }}
                    </div>
                </div>
            @endif

            {{-- Info del cierre --}}
            <div
                style="background:var(--bg);border-radius:var(--radius);padding:14px 16px;
                     border:1px solid var(--border);font-size:12px;">
                <div
                    style="font-weight:700;color:var(--text-muted);margin-bottom:8px;
                         text-transform:uppercase;letter-spacing:0.4px;">
                    Info del registro</div>
                <div style="display:flex;flex-direction:column;gap:6px;color:var(--text-muted);">
                    <div>Registrado por: <strong style="color:var(--text);">{{ $cashClosing->closedBy->name }}</strong>
                    </div>
                    <div>Estado: <strong style="color:var(--text);">{{ $cashClosing->status_label }}</strong></div>
                    @if ($cashClosing->closed_at)
                        <div>Cerrado: <strong
                                style="color:var(--text);">{{ $cashClosing->closed_at->format('d/m/Y H:i') }}</strong>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 768px) {
            .show-kpi-grid {
                grid-template-columns: 1fr 1fr !important;
            }

            .show-detail-grid {
                grid-template-columns: 1fr !important;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush
