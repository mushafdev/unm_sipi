<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AkunKasSeeder extends Seeder
{
    public function run(): void
    {
        // Insert akun kas
        DB::table('akun_kas')->insert([
            [
                'nama_akun' => 'Kas Tunai',
                'nomor_akun' => '1001',
                'nomor_rekening' => null,
                'bank' => null,
                'opening_balance' => 10000000.00,
                'current_balance' => 10000000.00,
                'is_active' => 'Y',
                'type' => 'cash',
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama_akun' => 'Rekening BCA',
                'nomor_akun' => '1002',
                'nomor_rekening' => '1234567890',
                'bank' => 'BCA',
                'opening_balance' => 25000000.00,
                'current_balance' => 25000000.00,
                'is_active' => 'Y',
                'type' => 'bank',
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama_akun' => 'Rekening Mandiri',
                'nomor_akun' => '1003',
                'nomor_rekening' => '9876543210',
                'bank' => 'Mandiri',
                'opening_balance' => 15000000.00,
                'current_balance' => 15000000.00,
                'is_active' => 'Y',
                'type' => 'bank',
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

        // Ambil id akun kas
        $kasTunai = DB::table('akun_kas')->where('nama_akun', 'Kas Tunai')->first();
        $bca = DB::table('akun_kas')->where('nama_akun', 'Rekening BCA')->first();
        $mandiri = DB::table('akun_kas')->where('nama_akun', 'Rekening Mandiri')->first();

        // Insert metode pembayaran
        DB::table('metode_pembayarans')->insert([
            [
                'metode_pembayaran' => 'Tunai',
                'is_active' => 'Y',
                'akun_kas_id' => $kasTunai->id ?? null,
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'metode_pembayaran' => 'Transfer BCA',
                'is_active' => 'Y',
                'akun_kas_id' => $bca->id ?? null,
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'metode_pembayaran' => 'Transfer Mandiri',
                'is_active' => 'Y',
                'akun_kas_id' => $mandiri->id ?? null,
                'inserted_by' => 1,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
