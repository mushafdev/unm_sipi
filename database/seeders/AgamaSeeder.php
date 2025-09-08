<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $agamas = [
            ['agama' => 'Islam'],
            ['agama' => 'Kristen'],
            ['agama' => 'Protestan'],
            ['agama' => 'Katolik'],
            ['agama' => 'Hindu'],
            ['agama' => 'Buddha'],
            ['agama' => 'Konghucu'],
            ['agama' => 'Lainnya'],
        ];

        DB::table('agamas')->insert($agamas);
    }
}
