<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('satuans')->insert([
             ['satuan'=>'Box','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Tablet','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Botol','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Tube','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Ampul','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Sachet','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Suppo','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Vial','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Caps','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Pcs','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Blister','created_at' => now(), 'updated_at' => now()],
             ['satuan'=>'Pot','created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
