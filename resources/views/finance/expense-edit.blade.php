@extends('layouts.app')
@section('title', 'Editar Egreso')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Finanzas', 'url' => route('finance.index')],
            ['label' => 'Editar Egreso', 'url' => '#'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Editar Egreso</h1>
            <p>{{ $expense->description }}</p>
        </div>
        <a href="{{ route('finance.index') }}" class="btn btn-outline">Cancelar</a>
    </div>

    <div style="max-width:620px;">
        <form method="POST" action="{{ route('finance.expenses.update', $expense) }}">
            @csrf @method('PUT')

            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <h3 class="card-title">Datos del Egreso</h3>
                </div>
                <div class="card-body">

                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">Fecha <span class="required">*</span></label>
                            <input type="date" name="expense_date" class="form-control"
                                value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Categoría <span class="required">*</span></label>
                            <select name="category" class="form-control" required>
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
                                    <option value="{{ $val }}"
                                        {{ old('category', $expense->category) == $val ? 'selected' : '' }}>
                                        {{ $lbl }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Descripción <span class="required">*</span></label>
                        <input type="text" name="description" class="form-control"
                            value="{{ old('description', $expense->description) }}" required>
                    </div>

                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">Monto <span class="required">*</span></label>
                            <div style="position:relative;">
                                <span
                                    style="position:absolute;left:13px;top:50%;
                                     transform:translateY(-50%);font-weight:700;
                                     font-size:16px;color:var(--text-muted);">$</span>
                                <input type="number" name="amount" class="form-control"
                                    style="padding-left:28px;font-size:18px;font-weight:700;" step="0.01" min="0.01"
                                    value="{{ old('amount', $expense->amount) }}" required>
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
                                        {{ old('payment_method', $expense->payment_method) == $val ? 'selected' : '' }}>
                                        {{ $lbl }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Número de Referencia / Factura</label>
                        <input type="text" name="reference" class="form-control"
                            value="{{ old('reference', $expense->reference) }}">
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Notas</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $expense->notes) }}</textarea>
                    </div>

                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
                <form method="POST" action="{{ route('finance.expenses.destroy', $expense) }}"
                    onsubmit="return confirm('¿Eliminar este egreso?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar
                    </button>
                </form>
                <a href="{{ route('finance.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Cambios
                </button>
            </div>

        </form>
    </div>

@endsection
