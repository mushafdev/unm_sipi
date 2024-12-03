<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('prodis')->insert([
             [
                'prodi'=>'Pendidikan Teknik Informatika',
                'kaprodi_nama'=>'Fathahillah, S.Pd, M.Eng.',
                'kaprodi_nip'=>'198603262015041001',
                'jurusan_id'=>1,'created_at' => now(), 'updated_at' => now()],
             [
                'prodi'=>'Teknik Komputer',
                'kaprodi_nama'=>'Dr. Satria Gunawan Zain, S.PD., M.T.',
                'kaprodi_nip'=>'198008092010121002',
                'jurusan_id'=>1,
                'created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
