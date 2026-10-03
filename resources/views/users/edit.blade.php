@extends('layouts.app')
@section('title', 'Editar Usuario')

@section('content')

    @php
        $breadcrumbs = [['label' => 'Usuarios', 'url' => route('users.index')], ['label' => $user->name, 'url' => '#']];
    @endphp

    <div class="page-header">
        <div>
            <h1>Editar Usuario</h1>
            <p>{{ $user->name }}</p>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-outline">Cancelar</a>
    </div>

    <div class="user-form-wrap">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')

            {{-- Datos principales --}}
            <div class="card" style="margin-bottom:18px;">
                <div class="card-header">
                    <h3 class="card-title">Datos del Usuario</h3>
                    @if ($user->id === auth()->id())
                        <span class="badge badge-info">Tu cuenta</span>
                    @endif
                </div>
                <div class="card-body">

                    <div class="form-grid cols-2">
                        <div class="form-group">
                            <label class="form-label">
                                Nombre Completo <span class="required">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                Correo Electrónico <span class="required">*</span>
                            </label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="form-grid cols-3">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Rol <span class="required">*</span></label>
                            <select name="role" class="form-control" {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                required>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    Administrador
                                </option>
                                <option value="dentist" {{ old('role', $user->role) == 'dentist' ? 'selected' : '' }}>
                                    Dentista
                                </option>
                                <option value="receptionist" {{ old('role', $user->role) == 'receptionist' ? 'selected' : '' }}>
                                    Recepcionista
                                </option>
                            </select>
                            @if ($user->id === auth()->id())
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <div class="form-hint">No puedes cambiar tu propio rol.</div>
                            @endif
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Especialidad</label>
                            <input type="text" name="specialty" class="form-control"
                                value="{{ old('specialty', $user->specialty) }}" placeholder="Ej: Ortodoncia, Endodoncia…">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}" placeholder="Ej: 0987654321">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Estado de la cuenta --}}
            <div class="card" style="margin-bottom:18px;">
                <div class="card-header">
                    <h3 class="card-title">Estado de la Cuenta</h3>
                </div>
                <div class="card-body">
                    <label
                        style="display:flex;align-items:center;gap:12px;cursor:pointer;
                           {{ $user->id === auth()->id() ? 'opacity:0.5;pointer-events:none;' : '' }}">
                        <input type="checkbox" name="active" value="1"
                            {{ old('active', $user->active) ? 'checked' : '' }}
                            style="width:18px;height:18px;accent-color:var(--primary);
                              flex-shrink:0;"
                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        <div>
                            <div style="font-size:14px;font-weight:600;">Usuario Activo</div>
                            <div style="font-size:12px;color:var(--text-muted);margin-top:2px;">
                                Los usuarios inactivos no pueden iniciar sesión en el sistema.
                            </div>
                        </div>
                        @if ($user->active)
                            <span class="badge badge-success" style="margin-left:auto;flex-shrink:0;">
                                Activo
                            </span>
                        @else
                            <span class="badge badge-danger" style="margin-left:auto;flex-shrink:0;">
                                Inactivo
                            </span>
                        @endif
                    </label>
                    @if ($user->id === auth()->id())
                        <div
                            style="margin-top:10px;padding:10px 12px;background:#FEF3C7;
                         border-radius:var(--radius-sm);font-size:12px;color:#92400E;">
                            No puedes desactivar tu propia cuenta.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Cambiar contraseña --}}
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">
                    <h3 class="card-title">Cambiar Contraseña</h3>
                    <span
                        style="font-size:12px;color:var(--text-muted);background:var(--bg);
                          padding:3px 8px;border-radius:100px;">
                        Opcional
                    </span>
                </div>
                <div class="card-body">
                    <div
                        style="background:var(--primary-bg);border-left:3px solid var(--primary);
                         border-radius:var(--radius-sm);padding:10px 14px;
                         font-size:13px;color:var(--primary);margin-bottom:16px;">
                        Deja en blanco si no deseas cambiar la contraseña actual.
                    </div>
                    <div class="form-grid cols-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres"
                                autocomplete="new-password">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Repite la nueva contraseña" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
                <a href="{{ route('users.index') }}" class="btn btn-outline">Cancelar</a>
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
