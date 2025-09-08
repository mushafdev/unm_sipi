<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = ['pendaftaran_id', 'room_id', 'bed_number', 'status', 'notes'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
