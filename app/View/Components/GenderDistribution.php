<?php

namespace App\View\Components;

use App\Services\AthleteService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GenderDistribution extends Component
{
    public $genderBreakdown;
    public $ratedAthletes;

    /**
     * Create a new component instance.
     */
    public function __construct(protected AthleteService $athleteService)
    {
        // Get the current route parameters
        $routeParameters = request()->route()->parameters();
        
        // Extract relevant parameters if they exist
        $ageGroup = $routeParameters['age_group'] ?? 'Open';
        $genderGroup = $routeParameters['gender_group'] ?? 'Mixed';
        $clubId = $routeParameters['club_id'] ?? 'all';

        $age_groups = $athleteService->getAgeGroupsMap();
        $this->ratedAthletes = $this->athleteService->getRecentlyPlayedAthletes($genderGroup, $age_groups[$ageGroup], $clubId);
        $this->genderBreakdown = $this->ratedAthletes
            ->groupBy('sex')
            ->map(function ($athletes, $sex) {
                return count($athletes);
            })
            ->mapWithKeys(function ($count, $sex) {
                $label = match($sex) {
                    'M' => 'Male',
                    'F' => 'Female',
                    default => $sex
                };
                return [$label => $count];
            });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        
        return view('components.gender-distribution')->with([
            'genderCounts' => $this->genderBreakdown->values(),
            'genderLabels' => $this->genderBreakdown->keys()
        ]);
    }
}
