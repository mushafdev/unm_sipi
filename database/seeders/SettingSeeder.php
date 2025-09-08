<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('settings')->insert([
            ['id'=>'setting','nama_web'=>'My Company','email'=>'hi@company.com','alamat'=>'Jl. Jalan','ppn'=>11,'telp'=>'085423444567','wa'=>'085875342122','created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
