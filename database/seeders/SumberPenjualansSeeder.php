<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SumberPenjualansSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $userId = 1; // Ganti sesuai ID user yang tersedia, atau null jika belum ada

        $data = [
            [
                'sumber_penjualan' => 'Offline',
                'inserted_by' => $userId,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sumber_penjualan' => 'Shopee',
                'inserted_by' => $userId,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sumber_penjualan' => 'Grab',
                'inserted_by' => $userId,
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('sumber_penjualans')->insert($data);
    }
}
