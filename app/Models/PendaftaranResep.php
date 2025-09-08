<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranResep extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftaran_id',
        'doketr_id',
        'resep',
    ];
}
