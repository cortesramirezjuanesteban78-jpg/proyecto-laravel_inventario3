@extends('layouts.sidebar')

@section('title', 'Gestión de Usuarios y Roles')
@section('page-title', 'Gestión de Usuarios')
@section('page-subtitle', 'Control centralizado de cuentas, roles y permisos del personal')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-primary">+ Nuevo Usuario</button>
@endsection

@push('styles')
<style>
    /* ── Banner de Política de Roles ── */
    .role-policy-banner {
        background: linear-gradient(135deg, #063826 0%, #0b4e37 60%, #106f4e 100%);
        border-radius: 18px;
        padding: 1.3rem 1.6rem;
        margin-bottom: 1.8rem;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        box-shadow: 0 4px 16px rgba(6, 56, 38, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .rpb-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
    }
    .rpb-text { flex: 1; }
    .rpb-title {
        font-size: 1.05rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .rpb-desc {
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.45;
    }
    .rpb-desc strong {
        color: #a7f3d0;
        font-weight: 700;
    }

    /* ── Tarjetas Estadísticas ── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.1rem;
        margin-bottom: 2rem;
    }
    @media (max-width: 900px) {
        .stats-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 500px) {
        .stats-row { grid-template-columns: 1fr; }
    }

    .stat-card {
        border-radius: 16px;
        padding: 1.25rem 1.4rem;
        background: #fff;
        border: 1.5px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        box-shadow: var(--shadow-sm);
        transition: transform .2s, box-shadow .2s, border-color .2s;
        cursor: pointer;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    .stat-card.is-active {
        box-shadow: 0 0 0 3px rgba(16, 111, 78, 0.2);
    }

    .stat-card.green  { border-left: 4px solid #106f4e; }
    .stat-card.green .sc-icon { background: #ecfdf5; border: 1px solid #d1fae5; }
    .stat-card.purple { border-left: 4px solid #7c3aed; }
    .stat-card.purple .sc-icon { background: #f5f3ff; border: 1px solid #ede9fe; }
    .stat-card.blue   { border-left: 4px solid #2563eb; }
    .stat-card.blue .sc-icon { background: #eff6ff; border: 1px solid #dbeafe; }
    .stat-card.amber  { border-left: 4px solid #d97706; }
    .stat-card.amber .sc-icon { background: #fffbeb; border: 1px solid #fef3c7; }

    .stat-card .sc-icon {
        font-size: 1.5rem;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-card .sc-num  { font-size: 1.75rem; font-weight: 900; color: var(--text-dark); line-height: 1.1; }
    .stat-card .sc-label { font-size: .84rem; font-weight: 600; color: var(--text-muted); margin-top: .15rem; }

    /* ── Contenedor de Tabla y Filtros ── */
    .card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .card-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.2rem 1.6rem;
        border-bottom: 1px solid var(--border-subtle);
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* Pestañas de filtrado rápido */
    .filter-tabs {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        background: #f1f5f9;
        padding: 0.35rem;
        border-radius: 12px;
    }
    .filter-tab {
        padding: 0.45rem 0.95rem;
        border-radius: 9px;
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        color: #475569;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .filter-tab:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.7);
    }
    .filter-tab.active {
        background: #ffffff;
        color: #065f46;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
    }
    .filter-tab .tab-badge {
        font-size: 0.72rem;
        padding: 0.15rem 0.45rem;
        border-radius: 10px;
        background: #e2e8f0;
        color: #334155;
    }
    .filter-tab.active .tab-badge {
        background: #ecfdf5;
        color: #065f46;
    }

    .search-wrap input {
        background: #fff;
        border: 1.5px solid var(--border-subtle);
        border-radius: 10px;
        padding: .5rem 1rem;
        font-family: inherit;
        font-size: .88rem;
        width: 250px;
        outline: none;
        transition: all .2s;
    }
    .search-wrap input:focus {
        border-color: #159c6c;
        box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }

    /* Tabla */
    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #f8fafc;
        color: var(--text-muted);
        font-weight: 700;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: .9rem 1.2rem;
        text-align: left;
        border-bottom: 1px solid var(--border-subtle);
    }
    tbody tr { border-bottom: 1px solid var(--border-subtle); transition: background .15s; }
    tbody tr:hover { background: #f8fafc; }
    tbody td { padding: .85rem 1.2rem; font-size: .9rem; color: var(--text-dark); vertical-align: middle; }

    .user-cell { display: flex; align-items: center; gap: .75rem; }
    .avatar-sm {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: .95rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(16,111,78,.2);
    }
    .avatar-sm.empleado {
        background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%);
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
    }
    .avatar-sm.cliente {
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        box-shadow: 0 2px 8px rgba(217, 119, 6, 0.2);
    }
    .avatar-sm.admin {
        background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%);
        box-shadow: 0 2px 8px rgba(109, 40, 217, 0.2);
    }

    .uname-main { font-weight: 700; color: var(--text-dark); font-size: .9rem; }
    .uname-sub  { font-size: .78rem; color: var(--text-muted); }

    /* Badges de rol */
    .badge {
        padding: .28rem .8rem;
        border-radius: 20px;
        font-size: .76rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        white-space: nowrap;
    }
    .badge-admin    { background: #f5f3ff; color: #6d28d9; border: 1px solid #ede9fe; }
    .badge-empleado { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
    .badge-cliente  { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
    .badge-activo   { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-inactivo { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }

    /* Botones de Asignación Rápida de Rol por el Administrador */
    .btn-role-action {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.42rem 0.85rem;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        border: none;
        white-space: nowrap;
    }
    /* Asignar Empleado (Para Clientes) */
    .btn-promote-employee {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
    }
    .btn-promote-employee:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.38);
        filter: brightness(1.06);
    }
    /* Revertir a Cliente (Para Empleados) */
    .btn-demote-client {
        background: #fffbeb;
        color: #b45309;
        border: 1.5px solid #fde68a;
    }
    .btn-demote-client:hover {
        background: #fef3c7;
        color: #92400e;
        border-color: #fcd34d;
        transform: translateY(-1px);
    }
    .badge-admin-fixed {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.75rem;
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 700;
        background: #f5f3ff;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
    }

    /* Botones de acción estándar */
    .actions { display: flex; align-items: center; gap: .45rem; }
    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        transition: all .15s;
        background: #f1f5f9;
        color: #374151;
    }
    .btn-icon:hover { transform: scale(1.1); }
    .btn-icon.blue   { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
    .btn-icon.yellow { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
    .btn-icon.red    { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }

    .pagination-wrap { padding: 1rem 1.6rem; border-top: 1px solid var(--border-subtle); }
    .pagination-wrap .pagination { display: flex; gap: .35rem; list-style: none; justify-content: flex-end; }
    .pagination-wrap .page-item .page-link {
        padding: .4rem .8rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: .84rem;
        font-weight: 700;
        color: var(--text-dark);
        background: #fff;
        border: 1.5px solid var(--border-subtle);
        transition: all .2s;
    }
    .pagination-wrap .page-item .page-link:hover { background: #f8fafc; border-color: #cbd5e1; }
    .pagination-wrap .page-item.active .page-link { background: #106f4e; color: #fff; border-color: #106f4e; }

    /* Modales */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,.5);
        backdrop-filter: blur(4px);
        z-index: 200;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 20px;
        padding: 2.2rem;
        width: 100%;
        max-width: 520px;
        position: relative;
        box-shadow: var(--shadow-lg);
    }
    .modal-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin-bottom: 1.4rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 700; color: #334155; margin-bottom: .35rem; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: .65rem .85rem;
        border: 1.5px solid var(--border-subtle);
        border-radius: 10px;
        font-family: inherit;
        font-size: .9rem;
        outline: none;
        transition: border .18s;
    }
    .form-group input:focus, .form-group select:focus {
        border-color: #159c6c;
        box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }
    .form-group small {
        color: #64748b;
        font-size: 0.76rem;
        display: block;
        margin-top: 0.3rem;
        line-height: 1.4;
    }
    .modal-footer { display: flex; gap: .8rem; justify-content: flex-end; margin-top: .8rem; }
    .modal-close {
        position: absolute;
        top: 1.2rem;
        right: 1.2rem;
        background: #f1f5f9;
        border: none;
        border-radius: 8px;
        width: 30px;
        height: 30px;
        cursor: pointer;
        font-size: 1rem;
        color: var(--text-muted);
        transition: all .2s;
    }
</style>
@endpush

@section('content')

{{-- ── Banner Informativo Exclusivo de Administrador ── --}}
<div class="role-policy-banner">
    <div class="rpb-icon">🛡️</div>
    <div class="rpb-text">
        <div class="rpb-title">
            <span>Control Exclusivo de Roles</span>
            <span style="background: rgba(255,255,255,0.2); font-size: 0.72rem; padding: 0.2rem 0.6rem; border-radius: 12px; letter-spacing: 0.05em; text-transform: uppercase;">Facultad del Administrador</span>
        </div>
        <div class="rpb-desc">
            Cuando una nueva persona se registra en la tienda web, ingresa automáticamente con el rol de <strong>Cliente</strong>.
            Solo tú como <strong>Administrador</strong> decides si le otorgas el rol de <strong>Empleado</strong> para que opere el inventario y catálogo. Puedes cambiar el rol en un clic desde la columna <em>"Decisión de Rol"</em>.
        </div>
    </div>
</div>

{{-- ── Tarjetas Estadísticas con Filtro Directo ── --}}
<div class="stats-row">
    <a href="{{ route('usuarios.index') }}" class="stat-card green {{ !$rol ? 'is-active' : '' }}" title="Ver todos los usuarios">
        <div class="sc-icon">👥</div>
        <div>
            <div class="sc-num">{{ $total }}</div>
            <div class="sc-label">Total Usuarios</div>
        </div>
    </a>
    <a href="{{ route('usuarios.index', ['rol' => 'cliente']) }}" class="stat-card amber {{ $rol === 'cliente' ? 'is-active' : '' }}" title="Filtrar por clientes">
        <div class="sc-icon">🧑‍💼</div>
        <div>
            <div class="sc-num">{{ $clientes }}</div>
            <div class="sc-label">Clientes Registrados</div>
        </div>
    </a>
    <a href="{{ route('usuarios.index', ['rol' => 'empleado']) }}" class="stat-card blue {{ $rol === 'empleado' ? 'is-active' : '' }}" title="Filtrar por empleados">
        <div class="sc-icon">💼</div>
        <div>
            <div class="sc-num">{{ $empleados }}</div>
            <div class="sc-label">Empleados Operativos</div>
        </div>
    </a>
    <a href="{{ route('usuarios.index', ['rol' => 'administrador']) }}" class="stat-card purple {{ $rol === 'administrador' ? 'is-active' : '' }}" title="Filtrar por administradores">
        <div class="sc-icon">🔑</div>
        <div>
            <div class="sc-num">{{ $admins }}</div>
            <div class="sc-label">Administradores</div>
        </div>
    </a>
</div>

{{-- ── Tarjeta Principal de Tabla ── --}}
<div class="card">
    <div class="card-header-bar">
        {{-- Pestañas de filtro por rol --}}
        <div class="filter-tabs">
            <a href="{{ route('usuarios.index') }}" class="filter-tab {{ empty($rol) ? 'active' : '' }}">
                <span>Todos</span>
                <span class="tab-badge">{{ $total }}</span>
            </a>
            <a href="{{ route('usuarios.index', ['rol' => 'cliente']) }}" class="filter-tab {{ $rol === 'cliente' ? 'active' : '' }}">
                <span>🧑‍💼 Clientes</span>
                <span class="tab-badge">{{ $clientes }}</span>
            </a>
            <a href="{{ route('usuarios.index', ['rol' => 'empleado']) }}" class="filter-tab {{ $rol === 'empleado' ? 'active' : '' }}">
                <span>💼 Empleados</span>
                <span class="tab-badge">{{ $empleados }}</span>
            </a>
            <a href="{{ route('usuarios.index', ['rol' => 'administrador']) }}" class="filter-tab {{ $rol === 'administrador' ? 'active' : '' }}">
                <span>🔑 Admins</span>
                <span class="tab-badge">{{ $admins }}</span>
            </a>
        </div>

        {{-- Buscador en tiempo real --}}
        <div class="search-wrap">
            <input type="text" id="search-input" placeholder="🔍 Buscar por nombre o correo..." onkeyup="filterTable()">
        </div>
    </div>

    <table id="users-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Contacto</th>
                <th>Rol Actual</th>
                <th>Decisión de Rol (Admin)</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($usuarios as $u)
        <tr>
            <td>{{ $u->id_usuario }}</td>
            <td>
                <div class="user-cell">
                    <div class="avatar-sm {{ $u->rol }}">
                        {{ strtoupper(substr($u->nombres, 0, 1)) }}
                    </div>
                    <div>
                        <div class="uname-main">{{ $u->nombres }} {{ $u->apellidos }}</div>
                        <div class="uname-sub">ID #{{ $u->id_usuario }}</div>
                    </div>
                </div>
            </td>
            <td>
                <div style="font-weight: 600; color: #1e293b;">{{ $u->email }}</div>
                <div style="font-size: 0.78rem; color: #64748b;">📞 {{ $u->telefono ?: 'Sin teléfono' }}</div>
            </td>
            <td>
                <span class="badge
                    @if($u->rol === 'administrador') badge-admin
                    @elseif($u->rol === 'empleado') badge-empleado
                    @else badge-cliente @endif">
                    @if($u->rol === 'administrador') 🔑 Administrador
                    @elseif($u->rol === 'empleado') 💼 Empleado
                    @else 🧑‍💼 Cliente @endif
                </span>
            </td>
            <td>
                {{-- ── Botón de Asignación Decisoria del Administrador ── --}}
                @if($u->rol === 'cliente')
                    <form method="POST" action="{{ route('usuarios.cambiarRol', $u->id_usuario) }}" style="display:inline"
                          onsubmit="return confirm('¿Otorgar el rol de EMPLEADO a {{ addslashes($u->nombres) }}? Tendrá acceso operativo al catálogo e inventario.')">
                        @csrf
                        <input type="hidden" name="rol" value="empleado">
                        <button type="submit" class="btn-role-action btn-promote-employee" title="Dar acceso operativo como Empleado">
                            <span>💼</span>
                            <span>Hacer Empleado</span>
                        </button>
                    </form>
                @elseif($u->rol === 'empleado')
                    <form method="POST" action="{{ route('usuarios.cambiarRol', $u->id_usuario) }}" style="display:inline"
                          onsubmit="return confirm('¿Revertir a {{ addslashes($u->nombres) }} al rol de CLIENTE? Perderá los permisos de empleado.')">
                        @csrf
                        <input type="hidden" name="rol" value="cliente">
                        <button type="submit" class="btn-role-action btn-demote-client" title="Revocar permisos de empleado y dejar como Cliente">
                            <span>🧑‍💼</span>
                            <span>Pasar a Cliente</span>
                        </button>
                    </form>
                @else
                    <span class="badge-admin-fixed" title="Cuenta con privilegios máximos">
                        <span>👑</span> Admin Principal
                    </span>
                @endif
            </td>
            <td>
                @if($u->estado)
                    <span class="badge badge-activo">• Activo</span>
                @else
                    <span class="badge badge-inactivo">• Inactivo</span>
                @endif
            </td>
            <td>
                <div class="actions">
                    {{-- Editar completo en Modal --}}
                    <button class="btn-icon blue" title="Editar datos y contraseña"
                        onclick="openEditModal(
                            {{ $u->id_usuario }},
                            '{{ addslashes($u->nombres) }}',
                            '{{ addslashes($u->apellidos) }}',
                            '{{ addslashes($u->email) }}',
                            '{{ addslashes($u->telefono ?? '') }}',
                            '{{ $u->rol }}'
                        )">✏️</button>

                    {{-- Bloquear / Activar --}}
                    @if($u->id_usuario !== auth()->id())
                    <form method="POST" action="{{ route('usuarios.toggle', $u->id_usuario) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-icon yellow" title="{{ $u->estado ? 'Bloquear acceso' : 'Desbloquear acceso' }}">
                            {{ $u->estado ? '🔒' : '🔓' }}
                        </button>
                    </form>

                    {{-- Eliminar --}}
                    <form method="POST" action="{{ route('usuarios.destroy', $u->id_usuario) }}" style="display:inline"
                          onsubmit="return confirm('¿Eliminar definitivamente al usuario {{ addslashes($u->nombres) }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon red" title="Eliminar usuario">🗑️</button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:3rem 1.5rem;color:#94a3b8;">
                <div style="font-size:2rem;margin-bottom:0.5rem;">🔍</div>
                <div style="font-weight:700;font-size:1rem;color:#475569;">No se encontraron usuarios</div>
                <div style="font-size:0.85rem;margin-top:0.25rem;">Intenta con otro término de búsqueda o limpia los filtros.</div>
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>

    @if($usuarios->hasPages())
    <div class="pagination-wrap">
        {{ $usuarios->links() }}
    </div>
    @endif
</div>

{{-- ── Modal: Crear Nuevo Usuario (Solo Administrador) ── --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        <div class="modal-title">➕ Crear Nuevo Usuario</div>
        <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Nombres *</label>
                    <input type="text" name="nombres" required value="{{ old('nombres') }}" placeholder="Ej: Carlos">
                </div>
                <div class="form-group">
                    <label>Apellidos *</label>
                    <input type="text" name="apellidos" required value="{{ old('apellidos') }}" placeholder="Ej: Gómez">
                </div>
            </div>
            <div class="form-group">
                <label>Correo Electrónico *</label>
                <input type="email" name="email" required value="{{ old('email') }}" placeholder="correo@ejemplo.com">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Teléfono móvil</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="3001234567">
                </div>
                <div class="form-group">
                    <label>Rol Inicial *</label>
                    <select name="rol" required>
                        <option value="empleado" {{ old('rol') === 'empleado' ? 'selected' : '' }}>💼 Empleado (Personal Operativo)</option>
                        <option value="cliente" {{ old('rol','cliente') === 'cliente' ? 'selected' : '' }}>🧑‍💼 Cliente (Comprador)</option>
                        <option value="administrador" {{ old('rol') === 'administrador' ? 'selected' : '' }}>🔑 Administrador (Acceso Total)</option>
                    </select>
                    <small>Como Administrador puedes registrar directamente empleados o clientes.</small>
                </div>
            </div>
            <div class="form-group">
                <label>Contraseña *</label>
                <input type="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Cancelar</button>
                <button type="submit" class="btn-primary">Crear Cuenta</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Editar Usuario ── --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        <div class="modal-title">✏️ Editar Usuario y Rol</div>
        <form method="POST" id="edit-form" action="">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label>Nombres *</label>
                    <input type="text" name="nombres" id="edit-nombres" required>
                </div>
                <div class="form-group">
                    <label>Apellidos *</label>
                    <input type="text" name="apellidos" id="edit-apellidos" required>
                </div>
            </div>
            <div class="form-group">
                <label>Correo Electrónico *</label>
                <input type="email" name="email" id="edit-email" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Teléfono móvil</label>
                    <input type="text" name="telefono" id="edit-telefono">
                </div>
                <div class="form-group">
                    <label>Rol Asignado *</label>
                    <select name="rol" id="edit-rol" required>
                        <option value="cliente">🧑‍💼 Cliente</option>
                        <option value="empleado">💼 Empleado</option>
                        <option value="administrador">🔑 Administrador</option>
                    </select>
                    <small>Tú como Administrador decides el nivel de acceso.</small>
                </div>
            </div>
            <div class="form-group">
                <label>Nueva Contraseña <small style="display:inline;color:#94a3b8;">(dejar vacío para no modificarla)</small></label>
                <input type="password" name="password" minlength="6" placeholder="Dejar en blanco si no cambia">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit')">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
function openEditModal(id, nombres, apellidos, email, telefono, rol) {
    document.getElementById('edit-form').action = '/usuarios/' + id;
    document.getElementById('edit-nombres').value = nombres;
    document.getElementById('edit-apellidos').value = apellidos;
    document.getElementById('edit-email').value = email;
    document.getElementById('edit-telefono').value = telefono;
    document.getElementById('edit-rol').value = rol;
    openModal('modal-edit');
}
function filterTable() {
    const q = document.getElementById('search-input').value.toLowerCase().trim();
    document.querySelectorAll('#users-table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
// Cerrar modal al hacer clic fuera del recuadro
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('open');
    });
});
@if($errors->any())
openModal('modal-create');
@endif
</script>
@endpush
