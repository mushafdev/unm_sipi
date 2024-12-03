<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
             ['nama'=>'Mushaf','email'=>'mushafdev@gmail.com','alamat'=>'Jl. Alamat','telp'=>'085423444567','photo'=>NULL,'role'=>'superadmin', 'username'=>'admin123','password' => Hash::make('#Admin123'),'created_at' => now(), 'updated_at' => now(),'aktif'=>'Y'],
         ]);

    }
}
