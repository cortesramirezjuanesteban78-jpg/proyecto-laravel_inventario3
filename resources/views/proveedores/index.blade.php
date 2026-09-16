@extends('layouts.sidebar')

@section('title', 'Proveedores')
@section('page-title', 'Proveedores')
@section('page-subtitle', $total . ' proveedores — ' . $activos . ' activos')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-teal">+ Nuevo Proveedor</button>
@endsection

@push('styles')
<style>
    .btn-teal {
        background: #0d9488; color: #fff; border: none; padding: .55rem 1.2rem;
        border-radius: 8px; font-family: inherit; font-size: .88rem; font-weight: 600; cursor: pointer;
    }
    .btn-teal:hover { background: #0f766e; }
    .btn-primary {
        background: #16a34a; color: #fff; border: none; padding: .5rem 1.1rem;
        border-radius: 8px; font-family: inherit; font-size: .87rem; font-weight: 600; cursor: pointer;
    }
    .btn-primary:hover { background: #15803d; }
    .btn-secondary {
        padding: .5rem 1.1rem; border-radius: 8px; border: 1.5px solid #e5e7eb;
        background: #fff; font-family: inherit; font-size: .87rem; font-weight: 600; cursor: pointer;
    }

    .stats-row { display: flex; gap: 1.2rem; margin-bottom: 1.8rem; }
    .stat-card {
        flex: 0 0 200px; border-radius: 14px; padding: 1.2rem 1.4rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); display: flex; align-items: center; gap: 1rem;
    }
    .stat-card.teal { background: #ccfbf1; }
    .stat-card.gray { background: #f3f4f6; }
    .stat-card .sc-icon { font-size: 1.8rem; }
    .stat-card .sc-num  { font-size: 1.8rem; font-weight: 900; color: #111827; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: #374151; }

    .card { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.4rem; border-bottom: 1px solid #f3f4f6;
    }
    .card-header h2 { font-size: 1rem; font-weight: 700; color: #111827; }
    .search-wrap input {
        background: #f3f4f6; border: none; border-radius: 8px;
        padding: .45rem .9rem; font-family: inherit; font-size: .85rem; width: 220px; outline: none;
    }
    .search-wrap input:focus { box-shadow: 0 0 0 2px #99f6e4; }

    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #f0fdf4; color: #15803d; font-weight: 600;
        font-size: .78rem; text-transform: uppercase; letter-spacing: .05em;
        padding: .75rem 1rem; text-align: left;
    }
    tbody tr { border-bottom: 1px solid #f3f4f6; }
    tbody tr:hover { background: #fafafa; }
    tbody td { padding: .7rem 1rem; font-size: .88rem; color: #374151; vertical-align: middle; }

    .prov-cell { display: flex; align-items: center; gap: .6rem; }
    .prov-icon {
        width: 34px; height: 34px; border-radius: 10px;
        background: #ccfbf1; color: #0f766e;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .prov-name { font-weight: 600; color: #111827; }

    .badge { padding: .22rem .65rem; border-radius: 20px; font-size: .75rem; font-weight: 700; }
    .badge-activo   { background: #dcfce7; color: #15803d; }
    .badge-inactivo { background: #fee2e2; color: #b91c1c; }

    .actions { display: flex; align-items: center; gap: .4rem; }
    .btn-icon {
        width: 30px; height: 30px; border-radius: 7px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .85rem; transition: all .15s; background: #f3f4f6;
    }
    .btn-icon:hover { transform: scale(1.1); }
    .btn-icon.blue   { background: #dbeafe; color: #1d4ed8; }
    .btn-icon.yellow { background: #fef9c3; color: #92400e; }
    .btn-icon.red    { background: #fee2e2; color: #b91c1c; }

    .pag-wrap { padding: .8rem 1.4rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; }

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
    .form-group input {
        width: 100%; padding: .55rem .8rem; border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-family: inherit; font-size: .88rem; outline: none;
    }
    .form-group input:focus { border-color: #0d9488; box-shadow: 0 0 0 2px #99f6e4; }
    .modal-footer { display: flex; gap: .7rem; justify-content: flex-end; margin-top: .5rem; }
    .modal-close {
        position: absolute; top: 1rem; right: 1rem; background: #f3f4f6; border: none;
        border-radius: 7px; width: 28px; height: 28px; cursor: pointer; font-size: .95rem;
    }
    .modal-close:hover { background: #fee2e2; color: #b91c1c; }
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card teal">
        <div class="sc-icon">✅</div>
        <div><div class="sc-num">{{ $activos }}</div><div class="sc-label">Activos</div></div>
    </div>
    <div class="stat-card gray">
        <div class="sc-icon">⛔</div>
        <div><div class="sc-num">{{ $inactivos }}</div><div class="sc-label">Inactivos</div></div>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h2>🚚 Lista de Proveedores</h2>
        <div class="search-wrap">
            <input type="text" id="search-input" placeholder="🔍 Buscar proveedor..." onkeyup="filterTable()">
        </div>
    </div>
    <table id="provs-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Proveedor</th>
                <th>Documento</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Dirección</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($proveedores as $prov)
        <tr>
            <td>{{ $prov->id_proveedor }}</td>
            <td>
                <div class="prov-cell">
                    <div class="prov-icon">🚚</div>
                    <div class="prov-name">{{ $prov->nombre }}</div>
                </div>
            </td>
            <td>{{ $prov->documento ?? '—' }}</td>
            <td>{{ $prov->telefono ?? '—' }}</td>
            <td>{{ $prov->correo ?? '—' }}</td>
            <td>{{ \Illuminate\Support\Str::limit($prov->direccion ?? '—', 30) }}</td>
            <td>
                @if($prov->estado)
                    <span class="badge badge-activo">• Activo</span>
                @else
                    <span class="badge badge-inactivo">• Inactivo</span>
                @endif
            </td>
            <td>
                <div class="actions">
                    <button class="btn-icon blue" title="Editar"
                        onclick="openEditModal(
                            {{ $prov->id_proveedor }},
                            '{{ addslashes($prov->nombre) }}',
                            '{{ addslashes($prov->documento ?? '') }}',
                            '{{ addslashes($prov->telefono ?? '') }}',
                            '{{ addslashes($prov->correo ?? '') }}',
                            '{{ addslashes($prov->direccion ?? '') }}'
                        )">✏️</button>
                    <form method="POST" action="{{ route('proveedores.toggle', $prov->id_proveedor) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn-icon yellow" title="{{ $prov->estado ? 'Desactivar' : 'Activar' }}">
                            {{ $prov->estado ? '🔒' : '🔓' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('proveedores.destroy', $prov->id_proveedor) }}" style="display:inline"
                          onsubmit="return confirm('¿Eliminar este proveedor?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon red" title="Eliminar">🗑️</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align:center;padding:2rem;color:#9ca3af;">No hay proveedores registrados.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
    @if($proveedores->hasPages())
    <div class="pag-wrap">{{ $proveedores->links() }}</div>
    @endif
</div>

{{-- Create Modal --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        <div class="modal-title">➕ Nuevo Proveedor</div>
        <form method="POST" action="{{ route('proveedores.store') }}">
            @csrf
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" required placeholder="Nombre de la empresa o persona">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Documento</label>
                    <input type="text" name="documento" placeholder="RUC, DNI...">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono">
                </div>
            </div>
            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" placeholder="correo@empresa.com">
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Cancelar</button>
                <button type="submit" class="btn-teal">Crear Proveedor</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        <div class="modal-title">✏️ Editar Proveedor</div>
        <form method="POST" id="edit-form" action="">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" id="e-nombre" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Documento</label>
                    <input type="text" name="documento" id="e-documento">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" id="e-telefono">
                </div>
            </div>
            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" id="e-correo">
            </div>
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion" id="e-direccion">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit')">Cancelar</button>
                <button type="submit" class="btn-teal">Actualizar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function openEditModal(id, nombre, doc, tel, correo, dir) {
    document.getElementById('edit-form').action = '/proveedores/' + id;
    document.getElementById('e-nombre').value = nombre;
    document.getElementById('e-documento').value = doc;
    document.getElementById('e-telefono').value = tel;
    document.getElementById('e-correo').value = correo;
    document.getElementById('e-direccion').value = dir;
    openModal('modal-edit');
}
function filterTable() {
    const q = document.getElementById('search-input').value.toLowerCase();
    document.querySelectorAll('#provs-table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
</script>
@endpush
