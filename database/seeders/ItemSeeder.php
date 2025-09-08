<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Ambil kategori dan user untuk relasi
        $kategoriId = DB::table('kategori_items')->inRandomOrder()->value('id');
        $userId = DB::table('users')->inRandomOrder()->value('id');
        $satuan = DB::table('satuans')->inRandomOrder()->value('satuan');

        for ($i = 1; $i <= 400; $i++) {
            DB::table('items')->insert([
                'kategori_item_id' => $kategoriId,
                'kode' => 'ITM-' . Str::padLeft($i, 4, '0'),
                'nama_item' => 'Item ' . $i,
                'barcode' => Str::random(10),
                'besaran' => $satuan,
                'isi' => rand(1, 20),
                'satuan' => $satuan,
                'hpp' => rand(5000, 10000),
                'hna' => rand(11000, 15000),
                'ppn' => 0,
                'harga_jual' => rand(15000, 20000),
                'reorder_point' => rand(5, 15),
                'inserted_by' => $userId,
                'updated_by' => $userId,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }
}
