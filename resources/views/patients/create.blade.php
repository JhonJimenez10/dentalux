@extends('layouts.app')
@section('title', 'Nuevo Paciente')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Pacientes', 'url' => route('patients.index')],
            ['label' => 'Nuevo Paciente', 'url' => '#'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Registrar Paciente</h1>
            <p>Completa los datos del nuevo paciente</p>
        </div>
        <a href="{{ route('patients.index') }}" class="btn btn-outline">Cancelar</a>
    </div>

    <form method="POST" action="{{ route('patients.store') }}">
        @csrf

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
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}"
                            placeholder="Ej: Juan Miguel" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Apellidos <span class="required">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}"
                            placeholder="Ej: Jiménez Arce" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cédula / Pasaporte</label>
                        <input type="text" name="cedula" class="form-control" value="{{ old('cedula') }}"
                            placeholder="Ej: 0102345678">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha de Nacimiento</label>
                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            Edad
                            <span style="font-weight:400;color:var(--text-muted);font-size:11px;">
                                (si no hay fecha)
                            </span>
                        </label>
                        <input type="number" name="age" class="form-control" value="{{ old('age') }}"
                            placeholder="Años" min="0" max="150">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Género</label>
                        <select name="gender" class="form-control">
                            <option value="">Seleccionar…</option>
                            <option value="masculino" {{ old('gender') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="femenino" {{ old('gender') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="otro" {{ old('gender') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid cols-3">
                    <div class="form-group">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}"
                            placeholder="Ej: 0987654321">
                    </div>
                    <div class="form-group">
                        <label class="form-label">WhatsApp</label>
                        <input type="tel" name="phone_whatsapp" class="form-control"
                            value="{{ old('phone_whatsapp') }}" placeholder="Número WhatsApp">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                            placeholder="correo@ejemplo.com">
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}"
                            placeholder="Calle, sector, referencia">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ciudad</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}"
                            placeholder="Ej: Cuenca">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Motivo de Consulta</label>
                    <input type="text" name="reason_for_consultation" class="form-control"
                        value="{{ old('reason_for_consultation') }}"
                        placeholder="Ej: Dolor dental, revisión, ortodoncia…">
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
                        <textarea name="allergies" class="form-control" rows="3" placeholder="Ej: Penicilina, látex…">{{ old('allergies') }}</textarea>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Patologías</label>
                        <textarea name="pathologies" class="form-control" rows="3" placeholder="Ej: Hipertensión, diabetes…">{{ old('pathologies') }}</textarea>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observations" class="form-control" rows="3" placeholder="Notas adicionales…">{{ old('observations') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Representante --}}
        <div class="card" style="margin-bottom:24px;">
            <div class="card-header">
                <h3 class="card-title">
                    <span style="display:inline-flex;align-items:center;gap:8px;">
                        <span
                            style="width:26px;height:26px;background:#DBEAFE;border-radius:6px;
                                 display:inline-flex;align-items:center;justify-content:center;">
                            <svg width="14" height="14" fill="none" stroke="#0284C7" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        Representante / Acudiente
                    </span>
                    <span style="font-size:12px;font-weight:400;color:var(--text-muted);margin-left:4px;">
                        Opcional
                    </span>
                </h3>
            </div>
            <div class="card-body">
                <div class="form-grid cols-2">
                    <div class="form-group">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="representative_name" class="form-control"
                            value="{{ old('representative_name') }}" placeholder="Nombre del representante">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Cédula</label>
                        <input type="text" name="representative_cedula" class="form-control"
                            value="{{ old('representative_cedula') }}">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Parentesco</label>
                        <input type="text" name="representative_relationship" class="form-control"
                            value="{{ old('representative_relationship') }}" placeholder="Ej: Padre, Madre, Tutor">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" name="representative_phone" class="form-control"
                            value="{{ old('representative_phone') }}">
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
            <a href="{{ route('patients.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Guardar Paciente
            </button>
        </div>

    </form>
@endsection
