<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStok extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'gudang_id',
        'stok',
        'no_batch',
        'tgl_kadaluarsa',
        'waktu_masuk',
        'hpp',
        'total',
    ];
}
