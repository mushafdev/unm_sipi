<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_table',
        'jenis_text',
        'type',
        'no_referensi',
        'waktu',
        'transaksi_id',
        'keterangan',
        'item_id',
        'gudang_id',
        'no_batch',
        'tgl_kadaluarsa',
        'qty',
        'selisih',
        'hpp',
        'total',
        'user_id',
    ];
}
