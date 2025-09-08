<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'lantai', 'description'];

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
}
