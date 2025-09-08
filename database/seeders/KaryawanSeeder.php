<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID'); // gunakan locale Indonesia
        $data = [];


        // data dummy faker
        for ($i = 2; $i <= 50; $i++) {
            $nama = $faker->name;
            $data[] = [
                'id_karyawan' => str_pad($i, 4, '0', STR_PAD_LEFT),
                'jabatan_id' =>  $faker->randomElement([1,2,3,4]),
                'nama' => $nama,
                'nik' => $faker->unique()->numerify('3204##########'),
                'alamat' => $faker->address,
                'tgl_masuk' => $faker->date('Y-m-d', '2005-01-01'),
                'no_hp' => $faker->phoneNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('karyawans')->insert($data);

    }
}
