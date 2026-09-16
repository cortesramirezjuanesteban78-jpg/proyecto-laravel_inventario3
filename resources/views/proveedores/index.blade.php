@extends('layouts.sidebar')

@section('title', 'Proveedores')
@section('page-title', 'Proveedores')
@section('page-subtitle', $total . ' proveedores — ' . $activos . ' activos')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-primary">+ Nuevo Proveedor</button>
@endsection

@push('styles')
<style>
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
        color: #fff; border: none; padding: .6rem 1.25rem;
        border-radius: 10px; font-family: inherit; font-size: .88rem; font-weight: 700;
        cursor: pointer; display: inline-flex; align-items: center; gap: .45rem;
        box-shadow: var(--shadow-glow); transition: all .25s ease;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(16,111,78,.35); }
    .btn-secondary {
        padding: .55rem 1.15rem; border-radius: 10px; border: 1.5px solid var(--border-subtle);
        background: #fff; font-family: inherit; font-size: .88rem; font-weight: 700; cursor: pointer; color: var(--text-dark);
        transition: all .2s;
    }
    .btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }

    .stats-row { display: flex; gap: 1.2rem; margin-bottom: 2rem; }
    .stat-card {
        flex: 0 0 220px; border-radius: 16px; padding: 1.3rem 1.5rem; background: #fff;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1rem;
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .stat-card.teal { border-left: 4px solid #106f4e; }
    .stat-card.teal .sc-icon { background: #ecfdf5; border: 1px solid #d1fae5; }
    .stat-card.gray { border-left: 4px solid #94a3b8; }
    .stat-card.gray .sc-icon { background: #f1f5f9; border: 1px solid #e2e8f0; }

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

    .prov-cell { display: flex; align-items: center; gap: .75rem; }
    .prov-icon {
        width: 36px; height: 36px; border-radius: 10px;
        background: #ecfdf5; color: #106f4e; border: 1px solid #d1fae5;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .prov-name { font-weight: 700; color: var(--text-dark); }

    .badge { padding: .25rem .75rem; border-radius: 20px; font-size: .76rem; font-weight: 700; }
    .badge-activo   { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-inactivo { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }

    .actions { display: flex; align-items: center; gap: .45rem; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .9rem; transition: all .15s; background: #f1f5f9;
    }
    .btn-icon:hover { transform: scale(1.1); }
    .btn-icon.blue   { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
    .btn-icon.yellow { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
    .btn-icon.red    { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }

    .pag-wrap { padding: 1rem 1.6rem; border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; }

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
    .form-group input {
        width: 100%; padding: .65rem .85rem; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        font-family: inherit; font-size: .9rem; outline: none; transition: all .2s;
    }
    .form-group input:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }
    .modal-footer { display: flex; gap: .8rem; justify-content: flex-end; margin-top: .8rem; }
    .modal-close {
        position: absolute; top: 1.2rem; right: 1.2rem; background: #f1f5f9; border: none;
        border-radius: 8px; width: 30px; height: 30px; cursor: pointer; font-size: 1rem;
        color: var(--text-muted); transition: all .2s;
    }
    .modal-close:hover { background: #fff1f2; color: #f43f5e; }
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
