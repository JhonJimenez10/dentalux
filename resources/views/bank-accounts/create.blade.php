@extends('layouts.app')
@section('title', 'Nueva Cuenta Bancaria')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Cuentas Bancarias', 'url' => route('bank-accounts.index')],
            ['label' => 'Nueva Cuenta', 'url' => '#'],
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
            <h1>Nueva Cuenta Bancaria</h1>
            <p>Agrega una cuenta para recibir transferencias</p>
        </div>
        <a href="{{ route('bank-accounts.index') }}" class="btn btn-outline">Cancelar</a>
    </div>

    <div style="max-width:580px;">
        <form method="POST" action="{{ route('bank-accounts.store') }}">
            @csrf

            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <h3 class="card-title">Datos de la Cuenta</h3>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label class="form-label">
                            Banco / Cooperativa <span class="required">*</span>
                        </label>
                        <select name="bank_name" class="form-control" id="bank-select" onchange="checkOther(this)" required>
                            <option value="">Seleccionar…</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>
                                    {{ $bank }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="other-bank-group" style="display:none;">
                        <label class="form-label">Nombre del banco / cooperativa</label>
                        <input type="text" name="bank_name_other" class="form-control"
                            placeholder="Escribe el nombre del banco">
                    </div>

                    <div class="form-grid cols-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">
                                Tipo de Cuenta <span class="required">*</span>
                            </label>
                            <select name="account_type" class="form-control" required>
                                <option value="Cuenta de Ahorros"
                                    {{ old('account_type', 'Cuenta de Ahorros') == 'Cuenta de Ahorros' ? 'selected' : '' }}>
                                    Cuenta de Ahorros
                                </option>
                                <option value="Cuenta Corriente"
                                    {{ old('account_type') == 'Cuenta Corriente' ? 'selected' : '' }}>
                                    Cuenta Corriente
                                </option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">
                                Número de Cuenta <span class="required">*</span>
                            </label>
                            <input type="text" name="account_number" class="form-control"
                                value="{{ old('account_number') }}" placeholder="Ej: 2200123456789" required>
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
                        <label class="form-label">
                            Nombre del Titular <span class="required">*</span>
                        </label>
                        {{-- Acceso rápido a titulares conocidos --}}
                        <div style="display:flex;gap:6px;margin-bottom:8px;flex-wrap:wrap;">
                            <span style="font-size:11px;color:var(--text-muted);align-self:center;">
                                Acceso rápido:
                            </span>
                            <button type="button" class="btn btn-outline btn-sm" onclick="fillOwner('Dayana Bermeo')">
                                Dayana Bermeo
                            </button>
                            <button type="button" class="btn btn-outline btn-sm" onclick="fillOwner('Flor Bravo')">
                                Flor Bravo
                            </button>
                        </div>
                        <input type="text" name="owner_name" id="owner-name" class="form-control"
                            value="{{ old('owner_name') }}" placeholder="Nombre completo del titular" required>
                    </div>

                    <div class="form-grid cols-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Cédula del Titular</label>
                            <input type="text" name="owner_id" id="owner-id" class="form-control"
                                value="{{ old('owner_id') }}" placeholder="Ej: 0102345678">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Teléfono (opcional)</label>
                            <input type="tel" name="phone" id="owner-phone" class="form-control"
                                value="{{ old('phone') }}" placeholder="Ej: 0987654321">
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="active" value="1" checked
                        style="width:18px;height:18px;accent-color:var(--primary);">
                    <div>
                        <div style="font-size:14px;font-weight:600;">Cuenta activa</div>
                        <div style="font-size:12px;color:var(--text-muted);">
                            Las cuentas activas se muestran a recepcionistas al registrar pagos.
                        </div>
                    </div>
                </label>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <a href="{{ route('bank-accounts.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Guardar Cuenta
                </button>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
    <script>
        const owners = {
            'Dayana Bermeo': {
                id: '0XXXXXXXXX',
                phone: '09XXXXXXXX'
            },
            'Flor Bravo': {
                id: '0XXXXXXXXX',
                phone: '09XXXXXXXX'
            },
        };

        function fillOwner(name) {
            document.getElementById('owner-name').value = name;
            document.getElementById('owner-id').value = owners[name]?.id || '';
            document.getElementById('owner-phone').value = owners[name]?.phone || '';
        }

        function checkOther(select) {
            const group = document.getElementById('other-bank-group');
            group.style.display = select.value === 'Otro' ? 'block' : 'none';
        }
    </script>
@endpush
