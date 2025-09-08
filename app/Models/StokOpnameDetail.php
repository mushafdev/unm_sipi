<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpnameDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'stok_opname_id',
        'item_id',
        'item_stok_id',
        'no_batch',
        'tgl_kadaluarsa',
        'stok_fisik',
        'stok_system',
        'selisih',
        'hpp',
        'total',
    ];

    protected $casts = [
        'tgl_kadaluarsa' => 'date',
        'stok_fisik' => 'float',
        'stok_system' => 'float',
        'selisih' => 'float',
        'hpp' => 'float',
        'total' => 'float',
    ];
}
