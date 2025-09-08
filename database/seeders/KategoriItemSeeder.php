<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KategoriItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('kategori_items')->insert([
             ['kategori_item'=>'Sabun','created_at' => now(), 'updated_at' => now()],
             ['kategori_item'=>'Sunblock','created_at' => now(), 'updated_at' => now()],
             ['kategori_item'=>'Toner','created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
