@extends('layouts.app')
@section('title', 'Nuevo Cierre de Caja')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Cierre de Caja', 'url' => route('cash-closing.index')],
            ['label' => 'Nuevo Cierre', 'url' => '#'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Cierre de Caja</h1>
            <p>{{ $date->isoFormat('dddd D [de] MMMM [de] YYYY') }}</p>
        </div>
        <a href="{{ route('cash-closing.index') }}" class="btn btn-outline">Cancelar</a>
    </div>

    <form method="POST" action="{{ route('cash-closing.store') }}" id="closing-form">
        @csrf

        <input type="hidden" name="income_cash" id="h-income-cash" value="{{ $incomeCash }}">
        <input type="hidden" name="income_transfer" id="h-income-transfer" value="{{ $incomeTransfer }}">
        <input type="hidden" name="income_card" id="h-income-card" value="{{ $incomeCard }}">
        <input type="hidden" name="income_other" id="h-income-other" value="{{ $incomeOther }}">
        <input type="hidden" name="total_income" id="h-total-income" value="{{ $totalIncome }}">
        <input type="hidden" name="total_expenses" id="h-total-expenses" value="{{ $totalExpenses }}">
        <input type="hidden" name="expected_cash" id="h-expected-cash" value="{{ $expectedCash }}">
        <input type="hidden" name="closing_date" value="{{ $date->format('Y-m-d') }}">

        <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;" class="cc-create-grid">

            {{-- Columna izquierda --}}
            <div>

                {{-- Resumen automático del día --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span style="display:inline-flex;align-items:center;gap:8px;">
                                <span
                                    style="width:24px;height:24px;background:#D1FAE5;border-radius:6px;
                                     display:inline-flex;align-items:center;justify-content:center;">
                                    <svg width="13" height="13" fill="none" stroke="#059669" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                </span>
                                Ingresos del Día
                            </span>
                        </h3>
                        <span class="badge badge-success" style="font-size:14px;padding:5px 12px;">
                            ${{ number_format($totalIncome, 2) }}
                        </span>
                    </div>
                    <div style="padding:0;">
                        @foreach ([['Efectivo', $incomeCash, '#059669'], ['Transferencia', $incomeTransfer, '#0284C7'], ['Tarjeta', $incomeCard, '#7C3AED'], ['Otros (cheque)', $incomeOther, '#D97706']] as $row)
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
                                <span style="font-weight:700;font-size:15px;color:{{ $row[2] }};">
                                    ${{ number_format($row[1], 2) }}
                                </span>
                            </div>
                        @endforeach
                        <div
                            style="display:flex;justify-content:space-between;padding:14px 18px;
                             background:var(--bg);">
                            <span style="font-weight:700;">TOTAL INGRESOS</span>
                            <span style="font-weight:700;font-size:16px;color:var(--success);">
                                ${{ number_format($totalIncome, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Egresos del día --}}
                <div class="card" style="margin-bottom:16px;">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span style="display:inline-flex;align-items:center;gap:8px;">
                                <span
                                    style="width:24px;height:24px;background:#FEE2E2;border-radius:6px;
                                     display:inline-flex;align-items:center;justify-content:center;">
                                    <svg width="13" height="13" fill="none" stroke="#DC2626" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                Egresos del Día
                            </span>
                        </h3>
                        <span class="badge badge-danger" style="font-size:14px;padding:5px 12px;">
                            ${{ number_format($totalExpenses, 2) }}
                        </span>
                    </div>
                    @if ($expenses->isEmpty())
                        <div style="padding:20px;text-align:center;color:var(--text-muted);font-size:13px;">
                            Sin egresos registrados hoy.
                            <a href="{{ route('finance.expenses.create') }}" style="color:var(--primary);">
                                Registrar egreso
                            </a>
                        </div>
                    @else
                        <div style="padding:0;">
                            @foreach ($expenses as $exp)
                                <div
                                    style="display:flex;justify-content:space-between;align-items:center;
                             padding:11px 18px;border-bottom:1px solid #F9FAFB;">
                                    <div>
                                        <div style="font-size:13px;font-weight:500;">{{ $exp->description }}</div>
                                        <div style="font-size:11px;color:var(--text-muted);">
                                            {{ $exp->category_label }} · {{ $exp->method_label }}
                                        </div>
                                    </div>
                                    <span style="font-weight:700;color:var(--danger);">
                                        ${{ number_format($exp->amount, 2) }}
                                    </span>
                                </div>
                            @endforeach
                            <div
                                style="display:flex;justify-content:space-between;padding:12px 18px;
                             background:var(--bg);">
                                <span style="font-weight:700;">TOTAL EGRESOS</span>
                                <span style="font-weight:700;font-size:16px;color:var(--danger);">
                                    ${{ number_format($totalExpenses, 2) }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Detalle de ingresos --}}
                @if ($payments->isNotEmpty())
                    <div class="card" style="margin-bottom:16px;">
                        <div class="card-header">
                            <h3 class="card-title">Detalle de Pagos Recibidos</h3>
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

            </div>

            {{-- Columna derecha --}}
            <div>

                {{-- Cálculo de caja --}}
                <div class="card" style="margin-bottom:14px;">
                    <div class="card-header">
                        <h3 class="card-title">Arqueo de Caja</h3>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label class="form-label">
                                Saldo Inicial (apertura)
                            </label>
                            <div style="position:relative;">
                                <span
                                    style="position:absolute;left:12px;top:50%;
                                     transform:translateY(-50%);font-weight:700;
                                     color:var(--text-muted);">$</span>
                                <input type="number" name="opening_balance" id="opening-balance" class="form-control"
                                    style="padding-left:26px;" step="0.01" min="0"
                                    value="{{ $openingBalance }}" oninput="recalculate()" required>
                            </div>
                            <div class="form-hint">
                                Saldo del cierre anterior.
                            </div>
                        </div>

                        {{-- Resumen sistema --}}
                        <div
                            style="background:var(--bg);border-radius:var(--radius-sm);
                             padding:12px 14px;margin-bottom:14px;
                             border:1px solid var(--border);">
                            <div
                                style="font-size:11px;font-weight:700;color:var(--text-muted);
                                 text-transform:uppercase;letter-spacing:0.4px;
                                 margin-bottom:10px;">
                                Según el sistema</div>
                            <div
                                style="display:flex;justify-content:space-between;
                                 font-size:13px;margin-bottom:6px;">
                                <span>+ Ingresos efectivo</span>
                                <span style="color:var(--success);font-weight:600;">
                                    ${{ number_format($incomeCash, 2) }}
                                </span>
                            </div>
                            <div
                                style="display:flex;justify-content:space-between;
                                 font-size:13px;margin-bottom:6px;">
                                <span>- Egresos en efectivo</span>
                                <span style="color:var(--danger);font-weight:600;">
                                    ${{ number_format($expensesCash, 2) }}
                                </span>
                            </div>
                            <div
                                style="border-top:1px solid var(--border);padding-top:8px;
                                 margin-top:4px;display:flex;justify-content:space-between;
                                 font-weight:700;">
                                <span>Efectivo esperado</span>
                                <span id="display-expected" style="color:var(--primary);">
                                    ${{ number_format($expectedCash, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- Efectivo real --}}
                        <div class="form-group">
                            <label class="form-label">
                                Efectivo Real en Caja
                                <span class="required">*</span>
                            </label>
                            <div style="position:relative;">
                                <span
                                    style="position:absolute;left:12px;top:50%;
                                     transform:translateY(-50%);font-weight:700;
                                     font-size:18px;color:var(--text-muted);">$</span>
                                <input type="number" name="actual_cash" id="actual-cash" class="form-control"
                                    style="padding-left:28px;font-size:20px;font-weight:700;
                                      height:52px;"
                                    step="0.01" min="0" value="0" placeholder="0.00"
                                    oninput="recalculate()" required>
                            </div>
                            <div class="form-hint">
                                Cuenta el dinero físico en la caja y escribe el total.
                            </div>
                        </div>

                        {{-- Diferencia --}}
                        <div style="border-radius:var(--radius-sm);padding:14px;
                             margin-bottom:14px;text-align:center;
                             border:2px solid var(--border);"
                            id="diff-box">
                            <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px;">
                                Diferencia
                            </div>
                            <div id="diff-display"
                                style="font-size:24px;font-weight:700;
                                 font-family:'Plus Jakarta Sans',sans-serif;">
                                $0.00
                            </div>
                            <div id="diff-label" style="font-size:12px;margin-top:4px;">
                                —
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Notas del cierre</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Observaciones, sobrante, faltante…">{{ old('notes') }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Botones --}}
                <div style="display:flex;flex-direction:column;gap:8px;">
                    {{-- Botón cerrar caja — abre modal de confirmación --}}
                    <button type="button" onclick="openConfirmClose()" class="btn btn-primary w-full"
                        style="justify-content:center;padding:14px;font-size:15px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Cerrar Caja del Día
                    </button>
                    <button type="submit" name="action" value="save" class="btn btn-outline w-full"
                        style="justify-content:center;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Guardar como Borrador
                    </button>
                </div>

                {{-- Modal de confirmación de cierre --}}
                <div id="confirm-modal"
                    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);
                            z-index:9999;align-items:center;justify-content:center;padding:20px;">
                    <div
                        style="background:#fff;border-radius:12px;padding:28px;max-width:420px;
                                width:100%;box-shadow:0 20px 60px rgba(0,0,0,0.3);">

                        {{-- Ícono --}}
                        <div style="text-align:center;margin-bottom:16px;">
                            <div
                                style="width:56px;height:56px;border-radius:50%;
                                         background:#FEF3C7;display:flex;align-items:center;
                                         justify-content:center;margin:0 auto 12px;">
                                <svg width="28" height="28" fill="none" stroke="#D97706" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 style="font-size:18px;font-weight:700;margin-bottom:6px;">
                                ¿Cerrar la caja definitivamente?
                            </h3>
                            <p style="font-size:13px;color:#6B7280;">
                                Esta acción <strong>no se puede deshacer</strong>.
                                El cierre quedará bloqueado permanentemente.
                            </p>
                        </div>

                        {{-- Resumen --}}
                        <div
                            style="background:#F9FAFB;border-radius:8px;padding:14px;
                                     margin-bottom:16px;border:1px solid #E5E7EB;">
                            <div
                                style="font-size:11px;font-weight:700;color:#6B7280;
                                         text-transform:uppercase;letter-spacing:0.4px;
                                         margin-bottom:8px;">
                                Resumen del cierre</div>
                            <div
                                style="display:flex;justify-content:space-between;
                                         font-size:13px;margin-bottom:5px;">
                                <span style="color:#6B7280;">Efectivo esperado</span>
                                <span style="font-weight:600;" id="modal-expected">$0.00</span>
                            </div>
                            <div
                                style="display:flex;justify-content:space-between;
                                         font-size:13px;margin-bottom:5px;">
                                <span style="color:#6B7280;">Efectivo real contado</span>
                                <span style="font-weight:600;" id="modal-actual">$0.00</span>
                            </div>
                            <div
                                style="border-top:1px solid #E5E7EB;padding-top:8px;
                                         margin-top:4px;display:flex;
                                         justify-content:space-between;font-size:15px;">
                                <span style="font-weight:700;">Diferencia</span>
                                <span style="font-weight:700;font-size:17px;" id="modal-diff">$0.00</span>
                            </div>
                            <div id="modal-diff-label"
                                style="text-align:center;margin-top:8px;padding:6px 12px;
                                        border-radius:100px;font-size:12px;font-weight:700;">
                            </div>
                        </div>

                        {{-- Advertencia no editable --}}
                        <div
                            style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;
                                     padding:10px 14px;margin-bottom:16px;
                                     font-size:12px;color:#991B1B;">
                            🔒 Una vez cerrada, la caja <strong>no se podrá reabrir ni editar</strong>.
                            Verifica que los datos sean correctos antes de continuar.
                        </div>

                        {{-- Botones del modal --}}
                        <div style="display:flex;gap:10px;">
                            <button type="button" onclick="closeConfirmModal()"
                                style="flex:1;padding:11px;background:#F3F4F6;
                                           color:#374151;border:none;border-radius:8px;
                                           cursor:pointer;font-size:14px;font-family:inherit;">
                                Revisar de nuevo
                            </button>
                            <button type="button" onclick="submitClose()"
                                style="flex:1;padding:11px;background:#C8395A;
                                           color:#fff;border:none;border-radius:8px;
                                           cursor:pointer;font-size:14px;font-weight:700;
                                           font-family:inherit;">
                                ✅ Sí, cerrar caja
                            </button>
                        </div>

                    </div>
                </div>

                <div
                    style="margin-top:10px;padding:10px 14px;background:var(--primary-bg);
                     border-radius:var(--radius-sm);font-size:12px;color:var(--primary);
                     text-align:center;line-height:1.5;">
                    Al cerrar la caja, el registro queda bloqueado. Solo el administrador puede reabrirlo.
                </div>

            </div>
        </div>

    </form>

@endsection

@push('styles')
    <style>
        .cc-create-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 768px) {
            .cc-create-grid {
                display: flex !important;
                flex-direction: column !important;
                gap: 0 !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        const incomeCash = {{ $incomeCash }};
        const expensesCash = {{ $expensesCash }};

        // ── Recalcular diferencia en tiempo real ──────────────
        function recalculate() {
            const opening = parseFloat(document.getElementById('opening-balance').value) || 0;
            const actual = parseFloat(document.getElementById('actual-cash').value) || 0;
            const newExp = opening + incomeCash - expensesCash;
            const diff = actual - newExp;

            document.getElementById('display-expected').textContent = '$' + newExp.toFixed(2);
            document.getElementById('h-expected-cash').value = newExp.toFixed(2);

            const box = document.getElementById('diff-box');
            const disp = document.getElementById('diff-display');
            const label = document.getElementById('diff-label');

            disp.textContent = (diff >= 0 ? '+' : '-') + '$' + Math.abs(diff).toFixed(2);

            if (Math.abs(diff) < 0.01) {
                box.style.borderColor = 'var(--success)';
                box.style.background = '#F0FDF4';
                disp.style.color = 'var(--success)';
                label.textContent = '✓ Cuadra perfectamente';
                label.style.color = 'var(--success)';
            } else if (diff > 0) {
                box.style.borderColor = '#0284C7';
                box.style.background = '#EFF6FF';
                disp.style.color = '#0284C7';
                label.textContent = '▲ Sobrante en caja';
                label.style.color = '#0284C7';
            } else {
                box.style.borderColor = 'var(--danger)';
                box.style.background = '#FFF5F5';
                disp.style.color = 'var(--danger)';
                label.textContent = '▼ Faltante en caja';
                label.style.color = 'var(--danger)';
            }

            // Validar que no sea negativo
            const actualInput = document.getElementById('actual-cash');
            if (actual < 0) {
                actualInput.style.borderColor = 'var(--danger)';
                actualInput.style.boxShadow = '0 0 0 3px #FEE2E2';
            } else {
                actualInput.style.borderColor = '';
                actualInput.style.boxShadow = '';
            }
        }

        // ── Abrir modal de confirmación ───────────────────────
        function openConfirmClose() {
            const actual = parseFloat(document.getElementById('actual-cash').value) || 0;
            const expected = parseFloat(document.getElementById('h-expected-cash').value) || 0;
            const diff = actual - expected;

            // Bloquear si efectivo negativo
            if (actual < 0) {
                alert('❌ No se puede cerrar la caja con efectivo negativo. Verifica el monto.');
                document.getElementById('actual-cash').focus();
                return;
            }

            // Rellenar datos del modal
            document.getElementById('modal-expected').textContent = '$' + expected.toFixed(2);
            document.getElementById('modal-actual').textContent = '$' + actual.toFixed(2);
            document.getElementById('modal-diff').textContent =
                (diff >= 0 ? '+' : '-') + '$' + Math.abs(diff).toFixed(2);

            const diffLabel = document.getElementById('modal-diff-label');
            if (Math.abs(diff) < 0.01) {
                document.getElementById('modal-diff').style.color = '#059669';
                diffLabel.textContent = '✅ La caja cuadra perfectamente';
                diffLabel.style.background = '#D1FAE5';
                diffLabel.style.color = '#065F46';
            } else if (diff > 0) {
                document.getElementById('modal-diff').style.color = '#0284C7';
                diffLabel.textContent = '🔵 Hay un sobrante de $' + diff.toFixed(2);
                diffLabel.style.background = '#DBEAFE';
                diffLabel.style.color = '#1E40AF';
            } else {
                document.getElementById('modal-diff').style.color = '#DC2626';
                diffLabel.textContent = '🔴 Hay un faltante de $' + Math.abs(diff).toFixed(2);
                diffLabel.style.background = '#FEE2E2';
                diffLabel.style.color = '#991B1B';
            }

            document.getElementById('confirm-modal').style.display = 'flex';
        }

        // ── Cerrar modal ──────────────────────────────────────
        function closeConfirmModal() {
            document.getElementById('confirm-modal').style.display = 'none';
        }

        // ── Confirmar y enviar cierre definitivo ──────────────
        function submitClose() {
            // Crear input hidden con action=close y enviar el form
            const form = document.getElementById('closing-form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'action';
            input.value = 'close';
            form.appendChild(input);
            form.submit();
        }

        // Cerrar modal con ESC o clic en fondo
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeConfirmModal();
        });
        document.addEventListener('DOMContentLoaded', function() {
            recalculate();
            const modal = document.getElementById('confirm-modal');
            if (modal) {
                modal.addEventListener('click', e => {
                    if (e.target === modal) closeConfirmModal();
                });
            }
        });
    </script>
@endpush
