<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('compras', function (Blueprint $table) {
            $table->increments('id_compra');
            $table->unsignedInteger('id_proveedor')->nullable();
            $table->unsignedInteger('id_usuario')->nullable();
            $table->dateTime('fecha_compra')->useCurrent();
            $table->string('estado', 20)->default('pendiente');
            $table->decimal('total', 12, 2)->default(0);
            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->nullOnDelete();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('compras'); }
};
