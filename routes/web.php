<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

// ─── Página principal ──────────────────────────────────────────────────────
Route::get('/', function () {
    return view('index');
})->name('index');

// ─── Autenticación ─────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ─── Área protegida ────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Solo administrador (la protección está en cada controller con abort_if) ──

    // Usuarios
    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    Route::post('usuarios/{id}/toggle-estado', [UsuarioController::class, 'toggleEstado'])->name('usuarios.toggle');

    // Categorías
    Route::get('categorias',        [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('categorias',       [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('categorias/{id}',   [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('categorias/{id}',[CategoriaController::class, 'destroy'])->name('categorias.destroy');

    // Ventas
    Route::resource('ventas', VentaController::class)->except(['show', 'edit', 'update']);

    // Proveedores
    Route::get('proveedores',                     [ProveedorController::class, 'index'])->name('proveedores.index');
    Route::post('proveedores',                    [ProveedorController::class, 'store'])->name('proveedores.store');
    Route::put('proveedores/{id}',                [ProveedorController::class, 'update'])->name('proveedores.update');
    Route::delete('proveedores/{id}',             [ProveedorController::class, 'destroy'])->name('proveedores.destroy');
    Route::post('proveedores/{id}/toggle-estado', [ProveedorController::class, 'toggleEstado'])->name('proveedores.toggle');

    // ── Administrador y Empleado ───────────────────────────────────────────
    // Productos
    Route::resource('productos', ProductoController::class)->except(['show']);
    Route::post('productos/{id}/toggle-estado', [ProductoController::class, 'toggleEstado'])->name('productos.toggle');

    // Inventario
    Route::get('inventario',             [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('inventario/stock-live',  [InventarioController::class, 'stockLive'])->name('inventario.stock-live');
    Route::post('inventario/{id}/mover', [InventarioController::class, 'mover'])->name('inventario.mover');

    // Reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
});
