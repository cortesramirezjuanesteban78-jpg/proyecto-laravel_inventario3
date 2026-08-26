@extends('layouts.sidebar')

@section('title', 'Usuarios')
@section('page-title', 'Usuarios')
@section('page-subtitle', $total . ' cuentas registradas')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-primary">+ Nuevo Usuario</button>
@endsection

@push('styles')
<style>
    .btn-primary {
        background: #16a34a; color: #fff; border: none; padding: .55rem 1.2rem;
        border-radius: 8px; font-family: inherit; font-size: .88rem; font-weight: 600;
        cursor: pointer; transition: background .18s;
    }
    .btn-primary:hover { background: #15803d; }

    .stats-row { display: flex; gap: 1.2rem; margin-bottom: 1.8rem; }
    .stat-card {
        flex: 1; border-radius: 14px; padding: 1.2rem 1.4rem;
        display: flex; align-items: center; gap: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
    }
    .stat-card.green  { background: #d1fae5; }
    .stat-card.purple { background: #ede9fe; }
    .stat-card.blue   { background: #dbeafe; }
    .stat-card .sc-icon { font-size: 1.8rem; }
    .stat-card .sc-num  { font-size: 1.8rem; font-weight: 900; color: #111827; line-height: 1; }
    .stat-card .sc-label { font-size: .88rem; font-weight: 600; color: #374151; }

    .card { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.4rem; border-bottom: 1px solid #f3f4f6;
    }
    .card-header h2 { font-size: 1rem; font-weight: 700; color: #111827; }

    .search-wrap input {
        background: #f3f4f6; border: none; border-radius: 8px;
        padding: .45rem .9rem; font-family: inherit; font-size: .85rem;
        width: 220px; outline: none;
    }
    .search-wrap input:focus { box-shadow: 0 0 0 2px #bbf7d0; }

    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #f0fdf4; color: #15803d; font-weight: 600;
        font-size: .78rem; text-transform: uppercase; letter-spacing: .05em;
        padding: .75rem 1rem; text-align: left;
    }
    tbody tr { border-bottom: 1px solid #f3f4f6; transition: background .15s; }
    tbody tr:hover { background: #fafafa; }
    tbody td { padding: .7rem 1rem; font-size: .88rem; color: #374151; vertical-align: middle; }

    .user-cell { display: flex; align-items: center; gap: .7rem; }
    .avatar-sm {
        width: 34px; height: 34px; border-radius: 10px; background: #16a34a; color: #fff;
        display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: .9rem; flex-shrink: 0;
    }
    .uname-main { font-weight: 600; color: #111827; font-size: .88rem; }
    .uname-sub  { font-size: .75rem; color: #9ca3af; }

    .badge {
        padding: .25rem .7rem; border-radius: 20px; font-size: .75rem; font-weight: 700;
        display: inline-block; white-space: nowrap;
    }
    .badge-admin    { background: #ede9fe; color: #6d28d9; }
    .badge-empleado { background: #dbeafe; color: #1d4ed8; }
    .badge-cliente  { background: #d1fae5; color: #065f46; }
    .badge-activo   { background: #dcfce7; color: #15803d; }
    .badge-inactivo { background: #fee2e2; color: #b91c1c; }

    .actions { display: flex; align-items: center; gap: .4rem; }
    .btn-icon {
        width: 30px; height: 30px; border-radius: 7px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .85rem; transition: all .15s; background: #f3f4f6; color: #374151;
    }
    .btn-icon:hover { transform: scale(1.1); }
    .btn-icon.blue   { background: #dbeafe; color: #1d4ed8; }
    .btn-icon.yellow { background: #fef9c3; color: #92400e; }
    .btn-icon.red    { background: #fee2e2; color: #b91c1c; }

    .pagination-wrap { padding: .8rem 1.4rem; border-top: 1px solid #f3f4f6; }
    .pagination-wrap .pagination { display: flex; gap: .3rem; list-style: none; justify-content: flex-end; }
    .pagination-wrap .page-item .page-link {
        padding: .35rem .7rem; border-radius: 6px; text-decoration: none;
        font-size: .82rem; color: #374151; background: #f9fafb; border: 1px solid #e5e7eb;
    }
    .pagination-wrap .page-item.active .page-link { background: #16a34a; color: #fff; border-color: #16a34a; }

    /* Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
        z-index: 200; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 16px; padding: 2rem;
        width: 100%; max-width: 480px; position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .modal-title { font-size: 1.1rem; font-weight: 800; color: #111827; margin-bottom: 1.2rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }
    .form-group { margin-bottom: .9rem; }
    .form-group label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .3rem; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%; padding: .55rem .8rem; border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-family: inherit; font-size: .88rem; outline: none; transition: border .18s;
    }
    .form-group input:focus, .form-group select:focus { border-color: #16a34a; box-shadow: 0 0 0 2px #bbf7d0; }
    .modal-footer { display: flex; gap: .7rem; justify-content: flex-end; margin-top: .5rem; }
    .btn-secondary {
        padding: .5rem 1.1rem; border-radius: 8px; border: 1.5px solid #e5e7eb;
        background: #fff; font-family: inherit; font-size: .87rem; font-weight: 600;
        cursor: pointer; color: #374151;
    }
    .btn-secondary:hover { background: #f9fafb; }
    .btn-danger {
        padding: .5rem 1.1rem; border-radius: 8px; border: none; background: #ef4444;
        font-family: inherit; font-size: .87rem; font-weight: 600; cursor: pointer; color: #fff;
    }
    .btn-danger:hover { background: #dc2626; }
    .modal-close {
        position: absolute; top: 1rem; right: 1rem; background: #f3f4f6; border: none;
        border-radius: 7px; width: 28px; height: 28px; cursor: pointer; font-size: .95rem;
    }
    .modal-close:hover { background: #fee2e2; color: #b91c1c; }
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
