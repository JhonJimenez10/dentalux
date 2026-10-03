@extends('layouts.app')
@section('title', 'Nuevo Control de Pagos')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => $patient->full_name, 'url' => route('patients.show', $patient)],
            ['label' => 'Nuevo Control de Pagos', 'url' => '#'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Nuevo Control de Pagos</h1>
            <p>{{ $patient->full_name }}</p>
        </div>
        <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline">Cancelar</a>
    </div>

    <div class="create-control-wrap">
        <form method="POST" action="{{ route('patients.payments.store', $patient) }}">
            @csrf

            {{-- Card principal --}}
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <span style="display:inline-flex;align-items:center;gap:8px;">
                            <span
                                style="width:26px;height:26px;background:var(--primary-light);
                                 border-radius:6px;display:inline-flex;align-items:center;
                                 justify-content:center;flex-shrink:0;">
                                <svg width="14" height="14" fill="none" stroke="var(--primary)"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </span>
                            Configurar Control de Pagos
                        </span>
                    </h3>
                </div>
                <div class="card-body">

                    {{-- Vincular presupuesto --}}
                    @if ($budgets->count())
                        <div class="form-group">
                            <label class="form-label">
                                <span style="display:flex;align-items:center;gap:6px;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                    Vincular a Presupuesto
                                </span>
                            </label>
                            <select name="budget_id" class="form-control" id="budget-select"
                                onchange="fillFromBudget(this)">
                                <option value="">— Ingresar monto manualmente —</option>
                                @foreach ($budgets as $b)
                                    <option value="{{ $b->id }}" data-total="{{ $b->total }}">
                                        Presupuesto #{{ $b->id }}
                                        · {{ $b->budget_date->format('d/m/Y') }}
                                        · ${{ number_format($b->total, 2) }}
                                        · {{ $b->status_label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-hint">
                                Al seleccionar un presupuesto el monto se completa automáticamente.
                            </div>
                        </div>

                        {{-- Divisor --}}
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                            <div style="flex:1;height:1px;background:var(--border);"></div>
                            <span
                                style="font-size:11px;color:var(--text-muted);font-weight:600;
                              text-transform:uppercase;letter-spacing:0.5px;">o</span>
                            <div style="flex:1;height:1px;background:var(--border);"></div>
                        </div>
                    @else
                        <input type="hidden" name="budget_id" value="">
                    @endif

                    {{-- Monto total --}}
                    <div class="form-group">
                        <label class="form-label">
                            Monto Total del Tratamiento
                            <span class="required">*</span>
                        </label>
                        <div style="position:relative;">
                            <span
                                style="position:absolute;left:14px;top:50%;
                                 transform:translateY(-50%);font-weight:700;font-size:18px;
                                 color:var(--text-muted);">$</span>
                            <input type="number" name="treatment_amount" id="treatment-amount" class="form-control"
                                style="padding-left:32px;font-size:24px;font-weight:700;
                                  height:58px;letter-spacing:-0.5px;"
                                step="0.01" min="1" value="{{ old('treatment_amount') }}" placeholder="0.00"
                                required>
                        </div>
                        <div class="form-hint">
                            Valor total que el paciente debe cancelar por su tratamiento.
                        </div>
                    </div>

                </div>
            </div>

            {{-- Info explicativa --}}
            <div
                style="background:var(--primary-bg);border:1px solid var(--primary-light);
                border-radius:var(--radius);padding:18px 20px;margin-bottom:20px;">
                <div style="display:flex;gap:12px;align-items:flex-start;">
                    <div
                        style="width:32px;height:32px;background:var(--primary-light);
                         border-radius:8px;display:flex;align-items:center;
                         justify-content:center;flex-shrink:0;margin-top:1px;">
                        <svg width="16" height="16" fill="none" stroke="var(--primary)" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div
                            style="font-size:13px;font-weight:700;color:var(--primary);
                             margin-bottom:8px;">
                            ¿Cómo funciona el control de pagos?</div>
                        <div style="display:grid;gap:6px;">
                            @foreach (['Se crea el control con el monto total del tratamiento.', 'Cada abono se registra individualmente con firma del paciente.', 'El saldo se reduce automáticamente con cada pago.', 'Puedes imprimir el historial completo de pagos.'] as $step)
                                <div
                                    style="display:flex;align-items:flex-start;gap:8px;font-size:13px;
                                 color:var(--text-muted);">
                                    <div
                                        style="width:18px;height:18px;background:var(--primary);
                                     border-radius:50%;display:flex;align-items:center;
                                     justify-content:center;flex-shrink:0;margin-top:1px;">
                                        <svg width="10" height="10" fill="none" stroke="#fff"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    {{ $step }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
                <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Crear Control de Pagos
                </button>
            </div>

        </form>
    </div>

@endsection

@push('styles')
    <style>
        .create-control-wrap {
            max-width: 580px;
        }

        @media (max-width: 640px) {
            .create-control-wrap {
                max-width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function fillFromBudget(select) {
            const opt = select.options[select.selectedIndex];
            const total = opt.dataset.total;
            const input = document.getElementById('treatment-amount');
            const hint = document.getElementById('amount-hint');
            const badge = document.getElementById('locked-badge');

            if (total) {
                // Rellenar con el total del presupuesto
                input.value = parseFloat(total).toFixed(2);

                // Bloquear el input — no editable
                input.setAttribute('readonly', 'readonly');
                input.style.background = '#F0FDF4';
                input.style.borderColor = 'var(--success)';
                input.style.boxShadow = '0 0 0 3px rgba(5,150,105,0.12)';
                input.style.color = '#065F46';
                input.style.cursor = 'not-allowed';

                // Mostrar badge de bloqueado
                if (badge) badge.style.display = 'inline-flex';

                // Cambiar hint
                if (hint) {
                    hint.innerHTML =
                        '🔒 Monto tomado del presupuesto seleccionado. Para cambiarlo selecciona "Ingresar monto manualmente".';
                    hint.style.color = '#059669';
                }

            } else {
                // Desbloquear — ingreso manual
                input.removeAttribute('readonly');
                input.value = '';
                input.style.background = '';
                input.style.borderColor = '';
                input.style.boxShadow = '';
                input.style.color = '';
                input.style.cursor = '';

                // Ocultar badge
                if (badge) badge.style.display = 'none';

                // Restaurar hint
                if (hint) {
                    hint.innerHTML = 'Valor total que el paciente debe cancelar por su tratamiento.';
                    hint.style.color = '';
                }

                input.focus();
            }
        }

        // Bloquear escritura a mano cuando está readonly
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('treatment-amount');
            input.addEventListener('keydown', function(e) {
                if (input.hasAttribute('readonly')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
