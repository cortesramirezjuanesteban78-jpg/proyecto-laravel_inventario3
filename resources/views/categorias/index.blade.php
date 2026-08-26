@extends('layouts.sidebar')

@section('title', 'Categorías')
@section('page-title', 'Categorías')
@section('page-subtitle', $count . ' categorías registradas')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-primary">+ Nueva Categoría</button>
@endsection

@push('styles')
<style>
    .btn-primary {
        background: #16a34a; color: #fff; border: none; padding: .55rem 1.2rem;
        border-radius: 8px; font-family: inherit; font-size: .88rem; font-weight: 600;
        cursor: pointer; transition: background .18s;
    }
    .btn-primary:hover { background: #15803d; }

    /* Category cards grid */
    .cats-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem; margin-bottom: 2rem;
    }
    .cat-card {
        border-radius: 14px; padding: 1.3rem 1.2rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        display: flex; flex-direction: column; gap: .3rem;
    }
    .cat-card .cat-icon { font-size: 1.6rem; margin-bottom: .3rem; }
    .cat-card .cat-name { font-weight: 700; font-size: .95rem; color: #111827; }
    .cat-card .cat-desc { font-size: .8rem; color: #6b7280; }
    .cat-card .cat-actions { display: flex; gap: .4rem; margin-top: .6rem; }

    .color-0 { background: #d1fae5; }
    .color-1 { background: #dbeafe; }
    .color-2 { background: #ede9fe; }
    .color-3 { background: #ffedd5; }
    .color-4 { background: #ccfbf1; }
    .color-5 { background: #fce7f3; }

    /* Table */
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
    .search-wrap input:focus { box-shadow: 0 0 0 2px #bbf7d0; }
    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #f0fdf4; color: #15803d; font-weight: 600;
        font-size: .78rem; text-transform: uppercase; letter-spacing: .05em;
        padding: .75rem 1rem; text-align: left;
    }
    tbody tr { border-bottom: 1px solid #f3f4f6; }
    tbody tr:hover { background: #fafafa; }
    tbody td { padding: .7rem 1rem; font-size: .88rem; color: #374151; vertical-align: middle; }

    .actions { display: flex; align-items: center; gap: .4rem; }
    .btn-icon {
        width: 30px; height: 30px; border-radius: 7px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .85rem; transition: all .15s; background: #f3f4f6;
    }
    .btn-icon:hover { transform: scale(1.1); }
    .btn-icon.blue { background: #dbeafe; color: #1d4ed8; }
    .btn-icon.red  { background: #fee2e2; color: #b91c1c; }

    /* Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
        z-index: 200; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 16px; padding: 2rem;
        width: 100%; max-width: 440px; position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .modal-title { font-size: 1.1rem; font-weight: 800; color: #111827; margin-bottom: 1.2rem; }
    .form-group { margin-bottom: .9rem; }
    .form-group label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .3rem; }
    .form-group input, .form-group textarea {
        width: 100%; padding: .55rem .8rem; border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-family: inherit; font-size: .88rem; outline: none;
    }
    .form-group input:focus, .form-group textarea:focus { border-color: #16a34a; box-shadow: 0 0 0 2px #bbf7d0; }
    .modal-footer { display: flex; gap: .7rem; justify-content: flex-end; margin-top: .5rem; }
    .btn-secondary {
        padding: .5rem 1.1rem; border-radius: 8px; border: 1.5px solid #e5e7eb;
        background: #fff; font-family: inherit; font-size: .87rem; font-weight: 600; cursor: pointer; color: #374151;
    }
    .btn-secondary:hover { background: #f9fafb; }
    .modal-close {
        position: absolute; top: 1rem; right: 1rem; background: #f3f4f6; border: none;
        border-radius: 7px; width: 28px; height: 28px; cursor: pointer; font-size: .95rem;
    }
    .modal-close:hover { background: #fee2e2; color: #b91c1c; }
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
