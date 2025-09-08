<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ItemStokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $itemIds = DB::table('items')->pluck('id');
        $gudangIds = DB::table('gudangs')->pluck('id');

        if ($itemIds->isEmpty() || $gudangIds->isEmpty()) {
            $this->command->warn('Seeder gagal: item atau gudang belum tersedia.');
            return;
        }

        foreach ($itemIds as $itemId) {
            $gudangId = 1;

            $stok = rand(10, 100);
            $hpp = rand(5000, 10000);
            $total = $stok * $hpp;

            DB::table('item_stoks')->insert([
                'item_id' => $itemId,
                'gudang_id' => $gudangId,
                'stok' => $stok,
                'no_batch' => 'BATCH-' . strtoupper(Str::random(6)),
                'tgl_kadaluarsa' => Carbon::now()->addMonths(rand(6, 24))->format('Y-m-d'),
                'waktu_masuk' => Carbon::now()->subDays(rand(1, 60)),
                'hpp' => $hpp,
                'total' => $total,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
