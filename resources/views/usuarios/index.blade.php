@extends('layouts.sidebar')

@section('title', 'Usuarios')
@section('page-title', 'Usuarios')
@section('page-subtitle', $total . ' cuentas registradas')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-primary">+ Nuevo Usuario</button>
@endsection

@push('styles')
<style>
    .stats-row { display: flex; gap: 1.2rem; margin-bottom: 2rem; }
    .stat-card {
        flex: 1; border-radius: 16px; padding: 1.3rem 1.5rem; background: #fff;
        border: 1px solid var(--border-light);
        display: flex; align-items: center; gap: 1rem;
        box-shadow: var(--shadow-sm); transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .stat-card.green  { border-left: 4px solid #106f4e; }
    .stat-card.green .sc-icon { background: #ecfdf5; border: 1px solid #d1fae5; }
    .stat-card.purple { border-left: 4px solid #7c3aed; }
    .stat-card.purple .sc-icon { background: #f5f3ff; border: 1px solid #ede9fe; }
    .stat-card.blue   { border-left: 4px solid #2563eb; }
    .stat-card.blue .sc-icon { background: #eff6ff; border: 1px solid #dbeafe; }

    .stat-card .sc-icon {
        font-size: 1.6rem; width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-card .sc-num  { font-size: 1.8rem; font-weight: 900; color: var(--text-dark); line-height: 1.1; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: var(--text-muted); margin-top: .15rem; }

    .card {
        background: #fff; border-radius: 18px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm); overflow: hidden;
    }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.2rem 1.6rem; border-bottom: 1px solid var(--border-subtle);
    }
    .card-header h2 { font-size: 1.05rem; font-weight: 800; color: var(--text-dark); }

    .search-wrap input {
        background: #fff; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        padding: .5rem 1rem; font-family: inherit; font-size: .88rem; width: 240px; outline: none;
        transition: all .2s;
    }
    .search-wrap input:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }

    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #f8fafc; color: var(--text-muted); font-weight: 700;
        font-size: .78rem; text-transform: uppercase; letter-spacing: .06em;
        padding: .85rem 1.2rem; text-align: left; border-bottom: 1px solid var(--border-subtle);
    }
    tbody tr { border-bottom: 1px solid var(--border-subtle); transition: background .15s; }
    tbody tr:hover { background: #f8fafc; }
    tbody td { padding: .85rem 1.2rem; font-size: .9rem; color: var(--text-dark); vertical-align: middle; }

    .user-cell { display: flex; align-items: center; gap: .75rem; }
    .avatar-sm {
        width: 36px; height: 36px; border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
        color: #fff; display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: .95rem; flex-shrink: 0; box-shadow: 0 2px 8px rgba(16,111,78,.2);
    }
    .uname-main { font-weight: 700; color: var(--text-dark); font-size: .9rem; }
    .uname-sub  { font-size: .78rem; color: var(--text-muted); }

    .badge {
        padding: .25rem .75rem; border-radius: 20px; font-size: .76rem; font-weight: 700;
        display: inline-block; white-space: nowrap;
    }
    .badge-admin    { background: #f5f3ff; color: #6d28d9; border: 1px solid #ede9fe; }
    .badge-empleado { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
    .badge-cliente  { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-activo   { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-inactivo { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }

    .actions { display: flex; align-items: center; gap: .45rem; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .9rem; transition: all .15s; background: #f1f5f9; color: #374151;
    }
    .btn-icon:hover { transform: scale(1.1); }
    .btn-icon.blue   { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
    .btn-icon.yellow { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
    .btn-icon.red    { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }

    .pagination-wrap { padding: 1rem 1.6rem; border-top: 1px solid var(--border-subtle); }
    .pagination-wrap .pagination { display: flex; gap: .35rem; list-style: none; justify-content: flex-end; }
    .pagination-wrap .page-item .page-link {
        padding: .4rem .8rem; border-radius: 8px; text-decoration: none;
        font-size: .84rem; font-weight: 700; color: var(--text-dark); background: #fff; border: 1.5px solid var(--border-subtle);
        transition: all .2s;
    }
    .pagination-wrap .page-item .page-link:hover { background: #f8fafc; border-color: #cbd5e1; }
    .pagination-wrap .page-item.active .page-link { background: #106f4e; color: #fff; border-color: #106f4e; }

    /* Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(15,23,42,.5);
        backdrop-filter: blur(4px);
        z-index: 200; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 20px; padding: 2.2rem;
        width: 100%; max-width: 500px; position: relative;
        box-shadow: var(--shadow-lg);
    }
    .modal-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin-bottom: 1.4rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 700; color: #334155; margin-bottom: .35rem; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%; padding: .65rem .85rem; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        font-family: inherit; font-size: .9rem; outline: none; transition: border .18s;
    }
    .form-group input:focus, .form-group select:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }
    .modal-footer { display: flex; gap: .8rem; justify-content: flex-end; margin-top: .8rem; }
    .modal-close {
        position: absolute; top: 1.2rem; right: 1.2rem; background: #f1f5f9; border: none;
        border-radius: 8px; width: 30px; height: 30px; cursor: pointer; font-size: 1rem;
        color: var(--text-muted); transition: all .2s;
    }
    .btn-danger {
        padding: .5rem 1.1rem; border-radius: 8px; border: none; background: #f43f5e;
        font-family: inherit; font-size: .87rem; font-weight: 700; cursor: pointer; color: #fff;
    }
    .btn-danger:hover { background: #e11d48; }
</style>
@endpush

@section('content')

{{-- Stat Cards --}}
<div class="stats-row">
    <div class="stat-card green">
        <div class="sc-icon">👥</div>
        <div>
            <div class="sc-num">{{ $total }}</div>
            <div class="sc-label">Total Usuarios</div>
        </div>
    </div>
    <div class="stat-card purple">
        <div class="sc-icon">🔑</div>
        <div>
            <div class="sc-num">{{ $admins }}</div>
            <div class="sc-label">Administradores</div>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="sc-icon">🧑‍💼</div>
        <div>
            <div class="sc-num">{{ $clientes }}</div>
            <div class="sc-label">Clientes</div>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="card">
    <div class="card-header">
        <h2>Lista de Usuarios</h2>
        <div class="search-wrap">
            <input type="text" id="search-input" placeholder="🔍 Buscar usuario..." onkeyup="filterTable()">
        </div>
    </div>

    <table id="users-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Rol</th>
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
                    <div class="avatar-sm">{{ strtoupper(substr($u->nombres, 0, 1)) }}</div>
                    <div>
                        <div class="uname-main">{{ $u->nombres }} {{ $u->apellidos }}</div>
                        <div class="uname-sub">ID #{{ $u->id_usuario }}</div>
                    </div>
                </div>
            </td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->telefono ?? '—' }}</td>
            <td>
                <span class="badge
                    @if($u->rol === 'administrador') badge-admin
                    @elseif($u->rol === 'empleado') badge-empleado
                    @else badge-cliente @endif">
                    {{ ucfirst($u->rol) }}
                </span>
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
                    {{-- Edit button triggers modal --}}
                    <button class="btn-icon blue" title="Editar"
                        onclick="openEditModal(
                            {{ $u->id_usuario }},
                            '{{ addslashes($u->nombres) }}',
                            '{{ addslashes($u->apellidos) }}',
                            '{{ addslashes($u->email) }}',
                            '{{ addslashes($u->telefono ?? '') }}',
                            '{{ $u->rol }}'
                        )">✏️</button>

                    {{-- Toggle estado --}}
                    <form method="POST" action="{{ route('usuarios.toggle', $u->id_usuario) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-icon yellow" title="{{ $u->estado ? 'Desactivar' : 'Activar' }}">
                            {{ $u->estado ? '🔒' : '🔓' }}
                        </button>
                    </form>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('usuarios.destroy', $u->id_usuario) }}" style="display:inline"
                          onsubmit="return confirm('¿Eliminar este usuario?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon red" title="Eliminar">🗑️</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af;">No hay usuarios registrados.</td>
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

{{-- Create Modal --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        <div class="modal-title">➕ Nuevo Usuario</div>
        <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Nombres *</label>
                    <input type="text" name="nombres" required value="{{ old('nombres') }}">
                </div>
                <div class="form-group">
                    <label>Apellidos *</label>
                    <input type="text" name="apellidos" required value="{{ old('apellidos') }}">
                </div>
            </div>
            <div class="form-group">
                <label>Correo Electrónico *</label>
                <input type="email" name="email" required value="{{ old('email') }}">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}">
                </div>
                <div class="form-group">
                    <label>Rol *</label>
                    <select name="rol" required>
                        <option value="administrador" {{ old('rol') === 'administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="empleado" {{ old('rol') === 'empleado' ? 'selected' : '' }}>Empleado</option>
                        <option value="cliente" {{ old('rol','cliente') === 'cliente' ? 'selected' : '' }}>Cliente</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Contraseña *</label>
                <input type="password" name="password" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Cancelar</button>
                <button type="submit" class="btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        <div class="modal-title">✏️ Editar Usuario</div>
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
                    <label>Teléfono</label>
                    <input type="text" name="telefono" id="edit-telefono">
                </div>
                <div class="form-group">
                    <label>Rol *</label>
                    <select name="rol" id="edit-rol" required>
                        <option value="administrador">Administrador</option>
                        <option value="empleado">Empleado</option>
                        <option value="cliente">Cliente</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Nueva Contraseña <small style="color:#9ca3af">(dejar vacío para no cambiar)</small></label>
                <input type="password" name="password">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit')">Cancelar</button>
                <button type="submit" class="btn-primary">Actualizar</button>
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
    const q = document.getElementById('search-input').value.toLowerCase();
    document.querySelectorAll('#users-table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
// Close modal when clicking outside
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
