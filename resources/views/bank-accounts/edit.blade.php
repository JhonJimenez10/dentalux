@extends('layouts.app')
@section('title', 'Editar Cuenta')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Cuentas Bancarias', 'url' => route('bank-accounts.index')],
            ['label' => 'Editar', 'url' => '#'],
        ];
        $banks = [
            'Banco Pichincha',
            'Banco Guayaquil',
            'Banco del Pacífico',
            'Banco Internacional',
            'Banco Bolivariano',
            'Produbanco',
            'BanEcuador',
            'Banco de Machala',
            'Banco Solidario',
            'Cooperativa JEP',
            'Cooperativa Jardín Azuayo',
            'Cooperativa 29 de Octubre',
            'Cooperativa CACPE Pastaza',
            'Cooperativa Mushuc Runa',
            'Cooperativa Policía Nacional',
            'Otro',
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Editar Cuenta</h1>
            <p>{{ $bankAccount->bank_name }} — {{ $bankAccount->owner_name }}</p>
        </div>
        <a href="{{ route('bank-accounts.index') }}" class="btn btn-outline">Cancelar</a>
    </div>

    <div style="max-width:580px;">
        <form method="POST" action="{{ route('bank-accounts.update', $bankAccount) }}">
            @csrf @method('PUT')

            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <h3 class="card-title">Datos de la Cuenta</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label class="form-label">Banco / Cooperativa <span class="required">*</span></label>
                        <select name="bank_name" class="form-control" required>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank }}"
                                    {{ old('bank_name', $bankAccount->bank_name) == $bank ? 'selected' : '' }}>
                                    {{ $bank }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-grid cols-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Tipo de Cuenta <span class="required">*</span></label>
                            <select name="account_type" class="form-control" required>
                                <option value="Cuenta de Ahorros"
                                    {{ old('account_type', $bankAccount->account_type) == 'Cuenta de Ahorros' ? 'selected' : '' }}>
                                    Cuenta de Ahorros
                                </option>
                                <option value="Cuenta Corriente"
                                    {{ old('account_type', $bankAccount->account_type) == 'Cuenta Corriente' ? 'selected' : '' }}>
                                    Cuenta Corriente
                                </option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Número de Cuenta <span class="required">*</span></label>
                            <input type="text" name="account_number" class="form-control"
                                value="{{ old('account_number', $bankAccount->account_number) }}" required>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <h3 class="card-title">Datos del Titular</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label class="form-label">Nombre del Titular <span class="required">*</span></label>
                        <input type="text" name="owner_name" class="form-control"
                            value="{{ old('owner_name', $bankAccount->owner_name) }}" required>
                    </div>

                    <div class="form-grid cols-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Cédula del Titular</label>
                            <input type="text" name="owner_id" class="form-control"
                                value="{{ old('owner_id', $bankAccount->owner_id) }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" name="phone" class="form-control"
                                value="{{ old('phone', $bankAccount->phone) }}">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;margin-top:16px;">
                        <label class="form-label">Orden de visualización</label>
                        <input type="number" name="order" class="form-control" style="width:100px;"
                            value="{{ old('order', $bankAccount->order) }}" min="0">
                        <div class="form-hint">
                            Número menor = aparece primero en la lista.
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="active" value="1"
                        {{ old('active', $bankAccount->active) ? 'checked' : '' }}
                        style="width:18px;height:18px;accent-color:var(--primary);">
                    <div>
                        <div style="font-size:14px;font-weight:600;">Cuenta activa</div>
                        <div style="font-size:12px;color:var(--text-muted);">
                            Desactiva para ocultar sin eliminar.
                        </div>
                    </div>
                </label>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
                <form method="POST" action="{{ route('bank-accounts.destroy', $bankAccount) }}"
                    onsubmit="return confirm('¿Eliminar esta cuenta?')" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Eliminar
                    </button>
                </form>
                <a href="{{ route('bank-accounts.index') }}" class="btn btn-outline">Cancelar</a>
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
