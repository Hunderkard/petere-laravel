<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipo = ['ENTRANTE', 'HAMBURGUESA', 'PASTA', 'PIZZA', 'BEBIDA_CALIENTE', 'REFRESCO'];
        for ($i=0; $i < 20; $i++) { 
            DB::table('platos')->insert([
                'id' => 100 + $i,
                'nombre' => 'plato_' . $i,
                'foto' => 'sin_imagen.png',
                'precio' => rand(100, 2000)/100,
                'Observaciones' => 'Generado desde el seeder.',
                'tipo' => $tipo[rand(0, 5)]
            ]);
        }
    }
}
