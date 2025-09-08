<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranFoto extends Model
{
    use HasFactory;
     protected $fillable = [
        'pendaftaran_id',
        'foto', 
        'position', 
        'keterangan',
    ];
}
