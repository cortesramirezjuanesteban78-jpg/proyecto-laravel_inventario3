@extends('layouts.sidebar')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')
@section('page-subtitle', 'Modifica los datos de "' . $producto->nombre . '"')

@push('styles')
<style>
    .form-card {
        background: #fff; border-radius: 14px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        padding: 2rem; max-width: 680px;
    }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label {
        display: block; font-size: .82rem; font-weight: 600;
        color: #374151; margin-bottom: .35rem;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%; padding: .6rem .85rem;
        border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-family: inherit; font-size: .9rem; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #7c3aed; box-shadow: 0 0 0 3px #ede9fe;
    }
    .form-group .error { font-size: .78rem; color: #ef4444; margin-top: .25rem; }
    .btn-row { display: flex; gap: .7rem; justify-content: flex-end; margin-top: .5rem; }
    .btn-primary {
        background: #7c3aed; color: #fff; border: none;
        padding: .6rem 1.4rem; border-radius: 8px;
        font-family: inherit; font-size: .9rem; font-weight: 700; cursor: pointer;
    }
    .btn-primary:hover { background: #6d28d9; }
    .btn-secondary {
        padding: .6rem 1.2rem; border-radius: 8px;
        border: 1.5px solid #e5e7eb; background: #fff;
        font-family: inherit; font-size: .9rem; font-weight: 600;
        cursor: pointer; color: #374151; text-decoration: none;
        display: inline-flex; align-items: center;
    }
    .btn-secondary:hover { background: #f9fafb; }
</style>
@endpush

@section('content')

<div class="form-card">

    @if($errors->any())
    <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:.85rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:.88rem;font-weight:600;">
        ❌ Por favor corrige los siguientes errores:<br>
        <ul style="margin:.5rem 0 0 1rem;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('productos.update', $producto->id_producto) }}">
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
            <textarea name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
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

@endsection
