@extends('layouts.sidebar')

@section('title', 'Categorías')
@section('page-title', 'Categorías')
@section('page-subtitle', $count . ' categorías registradas')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-primary">+ Nueva Categoría</button>
@endsection

@push('styles')
<style>
    /* Category cards grid */
    .cats-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 1.2rem; margin-bottom: 2.2rem;
    }
    .cat-card {
        border-radius: 16px; padding: 1.4rem 1.3rem; background: #fff;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        display: flex; flex-direction: column; gap: .35rem;
        transition: transform .2s, box-shadow .2s;
    }
    .cat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .cat-card .cat-icon { font-size: 1.8rem; margin-bottom: .4rem; }
    .cat-card .cat-name { font-weight: 700; font-size: 1rem; color: var(--text-dark); }
    .cat-card .cat-desc { font-size: .82rem; color: var(--text-muted); }
    .cat-card .cat-actions { display: flex; gap: .45rem; margin-top: .8rem; }

    .color-0 { border-left: 4px solid #106f4e; }
    .color-1 { border-left: 4px solid #2563eb; }
    .color-2 { border-left: 4px solid #7c3aed; }
    .color-3 { border-left: 4px solid #d97706; }
    .color-4 { border-left: 4px solid #0d9488; }
    .color-5 { border-left: 4px solid #f43f5e; }

    /* Table */
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

    .actions { display: flex; align-items: center; gap: .45rem; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .9rem; transition: all .15s; background: #f1f5f9;
    }
    .btn-icon:hover { transform: scale(1.1); }
    .btn-icon.blue { background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
    .btn-icon.red  { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }

    /* Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(15,23,42,.5);
        backdrop-filter: blur(4px);
        z-index: 200; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 20px; padding: 2.2rem;
        width: 100%; max-width: 460px; position: relative;
        box-shadow: var(--shadow-lg);
    }
    .modal-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin-bottom: 1.4rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 700; color: #334155; margin-bottom: .35rem; }
    .form-group input, .form-group textarea {
        width: 100%; padding: .65rem .85rem; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        font-family: inherit; font-size: .9rem; outline: none; transition: all .2s;
    }
    .form-group input:focus, .form-group textarea:focus {
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

{{-- Category Cards --}}
@if($categorias->count() > 0)
<div class="cats-grid">
    @php $icons = ['🍎','🥛','🥩','🧼','🥤','🍞','🧃','🥦','🏠','🛒']; @endphp
    @foreach($categorias as $i => $cat)
    <div class="cat-card color-{{ $i % 6 }}">
        <div class="cat-icon">{{ $icons[$i % count($icons)] }}</div>
        <div class="cat-name">{{ $cat->nombre }}</div>
        <div class="cat-desc">{{ $cat->descripcion ?? 'Sin descripción' }}</div>
        <div class="cat-actions">
            <button class="btn-icon blue" title="Editar"
                onclick="openEditModal({{ $cat->id_categoria }}, '{{ addslashes($cat->nombre) }}', '{{ addslashes($cat->descripcion ?? '') }}')">
                ✏️
            </button>
            <form method="POST" action="{{ route('categorias.destroy', $cat->id_categoria) }}" style="display:inline"
                  onsubmit="return confirm('¿Eliminar esta categoría?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-icon red" title="Eliminar">🗑️</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h2>Lista Completa</h2>
        <div class="search-wrap">
            <input type="text" id="search-input" placeholder="🔍 Buscar..." onkeyup="filterTable()">
        </div>
    </div>
    <table id="cat-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($categorias as $cat)
        <tr>
            <td>{{ $cat->id_categoria }}</td>
            <td><strong>{{ $cat->nombre }}</strong></td>
            <td>{{ $cat->descripcion ?? '—' }}</td>
            <td>
                <div class="actions">
                    <button class="btn-icon blue" title="Editar"
                        onclick="openEditModal({{ $cat->id_categoria }}, '{{ addslashes($cat->nombre) }}', '{{ addslashes($cat->descripcion ?? '') }}')">
                        ✏️
                    </button>
                    <form method="POST" action="{{ route('categorias.destroy', $cat->id_categoria) }}" style="display:inline"
                          onsubmit="return confirm('¿Eliminar esta categoría?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon red" title="Eliminar">🗑️</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align:center;padding:2rem;color:#9ca3af;">No hay categorías registradas.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Create Modal --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        <div class="modal-title">➕ Nueva Categoría</div>
        <form method="POST" action="{{ route('categorias.store') }}">
            @csrf
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" required placeholder="Ej: Lácteos, Carnes...">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="2" placeholder="Descripción opcional..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Cancelar</button>
                <button type="submit" class="btn-primary">Crear Categoría</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        <div class="modal-title">✏️ Editar Categoría</div>
        <form method="POST" id="edit-form" action="">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" id="edit-nombre" required>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" id="edit-descripcion" rows="2"></textarea>
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
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function openEditModal(id, nombre, desc) {
    document.getElementById('edit-form').action = '/categorias/' + id;
    document.getElementById('edit-nombre').value = nombre;
    document.getElementById('edit-descripcion').value = desc;
    openModal('modal-edit');
}
function filterTable() {
    const q = document.getElementById('search-input').value.toLowerCase();
    document.querySelectorAll('#cat-table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
</script>
@endpush
