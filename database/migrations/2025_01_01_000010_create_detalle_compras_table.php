<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->increments('id_detalle');
            $table->unsignedInteger('id_compra');
            $table->unsignedInteger('id_producto')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->foreign('id_compra')->references('id_compra')->on('compras')->cascadeOnDelete();
            $table->foreign('id_producto')->references('id_producto')->on('productos')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('detalle_compras'); }
};
