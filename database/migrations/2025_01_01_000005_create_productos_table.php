<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id_producto');
            $table->unsignedInteger('id_categoria')->nullable();
            $table->unsignedInteger('id_proveedor')->nullable();
            $table->string('codigo', 50)->nullable();
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 12, 2)->default(0);
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->string('imagen', 255)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias')->nullOnDelete();
            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('productos'); }
};
