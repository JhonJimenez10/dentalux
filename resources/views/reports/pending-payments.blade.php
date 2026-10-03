@extends('layouts.app')
@section('title', 'Pagos Pendientes')

@section('topbar-actions')
    <a href="{{ route('reports.pending.export', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
        class="btn btn-primary btn-sm">
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
            ['label' => 'Pagos Pendientes', 'url' => route('reports.pending')],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Pagos Pendientes</h1>
            <p>Clientes con saldo pendiente por cancelar</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="card no-print" style="margin-bottom:20px;">
        <div class="card-body" style="padding:14px 16px;">
            <form method="GET" action="{{ route('reports.pending') }}"
                style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">

                <div>
                    <label
                        style="font-size:11px;font-weight:700;color:var(--text-muted);
                               text-transform:uppercase;letter-spacing:0.4px;
                               display:block;margin-bottom:5px;">Fecha
                        inicio</label>
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}"
                        max="{{ date('Y-m-d') }}">
                </div>

                <div>
                    <label
                        style="font-size:11px;font-weight:700;color:var(--text-muted);
                               text-transform:uppercase;letter-spacing:0.4px;
                               display:block;margin-bottom:5px;">Fecha
                        fin</label>
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}"
                        max="{{ date('Y-m-d') }}">
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
                    </svg>
                    Filtrar
                </button>

                <a href="{{ route('reports.pending.export', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;
                      background:#217346;color:#fff;border-radius:var(--radius-sm);
                      font-size:13px;font-weight:500;text-decoration:none;border:none;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    📊 Exportar Excel
                </a>

                {{-- Accesos rápidos --}}
                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-left:auto;">
                    @php
                        $quickFilters = [
                            [
                                'label' => 'Este mes',
                                'from' => now()->startOfMonth()->format('Y-m-d'),
                                'to' => now()->format('Y-m-d'),
                            ],
                            [
                                'label' => 'Mes pasado',
                                'from' => now()->subMonth()->startOfMonth()->format('Y-m-d'),
                                'to' => now()->subMonth()->endOfMonth()->format('Y-m-d'),
                            ],
                            [
                                'label' => 'Este año',
                                'from' => now()->startOfYear()->format('Y-m-d'),
                                'to' => now()->format('Y-m-d'),
                            ],
                            ['label' => 'Todo', 'from' => '2020-01-01', 'to' => now()->format('Y-m-d')],
                        ];
                    @endphp
                    @foreach ($quickFilters as $qf)
                        <a href="{{ route('reports.pending', ['date_from' => $qf['from'], 'date_to' => $qf['to']]) }}"
                            style="padding:6px 10px;font-size:11px;font-weight:600;
                          border-radius:100px;text-decoration:none;
                          background:{{ $dateFrom == $qf['from'] && $dateTo == $qf['to'] ? 'var(--primary)' : 'var(--bg)' }};
                          color:{{ $dateFrom == $qf['from'] && $dateTo == $qf['to'] ? '#fff' : 'var(--text-muted)' }};
                          border:1px solid {{ $dateFrom == $qf['from'] && $dateTo == $qf['to'] ? 'var(--primary)' : 'var(--border)' }};">
                            {{ $qf['label'] }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

    {{-- KPIs --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;" class="pending-kpi-grid">

        <div class="card" style="padding:18px;border-left:4px solid var(--primary);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Pacientes con deuda</div>
            <div style="font-size:28px;font-weight:700;color:var(--primary);line-height:1;">
                {{ $totalPatients }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">en el período</div>
        </div>

        <div class="card" style="padding:18px;border-left:4px solid var(--danger);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Total pendiente</div>
            <div style="font-size:24px;font-weight:700;color:var(--danger);line-height:1;">
                ${{ number_format($totalPending, 2) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">por cobrar</div>
        </div>

        <div class="card" style="padding:18px;border-left:4px solid var(--success);">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Total cobrado</div>
            <div style="font-size:24px;font-weight:700;color:var(--success);line-height:1;">
                ${{ number_format($totalPaid, 2) }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">
                de ${{ number_format($totalTreatment, 2) }}
            </div>
        </div>

        <div class="card" style="padding:18px;border-left:4px solid #D97706;">
            <div
                style="font-size:11px;color:var(--text-muted);text-transform:uppercase;
                     letter-spacing:0.4px;margin-bottom:5px;">
                Alerta +90 días</div>
            <div style="font-size:28px;font-weight:700;color:#D97706;line-height:1;">
                {{ $overdue90 }}
            </div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:4px;">sin pagar</div>
        </div>

    </div>

    {{-- Indicadores de antigüedad --}}
    @if ($totalPatients > 0)
        <div class="card no-print" style="margin-bottom:20px;">
            <div class="card-body" style="padding:14px 16px;">
                <div
                    style="font-size:12px;font-weight:700;color:var(--text-muted);
                     text-transform:uppercase;letter-spacing:0.4px;margin-bottom:10px;">
                    Antigüedad de saldos
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <div
                        style="display:flex;align-items:center;gap:8px;padding:8px 14px;
                         background:#F0FDF4;border:1px solid #BBF7D0;border-radius:var(--radius-sm);">
                        <div style="width:10px;height:10px;border-radius:50%;background:#059669;"></div>
                        <span style="font-size:12px;font-weight:600;color:#065F46;">
                            Recientes (&lt;30 días): <strong>{{ $recent }}</strong>
                        </span>
                    </div>
                    <div
                        style="display:flex;align-items:center;gap:8px;padding:8px 14px;
                         background:#FEF3C7;border:1px solid #FDE68A;border-radius:var(--radius-sm);">
                        <div style="width:10px;height:10px;border-radius:50%;background:#D97706;"></div>
                        <span style="font-size:12px;font-weight:600;color:#92400E;">
                            30-60 días: <strong>{{ $overdue30 }}</strong>
                        </span>
                    </div>
                    <div
                        style="display:flex;align-items:center;gap:8px;padding:8px 14px;
                         background:#FFF7ED;border:1px solid #FED7AA;border-radius:var(--radius-sm);">
                        <div style="width:10px;height:10px;border-radius:50%;background:#EA580C;"></div>
                        <span style="font-size:12px;font-weight:600;color:#9A3412;">
                            60-90 días: <strong>{{ $overdue60 }}</strong>
                        </span>
                    </div>
                    <div
                        style="display:flex;align-items:center;gap:8px;padding:8px 14px;
                         background:#FEF2F2;border:1px solid #FECACA;border-radius:var(--radius-sm);">
                        <div style="width:10px;height:10px;border-radius:50%;background:#DC2626;"></div>
                        <span style="font-size:12px;font-weight:600;color:#991B1B;">
                            +90 días: <strong>{{ $overdue90 }}</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Tabla --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Detalle de Pendientes
                <span style="font-size:12px;color:var(--text-muted);font-weight:400;margin-left:6px;">
                    {{ \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') }}
                    al
                    {{ \Carbon\Carbon::parse($dateTo)->format('d/m/Y') }}
                </span>
            </h3>
            <span class="badge badge-danger">{{ $totalPatients }} paciente(s)</span>
        </div>

        @if ($pendingControls->isEmpty())
            <div style="padding:48px 20px;text-align:center;color:var(--text-muted);">
                <div style="font-size:40px;margin-bottom:12px;">🎉</div>
                <p style="font-size:15px;font-weight:600;margin-bottom:6px;">
                    ¡Sin pendientes en este período!
                </p>
                <p style="font-size:13px;">Todos los pacientes están al día con sus pagos.</p>
            </div>
        @else
            {{-- Desktop: tabla --}}
            <div class="table-wrap" id="pending-table">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paciente</th>
                            <th class="hide-xs">Cédula</th>
                            <th class="hide-xs">Teléfono</th>
                            <th>Total</th>
                            <th>Abonado</th>
                            <th>Pendiente</th>
                            <th class="hide-xs">Avance</th>
                            <th class="hide-xs">Último Pago</th>
                            <th class="hide-xs">Días</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingControls as $i => $pc)
                            @php
                                $patient = $pc->patient;
                                $lastPayment = $pc->payments->sortByDesc('payment_date')->first();
                                $daysSince = $lastPayment
                                    ? now()->diffInDays($lastPayment->payment_date)
                                    : now()->diffInDays($pc->created_at);
                                $pct =
                                    $pc->treatment_amount > 0
                                        ? min(100, round(($pc->total_paid / $pc->treatment_amount) * 100))
                                        : 0;
                                $urgency =
                                    $daysSince >= 90
                                        ? 'danger'
                                        : ($daysSince >= 60
                                            ? 'orange'
                                            : ($daysSince >= 30
                                                ? 'warning'
                                                : 'ok'));
                                $urgencyColors = [
                                    'danger' => ['bg' => '#FEF2F2', 'text' => '#DC2626', 'badge' => '#DC2626'],
                                    'orange' => ['bg' => '#FFF7ED', 'text' => '#EA580C', 'badge' => '#EA580C'],
                                    'warning' => ['bg' => '#FEF3C7', 'text' => '#D97706', 'badge' => '#D97706'],
                                    'ok' => ['bg' => '#F0FDF4', 'text' => '#059669', 'badge' => '#059669'],
                                ];
                                $uc = $urgencyColors[$urgency];
                            @endphp
                            <tr style="{{ $daysSince >= 90 ? 'background:#FFF8F8;' : '' }}">
                                <td style="color:var(--text-muted);font-size:12px;">{{ $i + 1 }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $patient) }}"
                                        style="font-weight:600;color:var(--primary);text-decoration:none;
                                  font-size:13px;">
                                        {{ $patient?->full_name ?? '—' }}
                                    </a>
                                    @if ($pc->budget)
                                        <div style="font-size:10px;color:var(--text-muted);margin-top:1px;">
                                            Pres. #{{ $pc->budget_id }}
                                        </div>
                                    @endif
                                </td>
                                <td class="hide-xs" style="font-size:12px;color:var(--text-muted);">
                                    {{ $patient?->cedula ?? '—' }}
                                </td>
                                <td class="hide-xs" style="font-size:12px;">
                                    {{ $patient?->phone ?? '—' }}
                                </td>
                                <td style="font-size:13px;font-weight:600;">
                                    ${{ number_format($pc->treatment_amount, 2) }}
                                </td>
                                <td style="font-size:13px;color:var(--success);font-weight:600;">
                                    ${{ number_format($pc->total_paid, 2) }}
                                </td>
                                <td>
                                    <span style="font-size:14px;font-weight:700;color:var(--danger);">
                                        ${{ number_format($pc->balance, 2) }}
                                    </span>
                                </td>
                                <td class="hide-xs">
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <div
                                            style="flex:1;height:6px;background:#E5E7EB;
                                         border-radius:100px;overflow:hidden;min-width:50px;">
                                            <div
                                                style="height:100%;width:{{ $pct }}%;
                                             background:{{ $pct >= 75 ? '#059669' : ($pct >= 50 ? '#D97706' : '#DC2626') }};
                                             border-radius:100px;">
                                            </div>
                                        </div>
                                        <span
                                            style="font-size:11px;font-weight:700;
                                          color:{{ $pct >= 75 ? 'var(--success)' : ($pct >= 50 ? '#D97706' : 'var(--danger)') }};">
                                            {{ $pct }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="hide-xs" style="font-size:12px;white-space:nowrap;">
                                    @if ($lastPayment)
                                        {{ $lastPayment->payment_date->format('d/m/Y') }}
                                    @else
                                        <span style="color:var(--text-muted);">Sin pagos</span>
                                    @endif
                                </td>
                                <td class="hide-xs">
                                    <span
                                        style="display:inline-flex;align-items:center;gap:3px;
                                      padding:3px 8px;border-radius:100px;font-size:11px;
                                      font-weight:700;
                                      background:{{ $uc['bg'] }};color:{{ $uc['text'] }};">
                                        {{ $daysSince }}d
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('patients.payments.show', [$patient, $pc]) }}"
                                        class="btn btn-ghost btn-sm" title="Ver pagos">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            style="width:13px;height:13px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"
                                style="padding:12px 14px;font-weight:700;font-size:13px;
                               background:var(--bg);text-align:right;">
                                TOTALES
                            </td>
                            <td style="padding:12px 14px;font-weight:700;background:var(--bg);">
                                ${{ number_format($totalTreatment, 2) }}
                            </td>
                            <td
                                style="padding:12px 14px;font-weight:700;color:var(--success);
                               background:var(--bg);">
                                ${{ number_format($totalPaid, 2) }}
                            </td>
                            <td
                                style="padding:12px 14px;font-weight:700;font-size:16px;
                               color:var(--danger);background:var(--bg);">
                                ${{ number_format($totalPending, 2) }}
                            </td>
                            <td colspan="4" style="background:var(--bg);padding:12px 14px;">
                                <span style="font-size:12px;color:var(--text-muted);">
                                    {{ $totalPatients }} paciente(s) con deuda
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Mobile: tarjetas --}}
            <div id="pending-cards" style="display:none;">
                @foreach ($pendingControls as $i => $pc)
                    @php
                        $patient = $pc->patient;
                        $lastPayment = $pc->payments->sortByDesc('payment_date')->first();
                        $daysSince = $lastPayment
                            ? now()->diffInDays($lastPayment->payment_date)
                            : now()->diffInDays($pc->created_at);
                        $pct =
                            $pc->treatment_amount > 0
                                ? min(100, round(($pc->total_paid / $pc->treatment_amount) * 100))
                                : 0;
                    @endphp
                    <div style="padding:14px 16px;border-bottom:1px solid #F3F4F6;">
                        <div
                            style="display:flex;justify-content:space-between;
                         align-items:flex-start;margin-bottom:8px;">
                            <div style="flex:1;min-width:0;">
                                <a href="{{ route('patients.show', $patient) }}"
                                    style="font-weight:700;font-size:14px;color:var(--primary);
                              text-decoration:none;display:block;">
                                    {{ $patient?->full_name ?? '—' }}
                                </a>
                                <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
                                    {{ $patient?->phone ?? 'Sin teléfono' }}
                                    @if ($lastPayment)
                                        · Último pago: {{ $lastPayment->payment_date->format('d/m/Y') }}
                                    @else
                                        · Sin pagos registrados
                                    @endif
                                </div>
                            </div>
                            <div style="text-align:right;flex-shrink:0;margin-left:12px;">
                                <div style="font-size:20px;font-weight:700;color:var(--danger);">
                                    ${{ number_format($pc->balance, 2) }}
                                </div>
                                <div style="font-size:10px;color:var(--text-muted);">pendiente</div>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                            <div style="flex:1;height:6px;background:#E5E7EB;border-radius:100px;overflow:hidden;">
                                <div
                                    style="height:100%;width:{{ $pct }}%;
                                 background:{{ $pct >= 75 ? '#059669' : ($pct >= 50 ? '#D97706' : '#DC2626') }};
                                 border-radius:100px;">
                                </div>
                            </div>
                            <span
                                style="font-size:11px;font-weight:700;min-width:32px;text-align:right;
                              color:{{ $pct >= 75 ? 'var(--success)' : ($pct >= 50 ? '#D97706' : 'var(--danger)') }};">
                                {{ $pct }}%
                            </span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <div style="display:flex;gap:6px;font-size:11px;">
                                <span style="color:var(--text-muted);">
                                    Total: <strong>${{ number_format($pc->treatment_amount, 2) }}</strong>
                                </span>
                                <span style="color:var(--success);">
                                    Pagado: <strong>${{ number_format($pc->total_paid, 2) }}</strong>
                                </span>
                            </div>
                            <a href="{{ route('patients.payments.show', [$patient, $pc]) }}"
                                class="btn btn-ghost btn-sm">Ver</a>
                        </div>
                    </div>
                @endforeach
                <div
                    style="padding:12px 16px;background:var(--bg);display:flex;
                     justify-content:space-between;font-weight:700;">
                    <span>Total pendiente</span>
                    <span style="color:var(--danger);">${{ number_format($totalPending, 2) }}</span>
                </div>
            </div>

        @endif
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width:768px) {
            .pending-kpi-grid {
                grid-template-columns: 1fr 1fr !important;
            }
        }

        @media (max-width:640px) {
            #pending-table {
                display: none !important;
            }

            #pending-cards {
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
