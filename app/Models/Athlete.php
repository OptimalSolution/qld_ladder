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
        'stdev',
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
        return $query->where('last_played', '>=', now()->startOfYear()->subYears(1))->where('stdev', '<', 200);
    }

    public function getAgeAttribute() : int|string
    {
        $start_of_year_age = (empty($this->birth_date)) ? 0 : Carbon::parse($this->birth_date)->startOfYear()->age;
        return $start_of_year_age > 21 ? '21+' : $start_of_year_age;
    }
}
