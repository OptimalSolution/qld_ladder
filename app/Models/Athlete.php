<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Services\AthleteService;

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
        return Carbon::parse($this->birth_date)->startOfYear()->age;   
    }

    public function clubWebsite()
    {
        $special_case_clubs = [
            '164' => 'https://tabletenniscairns.com.au',
            '175' => 'https://townsvilletabletennis.org.au',
            '169' => 'https://www.revolutionise.com.au/mackaytta',
            '172' => 'https://www.rockhamptontabletennis.com',
            '163' => 'https://www.revolutionise.com.au/bundytt/',
            '173' => 'https://www.sunshinecoasttabletennis.club',
            '174' => 'https://toowoombatabletennis.club',
        ];

        $website = $this->club?->website;
        if($this->club_id && empty($website)) {
            $website = $special_case_clubs[$this->club_id] ?? null;
        }

        return $website;
    }

    public function ageRange()
    {
        $start_of_year_age = (empty($this->birth_date)) ? 0 : Carbon::parse($this->birth_date)->startOfYear()->age;
        return $start_of_year_age > 21 ? '21+' : $start_of_year_age;

        // return $this->age . ' => ' . (new AthleteService())->calculateAgeRange(intval($this->age));
    }
}
