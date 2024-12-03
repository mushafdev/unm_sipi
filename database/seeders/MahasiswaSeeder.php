<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('mahasiswas')->insert([
             ['nama'=>'Alimuddin','prodi_id'=>2,'nim'=>'20240201','kelas'=>'TEKOM A','telp'=>'085212555432','email'=>'alimuddin@gmail.com','role'=>'mahasiswa','username'=>'mhs001','password'=>bcrypt('mhs001'),'aktif'=>'Y','created_at' => now(), 'updated_at' => now()],
             ['nama'=>'Harlina','prodi_id'=>2,'nim'=>'20240202','kelas'=>'TEKOM A','telp'=>'08544235432','email'=>'herlina@gmail.com','role'=>'mahasiswa','username'=>'mhs002','password'=>bcrypt('mhs002'),'aktif'=>'Y','created_at' => now(), 'updated_at' => now()],
             ['nama'=>'Basuki','prodi_id'=>2,'nim'=>'20240203','kelas'=>'TEKOM A','telp'=>'08534325432','email'=>'basuki@gmail.com','role'=>'mahasiswa','username'=>'mhs003','password'=>bcrypt('mhs003'),'aktif'=>'Y','created_at' => now(), 'updated_at' => now()],
             ['nama'=>'Jajang','prodi_id'=>2,'nim'=>'20240204','kelas'=>'TEKOM A','telp'=>'08521253232','email'=>'jajang@gmail.com','role'=>'mahasiswa','username'=>'mhs004','password'=>bcrypt('mhs004'),'aktif'=>'Y','created_at' => now(), 'updated_at' => now()],
         ]);

    }
}
