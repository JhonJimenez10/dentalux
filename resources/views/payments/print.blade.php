<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Pagos — {{ $patient->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=DM+Sans:wght@400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F8F7F5;
            color: #1A1A2E;
            font-size: 13px;
            padding: 24px;
        }

        /* ─── NO PRINT TOOLBAR ──────────────────── */
        .toolbar {
            max-width: 760px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.15s;
        }

        .btn-primary {
            background: #C8395A;
            color: #fff;
        }

        .btn-primary:hover {
            background: #A62A48;
        }

        .btn-outline {
            background: transparent;
            color: #1A1A2E;
            border: 1px solid #E5E7EB;
        }

        .btn-outline:hover {
            background: #F3F4F6;
        }

        /* ─── PAGE ──────────────────────────────── */
        .page {
            max-width: 760px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        /* ─── HEADER ────────────────────────────── */
        .print-header {
            background: #1A1A2E;
            padding: 22px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: #C8395A;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .brand-sub {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.38);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .doc-info {
            text-align: right;
        }

        .doc-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 1px;
        }

        .doc-date {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 2px;
        }

        /* ─── PATIENT SECTION ───────────────────── */
        .patient-section {
            padding: 18px 28px;
            background: #F8F7F5;
            border-bottom: 1px solid #E5E7EB;
        }

        .patient-name {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .patient-meta {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .meta-item {
            font-size: 12px;
            color: #6B7280;
        }

        .meta-item strong {
            color: #1A1A2E;
            font-weight: 600;
        }

        /* ─── RESUMEN ECONÓMICO ─────────────────── */
        .summary-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-bottom: 1px solid #E5E7EB;
        }

        .summary-item {
            padding: 16px 20px;
            text-align: center;
            border-right: 1px solid #E5E7EB;
        }

        .summary-item:last-child {
            border-right: none;
        }

        .summary-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #9CA3AF;
            margin-bottom: 4px;
        }

        .summary-value {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 700;
        }

        .summary-value.red {
            color: #DC2626;
        }

        .summary-value.green {
            color: #059669;
        }

        .summary-value.normal {
            color: #1A1A2E;
        }

        /* ─── PROGRESS BAR ──────────────────────── */
        .progress-section {
            padding: 14px 28px;
            border-bottom: 1px solid #E5E7EB;
            background: #FAFAFA;
        }

        .progress-bar-wrap {
            height: 8px;
            background: #E5E7EB;
            border-radius: 100px;
            overflow: hidden;
            margin-bottom: 6px;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 100px;
            background: #C8395A;
        }

        .progress-bar-fill.complete {
            background: #059669;
        }

        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #9CA3AF;
        }

        /* ─── PAYMENTS TABLE ────────────────────── */
        .payments-section {
            padding: 0;
        }

        .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            color: #C8395A;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 28px 10px;
            border-bottom: 2px solid #F4D0D8;
        }

        table.ptable {
            width: 100%;
            border-collapse: collapse;
        }

        table.ptable thead th {
            padding: 9px 14px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #fff;
            background: #1A1A2E;
            white-space: nowrap;
        }

        table.ptable tbody tr:nth-child(even) td {
            background: #FAFAFA;
        }

        table.ptable tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 13px;
            vertical-align: middle;
        }

        table.ptable tbody tr:last-child td {
            border-bottom: none;
        }

        .empty-rows td {
            padding: 10px 14px;
            border-bottom: 1px solid #F9FAFB;
            height: 36px;
        }

        .amount-cell {
            font-weight: 700;
            color: #059669;
            font-size: 14px;
        }

        .balance-cell {
            font-weight: 600;
        }

        .balance-red {
            color: #DC2626;
        }

        .balance-green {
            color: #059669;
        }

        .sig-cell {
            text-align: center;
            min-width: 100px;
        }

        .sig-img {
            max-height: 38px;
            max-width: 90px;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 2px;
            background: #fff;
        }

        .sig-line {
            width: 80px;
            height: 30px;
            border-bottom: 1.5px solid #D1D5DB;
            display: inline-block;
        }

        /* ─── FOOTER ────────────────────────────── */
        .print-footer {
            padding: 16px 28px;
            background: #F8F7F5;
            border-top: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .footer-brand {
            font-size: 12px;
            font-weight: 600;
            color: #1A1A2E;
        }

        .footer-info {
            font-size: 11px;
            color: #9CA3AF;
        }

        /* ─── RESPONSIVE ────────────────────────── */
        @media (max-width: 600px) {
            body {
                padding: 0;
                background: #fff;
            }

            .page {
                border-radius: 0;
                box-shadow: none;
            }

            .print-header {
                padding: 16px 16px;
            }

            .patient-section {
                padding: 14px 16px;
            }

            .summary-section {
                grid-template-columns: repeat(3, 1fr);
            }

            .summary-value {
                font-size: 16px;
            }

            .progress-section {
                padding: 12px 16px;
            }

            .section-title {
                padding: 12px 16px 8px;
            }

            table.ptable thead th,
            table.ptable tbody td {
                padding: 8px 10px;
            }

            .print-footer {
                padding: 12px 16px;
            }

            .toolbar {
                padding: 12px 16px;
            }

            .hide-print-mobile {
                display: none;
            }
        }

        /* ─── PRINT ─────────────────────────────── */
        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .toolbar {
                display: none !important;
            }

            .page {
                border-radius: 0;
                box-shadow: none;
            }

            table.ptable {
                page-break-inside: auto;
            }

            table.ptable tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    {{-- Toolbar --}}
    <div class="toolbar no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Imprimir
        </button>
        <a href="{{ route('patients.payments.show', [$patient, $paymentControl]) }}" class="btn btn-outline">
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
        <span style="font-size:13px;color:#6B7280;margin-left:auto;" class="hide-print-mobile">
            {{ now()->format('d/m/Y H:i') }}
        </span>
    </div>

    <div class="page">

        {{-- Header --}}
        <div class="print-header">
            <div class="brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 38 38" fill="none" width="22" height="22">
                        <path
                            d="M19 4C13 4 8 9 8 15C8 18 9 20.5 10.5 23L13 31C13.5 33 15.5 34.5 17.2 34.5H20.8C22.5 34.5 24.5 33 25 31L27.5 23C29 20.5 30 18 30 15C30 9 25 4 19 4Z"
                            fill="white" opacity="0.9" />
                    </svg>
                </div>
                <div>
                    <div class="brand-name">dentalux</div>
                    <div class="brand-sub">Odontología Familiar</div>
                </div>
            </div>
            <div class="doc-info">
                <div class="doc-title">CONTROL DE PAGOS</div>
                <div class="doc-date">
                    Emitido: {{ now()->format('d/m/Y') }}
                    · Control #{{ str_pad($paymentControl->id, 4, '0', STR_PAD_LEFT) }}
                </div>
            </div>
        </div>

        {{-- Paciente --}}
        <div class="patient-section">
            <div class="patient-name">{{ $patient->full_name }}</div>
            <div class="patient-meta">
                @if ($patient->cedula)
                    <div class="meta-item">
                        Cédula: <strong>{{ $patient->cedula }}</strong>
                    </div>
                @endif
                @if ($patient->age_calculated)
                    <div class="meta-item">
                        Edad: <strong>{{ $patient->age_calculated }} años</strong>
                    </div>
                @endif
                @if ($patient->phone)
                    <div class="meta-item">
                        Tel: <strong>{{ $patient->phone }}</strong>
                    </div>
                @endif
                @if ($paymentControl->budget)
                    <div class="meta-item">
                        Presupuesto: <strong>#{{ $paymentControl->budget_id }}</strong>
                    </div>
                @endif
            </div>
        </div>

        {{-- Resumen económico --}}
        <div class="summary-section">
            <div class="summary-item">
                <div class="summary-label">Monto Tratamiento</div>
                <div class="summary-value normal">
                    ${{ number_format($paymentControl->treatment_amount, 2) }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Abonado</div>
                <div class="summary-value green">
                    ${{ number_format($paymentControl->total_paid, 2) }}
                </div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Saldo Pendiente</div>
                <div class="summary-value {{ $paymentControl->is_fully_paid ? 'green' : 'red' }}">
                    ${{ number_format($paymentControl->balance, 2) }}
                </div>
            </div>
        </div>

        {{-- Progreso --}}
        <div class="progress-section">
            <div class="progress-bar-wrap">
                <div class="progress-bar-fill {{ $paymentControl->is_fully_paid ? 'complete' : '' }}"
                    style="width:{{ $paymentControl->progress_percent }}%;">
                </div>
            </div>
            <div class="progress-labels">
                <span>
                    {{ $paymentControl->progress_percent }}% completado
                    @if ($paymentControl->is_fully_paid)
                        · ✓ Tratamiento saldado
                    @endif
                </span>
                <span>
                    {{ $paymentControl->payments->count() }} pago(s) registrado(s)
                </span>
            </div>
        </div>

        {{-- Tabla de pagos --}}
        <div class="payments-section">
            <div class="section-title">Historial de Pagos</div>

            <table class="ptable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha de Pago</th>
                        <th>Abono</th>
                        <th>Saldo</th>
                        <th>Método</th>
                        <th>Firma del Paciente</th>
                        <th>Registrado por</th>
                    </tr>
                </thead>
                <tbody>
                    @php $runningBalance = $paymentControl->treatment_amount; @endphp

                    @foreach ($paymentControl->payments as $i => $payment)
                        @php $runningBalance -= $payment->amount; @endphp
                        <tr>
                            <td style="color:#9CA3AF;font-size:12px;">{{ $i + 1 }}</td>
                            <td style="white-space:nowrap;font-weight:500;">
                                {{ $payment->payment_date->format('d/m/Y') }}
                            </td>
                            <td class="amount-cell">
                                ${{ number_format($payment->amount, 2) }}
                            </td>
                            <td class="balance-cell {{ $runningBalance <= 0 ? 'balance-green' : 'balance-red' }}">
                                ${{ number_format(max(0, $runningBalance), 2) }}
                            </td>
                            <td style="font-size:12px;">{{ $payment->method_label }}</td>
                            <td class="sig-cell">
                                @if ($payment->patient_signature)
                                    <img src="{{ $payment->patient_signature }}" class="sig-img" alt="Firma">
                                @else
                                    <div class="sig-line"></div>
                                @endif
                            </td>
                            <td style="font-size:12px;color:#6B7280;">
                                {{ $payment->registeredBy->name }}
                            </td>
                        </tr>
                    @endforeach

                    {{-- Filas vacías para escribir a mano --}}
                    @php $emptyRows = max(0, 10 - $paymentControl->payments->count()); @endphp
                    @for ($e = 0; $e < $emptyRows; $e++)
                        <tr class="empty-rows">
                            <td style="color:#E5E7EB;font-size:11px;">{{ $paymentControl->payments->count() + $e + 1 }}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="sig-cell">
                                <div class="sig-line"></div>
                            </td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="print-footer">
            <div>
                <div class="footer-brand">Dentalux Odontología Familiar</div>
                <div class="footer-info">
                    Dir. Luis Cordero y Héroes de Verdeloma · Telf: 0962248526
                </div>
            </div>
            <div style="text-align:right;">
                <div class="footer-info">
                    Generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}
                </div>
                @if ($paymentControl->is_fully_paid)
                    <div style="font-size:12px;font-weight:700;color:#059669;margin-top:4px;">
                        ✓ Tratamiento completamente saldado
                    </div>
                @else
                    <div style="font-size:12px;color:#DC2626;font-weight:600;margin-top:4px;">
                        Saldo pendiente:
                        ${{ number_format($paymentControl->balance, 2) }}
                    </div>
                @endif
            </div>
        </div>

    </div>

</body>

</html>
