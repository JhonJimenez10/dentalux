@extends('layouts.app')
@section('title', 'Editar Paciente')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => $patient->full_name, 'url' => route('patients.show', $patient)],
            ['label' => 'Editar', 'url' => '#'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Editar Paciente</h1>
            <p>{{ $patient->full_name }}</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline">Cancelar</a>
            <form method="POST" action="{{ route('patients.destroy', $patient) }}"
                onsubmit="return confirm('¿Eliminar este paciente? Esta acción no se puede deshacer.')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="btn-hide-mobile">Eliminar</span>
                </button>
            </form>
        </div>
    </div>

    <form method="POST" action="{{ route('patients.update', $patient) }}">
        @csrf @method('PUT')

        {{-- Datos personales --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <h3 class="card-title">
                    <span style="display:inline-flex;align-items:center;gap:8px;">
                        <span
                            style="width:26px;height:26px;background:var(--primary-light);
                                 border-radius:6px;display:inline-flex;align-items:center;
                                 justify-content:center;">
                            <svg width="14" height="14" fill="none" stroke="var(--primary)" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        Datos Personales
                    </span>
                </h3>
            </div>
            <div class="card-body">
                <div class="form-grid cols-3">
                    <div class="form-group">
                        <label class="form-label">Nombres <span class="required">*</span></label>
                        <input type="text" name="first_name" class="form-control"
                            value="{{ old('first_name', $patient->first_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Apellidos <span class="required">*</span></label>
                        <input type="text" name="last_name" class="form-control"
                            value="{{ old('last_name', $patient->last_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cédula / Pasaporte</label>
                        <input type="text" name="cedula" class="form-control"
                            value="{{ old('cedula', $patient->cedula) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="birth_date" class="form-control"
                            value="{{ old('birth_date', $patient->birth_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Edad</label>
                        <input type="number" name="age" class="form-control" value="{{ old('age', $patient->age) }}"
                            min="0" max="150">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Género</label>
                        <select name="gender" class="form-control">
                            <option value="">Seleccionar…</option>
                            <option value="masculino" {{ old('gender', $patient->gender) == 'masculino' ? 'selected' : '' }}>
                                Masculino</option>
                            <option value="femenino" {{ old('gender', $patient->gender) == 'femenino' ? 'selected' : '' }}>
                                Femenino</option>
                            <option value="otro" {{ old('gender', $patient->gender) == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid cols-3">
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="phone" class="form-control"
                            value="{{ old('phone', $patient->phone) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">WhatsApp</label>
                        <input type="tel" name="phone_whatsapp" class="form-control"
                            value="{{ old('phone_whatsapp', $patient->phone_whatsapp) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $patient->email) }}">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $patient->address) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ciudad</label>
                        <input type="text" name="city" class="form-control"
                            value="{{ old('city', $patient->city) }}">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Motivo de Consulta</label>
                    <input type="text" name="reason_for_consultation" class="form-control"
                        value="{{ old('reason_for_consultation', $patient->reason_for_consultation) }}">
                </div>
            </div>
        </div>

        {{-- Historial médico --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header">
                <h3 class="card-title">
                    <span style="display:inline-flex;align-items:center;gap:8px;">
                        <span
                            style="width:26px;height:26px;background:#D1FAE5;border-radius:6px;
                                 display:inline-flex;align-items:center;justify-content:center;">
                            <svg width="14" height="14" fill="none" stroke="#059669" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        Historial Médico
                    </span>
                </h3>
            </div>
            <div class="card-body">
                <div class="form-grid cols-3">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Alergias</label>
                        <textarea name="allergies" class="form-control" rows="3">{{ old('allergies', $patient->allergies) }}</textarea>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Patologías</label>
                        <textarea name="pathologies" class="form-control" rows="3">{{ old('pathologies', $patient->pathologies) }}</textarea>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observations" class="form-control" rows="3">{{ old('observations', $patient->observations) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Representante --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <h3 class="card-title">Representante / Acudiente
                    <span style="font-size:12px;font-weight:400;color:var(--text-muted);margin-left:6px;">
                        Opcional
                    </span>
                </h3>
            </div>
            <div class="card-body">
                <div class="form-grid cols-2">
                    <div class="form-group">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="representative_name" class="form-control"
                            value="{{ old('representative_name', $patient->representative_name) }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cédula</label>
                        <input type="text" name="representative_cedula" class="form-control"
                            value="{{ old('representative_cedula', $patient->representative_cedula) }}">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Parentesco</label>
                        <input type="text" name="representative_relationship" class="form-control"
                            value="{{ old('representative_relationship', $patient->representative_relationship) }}">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="representative_phone" class="form-control"
                            value="{{ old('representative_phone', $patient->representative_phone) }}">
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
            <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Guardar Cambios
            </button>
        </div>

    </form>
@endsection
