<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'nom',
        'capacite',
        'surface',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
