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
use Illuminate\Support\Facades\Log;
use Modules\Tag\Models\Tag;



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

    public function ladderFilter(?string $gender_group = 'Mixed', ?string $age_group = 'Open', ?string $club_id = null, ?string $club_slug = null)
    {
        $athletes = $this->athleteService->getRecentlyPlayedAthletes($gender_group, $this->age_groups[$age_group], $club_id);
        $clubs = Club::all();
        $selected_location = $this->getLocationFromClubId($club_id);
        return view('frontend.ladder.ladder-filter', compact('athletes', 'gender_group', 'age_group', 'club_id', 'club_slug', 'clubs', 'selected_location'))
                ->with('age_groups', $this->age_groups);
    }

    public function getLocationFromClubId($club_id)
    {
        $location = null;
        
        if (str_starts_with($club_id, 'region-')) {
            $parts = explode('-', $club_id);
            $club_id = array_pop($parts);
            $location = Tag::with('clubs')->select('name')->where('group_name', 'Regions')->where('id', $club_id)->orderBy('name')->first()->name; 
        } elseif (str_starts_with($club_id, 'sub-region-')) {
            $parts = explode('-', $club_id);
            $club_id = array_pop($parts);
            $location = Tag::with('clubs')->select('name')->where('group_name', 'Sub Regions')->where('id', $club_id)->orderBy('name')->first()->name;
        } elseif (!empty($club_id) && is_numeric($club_id)) {
            $location = Club::with('tags')->select('name')->where('ratings_central_club_id', $club_id)->orderBy('name')->first()->name;
        }

        return $location;
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
    public function genderGroups(?string $gender_group = 'Mixed')
    {
        // Handle invalid groups
        if (!in_array($gender_group, array_keys($this->gender_groupings))) {
            return redirect()->route('gender-groups');
        }

        $athletes = Athlete::with('club:ratings_central_club_id,name,website')
                        ->recentlyPlayed();

        if ($gender_group !== 'Mixed') {
            $athletes = $athletes->where('sex', $this->gender_groupings[$gender_group]);
        }

        $athletes = $athletes->orderByDesc('rating')->get();
        $gender_groups = $this->athleteService->getUniqueGenderGroups();
        $page_title = 'Gender Groups';
        $athlete_total = Athlete::count();
        $ladder_total = $athletes->count();

        $gender_grouped_athletes = [];
        return view('frontend.ladder.gender-groups', compact('athletes', 'gender_group', 'gender_groups', 'page_title', 'athlete_total', 'ladder_total', 'gender_grouped_athletes'))->with('age_groups', $this->age_groups);
    }

    /**
     * QLD Junior Ladder Page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function clubGroups(?string $club_id = null, ?string $club_slug = null, ?string $gender_group = 'Mixed')
    {
        $mixed_clubs = true;

        // Pick a random club if no club is selected
        if(empty($club_id)) {
            $selected_club = Club::whereHas('athletes', function ($query) {
                $query->recentlyPlayed();
            })->inRandomOrder()->first();
            $club_id = $selected_club->ratings_central_club_id;
            $page_title = 'Club & Regional Ladders'; 
        } else {
            $selected_club = Club::where('ratings_central_club_id', $club_id)
                                ->whereHas('athletes', function ($query) {
                                    $query->recentlyPlayed();
                                })->first();
            $page_title = $selected_club->name;
        }

        $club_slug = Str::slug($selected_club->name);
        
        
        $athletes = Athlete::with('club:ratings_central_club_id,name,website')
            ->recentlyPlayed()
            ->orderByDesc('rating');

        // Region & sub-region filter
        if (str_starts_with($club_id, 'region-') || str_starts_with($club_id, 'sub-region-')) {

            // Extract the ID from either region-X or sub-region-X pattern
            $parts = explode('-', $club_id);
            $region_id = end($parts);
            
            // Find all athletes in this region
            $athletes = Athlete::with('club:ratings_central_club_id,name,website')
                            ->recentlyPlayed()
                            ->orderByDesc('rating')
                            ->whereHas('club', function($query) use ($region_id) {
                                $query->whereHas('tags', function($tagQuery) use ($region_id) {
                                    $tagQuery->where('tags.id', $region_id);
                                });
                            });

        } else if ($club_id !== 'all') {

            // Single club filter
            $mixed_clubs = false;
            $athletes = $athletes->where('club_id', $club_id);
        }

        if ($gender_group && $gender_group !== 'Mixed') {
            $athletes = $athletes->where('sex', $this->gender_groupings[$gender_group]);
        }
                        
        $athletes = $athletes->get();
        $genders = $this->athleteService->getUniqueGenderGroups();
        return view('frontend.ladder.club-groups', compact('athletes', 'club_id', 'club_slug', 'gender_group', 'genders', 'mixed_clubs', 'page_title'));
    }
}
