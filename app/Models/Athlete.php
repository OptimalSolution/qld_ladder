<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    protected $fillable = [
        'name',
        'ratings_central_id',
        'rating',
        'club_id',
        'city',
        'state',
        'province',
        'postal_code',
        'country',
        'email',
        'birth_date',
        'sex',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id', 'ratings_central_club_id');
    }
}
