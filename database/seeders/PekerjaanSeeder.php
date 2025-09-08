<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pekerjaans = [
            ['pekerjaan' => 'Tidak Bekerja'],
            ['pekerjaan' => 'Pelajar / Mahasiswa'],
            ['pekerjaan' => 'PNS'],
            ['pekerjaan' => 'TNI / POLRI'],
            ['pekerjaan' => 'Pegawai Swasta'],
            ['pekerjaan' => 'Wiraswasta'],
            ['pekerjaan' => 'Petani'],
            ['pekerjaan' => 'Nelayan'],
            ['pekerjaan' => 'Buruh'],
            ['pekerjaan' => 'Guru'],
            ['pekerjaan' => 'Dosen'],
            ['pekerjaan' => 'Dokter'],
            ['pekerjaan' => 'Perawat'],
            ['pekerjaan' => 'Pedagang'],
            ['pekerjaan' => 'Sopir'],
            ['pekerjaan' => 'Ibu Rumah Tangga'],
            ['pekerjaan' => 'Pensiunan'],
            ['pekerjaan' => 'Lainnya']
        ];

        DB::table('pekerjaans')->insert($pekerjaans);
    }
}
