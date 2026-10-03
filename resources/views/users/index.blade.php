@extends('layouts.app')
@section('title', 'Usuarios')

@section('topbar-actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="btn-hide-mobile">Nuevo Usuario</span>
    </a>
@endsection

@section('content')

    @php
        $breadcrumbs = [
            ['label' => 'Administración', 'url' => '#'],
            ['label' => 'Usuarios', 'url' => route('users.index')],
        ];
    @endphp

    <div class="page-header">
        <div>
            <h1>Usuarios del Sistema</h1>
            <p>{{ $users->count() }} usuarios registrados</p>
        </div>
    </div>

    {{-- Roles explicados --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;" class="roles-grid">

        @foreach ([['admin', 'Administrador', 'Acceso total al sistema. Gestiona usuarios, pacientes y reportes.', '#FEF3C7', '#D4A843', '#92400E'], ['dentist', 'Dentista', 'Gestiona pacientes, presupuestos, pagos y reportes de ingresos.', '#DBEAFE', '#3B82F6', '#1E40AF'], ['receptionist', 'Recepcionista', 'Gestiona pacientes y registra pagos. Sin acceso a reportes.', '#D1FAE5', '#10B981', '#065F46']] as [$role, $name, $desc, $bg, $border, $color])
            <div
                style="background:{{ $bg }};border:1px solid {{ $border }}33;
                border-radius:var(--radius);padding:14px 16px;">
                <div style="font-size:13px;font-weight:700;color:{{ $color }};margin-bottom:4px;">
                    {{ $name }}
                </div>
                <div style="font-size:12px;color:{{ $color }}99;line-height:1.5;">
                    {{ $desc }}
                </div>
            </div>
        @endforeach

    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Usuarios</h3>
        </div>

        {{-- Desktop: tabla --}}
        <div class="table-wrap" id="users-table">
            <table class="dtable">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th class="hide-xs">Especialidad</th>
                        <th class="hide-xs">Teléfono</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div
                                        style="width:36px;height:36px;border-radius:50%;
                                        background:var(--primary-light);flex-shrink:0;
                                        display:flex;align-items:center;
                                        justify-content:center;font-size:12px;
                                        font-weight:700;color:var(--primary);">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:600;font-size:14px;">
                                            {{ $user->name }}
                                            @if ($user->id === auth()->id())
                                                <span
                                                    style="font-size:11px;font-weight:400;
                                                  color:var(--text-muted);">(tú)</span>
                                            @endif
                                        </div>
                                        <div style="font-size:12px;color:var(--text-muted);">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $roleBadge = match ($user->role) {
                                        'admin' => ['badge-warning', 'Administrador'],
                                        'dentist' => ['badge-info', 'Dentista'],
                                        'receptionist' => ['badge-success', 'Recepcionista'],
                                        default => ['badge-gray', ucfirst($user->role)],
                                    };
                                @endphp
                                <span class="badge {{ $roleBadge[0] }}">{{ $roleBadge[1] }}</span>
                            </td>
                            <td class="hide-xs" style="color:var(--text-muted);font-size:13px;">
                                {{ $user->specialty ?? '—' }}
                            </td>
                            <td class="hide-xs" style="font-size:13px;">
                                {{ $user->phone ?? '—' }}
                            </td>
                            <td>
                                @if ($user->active)
                                    <span class="badge badge-success">Activo</span>
                                @else
                                    <span class="badge badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:4px;">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-ghost btn-sm">Editar</a>
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}"
                                            onsubmit="return confirm('¿Eliminar usuario {{ $user->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                ✕
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile: tarjetas --}}
        <div id="users-cards" style="display:none;">
            @foreach ($users as $user)
                <div style="padding:14px 16px;border-bottom:1px solid #F3F4F6;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <div
                            style="width:40px;height:40px;border-radius:50%;
                            background:var(--primary-light);flex-shrink:0;
                            display:flex;align-items:center;justify-content:center;
                            font-size:13px;font-weight:700;color:var(--primary);">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;font-size:14px;">
                                {{ $user->name }}
                                @if ($user->id === auth()->id())
                                    <span style="font-size:11px;color:var(--text-muted);">(tú)</span>
                                @endif
                            </div>
                            <div
                                style="font-size:12px;color:var(--text-muted);
                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $user->email }}
                            </div>
                        </div>
                        @php
                            $roleBadge = match ($user->role) {
                                'admin' => ['badge-warning', 'Admin'],
                                'dentist' => ['badge-info', 'Dentista'],
                                'receptionist' => ['badge-success', 'Recepción'],
                                default => ['badge-gray', ucfirst($user->role)],
                            };
                        @endphp
                        <span class="badge {{ $roleBadge[0] }}" style="flex-shrink:0;">
                            {{ $roleBadge[1] }}
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <div style="display:flex;gap:6px;align-items:center;">
                            @if ($user->active)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                            @if ($user->specialty)
                                <span style="font-size:12px;color:var(--text-muted);">
                                    {{ $user->specialty }}
                                </span>
                            @endif
                        </div>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-ghost btn-sm">Editar</a>
                            @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                    onsubmit="return confirm('¿Eliminar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">✕</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

@endsection

@push('styles')
    <style>
        @media (max-width: 640px) {
            #users-table {
                display: none !important;
            }

            #users-cards {
                display: block !important;
            }

            .roles-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endpush
