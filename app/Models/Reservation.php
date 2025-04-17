<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'h_debut',
        'h_fin',
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
