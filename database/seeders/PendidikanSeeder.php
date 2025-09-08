<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pendidikans = [
            ['pendidikan' => 'SD Sederajat'],
            ['pendidikan' => 'SMP Sederajat'],
            ['pendidikan' => 'SMA Sederajat'],
            ['pendidikan' => 'Diploma I / II'],
            ['pendidikan' => 'Diploma III'],
            ['pendidikan' => 'Sarjana / S1'],
            ['pendidikan' => 'Magister / S2'],
            ['pendidikan' => 'Doktor / S3'],
            ['pendidikan' => 'Tidak Sekolah'],
        ];

        DB::table('pendidikans')->insert($pendidikans);
    }
}
