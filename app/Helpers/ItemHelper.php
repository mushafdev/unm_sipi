<?php
namespace App\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ItemHistory;

class ItemHelper
{
    public static function createHistory(
        $no_referensi,
        $transaksi_id,
        $jenis_table,
        $jenis_text,
        $item_id,
        $qty,
        $hpp,
        $type,
        $waktu,
        $keterangan = null,
        $no_batch = null,
        $tgl_kadaluarsa = null,
        $gudang_id = null,
        $selisih = null
    ) {
        $save=ItemHistory::create([
            'jenis_table'            => $jenis_table, // table
            'jenis_text'       => $jenis_text, // Text
            'type'             => $type,  // masuk, keluar, reset
            'no_referensi'     => $no_referensi,
            'waktu'            => $waktu,
            'transaksi_id'     => $transaksi_id,
            'keterangan'       => $keterangan,
            'item_id'          => $item_id,
            'gudang_id'        => $gudang_id,
            'no_batch'         => $no_batch,
            'tgl_kadaluarsa'   => $tgl_kadaluarsa,
            'qty'              => $qty,
            'hpp'              => $hpp??0,
            'total'            => $qty * $hpp??0,
            'selisih'          => $selisih??0,
            'user_id'          => auth()->user()->id,
        ]);

        return $save;
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */

