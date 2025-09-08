<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KategoriServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori_services')->insert([
            [
                'kategori_service' => 'Facial',
                'inserted_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategori_service' => 'Body Treatment',
                'inserted_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'kategori_service' => 'Laser',
                'inserted_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
