<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DokterSeeder extends Seeder
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
        for ($i = 2; $i <= 10; $i++) {
            $nama = $faker->name;
            $data[] = [
                'no_str' => str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama' => $nama,
                'nik' => $faker->unique()->numerify('3204##########'),
                'alamat' => $faker->address,
                'no_hp' => $faker->phoneNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('dokters')->insert($data);

    }
}
