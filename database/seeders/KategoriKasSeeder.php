<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KategoriKasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori_kas')->insert([
            [
                'kategori_kas' => 'Modal Masuk',
                'type' => 'income',
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],

            // ✅ Kategori Pengeluaran (expense)
            [
                'kategori_kas' => 'Pembelian Barang',
                'type' => 'expense',
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'kategori_kas' => 'Biaya Operasional',
                'type' => 'expense',
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'kategori_kas' => 'Gaji Karyawan',
                'type' => 'expense',
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
