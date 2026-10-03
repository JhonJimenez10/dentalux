@extends('layouts.app')
@section('title', 'Cuentas Bancarias')

@section('topbar-actions')
    @if (auth()->user()->isAdmin())
        <a href="{{ route('bank-accounts.create') }}" class="btn btn-primary btn-sm">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="btn-hide-mobile">Nueva Cuenta</span>
        </a>
    @endif
@endsection

@section('content')

    @php
        $breadcrumbs = [['label' => 'Cuentas Bancarias', 'url' => route('bank-accounts.index')]];
    @endphp

    <div class="page-header">
        <div>
            <h1>Cuentas Bancarias</h1>
            <p>Cuentas para recibir transferencias de pacientes</p>
        </div>
    </div>

    {{-- Info de métodos de pago --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px;" class="payment-methods-grid">

        {{-- Efectivo --}}
        <div class="card" style="border-top:4px solid #059669;">
            <div class="card-body" style="padding:20px;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:#D1FAE5;
                             display:flex;align-items:center;justify-content:center;
                             flex-shrink:0;font-size:22px;">
                        💵
                    </div>
                    <div>
                        <div style="font-size:16px;font-weight:700;">Efectivo</div>
                        <div style="font-size:12px;color:var(--text-muted);">
                            Pago directo en consulta
                        </div>
                    </div>
                </div>
                <div style="font-size:13px;color:var(--text-muted);line-height:1.6;">
                    El paciente paga en el consultorio al momento de la consulta o al finalizar el tratamiento.
                </div>
                <div
                    style="margin-top:12px;padding:8px 12px;background:#D1FAE5;
                         border-radius:var(--radius-sm);font-size:12px;color:#065F46;
                         font-weight:500;">
                    ✓ Sin comisión · Inmediato
                </div>
            </div>
        </div>

        {{-- Transferencia --}}
        <div class="card" style="border-top:4px solid #0284C7;">
            <div class="card-body" style="padding:20px;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:#DBEAFE;
                             display:flex;align-items:center;justify-content:center;
                             flex-shrink:0;font-size:22px;">
                        🏦
                    </div>
                    <div>
                        <div style="font-size:16px;font-weight:700;">Transferencia</div>
                        <div style="font-size:12px;color:var(--text-muted);">
                            Bancos y cooperativas
                        </div>
                    </div>
                </div>
                <div style="font-size:13px;color:var(--text-muted);line-height:1.6;">
                    El paciente transfiere a una de las cuentas habilitadas. Pedir comprobante.
                </div>
                <div
                    style="margin-top:12px;padding:8px 12px;background:#DBEAFE;
                         border-radius:var(--radius-sm);font-size:12px;color:#1E40AF;
                         font-weight:500;">
                    ✓ Ver cuentas disponibles abajo
                </div>
            </div>
        </div>

        {{-- Tarjeta --}}
        <div class="card" style="border-top:4px solid #7C3AED;">
            <div class="card-body" style="padding:20px;">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div
                        style="width:44px;height:44px;border-radius:12px;background:#EDE9FE;
                             display:flex;align-items:center;justify-content:center;
                             flex-shrink:0;font-size:22px;">
                        💳
                    </div>
                    <div>
                        <div style="font-size:16px;font-weight:700;">Tarjeta de Crédito</div>
                        <div style="font-size:12px;color:var(--text-muted);">
                            Débito y crédito
                        </div>
                    </div>
                </div>
                <div style="font-size:13px;color:var(--text-muted);line-height:1.6;">
                    Pago con tarjeta de débito o crédito a través del POS del consultorio.
                </div>
                <div
                    style="margin-top:12px;padding:8px 12px;background:#EDE9FE;
                         border-radius:var(--radius-sm);font-size:12px;color:#4C1D95;
                         font-weight:500;">
                    ✓ Se necesita datafast / POS
                </div>
            </div>
        </div>

    </div>

    {{-- Cuentas bancarias para transferencias --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Cuentas para Transferencias
            </h3>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('bank-accounts.create') }}" class="btn btn-primary btn-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Agregar Cuenta
                </a>
            @endif
        </div>

        @if ($accounts->isEmpty())
            <div style="padding:40px;text-align:center;color:var(--text-muted);">
                <div style="font-size:40px;margin-bottom:12px;">🏦</div>
                <p style="font-size:14px;margin-bottom:16px;">
                    No hay cuentas bancarias configuradas.
                </p>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('bank-accounts.create') }}" class="btn btn-primary">
                        Agregar primera cuenta
                    </a>
                @endif
            </div>
        @else
            {{-- Agrupadas por titular --}}
            @php $grouped = $accounts->groupBy('owner_name'); @endphp

            @foreach ($grouped as $owner => $ownerAccounts)
                {{-- Header del titular --}}
                <div
                    style="padding:12px 20px;background:var(--bg);border-bottom:1px solid var(--border);
                 display:flex;align-items:center;gap:10px;">
                    <div
                        style="width:36px;height:36px;border-radius:50%;background:var(--primary-light);
                     display:flex;align-items:center;justify-content:center;
                     font-size:13px;font-weight:700;color:var(--primary);flex-shrink:0;">
                        {{ strtoupper(substr($owner, 0, 2)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:14px;">{{ $owner }}</div>
                        <div style="font-size:12px;color:var(--text-muted);">
                            {{ $ownerAccounts->count() }} cuenta(s) registrada(s)
                        </div>
                    </div>
                </div>

                {{-- Desktop: tabla --}}
                <div class="table-wrap acc-table">
                    <table class="dtable" style="min-width:auto;">
                        <thead>
                            <tr>
                                <th>Banco / Cooperativa</th>
                                <th>Tipo</th>
                                <th>Número de Cuenta</th>
                                <th class="hide-xs">Titular</th>
                                <th class="hide-xs">Cédula</th>
                                <th class="hide-xs">Teléfono</th>
                                <th>Estado</th>
                                @if (auth()->user()->isAdmin())
                                    <th></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ownerAccounts as $acc)
                                <tr style="{{ !$acc->active ? 'opacity:0.5;' : '' }}">
                                    <td>
                                        <div style="display:flex;align-items:center;gap:10px;">
                                            <div
                                                style="width:36px;height:36px;border-radius:8px;
                                         background:var(--primary-bg);
                                         display:flex;align-items:center;justify-content:center;
                                         font-size:18px;flex-shrink:0;">
                                                🏦
                                            </div>
                                            <div>
                                                <div style="font-weight:600;font-size:14px;">
                                                    {{ $acc->bank_name }}
                                                </div>
                                                <div style="font-size:11px;color:var(--text-muted);">
                                                    {{ $acc->account_type }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-gray">{{ $acc->account_type }}</span>
                                    </td>
                                    <td>
                                        <div
                                            style="font-family:monospace;font-size:14px;font-weight:600;
                                     letter-spacing:0.5px;color:var(--primary);">
                                            {{ $acc->account_number }}
                                        </div>
                                    </td>
                                    <td class="hide-xs" style="font-size:13px;">{{ $acc->owner_name }}</td>
                                    <td class="hide-xs" style="font-size:13px;color:var(--text-muted);">
                                        {{ $acc->owner_id ?? '—' }}
                                    </td>
                                    <td class="hide-xs" style="font-size:13px;">{{ $acc->phone ?? '—' }}</td>
                                    <td>
                                        @if ($acc->active)
                                            <span class="badge badge-success">Activa</span>
                                        @else
                                            <span class="badge badge-danger">Inactiva</span>
                                        @endif
                                    </td>
                                    @if (auth()->user()->isAdmin())
                                        <td>
                                            <div style="display:flex;gap:4px;">
                                                <a href="{{ route('bank-accounts.edit', $acc) }}"
                                                    class="btn btn-ghost btn-sm">Editar</a>
                                                <form method="POST" action="{{ route('bank-accounts.destroy', $acc) }}"
                                                    onsubmit="return confirm('¿Eliminar esta cuenta?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">✕</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile: cards --}}
                <div class="acc-cards" style="display:none;">
                    @foreach ($ownerAccounts as $acc)
                        <div
                            style="padding:14px 16px;border-bottom:1px solid #F3F4F6;
                     {{ !$acc->active ? 'opacity:0.5;' : '' }}">
                            <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:10px;">
                                <div
                                    style="width:40px;height:40px;border-radius:10px;background:var(--primary-bg);
                             display:flex;align-items:center;justify-content:center;
                             font-size:20px;flex-shrink:0;">
                                    🏦
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-weight:700;font-size:14px;">{{ $acc->bank_name }}</div>
                                    <div style="font-size:12px;color:var(--text-muted);">
                                        {{ $acc->account_type }}
                                    </div>
                                    <div
                                        style="font-family:monospace;font-size:15px;font-weight:700;
                                 color:var(--primary);margin-top:4px;letter-spacing:0.5px;">
                                        {{ $acc->account_number }}
                                    </div>
                                </div>
                                @if ($acc->active)
                                    <span class="badge badge-success">Activa</span>
                                @else
                                    <span class="badge badge-danger">Inactiva</span>
                                @endif
                            </div>
                            <div
                                style="display:grid;grid-template-columns:1fr 1fr;gap:8px;
                         font-size:12px;color:var(--text-muted);margin-bottom:8px;">
                                <div>Titular: <strong style="color:var(--text);">{{ $acc->owner_name }}</strong></div>
                                @if ($acc->owner_id)
                                    <div>CI: <strong style="color:var(--text);">{{ $acc->owner_id }}</strong></div>
                                @endif
                                @if ($acc->phone)
                                    <div>Tel: <strong style="color:var(--text);">{{ $acc->phone }}</strong></div>
                                @endif
                            </div>
                            @if (auth()->user()->isAdmin())
                                <div style="display:flex;gap:6px;">
                                    <a href="{{ route('bank-accounts.edit', $acc) }}"
                                        class="btn btn-ghost btn-sm">Editar</a>
                                    <form method="POST" action="{{ route('bank-accounts.destroy', $acc) }}"
                                        onsubmit="return confirm('¿Eliminar?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif

    </div>

    {{-- Instrucciones para recepcionistas --}}
    <div class="card" style="margin-top:20px;border-left:4px solid #0284C7;">
        <div class="card-body" style="padding:18px 20px;">
            <div style="font-size:14px;font-weight:700;color:#1E40AF;margin-bottom:10px;">
                📋 Instrucciones para cobros por transferencia
            </div>
            <ol style="font-size:13px;color:var(--text-muted);padding-left:18px;
                    line-height:2;">
                <li>Indica al paciente el banco y número de cuenta según su banco de preferencia.</li>
                <li>El paciente realiza la transferencia por el monto exacto del tratamiento.</li>
                <li>Solicita siempre el <strong style="color:var(--text);">comprobante de transferencia</strong> (captura
                    de pantalla o número de referencia).</li>
                <li>Anota el número de referencia al registrar el pago en el sistema.</li>
                <li>El pago se confirma solo cuando la transferencia aparece en la cuenta.</li>
            </ol>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 768px) {
            .payment-methods-grid {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 640px) {
            .acc-table {
                display: none !important;
            }

            .acc-cards {
                display: block !important;
            }
        }
    </style>
@endpush
