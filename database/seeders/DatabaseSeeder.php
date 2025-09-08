<?php

namespace Database\Seeders;

use App\Models\BankSoal;
use App\Models\JenisLayanan;
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
            AdminSeeder::class,
            SettingSeeder::class,
            SumberPenjualansSeeder::class,
            JabatanSeeder::class,
            JenisLayananSeeder::class,
            KategoriItemSeeder::class,
            GudangSeeder::class,
            SatuanSeeder::class,
            AgamaSeeder::class,
            PendidikanSeeder::class,
            PekerjaanSeeder::class,
            KaryawanSeeder::class,
            DokterSeeder::class,
            PasienSeeder::class,
            ItemSeeder::class,
            ItemStokSeeder::class,
            AkunKasSeeder::class,
            KategoriKasSeeder::class,
            KategoriServiceSeeder::class,
            ServiceSeeder::class,
            RoomSeeder::class,
            BedSeeder::class,


          ]);
    }
}
