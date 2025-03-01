<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Illuminate\Http\Request;

class LadderController extends Controller
{
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
    public function ageGroups()
    {
        return view('frontend.ladder.age-groups');
    }

    /**
     * QLD Junior Ladder Page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function genderGroups()
    {
        $athletes = Athlete::with('club:ratings_central_club_id,nickname')->get()->sortByDesc('rating');
        
        $athlete_total = $athletes->count();
        $gender_grouped_athletes = $athletes->groupBy('sex');

        $gender_grouped_athletes = $gender_grouped_athletes->mapWithKeys(function ($group, $key) {
            $key = match ($key) {
                'M' => 'Male',
                'F' => 'Female',
                default => 'Other',
            };
            return [$key => $group];
        });
        $gender_groups = $gender_grouped_athletes->keys();
        return view('frontend.ladder.gender-groups', compact('gender_grouped_athletes', 'gender_groups', 'athlete_total'));
    }

    /**
     * QLD Junior Ladder Page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function clubGroups()
    {
        return view('frontend.ladder.club-groups');
    }
}
