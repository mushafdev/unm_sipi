<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('jurusans')->insert([
             [
                'jurusan'=>'Teknik Informatika dan Komputer',
                'pengelola_nama'=>'Shabrina Syntha Dewi, S.Pd., M.Pd',
                'pengelola_nip'=>'199310052019032026',
                'kajur_nama'=>'Dr. Ir. Abdul Muis Mappalotteng, S.Pd., M.Pd., M.T., IPM.',
                'kajur_nip'=>'196910181994031001',
                'sekjur_nama'=>'Dr. Sanatang., S.Pd., M.T',
                'sekjur_nip'=>'197520072010122001',
                'alamat'=>'Jl. Daeng Tata Raya Parang Tambung Makassar - 90224',
                'telp'=>'0411-864935',
                'fax'=>'0411-861507',
                'hp'=>'0853-1122-4040',
                'email'=>'jtik@unm.ac.id',
                'website'=>'tik.ft.unm.ac.id',
                'kota'=>'Makassar',
                'fakultas_id'=>1,'created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
