<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Room;

use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rooms')->insert([
            [
                'name' => 'Ruang Konsultasi',
                'code' => 'KONS001',
                'lantai' => '1',
                'description' => 'Ruang untuk konsultasi awal dengan dokter.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Perawatan 1',
                'code' => 'PRWT001',
                'lantai' => '2',
                'description' => 'Ruang untuk facial dan perawatan dasar.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Perawatan 2',
                'code' => 'PRWT002',
                'lantai' => '2',
                'description' => 'Ruang perawatan lanjutan seperti peeling atau laser ringan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Tindakan Medis',
                'code' => 'TNDK001',
                'lantai' => '2',
                'description' => 'Ruang untuk tindakan dokter seperti botox atau filler.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruang Sterilisasi',
                'code' => 'STER001',
                'lantai' => '1',
                'description' => 'Tempat sterilisasi alat medis.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
