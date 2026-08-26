<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id_usuario');
            $table->unsignedInteger('id_rol')->nullable();
            $table->string('nombres', 100);
            $table->string('apellidos', 100)->default('');
            $table->string('email', 150)->unique();
            $table->string('password_hash', 255);
            $table->string('telefono', 30)->nullable();
            $table->string('rol', 50)->default('cliente');
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion')->useCurrent();
            $table->foreign('id_rol')->references('id_rol')->on('roles')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('usuarios'); }
};
