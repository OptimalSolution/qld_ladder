<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Athlete;
use App\Services\AthleteService;

class RatingDistribution extends Component
{
    public $ratedAthletes;
    public $ratings;
    public $ratingsBreakdown;
    

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
        // $this->ratings = Athlete::select('rating')->get()->pluck('rating');

        $age_groups = $athleteService->getAgeGroupsMap();
        $this->ratedAthletes = $this->athleteService->getRecentlyPlayedAthletes($genderGroup, $age_groups[$ageGroup], $clubId);
        $this->ratings = $this->ratedAthletes->pluck('rating');

        // Get the min and max ratings
        $minRating = $this->ratings->min();
        $maxRating = $this->ratings->max();
        
        // Round min and max to nearest 100 boundaries
        $minRating = floor($minRating / 100) * 100;
        $maxRating = ceil($maxRating / 100) * 100;
        
        // Use fixed bucket size of 100
        $bucketSize = 200;
        
        // Calculate number of buckets needed
        $numBuckets = ($maxRating - $minRating) / $bucketSize;
        
        // Initialize buckets
        $buckets = [];
        for ($i = 0; $i < $numBuckets; $i++) {
            $lowerBound = $minRating + ($i * $bucketSize);
            $buckets[$lowerBound] = 0;
        }
        
        // Count ratings in each bucket
        $this->ratingsBreakdown = $this->ratings->reduce(function ($carry, $rating) use ($minRating, $maxRating, $bucketSize) {
            // Skip ratings outside our rounded boundaries (shouldn't happen but just in case)
            if ($rating < $minRating || $rating > $maxRating) {
                return $carry;
            }
            
            $bucketIndex = floor(($rating - $minRating) / $bucketSize);
            // Handle edge case for max value
            if ($rating == $maxRating) {
                $bucketIndex = ($maxRating - $minRating) / $bucketSize - 1;
            }
            
            $lowerBound = $minRating + ($bucketIndex * $bucketSize);
            
            $carry[$lowerBound] = ($carry[$lowerBound] ?? 0) + 1;
            return $carry;
        }, $buckets);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        // Format the ratings breakdown for the chart
        $formattedRatingsBreakdown = collect($this->ratingsBreakdown)
            ->map(function ($count, $lowerBound) {
                return [
                    'x' => (string) $lowerBound,
                    'y' => $count
                ];
            })
            ->values();
            
        return view('components.rating-distribution')->with([
            'ratings' => $this->ratings,
            'ratingsBreakdown' => $formattedRatingsBreakdown,
        ]);
    }
}
