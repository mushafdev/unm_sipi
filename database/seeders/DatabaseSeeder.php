<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersSeeder::class,
            SettingSeeder::class,
            
            FakultasSeeder::class,
            JurusanSeeder::class,
            ProdiSeeder::class,
            LokasiPiSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,


          ]);
    }
}
