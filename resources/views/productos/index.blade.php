@extends('layouts.sidebar')

@section('title', 'Productos')
@section('page-title', 'Productos')
@section('page-subtitle', $total . ' productos en catálogo')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-purple">+ Nuevo Producto</button>
@endsection

@push('styles')
<style>
    .btn-purple {
        background: #7c3aed; color: #fff; border: none; padding: .55rem 1.2rem;
        border-radius: 8px; font-family: inherit; font-size: .88rem; font-weight: 600; cursor: pointer;
    }
    .btn-purple:hover { background: #6d28d9; }
    .btn-primary {
        background: #16a34a; color: #fff; border: none; padding: .5rem 1.1rem;
        border-radius: 8px; font-family: inherit; font-size: .87rem; font-weight: 600; cursor: pointer;
    }
    .btn-primary:hover { background: #15803d; }
    .btn-secondary {
        padding: .5rem 1.1rem; border-radius: 8px; border: 1.5px solid #e5e7eb;
        background: #fff; font-family: inherit; font-size: .87rem; font-weight: 600; cursor: pointer; color: #374151;
    }

    /* Stat row */
    .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.2rem; margin-bottom: 1.8rem; }
    .stat-card {
        border-radius: 14px; padding: 1.2rem 1.4rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); display: flex; align-items: center; gap: 1rem;
    }
    .stat-card.green  { background: #d1fae5; }
    .stat-card.blue   { background: #dbeafe; }
    .stat-card.orange { background: #ffedd5; }
    .stat-card.red    { background: #fee2e2; }
    .stat-card .sc-icon { font-size: 1.8rem; }
    .stat-card .sc-num  { font-size: 1.8rem; font-weight: 900; color: #111827; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: #374151; }

    /* Filters */
    .filters-row { display: flex; gap: .8rem; margin-bottom: 1.4rem; flex-wrap: wrap; align-items: center; }
    .filters-row input, .filters-row select {
        background: #f3f4f6; border: none; border-radius: 8px;
        padding: .5rem .9rem; font-family: inherit; font-size: .85rem; outline: none;
    }
    .filters-row input { width: 220px; }
    .filters-row input:focus, .filters-row select:focus { box-shadow: 0 0 0 2px #bbf7d0; }

    /* Product cards grid */
    .products-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.2rem;
        margin-bottom: 1.5rem;
    }
    .product-card {
        background: #fff; border-radius: 14px; overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); transition: transform .2s, box-shadow .2s;
    }
    .product-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
    .product-card img { width: 100%; height: 130px; object-fit: cover; }
    .product-card .pc-body { padding: .9rem 1rem; }
    .pc-badge {
        display: inline-block; padding: .18rem .6rem; border-radius: 20px;
        font-size: .72rem; font-weight: 700; margin-bottom: .4rem;
    }
    .pc-name { font-weight: 700; font-size: .9rem; color: #111827; margin-bottom: .2rem; }
    .pc-code { font-size: .75rem; color: #9ca3af; margin-bottom: .5rem; }
    .pc-price { font-size: 1rem; font-weight: 800; color: #16a34a; margin-bottom: .5rem; }
    .stock-bar-wrap { margin-bottom: .5rem; }
    .stock-label { display: flex; justify-content: space-between; font-size: .75rem; color: #6b7280; margin-bottom: .2rem; }
    .stock-bar { height: 5px; border-radius: 3px; background: #e5e7eb; overflow: hidden; }
    .stock-fill { height: 100%; border-radius: 3px; }
    .stock-fill.ok  { background: #16a34a; }
    .stock-fill.low { background: #f97316; }
    .stock-fill.out { background: #ef4444; }
    .pc-actions { display: flex; gap: .4rem; margin-top: .6rem; }
    .btn-edit-sm {
        flex: 1; padding: .35rem; border-radius: 7px; background: #dbeafe; color: #1d4ed8;
        border: none; cursor: pointer; font-size: .8rem; font-weight: 600;
    }
    .btn-edit-sm:hover { background: #bfdbfe; }
    .btn-icon {
        width: 30px; height: 30px; border-radius: 7px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .85rem; transition: all .15s;
    }
    .btn-icon.yellow { background: #fef9c3; color: #92400e; }
    .btn-icon.red    { background: #fee2e2; color: #b91c1c; }
    .btn-icon:hover  { transform: scale(1.1); }

    /* Pagination */
    .pag-wrap { display: flex; justify-content: center; }

    /* Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
        z-index: 200; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 16px; padding: 2rem;
        width: 100%; max-width: 520px; position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,.2); max-height: 90vh; overflow-y: auto;
    }
    .modal-title { font-size: 1.1rem; font-weight: 800; color: #111827; margin-bottom: 1.2rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }
    .form-group { margin-bottom: .9rem; }
    .form-group label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .3rem; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%; padding: .55rem .8rem; border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-family: inherit; font-size: .88rem; outline: none;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: #7c3aed; box-shadow: 0 0 0 2px #ede9fe;
    }
    .modal-footer { display: flex; gap: .7rem; justify-content: flex-end; margin-top: .5rem; }
    .modal-close {
        position: absolute; top: 1rem; right: 1rem; background: #f3f4f6; border: none;
        border-radius: 7px; width: 28px; height: 28px; cursor: pointer; font-size: .95rem;
    }
    .modal-close:hover { background: #fee2e2; color: #b91c1c; }

    /* Empty state */
    .empty-state { text-align: center; padding: 3rem; color: #9ca3af; }
    .empty-state .empty-icon { font-size: 3rem; margin-bottom: 1rem; }
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card green">
        <div class="sc-icon">📦</div>
        <div><div class="sc-num">{{ $total }}</div><div class="sc-label">Total Productos</div></div>
    </div>
    <div class="stat-card blue">
        <div class="sc-icon">✅</div>
        <div><div class="sc-num">{{ $activos }}</div><div class="sc-label">Activos</div></div>
    </div>
    <div class="stat-card orange">
        <div class="sc-icon">⚠️</div>
        <div><div class="sc-num">{{ $stockBajo }}</div><div class="sc-label">Stock Bajo</div></div>
    </div>
    <div class="stat-card red">
        <div class="sc-icon">🚫</div>
        <div><div class="sc-num">{{ $agotados }}</div><div class="sc-label">Agotados</div></div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('productos.index') }}" class="filters-row">
    <input type="text" name="search" placeholder="🔍 Buscar producto..." value="{{ request('search') }}">
    <select name="estado" onchange="this.form.submit()">
        <option value="">Todos los estados</option>
        <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>Activos</option>
        <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>Inactivos</option>
    </select>
    <select name="categoria" onchange="this.form.submit()">
        <option value="">Todas las categorías</option>
        @foreach($categorias as $cat)
        <option value="{{ $cat->id_categoria }}" {{ request('categoria') == $cat->id_categoria ? 'selected' : '' }}>
            {{ $cat->nombre }}
        </option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary">Filtrar</button>
    @if(request()->hasAny(['search','estado','categoria']))
    <a href="{{ route('productos.index') }}" style="color:#6b7280;font-size:.85rem;text-decoration:none;">✕ Limpiar</a>
    @endif
</form>

{{-- Products Grid --}}
@if($productos->count() > 0)
<div class="products-grid">
    @php
        $catColors = ['#d1fae5','#dbeafe','#ede9fe','#ffedd5','#ccfbf1','#fce7f3'];
        $catTextColors = ['#065f46','#1d4ed8','#6d28d9','#c2410c','#0f766e','#be185d'];
    @endphp
    @foreach($productos as $p)
    @php
        $idx = ($p->id_categoria - 1) % 6;
        if ($p->stock_actual == 0) { $stockClass = 'out'; }
        elseif ($p->stock_actual <= $p->stock_minimo) { $stockClass = 'low'; }
        else { $stockClass = 'ok'; }
        $stockPct = $p->stock_minimo > 0 ? min(100, ($p->stock_actual / ($p->stock_minimo * 2)) * 100) : min(100, $p->stock_actual);
    @endphp
    <div class="product-card">
        <img src="{{ $p->imagen ?: 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=60' }}"
             alt="{{ $p->nombre }}" loading="lazy">
        <div class="pc-body">
            @if($p->categoria)
            <span class="pc-badge"
                  style="background: {{ $catColors[$idx] }}; color: {{ $catTextColors[$idx] }};">
                {{ $p->categoria->nombre }}
            </span>
            @endif
            <div class="pc-name">{{ $p->nombre }}</div>
            <div class="pc-code">{{ $p->codigo }}</div>
            <div class="pc-price">${{ number_format($p->precio, 2) }}</div>
            <div class="stock-bar-wrap">
                <div class="stock-label">
                    <span>Stock: {{ $p->stock_actual }}</span>
                    <span>Mín: {{ $p->stock_minimo }}</span>
                </div>
                <div class="stock-bar">
                    <div class="stock-fill {{ $stockClass }}" style="width: {{ $stockPct }}%"></div>
                </div>
            </div>
            <div class="pc-actions">
                <button class="btn-edit-sm" onclick="openEditModal(
                    {{ $p->id_producto }},
                    '{{ addslashes($p->nombre) }}',
                    '{{ addslashes($p->codigo) }}',
                    '{{ addslashes($p->descripcion ?? '') }}',
                    {{ $p->precio }},
                    {{ $p->stock_actual }},
                    {{ $p->stock_minimo }},
                    {{ $p->id_categoria }},
                    {{ $p->id_proveedor ?? 'null' }},
                    {{ $p->estado }}
                )">✏️ Editar</button>
                <form method="POST" action="{{ route('productos.toggle', $p->id_producto) }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-icon yellow" title="{{ $p->estado ? 'Desactivar':'Activar' }}">
                        {{ $p->estado ? '🔒' : '🔓' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('productos.destroy', $p->id_producto) }}" style="display:inline"
                      onsubmit="return confirm('¿Eliminar este producto?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-icon red" title="Eliminar">🗑️</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="pag-wrap">{{ $productos->links() }}</div>

@else
<div class="empty-state">
    <div class="empty-icon">📦</div>
    <p>No se encontraron productos.</p>
</div>
@endif

{{-- Create Modal --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        <div class="modal-title">➕ Nuevo Producto</div>
        <form method="POST" action="{{ route('productos.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Código *</label>
                    <input type="text" name="codigo" required>
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" rows="2"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Precio *</label>
                    <input type="number" name="precio" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>Estado *</label>
                    <select name="estado" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Stock Actual *</label>
                    <input type="number" name="stock_actual" min="0" required value="0">
                </div>
                <div class="form-group">
                    <label>Stock Mínimo *</label>
                    <input type="number" name="stock_minimo" min="0" required value="5">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Categoría *</label>
                    <select name="id_categoria" required>
                        <option value="">Seleccionar...</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id_categoria }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Proveedor</label>
                    <select name="id_proveedor">
                        <option value="">Sin proveedor</option>
                        @foreach($proveedores as $prov)
                        <option value="{{ $prov->id_proveedor }}">{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Cancelar</button>
                <button type="submit" class="btn-purple">Crear Producto</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        <div class="modal-title">✏️ Editar Producto</div>
        <form method="POST" id="edit-form" action="">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" id="e-nombre" required>
                </div>
                <div class="form-group">
                    <label>Código *</label>
                    <input type="text" name="codigo" id="e-codigo" required>
                </div>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" id="e-descripcion" rows="2"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Precio *</label>
                    <input type="number" name="precio" id="e-precio" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>Estado *</label>
                    <select name="estado" id="e-estado" required>
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Stock Actual *</label>
                    <input type="number" name="stock_actual" id="e-stock" min="0" required>
                </div>
                <div class="form-group">
                    <label>Stock Mínimo *</label>
                    <input type="number" name="stock_minimo" id="e-stock-min" min="0" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Categoría *</label>
                    <select name="id_categoria" id="e-categoria" required>
                        <option value="">Seleccionar...</option>
                        @foreach($categorias as $cat)
                        <option value="{{ $cat->id_categoria }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Proveedor</label>
                    <select name="id_proveedor" id="e-proveedor">
                        <option value="">Sin proveedor</option>
                        @foreach($proveedores as $prov)
                        <option value="{{ $prov->id_proveedor }}">{{ $prov->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit')">Cancelar</button>
                <button type="submit" class="btn-purple">Actualizar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function openEditModal(id, nombre, codigo, desc, precio, stock, stockMin, catId, provId, estado) {
    document.getElementById('edit-form').action = '/productos/' + id;
    document.getElementById('e-nombre').value = nombre;
    document.getElementById('e-codigo').value = codigo;
    document.getElementById('e-descripcion').value = desc;
    document.getElementById('e-precio').value = precio;
    document.getElementById('e-stock').value = stock;
    document.getElementById('e-stock-min').value = stockMin;
    document.getElementById('e-categoria').value = catId;
    document.getElementById('e-proveedor').value = provId || '';
    document.getElementById('e-estado').value = estado;
    openModal('modal-edit');
}
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
</script>
@endpush
