@extends('layouts.app')
@section('title', $patient->full_name)

@section('topbar-actions')
    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        <span class="btn-hide-mobile">Editar</span>
    </a>
@endsection

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => $patient->full_name, 'url' => '#'],
        ];
    @endphp

    {{-- Cabecera paciente --}}
    <div
        style="background:var(--text);border-radius:var(--radius);padding:16px 18px;
            margin-bottom:16px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <div
            style="width:48px;height:48px;border-radius:50%;background:var(--primary);
                display:flex;align-items:center;justify-content:center;
                font-size:17px;font-weight:700;color:#fff;flex-shrink:0;">
            {{ $patient->initials }}
        </div>
        <div style="flex:1;min-width:0;">
            <div
                style="font-family:'Plus Jakarta Sans',sans-serif;font-size:17px;
                    font-weight:700;color:#fff;overflow:hidden;text-overflow:ellipsis;
                    white-space:nowrap;">
                {{ $patient->full_name }}
            </div>
            {{-- Badge historia clínica --}}
            <div style="margin-top:5px;">
                <span
                    style="display:inline-flex;align-items:center;gap:5px;
                             font-size:11px;font-weight:700;letter-spacing:0.5px;
                             background:var(--primary);color:#fff;
                             padding:3px 10px;border-radius:100px;">
                    🩺 {{ $patient->history_code }}
                </span>
            </div>
            <div
                style="font-size:12px;color:rgba(255,255,255,0.45);margin-top:3px;
                    display:flex;flex-wrap:wrap;gap:8px;">
                @if ($patient->age_calculated)
                    <span>{{ $patient->age_calculated }} años</span>
                @endif
                @if ($patient->cedula)
                    <span>CI: {{ $patient->cedula }}</span>
                @endif
                @if ($patient->gender)
                    <span>{{ ucfirst($patient->gender) }}</span>
                @endif
                @if ($patient->city)
                    <span>{{ $patient->city }}</span>
                @endif
            </div>
        </div>

        {{-- Botones de acción en la cabecera oscura --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;flex-shrink:0;">

            {{-- Botón Imágenes --}}
            <a href="{{ route('patients.images.index', $patient) }}"
                style="display:inline-flex;align-items:center;gap:6px;padding:7px 12px;
                  border-radius:var(--radius-sm);font-size:13px;font-weight:500;
                  background:rgba(255,255,255,0.1);color:#fff;text-decoration:none;
                  border:1px solid rgba(255,255,255,0.15);transition:background 0.15s;
                  white-space:nowrap;"
                onmouseover="this.style.background='rgba(255,255,255,0.18)'"
                onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Imágenes
            </a>

            {{-- Botón Editar --}}
            <a href="{{ route('patients.edit', $patient) }}"
                style="display:inline-flex;align-items:center;gap:6px;padding:7px 12px;
                  border-radius:var(--radius-sm);font-size:13px;font-weight:500;
                  background:rgba(255,255,255,0.1);color:#fff;text-decoration:none;
                  border:1px solid rgba(255,255,255,0.15);transition:background 0.15s;
                  white-space:nowrap;"
                onmouseover="this.style.background='rgba(255,255,255,0.18)'"
                onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar
            </a>

        </div>
    </div>

    {{-- Info grid --}}
    <div class="patient-info-grid" style="display:grid;grid-template-columns:1fr 1fr;
     gap:14px;margin-bottom:14px;">

        {{-- Datos de contacto --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Datos de Contacto</h3>
            </div>
            <div style="padding:0;">
                @foreach ([
            'Historia' => $patient->history_code,
            'Cédula' => $patient->cedula,
            'Teléfono' => $patient->phone,
            'WhatsApp' => $patient->phone_whatsapp,
            'Correo' => $patient->email,
            'Dirección' => $patient->address,
            'Ciudad' => $patient->city,
            'Motivo' => $patient->reason_for_consultation,
        ] as $label => $value)
                    <div
                        style="display:flex;padding:10px 14px;border-bottom:1px solid #F9FAFB;
                        gap:8px;align-items:flex-start;">
                        <span
                            style="min-width:76px;font-size:10px;font-weight:700;
                              color:var(--text-muted);padding-top:2px;
                              text-transform:uppercase;letter-spacing:0.3px;
                              flex-shrink:0;">
                            {{ $label }}
                        </span>
                        <span
                            style="font-size:13px;color:var(--text);word-break:break-word;
                              min-width:0;flex:1;">
                            {{ $value ?? '—' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Historial médico --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Historial Médico</h3>
            </div>
            <div style="padding:0;">
                @foreach ([
            'Alergias' => $patient->allergies,
            'Patologías' => $patient->pathologies,
            'Observaciones' => $patient->observations,
        ] as $label => $value)
                    <div
                        style="display:flex;padding:10px 14px;border-bottom:1px solid #F9FAFB;
                        gap:8px;align-items:flex-start;">
                        <span
                            style="min-width:76px;font-size:10px;font-weight:700;
                              color:var(--text-muted);padding-top:2px;
                              text-transform:uppercase;letter-spacing:0.3px;
                              flex-shrink:0;">
                            {{ $label }}
                        </span>
                        <span
                            style="font-size:13px;color:var(--text);line-height:1.5;
                              word-break:break-word;min-width:0;flex:1;">
                            {{ $value ?? '—' }}
                        </span>
                    </div>
                @endforeach

                @if ($patient->representative_name)
                    <div
                        style="padding:12px 14px;background:var(--primary-bg);
                        border-top:1px solid var(--primary-light);">
                        <div
                            style="font-size:10px;font-weight:700;color:var(--primary);
                             text-transform:uppercase;letter-spacing:0.5px;
                             margin-bottom:8px;">
                            Representante</div>
                        @foreach ([
            'Nombre' => $patient->representative_name,
            'Parentesco' => $patient->representative_relationship,
            'Teléfono' => $patient->representative_phone,
        ] as $l => $v)
                            @if ($v)
                                <div style="display:flex;gap:8px;margin-bottom:5px;">
                                    <span
                                        style="min-width:70px;font-size:10px;font-weight:700;
                                  color:var(--text-muted);text-transform:uppercase;">
                                        {{ $l }}
                                    </span>
                                    <span style="font-size:12px;word-break:break-word;">{{ $v }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Presupuestos y Pagos --}}
    <div class="patient-info-grid" style="display:grid;grid-template-columns:1fr 1fr;
     gap:14px;margin-bottom:14px;">

        {{-- Presupuestos --}}
        <div class="card">
            <div class="card-header" style="padding:12px 14px;">
                <h3 class="card-title" style="font-size:14px;">Presupuestos</h3>
                <a href="{{ route('patients.budgets.create', $patient) }}" class="btn btn-primary btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo
                </a>
            </div>

            @if ($patient->budgets->isEmpty())
                <div style="padding:24px 14px;text-align:center;color:var(--text-muted);">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="margin:0 auto 8px;display:block;opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p style="font-size:12px;margin-bottom:10px;">Sin presupuestos.</p>
                    <a href="{{ route('patients.budgets.create', $patient) }}" class="btn btn-primary btn-sm">Crear</a>
                </div>
            @else
                {{-- Desktop: tabla --}}
                <div id="budgets-table">
                    <table class="dtable" style="min-width:auto;">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patient->budgets as $budget)
                                <tr>
                                    <td style="font-size:12px;white-space:nowrap;">
                                        {{ $budget->budget_date->format('d/m/Y') }}
                                    </td>
                                    <td style="font-weight:600;font-size:13px;white-space:nowrap;">
                                        ${{ number_format($budget->total, 2) }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $budget->status_badge }}" style="font-size:11px;">
                                            {{ $budget->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('patients.budgets.show', [$patient, $budget]) }}"
                                            class="btn btn-ghost btn-sm">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Mobile: lista --}}
                <div id="budgets-list" style="display:none;">
                    @foreach ($patient->budgets as $budget)
                        <a href="{{ route('patients.budgets.show', [$patient, $budget]) }}"
                            style="display:flex;align-items:center;justify-content:space-between;
                      padding:12px 14px;border-bottom:1px solid #F3F4F6;
                      text-decoration:none;color:var(--text);">
                            <div>
                                <div style="font-size:13px;font-weight:600;">
                                    ${{ number_format($budget->total, 2) }}
                                </div>
                                <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">
                                    {{ $budget->budget_date->format('d/m/Y') }}
                                </div>
                            </div>
                            <span class="badge {{ $budget->status_badge }}" style="font-size:11px;">
                                {{ $budget->status_label }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Control de Pagos --}}
        <div class="card">
            <div class="card-header" style="padding:12px 14px;">
                <h3 class="card-title" style="font-size:14px;">Control de Pagos</h3>
                <a href="{{ route('patients.payments.create', $patient) }}" class="btn btn-primary btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nuevo
                </a>
            </div>

            @if ($patient->paymentControls->isEmpty())
                <div style="padding:24px 14px;text-align:center;color:var(--text-muted);">
                    <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="margin:0 auto 8px;display:block;opacity:0.3;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p style="font-size:12px;margin-bottom:10px;">Sin control de pagos.</p>
                    <a href="{{ route('patients.payments.create', $patient) }}" class="btn btn-primary btn-sm">Crear</a>
                </div>
            @else
                {{-- Desktop: tabla --}}
                <div id="payments-table-show">
                    <table class="dtable" style="min-width:auto;">
                        <thead>
                            <tr>
                                <th>Total</th>
                                <th>Pagado</th>
                                <th>%</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($patient->paymentControls as $pc)
                                @php $pc->load('payments'); @endphp
                                <tr>
                                    <td style="font-weight:600;font-size:13px;white-space:nowrap;">
                                        ${{ number_format($pc->treatment_amount, 2) }}
                                    </td>
                                    <td
                                        style="color:var(--success);font-weight:600;font-size:13px;
                                   white-space:nowrap;">
                                        ${{ number_format($pc->total_paid, 2) }}
                                    </td>
                                    <td style="min-width:60px;">
                                        <div
                                            style="height:5px;background:var(--border);
                                        border-radius:100px;overflow:hidden;margin-bottom:2px;">
                                            <div
                                                style="height:100%;
                                            width:{{ $pc->progress_percent }}%;
                                            background:{{ $pc->is_fully_paid ? 'var(--success)' : 'var(--primary)' }};
                                            border-radius:100px;">
                                            </div>
                                        </div>
                                        <div style="font-size:10px;color:var(--text-muted);">
                                            {{ $pc->progress_percent }}%
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('patients.payments.show', [$patient, $pc]) }}"
                                            class="btn btn-ghost btn-sm">Ver</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Mobile: lista --}}
                <div id="payments-list-show" style="display:none;">
                    @foreach ($patient->paymentControls as $pc)
                        @php $pc->load('payments'); @endphp
                        <a href="{{ route('patients.payments.show', [$patient, $pc]) }}"
                            style="display:flex;align-items:center;justify-content:space-between;
                      padding:12px 14px;border-bottom:1px solid #F3F4F6;
                      text-decoration:none;color:var(--text);gap:10px;">
                            <div style="min-width:0;flex:1;">
                                <div style="display:flex;gap:8px;align-items:baseline;flex-wrap:wrap;">
                                    <span style="font-size:14px;font-weight:700;">
                                        ${{ number_format($pc->total_paid, 2) }}
                                    </span>
                                    <span style="font-size:11px;color:var(--text-muted);">
                                        de ${{ number_format($pc->treatment_amount, 2) }}
                                    </span>
                                </div>
                                <div
                                    style="height:5px;background:var(--border);border-radius:100px;
                                overflow:hidden;margin-top:6px;">
                                    <div
                                        style="height:100%;width:{{ $pc->progress_percent }}%;
                                    background:{{ $pc->is_fully_paid ? 'var(--success)' : 'var(--primary)' }};
                                    border-radius:100px;">
                                    </div>
                                </div>
                            </div>
                            <div style="text-align:right;flex-shrink:0;">
                                <div style="font-size:13px;font-weight:700;color:var(--primary);">
                                    {{ $pc->progress_percent }}%
                                </div>
                                @if ($pc->is_fully_paid)
                                    <div style="font-size:10px;color:var(--success);font-weight:600;">
                                        ✓ Saldado
                                    </div>
                                @else
                                    <div style="font-size:11px;color:var(--danger);">
                                        ${{ number_format($pc->balance, 2) }}
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- Accesos rápidos a otros módulos --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;" class="quick-actions-grid">

        <a href="{{ route('patients.images.index', $patient) }}"
            style="display:flex;align-items:center;gap:12px;padding:16px;
              background:var(--surface);border:1px solid var(--border);
              border-radius:var(--radius);text-decoration:none;color:var(--text);
              transition:all 0.15s;"
            onmouseover="this.style.borderColor='var(--primary)';this.style.transform='translateY(-1px)'"
            onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
            <div
                style="width:40px;height:40px;border-radius:10px;background:#EFF6FF;
                     display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="#1D4ED8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <div style="font-weight:600;font-size:14px;">Imágenes</div>
                <div style="font-size:12px;color:var(--text-muted);">
                    Rx y fotografías
                </div>
            </div>
        </a>

        <a href="{{ route('patients.budgets.create', $patient) }}"
            style="display:flex;align-items:center;gap:12px;padding:16px;
              background:var(--surface);border:1px solid var(--border);
              border-radius:var(--radius);text-decoration:none;color:var(--text);
              transition:all 0.15s;"
            onmouseover="this.style.borderColor='var(--primary)';this.style.transform='translateY(-1px)'"
            onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
            <div
                style="width:40px;height:40px;border-radius:10px;background:var(--primary-light);
                     display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="var(--primary)" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <div style="font-weight:600;font-size:14px;">Nuevo Presupuesto</div>
                <div style="font-size:12px;color:var(--text-muted);">
                    Crear presupuesto
                </div>
            </div>
        </a>

        <a href="{{ route('patients.payments.create', $patient) }}"
            style="display:flex;align-items:center;gap:12px;padding:16px;
              background:var(--surface);border:1px solid var(--border);
              border-radius:var(--radius);text-decoration:none;color:var(--text);
              transition:all 0.15s;"
            onmouseover="this.style.borderColor='var(--primary)';this.style.transform='translateY(-1px)'"
            onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
            <div
                style="width:40px;height:40px;border-radius:10px;background:#D1FAE5;
                     display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="20" height="20" fill="none" stroke="#059669" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <div style="font-weight:600;font-size:14px;">Control de Pagos</div>
                <div style="font-size:12px;color:var(--text-muted);">
                    Registrar pago
                </div>
            </div>
        </a>

    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 640px) {
            .patient-info-grid {
                grid-template-columns: 1fr !important;
            }

            .quick-actions-grid {
                grid-template-columns: 1fr !important;
            }

            #budgets-table {
                display: none !important;
            }

            #budgets-list {
                display: block !important;
            }

            #payments-table-show {
                display: none !important;
            }

            #payments-list-show {
                display: block !important;
            }
        }
    </style>
@endpush
