<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('dosens')->insert([
             ['nama'=>'Mushaf S.Kom.,M.Kom.','prodi_id'=>2,'nip'=>'199400124423212','pangkat'=>'Assisten Ahli','golongan'=>'IIIB','jabatan'=>'Dosen','telp'=>'085676555432','role'=>'dosen','created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
