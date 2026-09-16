@extends('layouts.sidebar')

@section('title', 'Nuevo Producto')
@section('page-title', 'Nuevo Producto')
@section('page-subtitle', 'Agrega un producto al catálogo')

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

    <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Nombre *</label>
                <input type="text" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Código *</label>
                <input type="text" name="codigo" value="{{ old('codigo') }}" required>
                @error('codigo')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="2">{{ old('descripcion') }}</textarea>
        </div>

        {{-- Sección de Imagen --}}
        <div class="form-group" style="background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:12px;padding:1rem;margin-bottom:1.2rem;">
            <label style="display:flex;align-items:center;gap:.4rem;color:#0f172a;font-weight:700;margin-bottom:.5rem;">
                🖼️ Imagen del Producto <span style="font-weight:400;font-size:.78rem;color:#64748b;">(Subir archivo o pegar enlace URL)</span>
            </label>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.8rem;align-items:start;">
                <div>
                    <label style="font-size:.78rem;color:#475569;margin-bottom:.2rem;">Subir archivo local</label>
                    <input type="file" name="imagen_archivo" id="img-archivo" accept="image/*" style="font-size:.82rem;padding:.4rem;background:#fff;">
                </div>
                <div>
                    <label style="font-size:.78rem;color:#475569;margin-bottom:.2rem;">O enlace directo (URL)</label>
                    <input type="url" name="imagen_url" id="img-url" value="{{ old('imagen_url') }}" placeholder="https://ejemplo.com/foto.jpg" style="font-size:.85rem;background:#fff;">
                </div>
            </div>
            <div id="preview-box" style="display:none;margin-top:.8rem;align-items:center;gap:.8rem;background:#fff;padding:.6rem .8rem;border-radius:10px;border:1px solid #e2e8f0;">
                <img id="img-preview" src="" alt="Vista previa" style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid #cbd5e1;">
                <div>
                    <span style="font-size:.82rem;color:#106f4e;font-weight:700;">✓ Vista previa cargada</span>
                    <div style="font-size:.75rem;color:#64748b;">Se guardará al crear el producto</div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Precio *</label>
                <input type="number" name="precio" step="0.01" min="0" value="{{ old('precio', '0.00') }}" required>
                @error('precio')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Estado *</label>
                <select name="estado" required>
                    <option value="1" {{ old('estado', '1') == '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Stock Actual *</label>
                <input type="number" name="stock_actual" min="0" value="{{ old('stock_actual', 0) }}" required>
                @error('stock_actual')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Stock Mínimo *</label>
                <input type="number" name="stock_minimo" min="0" value="{{ old('stock_minimo', 5) }}" required>
                @error('stock_minimo')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Categoría *</label>
                <select name="id_categoria" required>
                    <option value="">Seleccionar categoría...</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id_categoria }}" {{ old('id_categoria') == $cat->id_categoria ? 'selected' : '' }}>
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
                    <option value="{{ $prov->id_proveedor }}" {{ old('id_proveedor') == $prov->id_proveedor ? 'selected' : '' }}>
                        {{ $prov->nombre }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="btn-row">
            <a href="{{ route('productos.index') }}" class="btn-secondary">← Cancelar</a>
            <button type="submit" class="btn-primary">Crear Producto</button>
        </div>

    </form>
</div>

@push('scripts')
<script>
const fileInput = document.getElementById('img-archivo');
const urlInput = document.getElementById('img-url');
const previewBox = document.getElementById('preview-box');
const previewImg = document.getElementById('img-preview');

if (fileInput) {
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewBox.style.display = 'flex';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}
if (urlInput) {
    urlInput.addEventListener('input', function() {
        if (this.value.trim().length > 8) {
            previewImg.src = this.value.trim();
            previewBox.style.display = 'flex';
        }
    });
}
</script>
@endpush

@endsection
