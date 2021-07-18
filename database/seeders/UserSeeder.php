<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 10; $i++) { 
            $level = $i % 5;

            if($level == 1) $level = 2;
            DB::table('users')->insert([
                'name' => 'Usuario' . $i ,
                'email' => 'usuario' . $i . '@gmail.com',
                'password' => Hash::make('secret'),
                'level' =>  $level,
            ]);
        }
    }
}
