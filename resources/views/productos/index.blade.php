@extends('layouts.sidebar')

@section('title', 'Productos')
@section('page-title', 'Productos')
@section('page-subtitle', $total . ' productos en catálogo')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-primary">+ Nuevo Producto</button>
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

    /* Stat row */
    .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.2rem; margin-bottom: 2rem; }
    .stat-card {
        border-radius: 16px; padding: 1.3rem 1.5rem; background: #fff;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1rem;
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .stat-card.green  { border-left: 4px solid #106f4e; }
    .stat-card.green .sc-icon { background: #ecfdf5; border: 1px solid #d1fae5; }
    .stat-card.blue   { border-left: 4px solid #2563eb; }
    .stat-card.blue .sc-icon { background: #eff6ff; border: 1px solid #dbeafe; }
    .stat-card.orange { border-left: 4px solid #d97706; }
    .stat-card.orange .sc-icon { background: #fffbeb; border: 1px solid #fef3c7; }
    .stat-card.red    { border-left: 4px solid #f43f5e; }
    .stat-card.red .sc-icon { background: #fff1f2; border: 1px solid #fecdd3; }
    .stat-card .sc-icon {
        font-size: 1.6rem; width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-card .sc-num  { font-size: 1.8rem; font-weight: 900; color: var(--text-dark); line-height: 1.1; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: var(--text-muted); margin-top: .15rem; }

    /* Filters */
    .filters-row { display: flex; gap: .8rem; margin-bottom: 1.6rem; flex-wrap: wrap; align-items: center; }
    .filters-row input, .filters-row select {
        background: #fff; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        padding: .55rem 1rem; font-family: inherit; font-size: .88rem; outline: none;
        transition: all .2s;
    }
    .filters-row input { width: 230px; }
    .filters-row input:focus, .filters-row select:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }

    /* Product cards grid */
    .products-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 1.3rem;
        margin-bottom: 1.8rem;
    }
    .product-card {
        background: #fff; border-radius: 16px; overflow: hidden;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm); transition: transform .25s, box-shadow .25s;
    }
    .product-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: rgba(16,111,78,.3); }
    .product-card img { width: 100%; height: 140px; object-fit: cover; }
    .product-card .pc-body { padding: 1rem 1.1rem; }
    .pc-badge {
        display: inline-block; padding: .2rem .65rem; border-radius: 20px;
        font-size: .72rem; font-weight: 700; margin-bottom: .4rem;
    }
    .pc-name { font-weight: 700; font-size: .95rem; color: var(--text-dark); margin-bottom: .25rem; }
    .pc-code { font-size: .76rem; color: var(--text-muted); margin-bottom: .5rem; }
    .pc-price { font-size: 1.15rem; font-weight: 800; color: #106f4e; margin-bottom: .6rem; }
    .stock-bar-wrap { margin-bottom: .6rem; }
    .stock-label { display: flex; justify-content: space-between; font-size: .76rem; color: var(--text-muted); margin-bottom: .25rem; }
    .stock-bar { height: 6px; border-radius: 3px; background: #e2e8f0; overflow: hidden; }
    .stock-fill { height: 100%; border-radius: 3px; }
    .stock-fill.ok  { background: #159c6c; }
    .stock-fill.low { background: #f59e0b; }
    .stock-fill.out { background: #f43f5e; }
    .pc-actions { display: flex; gap: .45rem; margin-top: .75rem; }
    .btn-edit-sm {
        flex: 1; padding: .42rem; border-radius: 8px; background: #eff6ff; color: #2563eb;
        border: 1px solid #dbeafe; cursor: pointer; font-size: .82rem; font-weight: 700;
        transition: all .2s;
    }
    .btn-edit-sm:hover { background: #dbeafe; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .9rem; transition: all .15s;
    }
    .btn-icon.yellow { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
    .btn-icon.red    { background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; }
    .btn-icon:hover  { transform: scale(1.1); }

    /* Pagination */
    .pag-wrap { display: flex; justify-content: center; }

    /* Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(15,23,42,.5);
        backdrop-filter: blur(4px);
        z-index: 200; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 20px; padding: 2.2rem;
        width: 100%; max-width: 540px; position: relative;
        box-shadow: var(--shadow-lg); max-height: 90vh; overflow-y: auto;
    }
    .modal-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin-bottom: 1.4rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 700; color: #334155; margin-bottom: .35rem; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%; padding: .65rem .85rem; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        font-family: inherit; font-size: .9rem; outline: none; transition: all .2s;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }
    .modal-footer { display: flex; gap: .8rem; justify-content: flex-end; margin-top: .8rem; }
    .modal-close {
        position: absolute; top: 1.2rem; right: 1.2rem; background: #f1f5f9; border: none;
        border-radius: 8px; width: 30px; height: 30px; cursor: pointer; font-size: 1rem;
        color: var(--text-muted); transition: all .2s;
    }
    .modal-close:hover { background: #fff1f2; color: #f43f5e; }

    /* Empty state */
    .empty-state { text-align: center; padding: 3.5rem; color: var(--text-muted); }
    .empty-state .empty-icon { font-size: 3.2rem; margin-bottom: 1rem; }
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
        <img src="{{ $p->imagen_url }}"
             alt="{{ $p->nombre }}" loading="lazy" id="prod-img-{{ $p->id_producto }}">
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
                    {{ $p->estado }},
                    '{{ addslashes($p->imagen_url) }}'
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
        <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
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

            {{-- Sección de Imagen (Crear) --}}
            <div class="form-group" style="background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:12px;padding:1rem;margin-bottom:1.2rem;">
                <label style="display:flex;align-items:center;gap:.4rem;color:#0f172a;font-weight:700;margin-bottom:.5rem;">
                    🖼️ Imagen del Producto <span style="font-weight:400;font-size:.78rem;color:#64748b;">(Sube archivo o ingresa enlace)</span>
                </label>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;align-items:start;">
                    <div>
                        <label style="font-size:.78rem;color:#475569;margin-bottom:.2rem;">Subir archivo local</label>
                        <input type="file" name="imagen_archivo" id="c-imagen-archivo" accept="image/*" style="font-size:.82rem;padding:.4rem;background:#fff;">
                    </div>
                    <div>
                        <label style="font-size:.78rem;color:#475569;margin-bottom:.2rem;">O enlace directo (URL)</label>
                        <input type="url" name="imagen_url" id="c-imagen-url" placeholder="https://ejemplo.com/foto.jpg" style="font-size:.85rem;background:#fff;">
                    </div>
                </div>
                <div id="c-preview-container" style="display:none;margin-top:.8rem;align-items:center;gap:.8rem;background:#fff;padding:.6rem .8rem;border-radius:10px;border:1px solid #e2e8f0;">
                    <img id="c-img-preview" src="" alt="Vista previa" style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid #cbd5e1;">
                    <div>
                        <span style="font-size:.82rem;color:#106f4e;font-weight:700;">✓ Vista previa cargada</span>
                        <div style="font-size:.75rem;color:#64748b;">Se guardará al crear el producto</div>
                    </div>
                </div>
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
                <button type="submit" class="btn-primary">Crear Producto</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal-overlay" id="modal-edit">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        <div class="modal-title">✏️ Editar Producto</div>
        <form method="POST" id="edit-form" action="" enctype="multipart/form-data">
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

            {{-- Sección de Imagen (Editar) --}}
            <div class="form-group" style="background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:12px;padding:1rem;margin-bottom:1.2rem;">
                <label style="display:flex;align-items:center;gap:.4rem;color:#0f172a;font-weight:700;margin-bottom:.5rem;">
                    🖼️ Imagen del Producto <span style="font-weight:400;font-size:.78rem;color:#64748b;">(Subir nueva foto o enlace)</span>
                </label>
                <div style="display:flex;gap:1rem;align-items:center;margin-bottom:.8rem;background:#fff;padding:.6rem .8rem;border-radius:10px;border:1px solid #e2e8f0;">
                    <img id="e-img-preview" src="" alt="Imagen actual" style="width:60px;height:60px;object-fit:cover;border-radius:8px;border:1px solid #cbd5e1;">
                    <div>
                        <div style="font-size:.82rem;font-weight:700;color:#0f172a;">Imagen Actual</div>
                        <label style="display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;color:#f43f5e;cursor:pointer;margin-top:.25rem;">
                            <input type="checkbox" name="eliminar_imagen" value="1" id="e-eliminar-imagen" onchange="toggleEliminarImagen(this)"> Quitar imagen actual
                        </label>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;align-items:start;">
                    <div>
                        <label style="font-size:.78rem;color:#475569;margin-bottom:.2rem;">Subir archivo local</label>
                        <input type="file" name="imagen_archivo" id="e-imagen-archivo" accept="image/*" style="font-size:.82rem;padding:.4rem;background:#fff;">
                    </div>
                    <div>
                        <label style="font-size:.78rem;color:#475569;margin-bottom:.2rem;">O nuevo enlace web (URL)</label>
                        <input type="url" name="imagen_url" id="e-imagen-url" placeholder="https://ejemplo.com/foto.jpg" style="font-size:.85rem;background:#fff;">
                    </div>
                </div>
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
                <button type="submit" class="btn-primary">Actualizar Producto</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function openEditModal(id, nombre, codigo, desc, precio, stock, stockMin, catId, provId, estado, imagenUrl) {
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

    // Cargar imagen actual
    const preview = document.getElementById('e-img-preview');
    preview.src = imagenUrl || 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&q=70';
    preview.style.opacity = '1';
    preview.style.filter = 'none';

    document.getElementById('e-imagen-archivo').value = '';
    document.getElementById('e-imagen-url').value = (imagenUrl && imagenUrl.startsWith('http') && !imagenUrl.includes('unsplash.com')) ? imagenUrl : '';
    const chkEliminar = document.getElementById('e-eliminar-imagen');
    if (chkEliminar) chkEliminar.checked = false;

    openModal('modal-edit');
}

// Previsualización en tiempo real - Modal Crear
const cInputFile = document.getElementById('c-imagen-archivo');
const cInputUrl = document.getElementById('c-imagen-url');
const cContainer = document.getElementById('c-preview-container');
const cImg = document.getElementById('c-img-preview');

if (cInputFile) {
    cInputFile.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                cImg.src = e.target.result;
                cContainer.style.display = 'flex';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}
if (cInputUrl) {
    cInputUrl.addEventListener('input', function() {
        if (this.value.trim().length > 8) {
            cImg.src = this.value.trim();
            cContainer.style.display = 'flex';
        }
    });
}

// Previsualización en tiempo real - Modal Editar
const eInputFile = document.getElementById('e-imagen-archivo');
const eInputUrl = document.getElementById('e-imagen-url');
const eImg = document.getElementById('e-img-preview');

if (eInputFile) {
    eInputFile.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                eImg.src = e.target.result;
                eImg.style.opacity = '1';
                eImg.style.filter = 'none';
                const chk = document.getElementById('e-eliminar-imagen');
                if (chk) chk.checked = false;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}
if (eInputUrl) {
    eInputUrl.addEventListener('input', function() {
        if (this.value.trim().length > 8) {
            eImg.src = this.value.trim();
            eImg.style.opacity = '1';
            eImg.style.filter = 'none';
            const chk = document.getElementById('e-eliminar-imagen');
            if (chk) chk.checked = false;
        }
    });
}

function toggleEliminarImagen(chk) {
    const preview = document.getElementById('e-img-preview');
    if (chk.checked) {
        preview.style.opacity = '0.35';
        preview.style.filter = 'grayscale(100%)';
    } else {
        preview.style.opacity = '1';
        preview.style.filter = 'none';
    }
}

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
</script>
@endpush
