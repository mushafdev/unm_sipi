<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('jabatans')->insert([
             ['jabatan'=>'Perawat','created_at' => now(), 'updated_at' => now()],
             ['jabatan'=>'Admin','created_at' => now(), 'updated_at' => now()],
             ['jabatan'=>'Kasir','created_at' => now(), 'updated_at' => now()],
             ['jabatan'=>'Manager','created_at' => now(), 'updated_at' => now()],
             ['jabatan'=>'Front Office','created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
