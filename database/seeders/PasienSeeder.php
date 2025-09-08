<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PasienSeeder extends Seeder
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
        for ($i = 2; $i <= 100; $i++) {
            $nama = $faker->name;
            $data[] = [
                'no_rm' => str_pad($i, 4, '0', STR_PAD_LEFT),
                'tgl_daftar' =>  $faker->date('Y-m-d'),
                'nama' => $nama,
                'panggilan' => explode(' ', $nama)[0],
                'nik' => $faker->unique()->numerify('3204##########'),
                'alamat' => $faker->address,
                'tgl_lahir' => $faker->date('Y-m-d', '2005-01-01'),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'no_hp' => $faker->phoneNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('pasiens')->insert($data);

    }
}
