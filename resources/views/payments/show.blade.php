@extends('layouts.app')
@section('title', 'Control de Pagos')

@section('topbar-actions')
    <a href="{{ route('patients.payments.print', [$patient, $paymentControl]) }}" class="btn btn-outline btn-sm"
        target="_blank">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2
                         4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002
                         2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        <span class="btn-hide-mobile">Imprimir</span>
    </a>
    <a href="{{ route('patients.payments.add', [$patient, $paymentControl]) }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="btn-hide-mobile">Registrar Pago</span>
    </a>
@endsection

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => $patient->full_name, 'url' => route('patients.show', $patient)],
            ['label' => 'Pagos', 'url' => '#'],
        ];
    @endphp

    {{-- Cabecera --}}
    <div
        style="background:var(--text);border-radius:var(--radius);padding:16px 18px;
            margin-bottom:18px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">

        <div
            style="width:44px;height:44px;border-radius:50%;background:var(--primary);
                display:flex;align-items:center;justify-content:center;
                font-size:15px;font-weight:700;color:#fff;flex-shrink:0;">
            {{ $patient->initials }}
        </div>

        <div style="flex:1;min-width:0;">
            <div
                style="font-family:'Plus Jakarta Sans',sans-serif;font-size:16px;
                    font-weight:700;color:#fff;overflow:hidden;
                    text-overflow:ellipsis;white-space:nowrap;">
                {{ $patient->full_name }}
            </div>
            <div
                style="font-size:11px;color:rgba(255,255,255,0.45);margin-top:2px;
                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                Control de Pagos #{{ $paymentControl->id }}
                @if ($paymentControl->budget)
                    · Presupuesto #{{ $paymentControl->budget_id }}
                @endif
            </div>
        </div>

        {{-- Resumen económico desktop --}}
        <div class="payment-summary-header"
            style="display:flex;gap:0;flex-shrink:0;background:rgba(255,255,255,0.07);
                border-radius:var(--radius-sm);overflow:hidden;
                border:1px solid rgba(255,255,255,0.1);">
            <div style="padding:10px 14px;border-right:1px solid rgba(255,255,255,0.1);">
                <div
                    style="font-size:9px;color:rgba(255,255,255,0.38);text-transform:uppercase;
                        letter-spacing:0.5px;margin-bottom:3px;">
                    Total</div>
                <div
                    style="font-size:15px;font-weight:700;color:#fff;
                        font-family:'Plus Jakarta Sans',sans-serif;">
                    ${{ number_format($paymentControl->treatment_amount, 2) }}
                </div>
            </div>
            <div style="padding:10px 14px;border-right:1px solid rgba(255,255,255,0.1);">
                <div
                    style="font-size:9px;color:rgba(255,255,255,0.38);text-transform:uppercase;
                        letter-spacing:0.5px;margin-bottom:3px;">
                    Abonado</div>
                <div
                    style="font-size:15px;font-weight:700;color:#6EE7B7;
                        font-family:'Plus Jakarta Sans',sans-serif;">
                    ${{ number_format($paymentControl->total_paid, 2) }}
                </div>
            </div>
            <div style="padding:10px 14px;">
                <div
                    style="font-size:9px;color:rgba(255,255,255,0.38);text-transform:uppercase;
                        letter-spacing:0.5px;margin-bottom:3px;">
                    Saldo</div>
                <div
                    style="font-size:15px;font-weight:700;
                        color:{{ $paymentControl->is_fully_paid ? '#6EE7B7' : '#FCA5A5' }};
                        font-family:'Plus Jakarta Sans',sans-serif;">
                    ${{ number_format($paymentControl->balance, 2) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Resumen económico mobile --}}
    <div class="payment-summary-mobile"
        style="display:none;background:var(--surface);border-radius:var(--radius);
            border:1px solid var(--border);margin-bottom:16px;overflow:hidden;">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);">
            <div style="padding:14px 10px;text-align:center;border-right:1px solid var(--border);">
                <div
                    style="font-size:10px;color:var(--text-muted);text-transform:uppercase;
                        letter-spacing:0.4px;margin-bottom:4px;">
                    Total</div>
                <div style="font-size:16px;font-weight:700;color:var(--text);">
                    ${{ number_format($paymentControl->treatment_amount, 2) }}
                </div>
            </div>
            <div style="padding:14px 10px;text-align:center;border-right:1px solid var(--border);">
                <div
                    style="font-size:10px;color:var(--text-muted);text-transform:uppercase;
                        letter-spacing:0.4px;margin-bottom:4px;">
                    Pagado</div>
                <div style="font-size:16px;font-weight:700;color:var(--success);">
                    ${{ number_format($paymentControl->total_paid, 2) }}
                </div>
            </div>
            <div style="padding:14px 10px;text-align:center;">
                <div
                    style="font-size:10px;color:var(--text-muted);text-transform:uppercase;
                        letter-spacing:0.4px;margin-bottom:4px;">
                    Saldo</div>
                <div
                    style="font-size:16px;font-weight:700;
                        color:{{ $paymentControl->is_fully_paid ? 'var(--success)' : 'var(--danger)' }};">
                    ${{ number_format($paymentControl->balance, 2) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de progreso --}}
    <div class="card" style="margin-bottom:18px;">
        <div class="card-body" style="padding:16px 18px;">
            <div
                style="display:flex;justify-content:space-between;align-items:center;
                    margin-bottom:10px;flex-wrap:wrap;gap:6px;">
                <span style="font-size:14px;font-weight:600;">Progreso del tratamiento</span>
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    @if ($paymentControl->is_fully_paid)
                        <span class="badge badge-success">✓ Saldado</span>
                    @else
                        <span style="font-size:13px;color:var(--text-muted);">
                            Faltan
                            <strong style="color:var(--danger);">
                                ${{ number_format($paymentControl->balance, 2) }}
                            </strong>
                        </span>
                    @endif
                    <span style="font-size:16px;font-weight:700;color:var(--primary);">
                        {{ $paymentControl->progress_percent }}%
                    </span>
                </div>
            </div>
            <div style="height:10px;background:var(--border);border-radius:100px;overflow:hidden;">
                <div
                    style="height:100%;
                        width:{{ $paymentControl->progress_percent }}%;
                        background:{{ $paymentControl->is_fully_paid ? 'var(--success)' : 'var(--primary)' }};
                        border-radius:100px;transition:width 0.6s ease;">
                </div>
            </div>
            <div
                style="display:flex;justify-content:space-between;margin-top:8px;
                    font-size:12px;color:var(--text-muted);">
                <span>${{ number_format($paymentControl->total_paid, 2) }} pagado</span>
                <span>${{ number_format($paymentControl->treatment_amount, 2) }} total</span>
            </div>
        </div>
    </div>

    {{-- Historial de pagos --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historial de Pagos</h3>
            <a href="{{ route('patients.payments.add', [$patient, $paymentControl]) }}" class="btn btn-primary btn-sm">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Registrar Pago
            </a>
        </div>

        @if ($paymentControl->payments->isEmpty())
            <div style="padding:40px 20px;text-align:center;color:var(--text-muted);">
                <div
                    style="width:52px;height:52px;background:var(--bg);border-radius:50%;
                     display:flex;align-items:center;justify-content:center;
                     margin:0 auto 12px;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p style="font-size:14px;font-weight:600;margin-bottom:6px;">
                    Sin pagos registrados
                </p>
                <p style="font-size:13px;margin-bottom:16px;">
                    Registra el primer abono del paciente.
                </p>
                <a href="{{ route('patients.payments.add', [$patient, $paymentControl]) }}" class="btn btn-primary">
                    Registrar primer pago
                </a>
            </div>
        @else
            {{-- ════ DESKTOP: tabla ════ --}}
            <div class="table-wrap" id="payments-table">
                <table class="dtable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Abono</th>
                            <th>Saldo</th>
                            <th class="hide-xs">Método</th>
                            <th class="hide-xs">Firma</th>
                            <th class="hide-xs">Registrado por</th>
                            <th style="text-align:center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $runningBalance = $paymentControl->treatment_amount; @endphp

                        @foreach ($paymentControl->payments as $i => $payment)
                            @php $runningBalance -= $payment->amount; @endphp
                            <tr>
                                <td style="color:var(--text-muted);font-size:12px;">
                                    {{ $i + 1 }}
                                </td>
                                <td style="white-space:nowrap;font-size:13px;">
                                    {{ $payment->payment_date->format('d/m/Y') }}
                                </td>
                                <td>
                                    <span style="font-weight:700;color:var(--success);font-size:15px;">
                                        ${{ number_format($payment->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        style="font-weight:600;
                                     color:{{ $runningBalance <= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                        ${{ number_format(max(0, $runningBalance), 2) }}
                                    </span>
                                </td>
                                <td class="hide-xs">
                                    <span class="badge badge-gray">
                                        {{ $payment->method_label }}
                                    </span>
                                    @if ($payment->reference)
                                        <div
                                            style="font-size:10px;color:var(--text-muted);
                                     margin-top:2px;max-width:120px;
                                     overflow:hidden;text-overflow:ellipsis;
                                     white-space:nowrap;">
                                            {{ $payment->reference }}
                                        </div>
                                    @endif
                                </td>
                                <td class="hide-xs">
                                    @if ($payment->patient_signature)
                                        <img src="{{ $payment->patient_signature }}"
                                            style="height:36px;border:1px solid var(--border);
                                    border-radius:6px;background:#fff;padding:2px;"
                                            alt="Firma">
                                    @else
                                        <span style="color:var(--text-muted);font-size:12px;">
                                            Sin firma
                                        </span>
                                    @endif
                                </td>
                                <td class="hide-xs" style="font-size:12px;color:var(--text-muted);">
                                    {{ $payment->registeredBy->name }}
                                </td>

                                {{-- ── ACCIONES ── --}}
                                <td>
                                    <div
                                        style="display:flex;gap:4px;align-items:center;
                                     justify-content:flex-end;flex-wrap:wrap;">

                                        {{-- Botón Voucher --}}
                                        <a href="{{ route('patients.payments.voucher', [$patient, $paymentControl, $payment]) }}"
                                            target="_blank" class="btn btn-ghost btn-sm"
                                            title="Imprimir voucher de este pago"
                                            style="display:inline-flex;align-items:center;gap:4px;">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                style="width:13px;height:13px;flex-shrink:0;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2
                                                 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2
                                                 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0
                                                 00-2 2v4h10z" />
                                            </svg>
                                            <span class="btn-hide-mobile">Voucher</span>
                                        </a>

                                        {{-- Botón Eliminar --}}
                                        <form method="POST"
                                            action="{{ route('patients.payments.destroy_payment', [$patient, $paymentControl, $payment]) }}"
                                            onsubmit="return confirm('¿Eliminar este pago? Esta acción no se puede deshacer.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Eliminar pago">
                                                ✕
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    {{-- Totales --}}
                    <tfoot>
                        <tr>
                            <td colspan="2"
                                style="padding:10px 14px;font-weight:700;font-size:13px;
                               background:var(--bg);text-align:right;
                               color:var(--text-muted);">
                                TOTAL ABONADO
                            </td>
                            <td
                                style="padding:10px 14px;font-weight:700;font-size:15px;
                               color:var(--success);background:var(--bg);">
                                ${{ number_format($paymentControl->total_paid, 2) }}
                            </td>
                            <td
                                style="padding:10px 14px;font-weight:700;font-size:15px;
                               color:{{ $paymentControl->is_fully_paid ? 'var(--success)' : 'var(--danger)' }};
                               background:var(--bg);">
                                ${{ number_format($paymentControl->balance, 2) }}
                            </td>
                            <td colspan="4" style="background:var(--bg);">
                                @if ($paymentControl->is_fully_paid)
                                    <span class="badge badge-success">✓ Completamente saldado</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- ════ MOBILE: tarjetas ════ --}}
            <div id="payments-cards" style="display:none;">
                @php $runningBalance = $paymentControl->treatment_amount; @endphp

                @foreach ($paymentControl->payments as $i => $payment)
                    @php $runningBalance -= $payment->amount; @endphp
                    <div style="padding:14px 16px;border-bottom:1px solid #F3F4F6;">

                        {{-- Fila principal: monto y saldo --}}
                        <div
                            style="display:flex;justify-content:space-between;
                         align-items:flex-start;margin-bottom:10px;">
                            <div>
                                <div style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">
                                    Pago #{{ $i + 1 }}
                                    · {{ $payment->payment_date->format('d/m/Y') }}
                                </div>
                                <div style="font-size:22px;font-weight:700;color:var(--success);">
                                    ${{ number_format($payment->amount, 2) }}
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:11px;color:var(--text-muted);margin-bottom:3px;">
                                    Saldo restante
                                </div>
                                <div
                                    style="font-size:17px;font-weight:700;
                                 color:{{ $runningBalance <= 0 ? 'var(--success)' : 'var(--danger)' }};">
                                    ${{ number_format(max(0, $runningBalance), 2) }}
                                </div>
                            </div>
                        </div>

                        {{-- Fila secundaria: método, firma, acciones --}}
                        <div
                            style="display:flex;align-items:center;
                         justify-content:space-between;flex-wrap:wrap;gap:8px;">
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <span class="badge badge-gray">{{ $payment->method_label }}</span>
                                @if ($payment->patient_signature)
                                    <img src="{{ $payment->patient_signature }}"
                                        style="height:28px;border:1px solid var(--border);
                                border-radius:5px;background:#fff;padding:2px;"
                                        alt="Firma">
                                @endif
                            </div>

                            {{-- Acciones mobile --}}
                            <div style="display:flex;gap:6px;align-items:center;">

                                {{-- Voucher --}}
                                <a href="{{ route('patients.payments.voucher', [$patient, $paymentControl, $payment]) }}"
                                    target="_blank"
                                    style="display:inline-flex;align-items:center;gap:4px;
                              padding:6px 10px;border-radius:var(--radius-sm);
                              font-size:12px;font-weight:500;
                              background:var(--bg);color:var(--text);
                              border:1px solid var(--border);text-decoration:none;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="width:12px;height:12px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2
                                         2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2
                                         2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0
                                         00-2 2v4h10z" />
                                    </svg>
                                    Voucher
                                </a>

                                {{-- Eliminar --}}
                                <form method="POST"
                                    action="{{ route('patients.payments.destroy_payment', [$patient, $paymentControl, $payment]) }}"
                                    onsubmit="return confirm('¿Eliminar este pago?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Eliminar
                                    </button>
                                </form>

                            </div>
                        </div>

                        {{-- Referencia si existe --}}
                        @if ($payment->reference)
                            <div style="margin-top:6px;font-size:11px;color:var(--text-muted);">
                                Ref: {{ $payment->reference }}
                            </div>
                        @endif

                    </div>
                @endforeach

                {{-- Total mobile --}}
                <div
                    style="padding:12px 16px;background:var(--bg);
                     display:flex;justify-content:space-between;
                     align-items:center;font-weight:700;">
                    <span>Total abonado</span>
                    <span style="font-size:16px;color:var(--success);">
                        ${{ number_format($paymentControl->total_paid, 2) }}
                    </span>
                </div>
            </div>

        @endif
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 640px) {
            #payments-table {
                display: none !important;
            }

            #payments-cards {
                display: block !important;
            }

            .payment-summary-header {
                display: none !important;
            }

            .payment-summary-mobile {
                display: block !important;
            }
        }
    </style>
@endpush
