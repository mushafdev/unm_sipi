<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LokasiPiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('lokasi_pis')->insert([
             ['lokasi_pi'=>'PT. Pertamina','kota'=>'Makassar','alamat'=>'Jl. AP. Pettarani','telp'=>'08565555455','created_at' => now(), 'updated_at' => now()],
             ['lokasi_pi'=>'PT. Kumala Motor Sejahtera','kota'=>'Makassar','alamat'=>'Jl. Kumala','telp'=>'0411093929','created_at' => now(), 'updated_at' => now()],
             ['lokasi_pi'=>'PT. Mencari Cinta Sejati','kota'=>'Makassar','alamat'=>'Jl. Jalan Poros','telp'=>'0411123442','created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
