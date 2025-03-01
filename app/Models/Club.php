<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
        'name',
        'ratings_central_club_id',
        'nickname',
        'city',
        'state',
        'province',
        'postal_code',
        'website',
    ];
}
