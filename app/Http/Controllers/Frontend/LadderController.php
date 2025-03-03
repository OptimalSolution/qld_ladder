<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Club;
use Illuminate\Support\Str;
use Debugbar;
use App\Services\AthleteService;

class LadderController extends Controller
{
    protected $athleteService;

    public $age_groups = [
        'U7' => 'Under 7',
        'U9' => 'Under 9',
        'U11' => 'Under 11',
        'U13' => 'Under 13',
        'U15' => 'Under 15',
        'U17' => 'Under 17',
        'U19' => 'Under 19',
        'U21' => 'Under 21',
        'O30' => 'Over 30',
        'O40' => 'Over 40',
        'O50' => 'Over 50',
        'O60' => 'Over 60',
        'O65' => 'Over 65',
        'O70' => 'Over 70',
        'O75' => 'Over 75',
        'O80' => 'Over 80',
        'O85' => 'Over 85',
        'Open' => 'Open'
    ];

    public $gender_groupings = [
        'Male' => 'M',
        'Female' => 'F',
        'Other' => '',
        'Mixed' => '-'
    ];

    public function __construct(AthleteService $athleteService)
    {
        $this->athleteService = $athleteService;
    }
 
    /**
     * Retrieves the view for the index page of the frontend.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('frontend.ladder.index');
    }

    /**
     * QLD Junior Ladder Page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function ageGroups(?string $gender_group = 'Mixed', ?string $group = 'Open', ?string $club_id = null)
    {
        // Handle invalid groups
        if (!in_array($group, array_keys($this->age_groups)) || 
            !in_array($gender_group, array_keys($this->gender_groupings))) {
            return redirect()->route('age-groups');
        }

        $athletes = $this->athleteService->getRecentlyPlayedAthletes($gender_group, $group, $club_id);
        $athletes = Athlete::with('club:ratings_central_club_id,name,website')
                        ->recentlyPlayed();

        if ($gender_group !== 'Mixed') {
            $athletes = $athletes->where('sex', $this->gender_groupings[$gender_group]);
        }

        if ($group !== 'Open') {
        
            $age_group_number = explode(' ', $this->age_groups[$group])[1];
            $date_to_compare = now()->startOfYear()->subYears($age_group_number);
            
            $athletes = $athletes->where('birth_date', '!=', '');
            
            if (str_starts_with($group, 'U')) {
                \Debugbar::info('Looking for players under ' . $age_group_number . ' and born before ' . $date_to_compare);
                $date_minimum = now()->subYears(3)->startOfYear();
                $athletes = $athletes->where('birth_date', '>=', $date_to_compare->format('Y-m-d'));
                $athletes = $athletes->where('birth_date', '<=', $date_minimum->format('Y-m-d'));
            } else if (str_starts_with($group, 'O')) {
                \Debugbar::info('Looking for players over ' . $age_group_number . ' and born before ' . $date_to_compare);
                $athletes = $athletes->where('birth_date', '<=', $date_to_compare);
            }           
        }

        $athletes = $athletes->orderByDesc('rating')->get();
        $gender_groups = $this->athleteService->getUniqueGenderGroups();
        
        return view('frontend.ladder.age-groups', compact('athletes', 'group', 'gender_group', 'gender_groups'))->with('age_groups', $this->age_groups);
    }

    /**
     * QLD Junior Ladder Page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function genderGroups()
    {
        // Add a cache key to the query
        $cache_key = 'recent-athletes';
        $athletes = \Cache::remember($cache_key, 7200, function () {
            return Athlete::with('club:ratings_central_club_id,nickname,website')
                        ->recentlyPlayed()
                        ->orderByDesc('rating')
                        ->get();
        });
        
        // Add caching to the total count
        $athlete_total = Cache::remember('athlete-total', 7200, function () {
            return Athlete::count();
        });

        $ladder_total = $athletes->count();
        $gender_grouped_athletes = $athletes->groupBy('sex')->sortKeys();

        $gender_grouped_athletes = $gender_grouped_athletes->mapWithKeys(function ($group, $key) {
            $key = match ($key) {
                'M' => 'Male',
                'F' => 'Female',
                default => 'Other',
            };
            return [$key => $group];
        });
        $gender_groups = $gender_grouped_athletes->keys();
        return view('frontend.ladder.gender-groups', 
                compact('gender_grouped_athletes', 
                    'gender_groups', 
                    'athlete_total',
                    'ladder_total'));
    }

    /**
     * QLD Junior Ladder Page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function clubGroups(?string $club_id = null, ?string $club_slug = null, ?string $gender_group = 'Mixed')
    {
  
        // All the clubs that have recently played athletes
        $club_groups = Cache::remember('clubs-with-recent-athletes', 7200, function () {
            return Club::whereHas('athletes', function ($query) {
                $query->recentlyPlayed();
            })
            ->orderBy('name')
            ->get();
        });

        // Pick a random club if no club is selected
        if(empty($club_id)) {
            
            $random_club = $club_groups->random();
            $club_id = $random_club->ratings_central_club_id;
            $club_slug = Str::slug($random_club->name);
        }

        $athletes = Athlete::with('club:ratings_central_club_id,name')
                        ->recentlyPlayed()
                        ->orderByDesc('rating')
                        ->where('club_id', $club_id);

        if ($gender_group && $gender_group !== 'Mixed') {
            $athletes = $athletes->where('sex', $this->gender_groupings[$gender_group]);
        }
                        
        $athletes = $athletes->get();
        $genders = $this->athleteService->getUniqueGenderGroups();
        return view('frontend.ladder.club-groups', compact('club_groups', 'athletes', 'club_id', 'club_slug', 'gender_group', 'genders'));
    }
}
