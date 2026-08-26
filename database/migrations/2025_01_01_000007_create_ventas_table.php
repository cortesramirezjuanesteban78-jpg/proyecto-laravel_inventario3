<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id_venta');
            $table->unsignedInteger('id_usuario')->nullable();
            $table->unsignedInteger('id_cliente')->nullable();
            $table->dateTime('fecha_venta')->useCurrent();
            $table->string('metodo_pago', 50)->default('efectivo');
            $table->string('estado', 20)->default('completada');
            $table->decimal('total', 12, 2)->default(0);
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->nullOnDelete();
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('ventas'); }
};
