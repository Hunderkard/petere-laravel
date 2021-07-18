<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientePlatoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 20; $i++) { 
            $ingredientes = rand(3, 8);
            for ($j=0; $j < $ingredientes; $j++) { 
                 DB::table('ingrediente_plato')->insert([
                    'plato_id' => 100 + $i,
                    'ingrediente_id' => rand(1, 20),
                    'cantidad' => rand(100, 10000)/100
            ]);
            }
            
        }
    }
}
