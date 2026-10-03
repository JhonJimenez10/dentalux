@extends('layouts.app')
@section('title', 'Nuevo Egreso')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Finanzas', 'url' => route('finance.index')],
            ['label' => 'Nuevo Egreso', 'url' => '#'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Registrar Egreso</h1>
            <p>Ingresa los datos del gasto</p>
        </div>
        <a href="{{ route('finance.index') }}" class="btn btn-outline">Cancelar</a>
    </div>

    <div style="max-width:620px;">
        <form method="POST" action="{{ route('finance.expenses.store') }}">
            @csrf

            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <h3 class="card-title">Datos del Egreso</h3>
                </div>
                <div class="card-body">

                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">
                                Fecha <span class="required">*</span>
                            </label>
                            <input type="date" name="expense_date" class="form-control"
                                value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Categoría <span class="required">*</span>
                            </label>
                            <select name="category" class="form-control" required>
                                <option value="">Seleccionar…</option>
                                @foreach ([
            'materiales' => 'Materiales dentales',
            'equipos' => 'Equipos e instrumental',
            'servicios' => 'Servicios (luz, agua, internet)',
            'arriendo' => 'Arriendo / local',
            'sueldos' => 'Sueldos y honorarios',
            'laboratorio' => 'Laboratorio dental',
            'publicidad' => 'Publicidad y marketing',
            'mantenimiento' => 'Mantenimiento',
            'otros' => 'Otros',
        ] as $val => $lbl)
                                    <option value="{{ $val }}" {{ old('category') == $val ? 'selected' : '' }}>
                                        {{ $lbl }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Descripción <span class="required">*</span>
                        </label>
                        <input type="text" name="description" class="form-control" value="{{ old('description') }}"
                            placeholder="Ej: Compra de guantes y mascarillas" required>
                    </div>

                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">
                                Monto <span class="required">*</span>
                            </label>
                            <div style="position:relative;">
                                <span
                                    style="position:absolute;left:13px;top:50%;
                                     transform:translateY(-50%);font-weight:700;
                                     font-size:16px;color:var(--text-muted);">$</span>
                                <input type="number" name="amount" class="form-control"
                                    style="padding-left:28px;font-size:18px;font-weight:700;" step="0.01" min="0.01"
                                    value="{{ old('amount') }}" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Método de Pago</label>
                            <select name="payment_method" class="form-control">
                                @foreach ([
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta_credito' => 'Tarjeta Crédito',
            'tarjeta_debito' => 'Tarjeta Débito',
            'cheque' => 'Cheque',
        ] as $val => $lbl)
                                    <option value="{{ $val }}"
                                        {{ old('payment_method', 'efectivo') == $val ? 'selected' : '' }}>
                                        {{ $lbl }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Número de Referencia / Factura</label>
                        <input type="text" name="reference" class="form-control" value="{{ old('reference') }}"
                            placeholder="Ej: Factura #001, Transferencia #12345">
                        <div class="form-hint">Opcional — número de comprobante o factura</div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Notas</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Observaciones adicionales…">{{ old('notes') }}</textarea>
                    </div>

                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <a href="{{ route('finance.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Registrar Egreso
                </button>
            </div>

        </form>
    </div>

@endsection
