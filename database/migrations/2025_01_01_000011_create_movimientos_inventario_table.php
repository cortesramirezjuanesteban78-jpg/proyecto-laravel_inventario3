<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->increments('id_movimiento');
            $table->unsignedInteger('id_producto')->nullable();
            $table->unsignedInteger('id_usuario')->nullable();
            $table->string('tipo_movimiento', 20);
            $table->integer('cantidad')->default(0);
            $table->dateTime('fecha_movimiento')->useCurrent();
            $table->string('observacion', 255)->nullable();
            $table->foreign('id_producto')->references('id_producto')->on('productos')->nullOnDelete();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('movimientos_inventario'); }
};
