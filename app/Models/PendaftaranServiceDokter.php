<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranServiceDokter extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_service_id',
        'dokter_id', 
        'catatan',
    ];
}
