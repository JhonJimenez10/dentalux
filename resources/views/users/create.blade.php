@extends('layouts.app')
@section('title', 'Nuevo Usuario')

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Usuarios', 'url' => route('users.index')],
            ['label' => 'Nuevo Usuario', 'url' => '#'],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Nuevo Usuario</h1>
            <p>Crea un acceso al sistema</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline">Cancelar</a>
    </div>

    <div class="user-form-wrap">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <h3 class="card-title">Datos del Usuario</h3>
                </div>
                <div class="card-body">

                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">
                                Nombre Completo <span class="required">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                placeholder="Ej: Dr. Juan Pérez" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Correo Electrónico <span class="required">*</span>
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                placeholder="correo@ejemplo.com" required>
                        </div>
                    </div>

                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">
                                Contraseña <span class="required">*</span>
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Confirmar Contraseña <span class="required">*</span>
                            </label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Repite la contraseña" required>
                        </div>
                    </div>

                    <div class="form-grid cols-3">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">
                                Rol <span class="required">*</span>
                            </label>
                            <select name="role" class="form-control" required onchange="toggleSpecialty(this)">
                                <option value="">Seleccionar…</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    Administrador
                                </option>
                                <option value="dentist" {{ old('role') == 'dentist' ? 'selected' : '' }}>
                                    Dentista
                                </option>
                                <option value="receptionist" {{ old('role') == 'receptionist' ? 'selected' : '' }}>
                                    Recepcionista
                                </option>
                            </select>
                        </div>
                        <div class="form-group" id="specialty-group" style="margin-bottom:0;">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="specialty" class="form-control" value="{{ old('specialty') }}"
                                placeholder="Ej: Ortodoncia, Cirugía…">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}"
                                placeholder="Ej: 0987654321">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Permisos por rol --}}
            <div
                style="background:var(--bg);border-radius:var(--radius);
                padding:16px 18px;margin-bottom:20px;
                border:1px solid var(--border);">
                <div style="font-size:13px;font-weight:600;margin-bottom:12px;">
                    Permisos por rol:
                </div>
                <div style="display:grid;gap:8px;">
                    @foreach ([['admin', 'Administrador', 'badge-warning', 'Acceso total: usuarios, pacientes, presupuestos, pagos y reportes.'], ['dentist', 'Dentista', 'badge-info', 'Pacientes, presupuestos, pagos y reportes de ingresos.'], ['receptionist', 'Recepcionista', 'badge-success', 'Solo pacientes y registro de pagos. Sin reportes ni usuarios.']] as [$r, $n, $b, $d])
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span class="badge {{ $b }}" style="min-width:90px;justify-content:center;">
                                {{ $n }}
                            </span>
                            <span style="font-size:13px;color:var(--text-muted);">{{ $d }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
                <a href="{{ route('users.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Crear Usuario
                </button>
            </div>

        </form>
    </div>

@endsection

@push('styles')
    <style>
        .user-form-wrap {
            max-width: 680px;
        }

        @media (max-width: 640px) {
            .user-form-wrap {
                max-width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function toggleSpecialty(select) {
            const group = document.getElementById('specialty-group');
            group.style.display = select.value === 'dentist' ? 'block' : 'block';
        }
    </script>
@endpush
