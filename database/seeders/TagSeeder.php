<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'Acne Treatment',
            'Whitening',
            'Anti Aging',
            'Chemical Peeling',
            'Facial Bulanan',
            'Botox',
            'Laser Treatment',
            'Kulit Sensitif',
            'Kulit Berminyak',
            'Kulit Kering',
            'Preferensi Produk Organik',
            'VIP Member',
            'Hair Removal',
            'Slimming Treatment',
            'Treatment Wajah Pria'
        ];

        foreach ($tags as $tagName) {
            Tag::firstOrCreate(['name' => $tagName]);
        }

    }
}
