<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
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
    protected $age_groups;

    public $gender_groupings = [
        'Male' => 'M',
        'Female' => 'F',
        'Other' => '',
        'Mixed' => '-'
    ];

    public function __construct(AthleteService $athleteService)
    {
        $this->athleteService = $athleteService;
        $this->age_groups = $athleteService->getAgeGroupsMap();
    }
 
    /**
     * Retrieves the view for the index page of the frontend.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return redirect()->route('ladder-filter', [
            'gender_group' => 'Mixed',
            'age_group' => 'Open',
            'club_id' => 'all',
            'club_slug' => 'combined'
        ]);
    }

    public function ladderFilter(?string $gender_group = 'Mixed', ?string $age_group = 'Open', ?string $club_id = 'all', ?string $club_slug = 'combined')
    {
        DebugBar::info('Ladder Filter: ' . $gender_group . ' ' . $age_group . ' ' . $club_id);
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
