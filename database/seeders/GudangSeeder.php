<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GudangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('gudangs')->insert([
             ['gudang'=>'Gudang Kasir','created_at' => now(), 'updated_at' => now()],
             ['gudang'=>'Gudang Apotek','created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
