<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         for($i = 0; $i < 10000; $i++){

            DB::table('users')->insert([
                'login'     => Str::random(10),
                
                'name'      => Str::random(5),
                'password'  => '123456',
            ]);
        }
    }
}
