<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 16; $i++) { 
            
            DB::table('mesas')->insert([
                'numero' => $i,
                'codigo' => 'za warudo'
            ]);
            
        }
    }
}
