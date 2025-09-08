<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID kategori berdasarkan nama
        $kategoriFacial = DB::table('kategori_services')->where('kategori_service', 'Facial')->first()?->id ?? 1;
        $kategoriTreatment = DB::table('kategori_services')->where('kategori_service', 'Body Treatment')->first()?->id ?? 2;

        DB::table('services')->insert([
            [
                'kategori_service_id' => $kategoriFacial,
                'kode' => 'SVC-001',
                'nama_service' => 'Facial Oxygeneo',
                'barcode' => null,
                'besaran' => 'per session',
                'isi' => 1,
                'satuan' => 'sesi',
                'ppn' => 0,
                'harga_jual' => 750000,
                'durasi' => 90,
                'total_dibeli' => 0,
                'inserted_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategori_service_id' => $kategoriFacial,
                'kode' => 'SVC-002',
                'nama_service' => 'Facial Hydrafacial',
                'barcode' => null,
                'besaran' => 'per session',
                'isi' => 1,
                'satuan' => 'sesi',
                'ppn' => 0,
                'harga_jual' => 850000,
                'durasi' => 75,
                'total_dibeli' => 0,
                'inserted_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategori_service_id' => $kategoriTreatment,
                'kode' => 'SVC-003',
                'nama_service' => 'Acne Treatment',
                'barcode' => null,
                'besaran' => 'per session',
                'isi' => 1,
                'satuan' => 'sesi',
                'ppn' => 0,
                'harga_jual' => 350000,
                'durasi' => 45,
                'total_dibeli' => 0,
                'inserted_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
