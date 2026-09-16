@extends('layouts.sidebar')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')
@section('page-subtitle', 'Modifica los datos de "' . $producto->nombre . '"')

@push('styles')
<style>
    .form-card {
        background: #fff; border-radius: 16px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        padding: 2.2rem; max-width: 720px;
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group { margin-bottom: 1.1rem; }
    .form-group label {
        display: block; font-size: .84rem; font-weight: 700;
        color: var(--text-dark); margin-bottom: .35rem;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%; padding: .65rem .85rem;
        border: 1.5px solid var(--border-subtle); border-radius: 10px;
        font-family: inherit; font-size: .9rem; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }
    .form-group .error { font-size: .78rem; color: #f43f5e; margin-top: .25rem; }
    .btn-row { display: flex; gap: .8rem; justify-content: flex-end; margin-top: 1rem; }
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
        color: #fff; border: none;
        padding: .65rem 1.4rem; border-radius: 10px;
        font-family: inherit; font-size: .9rem; font-weight: 700; cursor: pointer;
        box-shadow: var(--shadow-glow); transition: all .2s ease;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(16,111,78,.35); }
    .btn-secondary {
        padding: .65rem 1.2rem; border-radius: 10px;
        border: 1.5px solid var(--border-subtle); background: #fff;
        font-family: inherit; font-size: .9rem; font-weight: 700;
        cursor: pointer; color: var(--text-dark); text-decoration: none;
        display: inline-flex; align-items: center; transition: all .2s;
    }
    .btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }
</style>
@endpush

@section('content')

<div class="form-card">

    @if($errors->any())
    <div style="background:#fff1f2;border:1px solid #fecdd3;color:#9f1239;padding:.85rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:.88rem;font-weight:600;">
        ❌ Por favor corrige los siguientes errores:<br>
        <ul style="margin:.5rem 0 0 1rem;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('productos.update', $producto->id_producto) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                @error('nombre')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Código *</label>
                <input type="text" name="codigo" value="{{ old('codigo', $producto->codigo) }}" required>
                @error('codigo')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="2">{{ old('descripcion', $producto->descripcion) }}</textarea>
        </div>

        {{-- Sección de Imagen (Editar) --}}
        <div class="form-group" style="background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:12px;padding:1rem;margin-bottom:1.2rem;">
            <label style="display:flex;align-items:center;gap:.4rem;color:#0f172a;font-weight:700;margin-bottom:.5rem;">
                🖼️ Imagen del Producto <span style="font-weight:400;font-size:.78rem;color:#64748b;">(Subir nueva foto o enlace)</span>
            </label>
            <div style="display:flex;gap:1rem;align-items:center;margin-bottom:.8rem;background:#fff;padding:.6rem .8rem;border-radius:10px;border:1px solid #e2e8f0;">
                <img id="img-preview" src="{{ $producto->imagen_url }}" alt="Imagen actual" style="width:60px;height:60px;object-fit:cover;border-radius:8px;border:1px solid #cbd5e1;">
                <div>
                    <div style="font-size:.82rem;font-weight:700;color:#0f172a;">Imagen Actual</div>
                    <label style="display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;color:#f43f5e;cursor:pointer;margin-top:.25rem;">
                        <input type="checkbox" name="eliminar_imagen" value="1" id="chk-eliminar-imagen"> Quitar imagen actual
                    </label>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;align-items:start;">
                <div>
                    <label style="font-size:.78rem;color:#475569;margin-bottom:.2rem;">Subir nuevo archivo</label>
                    <input type="file" name="imagen_archivo" id="img-archivo" accept="image/*" style="font-size:.82rem;padding:.4rem;background:#fff;">
                </div>
                <div>
                    <label style="font-size:.78rem;color:#475569;margin-bottom:.2rem;">O nuevo enlace web (URL)</label>
                    <input type="url" name="imagen_url" id="img-url" value="{{ old('imagen_url', (str_starts_with($producto->imagen ?? '', 'http') ? $producto->imagen : '')) }}" placeholder="https://ejemplo.com/foto.jpg" style="font-size:.85rem;background:#fff;">
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Precio *</label>
                <input type="number" name="precio" step="0.01" min="0"
                       value="{{ old('precio', $producto->precio) }}" required>
                @error('precio')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Estado *</label>
                <select name="estado" required>
                    <option value="1" {{ old('estado', $producto->estado) == '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estado', $producto->estado) == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Stock Actual *</label>
                <input type="number" name="stock_actual" min="0"
                       value="{{ old('stock_actual', $producto->stock_actual) }}" required>
                @error('stock_actual')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Stock Mínimo *</label>
                <input type="number" name="stock_minimo" min="0"
                       value="{{ old('stock_minimo', $producto->stock_minimo) }}" required>
                @error('stock_minimo')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Categoría *</label>
                <select name="id_categoria" required>
                    <option value="">Seleccionar categoría...</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id_categoria }}"
                        {{ old('id_categoria', $producto->id_categoria) == $cat->id_categoria ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                    @endforeach
                </select>
                @error('id_categoria')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Proveedor</label>
                <select name="id_proveedor">
                    <option value="">Sin proveedor</option>
                    @foreach($proveedores as $prov)
                    <option value="{{ $prov->id_proveedor }}"
                        {{ old('id_proveedor', $producto->id_proveedor) == $prov->id_proveedor ? 'selected' : '' }}>
                        {{ $prov->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="btn-row">
            <a href="{{ route('productos.index') }}" class="btn-secondary">← Cancelar</a>
            <button type="submit" class="btn-primary">Actualizar Producto</button>
        </div>

    </form>
</div>

@push('scripts')
<script>
const fileInput = document.getElementById('img-archivo');
const urlInput = document.getElementById('img-url');
const previewImg = document.getElementById('img-preview');
const chkEliminar = document.getElementById('chk-eliminar-imagen');

if (fileInput) {
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.opacity = '1';
                previewImg.style.filter = 'none';
                if (chkEliminar) chkEliminar.checked = false;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}
if (urlInput) {
    urlInput.addEventListener('input', function() {
        if (this.value.trim().length > 8) {
            previewImg.src = this.value.trim();
            previewImg.style.opacity = '1';
            previewImg.style.filter = 'none';
            if (chkEliminar) chkEliminar.checked = false;
        }
    });
}
if (chkEliminar) {
    chkEliminar.addEventListener('change', function() {
        if (this.checked) {
            previewImg.style.opacity = '0.35';
            previewImg.style.filter = 'grayscale(100%)';
        } else {
            previewImg.style.opacity = '1';
            previewImg.style.filter = 'none';
        }
    });
}
</script>
@endpush

@endsection
