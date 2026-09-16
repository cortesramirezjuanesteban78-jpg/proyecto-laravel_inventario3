<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->increments('id_proveedor');
            $table->string('nombre', 255);
            $table->string('documento', 50)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('correo', 150)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->tinyInteger('estado')->default(1);
        });
    }
    public function down(): void { Schema::dropIfExists('proveedores'); }
};
