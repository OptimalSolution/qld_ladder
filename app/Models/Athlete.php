<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
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
        'last_played',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class, 'club_id', 'ratings_central_club_id');
    }

    public function scopeRecentlyPlayed($query)
    {
        return $query->where('last_played', '>=', now()->startOfYear()->subYears(1));
    }

    public function getAgeAttribute() : int
    {
        return Carbon::parse($this->birth_date)->age;
    }
}
