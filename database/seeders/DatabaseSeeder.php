<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert(
            ['id_rol' => 1],
            ['nombre' => 'administrador', 'descripcion' => 'Acceso total al sistema']
        );
        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert(
            ['id_rol' => 2],
            ['nombre' => 'empleado', 'descripcion' => 'Personal operativo de inventario y ventas']
        );
        \Illuminate\Support\Facades\DB::table('roles')->updateOrInsert(
            ['id_rol' => 3],
            ['nombre' => 'cliente', 'descripcion' => 'Usuario cliente / comprador']
        );
    }
}
