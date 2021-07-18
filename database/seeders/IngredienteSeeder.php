<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //También se puede cargar el model para el ->save();
        //También los factory.
        //vo $dias10 = 10 * 24 * 60 * 60;

        for ($i=0; $i < 20; $i++) { 
            DB::table('ingredientes')->insert([
                'nombre' => 'Ing_' . $i,
    
                'stock' => '1000',
                'unidad_de_medida' => 'unidades',
                'valor' => 1.00,
    
                'proveedor_id' => 1,
                'observaciones' => null,
                'foto' => 'sin_imagen.png',
                //vo 'fecha_compra' => date('Y-m-d'),
                //vo 'fecha_caducidad' => date('Y-m-d', time() + $dias10),
            ]);
        }
    }
}
