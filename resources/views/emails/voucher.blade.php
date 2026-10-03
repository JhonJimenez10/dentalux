<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: #F3F4F6;
            color: #1A1A2E;
            font-size: 15px;
            line-height: 1.5;
        }

        .wrapper {
            max-width: 520px;
            margin: 24px auto;
            padding: 0 16px 32px;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .header {
            background: #1A1A2E;
            padding: 24px;
            text-align: center;
        }

        .clinic-name {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .clinic-sub {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.45);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        /* Monto banner */
        .amount-band {
            background: #C8395A;
            padding: 20px 24px;
            text-align: center;
        }

        .amount-label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4px;
        }

        .amount-value {
            font-size: 36px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -1px;
            line-height: 1;
        }

        .amount-method {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.65);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Body */
        .body {
            padding: 24px;
        }

        .greeting {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 13px;
            color: #6B7280;
            margin-bottom: 20px;
        }

        /* Info rows */
        .info-box {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 13px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6B7280;
        }

        .info-value {
            font-weight: 600;
            text-align: right;
            max-width: 60%;
        }

        .info-value.green {
            color: #059669;
        }

        .info-value.red {
            color: #DC2626;
        }

        .info-value.primary {
            color: #C8395A;
        }

        /* Progreso */
        .progress-bar-outer {
            height: 6px;
            background: #E5E7EB;
            border-radius: 100px;
            overflow: hidden;
            margin: 6px 0;
        }

        .progress-bar-inner {
            height: 100%;
            border-radius: 100px;
        }

        /* PDF note */
        .pdf-note {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 0;
            font-size: 13px;
            color: #065F46;
        }

        .pdf-icon {
            font-size: 22px;
            flex-shrink: 0;
        }

        /* Footer */
        .footer {
            padding: 16px 24px;
            text-align: center;
            border-top: 1px solid #F3F4F6;
        }

        .footer-clinic {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
        }

        .footer-info {
            font-size: 11px;
            color: #9CA3AF;
            margin-top: 2px;
        }

        @media(max-width:520px) {
            .wrapper {
                padding: 0 8px 24px;
            }

            .amount-value {
                font-size: 28px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 2px;
            }

            .info-value {
                text-align: left;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="card">

            {{-- Header --}}
            <div class="header">
                <div class="clinic-name">🦷 DENTALUX</div>
                <div class="clinic-sub">Odontología Familiar</div>
            </div>

            {{-- Monto --}}
            <div class="amount-band">
                <div class="amount-label">Pago recibido</div>
                <div class="amount-value">${{ number_format($payment->amount, 2) }}</div>
                <div class="amount-method">{{ $payment->method_label }}</div>
            </div>

            {{-- Body --}}
            <div class="body">

                <div class="greeting">Estimado/a {{ $patient->first_name }},</div>
                <div class="subtitle">Su pago ha sido registrado. Adjuntamos el comprobante PDF.</div>

                {{-- Datos del pago --}}
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">N° Comprobante</span>
                        <span class="info-value primary">PAY-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Fecha</span>
                        <span class="info-value">{{ $payment->payment_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Paciente</span>
                        <span class="info-value">{{ $patient->full_name }}</span>
                    </div>
                    @if ($payment->reference)
                        <div class="info-row">
                            <span class="info-label">Referencia</span>
                            <span class="info-value">{{ $payment->reference }}</span>
                        </div>
                    @endif
                </div>

                {{-- Resumen --}}
                @php
                    $pct =
                        $paymentControl->treatment_amount > 0
                            ? min(100, round(($paymentControl->total_paid / $paymentControl->treatment_amount) * 100))
                            : 0;
                    $pctColor = $pct >= 100 ? '#059669' : '#C8395A';
                @endphp
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Total tratamiento</span>
                        <span class="info-value">${{ number_format($paymentControl->treatment_amount, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total abonado</span>
                        <span class="info-value green">${{ number_format($paymentControl->total_paid, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Saldo pendiente</span>
                        <span class="info-value {{ $paymentControl->balance <= 0 ? 'green' : 'red' }}">
                            @if ($paymentControl->balance <= 0)
                                ✓ Saldado
                            @else
                                ${{ number_format($paymentControl->balance, 2) }}
                            @endif
                        </span>
                    </div>
                    <div style="padding:10px 14px;">
                        <div
                            style="display:flex;justify-content:space-between;font-size:12px;color:#6B7280;margin-bottom:4px;">
                            <span>Avance</span>
                            <span style="font-weight:700;color:{{ $pctColor }};">{{ $pct }}%</span>
                        </div>
                        <div class="progress-bar-outer">
                            <div class="progress-bar-inner"
                                style="width:{{ $pct }}%;background:{{ $pctColor }};"></div>
                        </div>
                    </div>
                </div>

                {{-- PDF adjunto --}}
                <div class="pdf-note">
                    <div class="pdf-icon">📄</div>
                    <div>
                        <div style="font-weight:600;margin-bottom:1px;">Comprobante PDF adjunto</div>
                        <div style="font-size:12px;opacity:0.8;">Guárdelo como respaldo de su pago.</div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="footer">
                <div class="footer-clinic">Dentalux Odontología Familiar</div>
                <div class="footer-info">Cuenca, Ecuador · Tel: (07) XXX-XXXX</div>
            </div>

        </div>
    </div>
</body>

</html>
