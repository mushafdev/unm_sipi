<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranService extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'service_id',
        'qty',
        'hpp',
        'harga_jual',
        'diskon',
        'diskon_rp',
        'ppn',
        'ppn_rp',
        'sub_total',
        'catatan',
    ];
}
