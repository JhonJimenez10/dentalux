<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher de Pago — {{ $patient->full_name }}</title>
    <style>
        /* ══════════════════════════════════════
   TICKET TÉRMICO 80mm
══════════════════════════════════════ */
        @page {
            size: 80mm auto;
            margin: 4mm 4mm 8mm 4mm;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            color: #000;
            background: #fff;
            width: 72mm;
            margin: 0 auto;
        }

        /* ─── Encabezado ─── */
        .header {
            text-align: center;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .logo-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: #1A1A2E;
            border-radius: 8px;
            margin-bottom: 4px;
        }

        .clinic-name {
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .clinic-sub {
            font-family: Arial, sans-serif;
            font-size: 9px;
            color: #444;
            margin-top: 1px;
        }

        .clinic-info {
            font-size: 8px;
            color: #555;
            margin-top: 3px;
            line-height: 1.4;
        }

        /* ─── Separadores ─── */
        .dashed {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .solid {
            border: none;
            border-top: 1px solid #000;
            margin: 6px 0;
        }

        .double {
            border: none;
            border-top: 3px double #000;
            margin: 6px 0;
        }

        /* ─── Título del voucher ─── */
        .voucher-title {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 3px 0;
        }

        .voucher-num {
            text-align: center;
            font-size: 9px;
            color: #555;
            margin-bottom: 4px;
        }

        /* ─── Secciones de datos ─── */
        .section-title {
            font-family: Arial, sans-serif;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555;
            margin-bottom: 3px;
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2px;
            line-height: 1.4;
        }

        .data-label {
            font-size: 9px;
            color: #333;
            flex-shrink: 0;
            margin-right: 4px;
        }

        .data-value {
            font-size: 9px;
            font-weight: bold;
            text-align: right;
            word-break: break-word;
        }

        .data-full {
            font-size: 9px;
            margin-bottom: 2px;
            line-height: 1.4;
        }

        .data-full .label {
            color: #333;
        }

        .data-full .value {
            font-weight: bold;
        }

        /* ─── Monto del abono ─── */
        .amount-box {
            text-align: center;
            padding: 6px 4px;
            margin: 6px 0;
            border: 2px solid #000;
            border-radius: 4px;
        }

        .amount-label {
            font-family: Arial, sans-serif;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #333;
            margin-bottom: 2px;
        }

        .amount-value {
            font-family: Arial, sans-serif;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* ─── Barra de progreso ─── */
        .progress-wrap {
            margin: 5px 0;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 8px;
            margin-bottom: 2px;
        }

        .progress-bar-outer {
            width: 100%;
            height: 6px;
            background: #ddd;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar-inner {
            height: 100%;
            background: #000;
            border-radius: 3px;
        }

        /* ─── Tabla de pagos ─── */
        .payments-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-top: 4px;
        }

        .payments-table th {
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding: 2px 1px;
        }

        .payments-table th:last-child,
        .payments-table td:last-child {
            text-align: right;
        }

        .payments-table td {
            padding: 2px 1px;
            border-bottom: 1px dashed #ccc;
            vertical-align: top;
        }

        .payments-table tr.current-row td {
            font-weight: bold;
            background: #f0f0f0;
        }

        .payments-table tfoot td {
            border-top: 1px solid #000;
            border-bottom: none;
            font-weight: bold;
            padding-top: 3px;
        }

        /* ─── Método de pago badge ─── */
        .method-badge {
            display: inline-block;
            border: 1px solid #000;
            border-radius: 3px;
            padding: 1px 5px;
            font-family: Arial, sans-serif;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ─── Transferencia info ─── */
        .transfer-box {
            background: #f5f5f5;
            border: 1px dashed #000;
            border-radius: 3px;
            padding: 5px 7px;
            margin: 4px 0;
            font-size: 8px;
            line-height: 1.5;
        }

        /* ─── Firma ─── */
        .sig-area {
            margin-top: 10px;
        }

        .sig-image {
            max-width: 100%;
            max-height: 40px;
            display: block;
            margin: 0 auto;
        }

        .sig-line {
            border-top: 1px solid #000;
            margin-bottom: 2px;
        }

        .sig-label {
            font-size: 8px;
            text-align: center;
            color: #444;
        }

        .sig-name {
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1px;
        }

        /* ─── Firmas en columnas ─── */
        .sig-cols {
            display: flex;
            justify-content: space-between;
            margin-top: 14px;
            gap: 8px;
        }

        .sig-col {
            flex: 1;
            text-align: center;
        }

        /* ─── Pie ─── */
        .footer {
            text-align: center;
            font-size: 8px;
            color: #555;
            line-height: 1.5;
            margin-top: 8px;
        }

        .footer .thanks {
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: bold;
            color: #000;
            margin-bottom: 3px;
        }

        /* ─── QR / código ─── */
        .code-box {
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 8px;
            letter-spacing: 2px;
            margin: 4px 0;
            color: #444;
        }

        /* ─── Copia ─── */
        .copy-tag {
            text-align: right;
            font-size: 8px;
            font-style: italic;
            color: #555;
            margin-bottom: 2px;
        }

        .page-break {
            page-break-after: always;
        }

        /* ─── Impresión ─── */
        @media screen {
            body {
                background: #f0f0f0;
                width: auto;
                padding: 20px;
            }

            .ticket-wrap {
                background: #fff;
                width: 72mm;
                margin: 0 auto;
                padding: 6mm;
                box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
                border-radius: 4px;
            }

            .no-print {
                display: block;
            }

            .print-only {
                display: none;
            }
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .ticket-wrap {
                padding: 0;
                box-shadow: none;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    @php
        $totalPaid = $paymentControl->total_paid;
        $totalAmount = $paymentControl->treatment_amount;
        $balance = $paymentControl->balance;
        $progress = $totalAmount > 0 ? min(100, round(($totalPaid / $totalAmount) * 100)) : 0;

        $copies = 2;
        $copyLabels = ['Original - Paciente', 'Copia - Consultorio'];
    @endphp

    {{-- ══════════════════════════════════════
     CONTROLES DE PANTALLA (no se imprimen)
══════════════════════════════════════ --}}
    <div class="no-print"
        style="background:#1A1A2E;padding:12px 20px;margin-bottom:20px;
            display:flex;align-items:center;justify-content:space-between;
            flex-wrap:wrap;gap:10px;">
        <div style="color:#fff;font-family:Arial,sans-serif;font-size:14px;font-weight:bold;">
            Voucher de Pago — {{ $patient->full_name }}
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('patients.payments.show', [$patient, $paymentControl]) }}"
                style="padding:7px 14px;background:rgba(255,255,255,0.1);color:#fff;
                  border:1px solid rgba(255,255,255,0.2);border-radius:6px;
                  text-decoration:none;font-size:13px;font-family:Arial,sans-serif;">
                ← Volver
            </a>
            <button onclick="window.print()"
                style="padding:7px 18px;background:#C8395A;color:#fff;border:none;
                       border-radius:6px;cursor:pointer;font-size:13px;
                       font-family:Arial,sans-serif;font-weight:bold;">
                🖨️ Imprimir / Guardar PDF
            </button>
            {{-- Dentro del div.no-print, después del botón imprimir --}}
            <button onclick="openSendModal()"
                style="padding:7px 18px;background:#059669;color:#fff;border:none;
               border-radius:6px;cursor:pointer;font-size:13px;
               font-family:Arial,sans-serif;font-weight:bold;">
                ✉️ Enviar por correo
            </button>

            {{-- Modal de envío --}}
            <div id="send-modal"
                style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);
            z-index:9999;align-items:center;justify-content:center;padding:20px;">
                <div
                    style="background:#fff;border-radius:12px;padding:28px;
                max-width:400px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,0.3);">

                    <div
                        style="font-family:Arial,sans-serif;font-size:17px;font-weight:bold;
                     margin-bottom:6px;">
                        Enviar Voucher por Correo
                    </div>
                    <div style="font-size:13px;color:#6B7280;margin-bottom:20px;">
                        Se enviará el comprobante PDF a la dirección indicada.
                    </div>

                    <div style="margin-bottom:16px;">
                        <label
                            style="display:block;font-size:12px;font-weight:700;
                           color:#374151;text-transform:uppercase;
                           letter-spacing:0.4px;margin-bottom:6px;">
                            Correo electrónico
                        </label>
                        <input type="email" id="send-email" value="{{ $patient->email ?? '' }}"
                            placeholder="correo@ejemplo.com"
                            style="width:100%;padding:10px 13px;border:1px solid #D1D5DB;
                          border-radius:8px;font-size:14px;font-family:Arial,sans-serif;
                          outline:none;"
                            onfocus="this.style.borderColor='#C8395A';this.style.boxShadow='0 0 0 3px #F4D0D8'"
                            onblur="this.style.borderColor='#D1D5DB';this.style.boxShadow='none'">
                        <div style="font-size:12px;color:#6B7280;margin-top:4px;">
                            Paciente: <strong>{{ $patient->full_name }}</strong>
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div id="send-status"
                        style="display:none;padding:10px 14px;
             border-radius:8px;font-size:13px;margin-bottom:14px;">
                    </div>

                    <div style="display:flex;gap:10px;justify-content:flex-end;">
                        <button onclick="closeSendModal()"
                            style="padding:9px 18px;background:#F3F4F6;color:#374151;
                           border:none;border-radius:8px;cursor:pointer;
                           font-size:14px;font-family:Arial,sans-serif;">
                            Cancelar
                        </button>
                        <button id="send-btn" onclick="sendVoucher()"
                            style="padding:9px 22px;background:#C8395A;color:#fff;
                           border:none;border-radius:8px;cursor:pointer;
                           font-size:14px;font-weight:bold;
                           font-family:Arial,sans-serif;">
                            ✉️ Enviar
                        </button>
                    </div>
                </div>
            </div>

            <script>
                const SEND_URL = "{{ route('patients.payments.send-voucher', [$patient, $paymentControl, $payment]) }}";
                const CSRF = "{{ csrf_token() }}";

                function openSendModal() {
                    document.getElementById('send-modal').style.display = 'flex';
                    document.getElementById('send-status').style.display = 'none';
                    document.getElementById('send-btn').disabled = false;
                    document.getElementById('send-btn').textContent = '✉️ Enviar';
                }

                function closeSendModal() {
                    document.getElementById('send-modal').style.display = 'none';
                }

                function sendVoucher() {
                    const email = document.getElementById('send-email').value.trim();
                    const btn = document.getElementById('send-btn');
                    const status = document.getElementById('send-status');

                    if (!email || !email.includes('@')) {
                        showStatus('Ingresa un correo válido.', 'error');
                        return;
                    }

                    btn.disabled = true;
                    btn.textContent = 'Enviando…';
                    status.style.display = 'none';

                    fetch(SEND_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                email: email
                            }),
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                showStatus('✓ ' + data.message, 'success');
                                btn.textContent = '✓ Enviado';
                                setTimeout(closeSendModal, 2500);
                            } else {
                                showStatus('✗ ' + data.message, 'error');
                                btn.disabled = false;
                                btn.textContent = '✉️ Reintentar';
                            }
                        })
                        .catch(err => {
                            showStatus('✗ Error de conexión. Intenta de nuevo.', 'error');
                            btn.disabled = false;
                            btn.textContent = '✉️ Reintentar';
                        });
                }

                function showStatus(msg, type) {
                    const el = document.getElementById('send-status');
                    el.textContent = msg;
                    el.style.display = 'block';
                    el.style.background = type === 'success' ? '#D1FAE5' : '#FEE2E2';
                    el.style.color = type === 'success' ? '#065F46' : '#991B1B';
                    el.style.border = '1px solid ' + (type === 'success' ? '#A7F3D0' : '#FECACA');
                }

                // Cerrar modal con ESC o clic fuera
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape') closeSendModal();
                });
                document.getElementById('send-modal').addEventListener('click', e => {
                    if (e.target === document.getElementById('send-modal')) closeSendModal();
                });
            </script>
        </div>
    </div>

    <div class="no-print"
        style="text-align:center;font-family:Arial,sans-serif;font-size:12px;
            color:#666;margin-bottom:16px;">
        Se generan <strong>2 copias</strong>:
        <span style="color:#059669;">Original (paciente)</span> +
        <span style="color:#0284C7;">Copia (consultorio)</span>
    </div>

    {{-- ══════════════════════════════════════
     COPIAS DEL TICKET
══════════════════════════════════════ --}}
    @for ($copy = 0; $copy < $copies; $copy++)

        <div class="ticket-wrap">

            {{-- Etiqueta de copia --}}
            <div class="copy-tag">
                {{ $copyLabels[$copy] ?? 'Copia ' . ($copy + 1) }}
            </div>

            {{-- ── ENCABEZADO ── --}}
            <div class="header">
                <div>
                    <svg width="32" height="32" viewBox="0 0 38 38" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect width="38" height="38" rx="8" fill="#1A1A2E" />
                        <path d="M19 8C14.5 8 11 11.5 11 15.5C11 17.5 11.8 19.3
                         12.5 21L14 27C14.4 28.6 15.8 29.5 17.2 29.5H20.8
                         C22.2 29.5 23.6 28.6 24 27L25.5 21C26.2 19.3 27
                         17.5 27 15.5C27 11.5 23.5 8 19 8Z" fill="white" opacity="0.9" />
                    </svg>
                </div>
                <div class="clinic-name" style="margin-top:3px;">DENTALUX</div>
                <div class="clinic-sub">Odontología Familiar</div>
                <div class="clinic-info">
                    Cuenca, Ecuador<br>
                    Tel: (07) XXX-XXXX · WhatsApp: 09X-XXX-XXXX
                </div>
            </div>

            <div class="double"></div>

            {{-- ── TÍTULO VOUCHER ── --}}
            <div class="voucher-title">COMPROBANTE DE PAGO</div>
            <div class="voucher-num">
                N° {{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
            </div>

            <div class="dashed"></div>

            {{-- ── DATOS DEL PAGO ── --}}
            <div class="section-title">Información del pago</div>

            <div class="data-row">
                <span class="data-label">Fecha:</span>
                <span class="data-value">
                    {{ $payment->payment_date->format('d/m/Y') }}
                </span>
            </div>
            <div class="data-row">
                <span class="data-label">Hora:</span>
                <span class="data-value">
                    {{ $payment->created_at->format('H:i') }}
                </span>
            </div>
            <div class="data-row">
                <span class="data-label">Recibido por:</span>
                <span class="data-value">
                    {{ $payment->registeredBy?->name ?? auth()->user()->name }}
                </span>
            </div>

            <div class="dashed"></div>

            {{-- ── DATOS DEL PACIENTE ── --}}
            <div class="section-title">Paciente</div>

            <div class="data-full">
                <span class="value">{{ strtoupper($patient->full_name) }}</span>
            </div>
            @if ($patient->cedula)
                <div class="data-full">
                    <span class="label">CI/RUC: </span>
                    <span class="value">{{ $patient->cedula }}</span>
                </div>
            @endif
            @if ($patient->phone)
                <div class="data-full">
                    <span class="label">Tel: </span>
                    <span class="value">{{ $patient->phone }}</span>
                </div>
            @endif

            <div class="dashed"></div>

            {{-- ── MONTO DEL ABONO ── --}}
            <div class="amount-box">
                <div class="amount-label">Valor del abono</div>
                <div class="amount-value">${{ number_format($payment->amount, 2) }}</div>
                <div style="font-size:8px;color:#333;margin-top:2px;">
                    {{ strtoupper($payment->method_label) }}
                </div>
            </div>

            {{-- ── MÉTODO DE PAGO ── --}}
            @if ($payment->payment_method === 'transferencia' && $payment->reference)
                <div class="transfer-box">
                    <div style="font-weight:bold;margin-bottom:2px;">🏦 Transferencia bancaria</div>
                    <div>Ref: {{ $payment->reference }}</div>
                </div>
            @elseif(in_array($payment->payment_method, ['tarjeta_credito', 'tarjeta_debito']))
                <div class="transfer-box">
                    <div style="font-weight:bold;margin-bottom:2px;">
                        💳 {{ $payment->method_label }}
                    </div>
                    @if ($payment->reference)
                        <div>Voucher: {{ $payment->reference }}</div>
                    @endif
                </div>
            @endif

            <div class="dashed"></div>

            {{-- ── RESUMEN DEL TRATAMIENTO ── --}}
            <div class="section-title">Estado del tratamiento</div>

            <div class="data-row">
                <span class="data-label">Total tratamiento:</span>
                <span class="data-value">${{ number_format($totalAmount, 2) }}</span>
            </div>
            <div class="data-row">
                <span class="data-label">Total abonado:</span>
                <span class="data-value">${{ number_format($totalPaid, 2) }}</span>
            </div>
            <div class="data-row">
                <span class="data-label">Saldo pendiente:</span>
                <span class="data-value">
                    {{ $balance > 0 ? '$' . number_format($balance, 2) : '✓ SALDADO' }}
                </span>
            </div>

            {{-- Barra de progreso --}}
            <div class="progress-wrap" style="margin-top:5px;">
                <div class="progress-label">
                    <span>Avance del pago</span>
                    <span>{{ $progress }}%</span>
                </div>
                <div class="progress-bar-outer">
                    <div class="progress-bar-inner" style="width:{{ $progress }}%;"></div>
                </div>
            </div>

            <div class="dashed"></div>

            {{-- ── HISTORIAL DE ABONOS ── --}}
            <div class="section-title">Historial de abonos</div>

            <table class="payments-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Método</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentControl->payments->sortBy('payment_date') as $i => $p)
                        <tr class="{{ $p->id === $payment->id ? 'current-row' : '' }}">
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $p->payment_date->format('d/m/y') }}</td>
                            <td>
                                @php
                                    $shortMethod =
                                        [
                                            'efectivo' => 'Efec.',
                                            'transferencia' => 'Trans.',
                                            'tarjeta_credito' => 'T.Cred.',
                                            'tarjeta_debito' => 'T.Deb.',
                                            'cheque' => 'Cheq.',
                                        ][$p->payment_method] ?? $p->payment_method;
                                @endphp
                                {{ $shortMethod }}
                                @if ($p->id === $payment->id)
                                    ◄
                                @endif
                            </td>
                            <td>${{ number_format($p->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">TOTAL ABONADO</td>
                        <td>${{ number_format($totalPaid, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            @if ($payment->notes)
                <div class="dashed"></div>
                <div class="section-title">Notas</div>
                <div style="font-size:9px;line-height:1.4;">{{ $payment->notes }}</div>
            @endif

            <div class="solid"></div>

            {{-- ── FIRMA DEL PACIENTE ── --}}
            @if ($payment->patient_signature)
                <div class="sig-area" style="text-align:center;margin-top:6px;margin-bottom:6px;">
                    <div style="font-size:8px;color:#555;margin-bottom:3px;">
                        Firma del paciente
                    </div>
                    <img src="{{ $payment->patient_signature }}" class="sig-image" alt="Firma">
                </div>
            @else
                <div style="height:28px;"></div>
            @endif

            {{-- ── LÍNEAS DE FIRMA ── --}}
            <div class="sig-cols">
                <div class="sig-col">
                    <div class="sig-line"></div>
                    <div class="sig-label">(f) Odontólogo / Recepcionista</div>
                </div>
                <div class="sig-col">
                    <div class="sig-line"></div>
                    <div class="sig-label">(f) Paciente</div>
                </div>
            </div>

            <div class="solid"></div>

            {{-- ── PIE ── --}}
            <div class="footer">
                <div class="thanks">¡Gracias por su confianza!</div>
                <div>Guarde este comprobante para</div>
                <div>cualquier consulta futura.</div>
                <div style="margin-top:4px;">
                    Cuenca, Ecuador — Tel: (07) XXX-XXXX
                </div>
            </div>

            <div class="dashed"></div>

            {{-- Código único del pago --}}
            <div class="code-box">
                PAY-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}-{{ $date = $payment->payment_date->format('Ymd') }}
            </div>

        </div>{{-- /.ticket-wrap --}}

        {{-- Salto de página entre copias --}}
        @if ($copy < $copies - 1)
            <div class="page-break"></div>
        @endif

    @endfor

    <script>
        // Auto-abrir diálogo de impresión al cargar
        window.addEventListener('load', function() {
            // Pequeño delay para que cargue la firma (imagen base64)
            setTimeout(function() {
                // No auto-imprimir, dejar que el usuario decida
            }, 500);
        });
    </script>

</body>

</html>
