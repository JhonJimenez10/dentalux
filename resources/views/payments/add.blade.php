@extends('layouts.app')
@section('title', 'Registrar Pago')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => $patient->full_name, 'url' => route('patients.show', $patient)],
            ['label' => 'Pagos', 'url' => route('patients.payments.show', [$patient, $paymentControl])],
            ['label' => 'Nuevo Pago', 'url' => '#'],
        ];
        $bankAccounts = \App\Models\BankAccount::active()->get();
        $balance = $paymentControl->balance;
    @endphp

    <div class="page-header">
        <div>
            <h1>Registrar Pago</h1>
            <p>{{ $patient->full_name }}</p>
        </div>
        <a href="{{ route('patients.payments.show', [$patient, $paymentControl]) }}" class="btn btn-outline">Cancelar</a>
    </div>

    {{-- Alerta si ya está saldado --}}
    @if ($balance <= 0)
        <div
            style="background:#D1FAE5;border:1px solid #A7F3D0;border-radius:var(--radius);
                padding:16px 20px;margin-bottom:16px;display:flex;align-items:center;
                justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div style="font-size:14px;font-weight:600;color:#065F46;">
                ✅ Este tratamiento ya está completamente saldado. No se pueden registrar más pagos.
            </div>
            <a href="{{ route('patients.payments.show', [$patient, $paymentControl]) }}"
                class="btn btn-outline btn-sm">Volver</a>
        </div>
    @endif

    {{-- Resumen rápido --}}
    <div
        style="background:var(--text);border-radius:var(--radius);padding:14px 16px;
                margin-bottom:16px;display:grid;grid-template-columns:repeat(3,1fr);
                text-align:center;">
        <div style="border-right:1px solid rgba(255,255,255,0.1);padding:0 8px;">
            <div
                style="font-size:9px;color:rgba(255,255,255,0.4);text-transform:uppercase;
                        letter-spacing:0.5px;margin-bottom:3px;">
                Total</div>
            <div style="font-size:15px;font-weight:700;color:#fff;">
                ${{ number_format($paymentControl->treatment_amount, 2) }}
            </div>
        </div>
        <div style="border-right:1px solid rgba(255,255,255,0.1);padding:0 8px;">
            <div
                style="font-size:9px;color:rgba(255,255,255,0.4);text-transform:uppercase;
                        letter-spacing:0.5px;margin-bottom:3px;">
                Abonado</div>
            <div style="font-size:15px;font-weight:700;color:#6EE7B7;">
                ${{ number_format($paymentControl->total_paid, 2) }}
            </div>
        </div>
        <div style="padding:0 8px;">
            <div
                style="font-size:9px;color:rgba(255,255,255,0.4);text-transform:uppercase;
                        letter-spacing:0.5px;margin-bottom:3px;">
                Saldo</div>
            <div
                style="font-size:15px;font-weight:700;
                        color:{{ $balance <= 0 ? '#6EE7B7' : '#FCA5A5' }};">
                ${{ number_format($balance, 2) }}
            </div>
        </div>
    </div>

    @if ($balance > 0)
        <form method="POST" action="{{ route('patients.payments.store_payment', [$patient, $paymentControl]) }}"
            id="payment-form">
            @csrf

            <div class="payment-add-grid">

                {{-- ── Columna izquierda: datos ── --}}
                <div class="payment-data-col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Datos del Pago</h3>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="font-size:11px;color:var(--text-muted);">Saldo:</span>
                                <span style="font-size:13px;font-weight:700;color:var(--danger);">
                                    ${{ number_format($balance, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-grid cols-2">
                                <div class="form-group">
                                    <label class="form-label">Fecha <span class="required">*</span></label>
                                    <input type="date" name="payment_date" class="form-control"
                                        value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Método de Pago <span class="required">*</span></label>
                                    <select name="payment_method" class="form-control" id="payment-method"
                                        onchange="handleMethodChange(this)" required>
                                        <option value="efectivo"
                                            {{ old('payment_method', 'efectivo') == 'efectivo' ? 'selected' : '' }}>💵
                                            Efectivo</option>
                                        <option value="transferencia"
                                            {{ old('payment_method') == 'transferencia' ? 'selected' : '' }}>🏦
                                            Transferencia bancaria</option>
                                        <option value="tarjeta_credito"
                                            {{ old('payment_method') == 'tarjeta_credito' ? 'selected' : '' }}>💳
                                            Tarjeta de Crédito</option>
                                        <option value="tarjeta_debito"
                                            {{ old('payment_method') == 'tarjeta_debito' ? 'selected' : '' }}>💳
                                            Tarjeta de Débito</option>
                                        <option value="cheque"
                                            {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>📄 Cheque
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- Monto --}}
                            <div class="form-group">
                                <label class="form-label">Monto del Abono <span class="required">*</span></label>
                                <div style="position:relative;">
                                    <span
                                        style="position:absolute;left:14px;top:50%;
                                             transform:translateY(-50%);font-weight:700;
                                             font-size:17px;color:var(--text-muted);">$</span>
                                    <input type="number" name="amount" id="amount-input"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        style="padding-left:30px;font-size:22px;font-weight:700;height:52px;" step="0.01"
                                        min="0.01" max="{{ $balance }}" value="{{ old('amount') }}"
                                        placeholder="0.00" oninput="updateBalancePreview(this.value)"
                                        onblur="clampAmount(this)" required>
                                </div>
                                @error('amount')
                                    <div style="color:var(--danger);font-size:12px;margin-top:4px;">
                                        {{ $message }}
                                    </div>
                                @enderror

                                {{-- Alerta dinámica de monto --}}
                                <div id="amount-alert"
                                    style="display:none;margin-top:6px;padding:8px 12px;
                                 border-radius:var(--radius-sm);font-size:12px;font-weight:600;">
                                </div>

                                <div class="form-hint">
                                    Máximo: <strong>${{ number_format($balance, 2) }}</strong>
                                    · Saldo pendiente del tratamiento
                                </div>
                            </div>

                            {{-- Cuentas bancarias (transferencia) --}}
                            <div id="transfer-accounts" style="display:none;margin-bottom:16px;">
                                @if ($bankAccounts->count())
                                    <div
                                        style="background:var(--primary-bg);border:1px solid var(--primary-light);
                                        border-radius:var(--radius-sm);padding:12px 14px;">
                                        <div
                                            style="font-size:11px;font-weight:700;color:var(--primary);
                                            text-transform:uppercase;letter-spacing:0.4px;margin-bottom:10px;">
                                            Selecciona la cuenta destino
                                        </div>
                                        @foreach ($bankAccounts->groupBy('owner_name') as $owner => $accs)
                                            <div style="margin-bottom:10px;">
                                                <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                                                    <div
                                                        style="width:22px;height:22px;border-radius:50%;
                                                    background:var(--primary);color:#fff;
                                                    display:flex;align-items:center;justify-content:center;
                                                    font-size:9px;font-weight:700;flex-shrink:0;">
                                                        {{ strtoupper(substr($owner, 0, 2)) }}
                                                    </div>
                                                    <span style="font-size:12px;font-weight:600;color:var(--text-muted);">
                                                        {{ $owner }}
                                                    </span>
                                                </div>
                                                @foreach ($accs as $acc)
                                                    <div class="bank-card" data-bank="{{ $acc->bank_name }}"
                                                        data-number="{{ $acc->account_number }}"
                                                        data-owner="{{ $acc->owner_name }}"
                                                        data-type="{{ $acc->account_type }}" onclick="selectAccount(this)"
                                                        style="background:var(--surface);border:1.5px solid var(--border);
                                                border-radius:var(--radius-sm);padding:10px 12px;
                                                margin-bottom:6px;cursor:pointer;transition:all 0.15s;">
                                                        <div
                                                            style="display:flex;align-items:center;
                                                    justify-content:space-between;gap:8px;">
                                                            <div style="min-width:0;flex:1;">
                                                                <div
                                                                    style="font-weight:600;font-size:13px;
                                                            display:flex;align-items:center;gap:6px;">
                                                                    <span>🏦</span> {{ $acc->bank_name }}
                                                                </div>
                                                                <div
                                                                    style="font-size:11px;color:var(--text-muted);margin-top:2px;">
                                                                    {{ $acc->account_type }}
                                                                </div>
                                                            </div>
                                                            <div style="text-align:right;flex-shrink:0;">
                                                                <div
                                                                    style="font-family:monospace;font-weight:700;
                                                            font-size:13px;color:var(--primary);letter-spacing:0.5px;">
                                                                    {{ $acc->account_number }}
                                                                </div>
                                                                <div
                                                                    style="font-size:10px;color:var(--text-muted);margin-top:1px;">
                                                                    ✓ Toca para seleccionar
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        style="background:#FEF3C7;border:1px solid #D97706;
                                        border-radius:var(--radius-sm);padding:12px 14px;
                                        font-size:13px;color:#92400E;">
                                        ⚠️ No hay cuentas bancarias configuradas.
                                        <a href="{{ route('bank-accounts.index') }}"
                                            style="color:var(--primary);font-weight:600;">Configurar cuentas</a>
                                    </div>
                                @endif
                            </div>

                            {{-- Referencia --}}
                            <div class="form-group" id="reference-group" style="display:none;">
                                <label class="form-label">Número de Referencia / Comprobante</label>
                                <input type="text" name="reference" id="reference-input" class="form-control"
                                    value="{{ old('reference') }}" placeholder="Ej: N° transferencia, voucher, cheque…">
                                <div class="form-hint" id="reference-hint">
                                    Ingresa el número de comprobante para respaldo.
                                </div>
                            </div>

                            {{-- Info tarjeta --}}
                            <div id="card-info" style="display:none;margin-bottom:16px;">
                                <div
                                    style="background:#EDE9FE;border:1px solid #7C3AED33;
                                        border-radius:var(--radius-sm);padding:12px 14px;">
                                    <div style="font-size:12px;font-weight:700;color:#4C1D95;margin-bottom:6px;">
                                        💳 Pago con tarjeta
                                    </div>
                                    <div style="font-size:12px;color:#6D28D9;line-height:1.6;">
                                        Pasa la tarjeta por el POS del consultorio.
                                        Anota el número de voucher en el campo de referencia.
                                    </div>
                                </div>
                            </div>

                            {{-- Notas --}}
                            <div class="form-group">
                                <label class="form-label">Notas del Pago</label>
                                <textarea name="notes" class="form-control" rows="2"
                                    placeholder="Ej: Abono inicial, cuota mensual, saldo total…">{{ old('notes') }}</textarea>
                            </div>

                            {{-- Preview totales --}}
                            <div
                                style="background:var(--bg);border-radius:var(--radius-sm);
                                    border:1px solid var(--border);overflow:hidden;">
                                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;text-align:center;">
                                    <div style="padding:11px 8px;border-right:1px solid var(--border);">
                                        <div
                                            style="font-size:9px;color:var(--text-muted);text-transform:uppercase;
                                                letter-spacing:0.4px;margin-bottom:3px;">
                                            Total</div>
                                        <div style="font-size:14px;font-weight:700;">
                                            ${{ number_format($paymentControl->treatment_amount, 2) }}
                                        </div>
                                    </div>
                                    <div style="padding:11px 8px;border-right:1px solid var(--border);">
                                        <div
                                            style="font-size:9px;color:var(--text-muted);text-transform:uppercase;
                                                letter-spacing:0.4px;margin-bottom:3px;">
                                            Este Abono</div>
                                        <div id="preview-amount"
                                            style="font-size:14px;font-weight:700;color:var(--success);">
                                            $0.00
                                        </div>
                                    </div>
                                    <div style="padding:11px 8px;">
                                        <div
                                            style="font-size:9px;color:var(--text-muted);text-transform:uppercase;
                                                letter-spacing:0.4px;margin-bottom:3px;">
                                            Nuevo Saldo</div>
                                        <div id="preview-balance"
                                            style="font-size:14px;font-weight:700;color:var(--danger);">
                                            ${{ number_format($balance, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ── Columna derecha: firma ── --}}
                <div class="payment-sig-col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Firma del Paciente</h3>
                            <span
                                style="font-size:11px;color:var(--text-muted);background:var(--bg);
                                     padding:3px 8px;border-radius:100px;">Por
                                este pago</span>
                        </div>
                        <div class="card-body" style="padding:14px;">

                            <div id="method-info"
                                style="padding:10px 12px;margin-bottom:12px;font-size:13px;
                                    border-radius:var(--radius-sm);border-left:3px solid var(--primary);
                                    background:var(--primary-bg);color:var(--primary);">
                                Confirmar abono de
                                <strong id="sig-amount-label">$0.00</strong>
                                en <strong id="sig-method-label">efectivo</strong>
                            </div>

                            <div id="sig-container"
                                style="border:2px dashed var(--border);border-radius:var(--radius-sm);
                                    background:#FAFAFA;cursor:crosshair;transition:border-color 0.2s;
                                    position:relative;">
                                <canvas id="sig-canvas"
                                    style="display:block;width:100%;height:180px;
                                           touch-action:none;border-radius:var(--radius-sm);">
                                </canvas>
                            </div>

                            <div style="display:flex;align-items:center;gap:8px;margin-top:8px;margin-bottom:16px;">
                                <button type="button" class="btn btn-ghost btn-sm" onclick="clearSignature()">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        style="width:13px;height:13px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Limpiar firma
                                </button>
                                <span id="sig-status" style="font-size:12px;color:var(--text-muted);margin-left:auto;">
                                    Esperando firma…
                                </span>
                            </div>

                            <input type="hidden" name="patient_signature" id="sig-input">

                            <button type="submit" id="submit-btn" class="btn btn-primary w-full"
                                style="justify-content:center;padding:14px;font-size:15px;"
                                onclick="return validateAndSubmit()">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Confirmar y Guardar Pago
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </form>
    @endif

@endsection

@push('styles')
    <style>
        .payment-add-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 768px) {
            .payment-add-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 0 !important;
            }

            .payment-data-col {
                order: 1;
                width: 100%;
            }

            .payment-sig-col {
                order: 2;
                width: 100%;
                margin-top: 16px;
            }
        }

        .bank-card.selected {
            border-color: var(--primary) !important;
            background: var(--primary-bg) !important;
        }

        .bank-card:hover {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        #sig-container:hover {
            border-color: var(--primary);
        }

        #sig-container.has-signature {
            border-color: var(--success);
            border-style: solid;
        }

        #amount-input.error {
            border-color: var(--danger) !important;
            box-shadow: 0 0 0 3px #FEE2E2 !important;
        }

        #amount-input.ok {
            border-color: var(--success) !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        const MAX_BALANCE = {{ $balance }};

        const METHOD_LABELS = {
            'efectivo': 'efectivo',
            'transferencia': 'transferencia bancaria',
            'tarjeta_credito': 'tarjeta de crédito',
            'tarjeta_debito': 'tarjeta de débito',
            'cheque': 'cheque',
        };

        // ── Validar y clampear monto al perder el foco ────────────────
        function clampAmount(input) {
            let val = parseFloat(input.value) || 0;

            if (val <= 0) {
                input.value = '';
                showAmountAlert('Ingresa un monto mayor a $0.00', 'error');
                input.classList.add('error');
                input.classList.remove('ok');
                return;
            }

            // Si supera el saldo, corregir automáticamente al máximo
            if (val > MAX_BALANCE) {
                input.value = MAX_BALANCE.toFixed(2);
                val = MAX_BALANCE;
                showAmountAlert(
                    '⚠️ El monto fue ajustado al saldo máximo: $' + MAX_BALANCE.toFixed(2),
                    'warning'
                );
            } else {
                hideAmountAlert();
            }

            input.classList.remove('error');
            input.classList.add('ok');
            updateBalancePreview(input.value);
        }

        // ── Preview en tiempo real ────────────────────────────────────
        function updateBalancePreview(value) {
            const amount = Math.min(parseFloat(value) || 0, MAX_BALANCE);
            const newBal = Math.max(0, MAX_BALANCE - amount);

            document.getElementById('preview-amount').textContent = '$' + amount.toFixed(2);
            document.getElementById('preview-balance').textContent = '$' + newBal.toFixed(2);
            document.getElementById('sig-amount-label').textContent = '$' + amount.toFixed(2);

            document.getElementById('preview-balance').style.color =
                newBal <= 0 ? 'var(--success)' : 'var(--danger)';
        }

        // ── Alertas de monto ──────────────────────────────────────────
        function showAmountAlert(msg, type) {
            const el = document.getElementById('amount-alert');
            el.textContent = msg;
            el.style.display = 'block';
            if (type === 'error') {
                el.style.background = '#FEE2E2';
                el.style.color = '#991B1B';
                el.style.border = '1px solid #FECACA';
            } else {
                el.style.background = '#FEF3C7';
                el.style.color = '#92400E';
                el.style.border = '1px solid #FDE68A';
            }
        }

        function hideAmountAlert() {
            document.getElementById('amount-alert').style.display = 'none';
        }

        // ── Validación al enviar ──────────────────────────────────────
        function validateAndSubmit() {
            const input = document.getElementById('amount-input');
            const val = parseFloat(input.value) || 0;

            if (val <= 0) {
                showAmountAlert('❌ El monto debe ser mayor a $0.00', 'error');
                input.classList.add('error');
                input.focus();
                return false;
            }

            if (val > MAX_BALANCE) {
                showAmountAlert('❌ El monto no puede superar el saldo: $' + MAX_BALANCE.toFixed(2), 'error');
                input.classList.add('error');
                input.value = MAX_BALANCE.toFixed(2);
                updateBalancePreview(MAX_BALANCE);
                input.focus();
                return false;
            }

            // Guardar firma si la hay
            const sigInput = document.getElementById('sig-input');
            if (!sigInput.value && window._sigPad && !window._sigPad.isEmpty()) {
                sigInput.value = window._sigPad.toDataURL('image/png');
            }

            return true;
        }

        // ── Cambio de método ──────────────────────────────────────────
        function handleMethodChange(select) {
            const method = select.value;
            const refGroup = document.getElementById('reference-group');
            const refInput = document.getElementById('reference-input');
            const transferBox = document.getElementById('transfer-accounts');
            const cardInfo = document.getElementById('card-info');
            const methodLabel = document.getElementById('sig-method-label');

            methodLabel.textContent = METHOD_LABELS[method] || method;
            transferBox.style.display = method === 'transferencia' ? 'block' : 'none';
            cardInfo.style.display = (method === 'tarjeta_credito' || method === 'tarjeta_debito') ? 'block' : 'none';

            const needsRef = ['transferencia', 'tarjeta_credito', 'tarjeta_debito', 'cheque'];
            refGroup.style.display = needsRef.includes(method) ? 'block' : 'none';

            const hints = {
                'transferencia': 'Número de comprobante de transferencia',
                'tarjeta_credito': 'Número de voucher / autorización',
                'tarjeta_debito': 'Número de voucher / autorización',
                'cheque': 'Número de cheque',
            };
            if (refInput && hints[method]) refInput.placeholder = hints[method];

            if (method !== 'transferencia') {
                document.querySelectorAll('.bank-card.selected').forEach(c => c.classList.remove('selected'));
            }
        }

        // ── Seleccionar cuenta bancaria ───────────────────────────────
        function selectAccount(card) {
            document.querySelectorAll('.bank-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            const ref = document.getElementById('reference-input');
            if (ref) {
                ref.value = card.dataset.bank + ' · ' + card.dataset.type +
                    ' · ' + card.dataset.number + ' · ' + card.dataset.owner;
            }
            document.getElementById('reference-group').style.display = 'block';
        }

        // ── Signature Pad ─────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('sig-canvas');
            const container = document.getElementById('sig-container');
            if (!canvas) return;

            const sigPad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255,255,255,0)',
                penColor: '#1A1A2E',
                minWidth: 1,
                maxWidth: 2.5,
            });
            window._sigPad = sigPad;

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const w = container.offsetWidth;
                canvas.width = w * ratio;
                canvas.height = 180 * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                sigPad.clear();
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);

            sigPad.addEventListener('endStroke', () => {
                document.getElementById('sig-status').textContent = '✓ Firma capturada';
                document.getElementById('sig-status').style.color = 'var(--success)';
                document.getElementById('sig-input').value = sigPad.toDataURL('image/png');
                container.classList.add('has-signature');
            });

            window.clearSignature = function() {
                sigPad.clear();
                document.getElementById('sig-input').value = '';
                document.getElementById('sig-status').textContent = 'Esperando firma…';
                document.getElementById('sig-status').style.color = 'var(--text-muted)';
                container.classList.remove('has-signature');
            };

            // Inicializar método al cargar
            const methodSelect = document.getElementById('payment-method');
            if (methodSelect) handleMethodChange(methodSelect);

            // Pre-rellenar si hay old value
            @if (old('amount'))
                const amountInput = document.getElementById('amount-input');
                if (amountInput) updateBalancePreview(amountInput.value);
            @endif
        });
    </script>
@endpush
