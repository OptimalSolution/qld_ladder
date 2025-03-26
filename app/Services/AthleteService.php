<?php

namespace App\Services;

use App\Models\Athlete;
use Debugbar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class AthleteService
{
    /**
     * Get athletes who have recently played, with optional filtering
     *
     * @param string|null $gender Gender filter (M, F, or null for all)
     * @param string|null $ageGroup Age group filter (U7, O30, etc.)
     * @param string|null $clubId Club ID filter
     * @return Collection
     */
    public function getRecentlyPlayedAthletes(?string $gender = 'Mixed', ?string $age_group = null, ?string $club_id = null): Collection
    {
        // Create a cache key based on the filter parameters
        $cacheKey = 'recently-played-athletes-filtered-' . md5($gender . '-' . $age_group . '-' . $club_id);
        
        $recentlyPlayedAthletes = Cache::remember($cacheKey, 0, function () use ($gender, $age_group, $club_id) {
            $query = Athlete::with('club:ratings_central_club_id,name,website')
                            ->recentlyPlayed();
    
            if ($gender !== 'Mixed') {
                // TODO: Change this to a validated gender that exists in the source data
                $query->where('sex', $gender[0]); 
                DebugBar::info('Recent Athletes: Filtered by gender - ' . $gender);
            }
    
            if ($age_group) {
                $this->applyAgeGroupFilter($query, $age_group);
                DebugBar::info('Recent Athletes: Filtered by age group - ' . $age_group);
            }
    
            // if (!empty($club_id) && $club_id !== 'all') {
            //     $query->where('club_id', $club_id);
            // }

            // Region & sub-region filter
            if (str_starts_with($club_id, 'region-') || str_starts_with($club_id, 'sub-region-')) {

                // Extract the ID from either region-X or sub-region-X pattern
                $parts = explode('-', $club_id);
                $region_id = end($parts);
                
                // Find all athletes in this region
                $query->whereHas('club', function($query) use ($region_id) {
                    $query->whereHas('tags', function($tagQuery) use ($region_id) {
                        $tagQuery->where('tags.id', $region_id);
                    });
                });
                \DebugBar::info('Recent Athletes: Filtered by region - ' . $region_id);

            } else if ($club_id !== 'all') {
                $query->where('club_id', $club_id);
                \DebugBar::info('Recent Athletes: Filtered by club - ' . $club_id);
            }
    
            return $query->orderByDesc('rating')->get();
        });
        

        return $recentlyPlayedAthletes;
    }

    /**
     * Apply age group filtering to the athlete query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $ageGroup
     * @return void
     */
    private function applyAgeGroupFilter(Builder &$query, string $ageGroup): void
    {
        
        $age_group_parts = explode(' ', $ageGroup);
        if (count($age_group_parts) < 2) {
            return;
        }
        
        $age_group_number = $age_group_parts[1];
        $query->where('birth_date', '!=', '');
        if (str_starts_with($ageGroup, 'Under')) {
            // For "Under X", get people born after Jan 1 of the year when they would turn X
            $date_to_compare = now()->startOfYear()->subYears($age_group_number);
            $date_minimum = now()->subYears(3)->startOfYear();
            $query->where('birth_date', '>=', $date_to_compare->format('Y-m-d'))
                  ->where('birth_date', '<=', $date_minimum->format('Y-m-d'));
        } else if (str_starts_with($ageGroup, 'Over')) {
            // For "Over X", get people born on or before Dec 31 of the year when they turn X
            $date_to_compare = now()->endOfYear()->subYears($age_group_number);
            $query->where('birth_date', '<=', $date_to_compare->format('Y-m-d'));
        }
    }

    /**
     * Convert sex codes to readable gender names
     *
     * @param Collection|SupportCollection $athletes
     * @return SupportCollection
     */
    public function getUniqueGenderGroups(): SupportCollection
    {
        return \Cache::remember('unique-gender-groups', 7200, function () {
            $genderGroups = Athlete::recentlyPlayed()->pluck('sex')->unique()->map(function($sex) {
                return match($sex) {
                    'M' => 'Male',
                    'F' => 'Female',
                    default => 'Not Specified',
                };
            });
            
            // Add Mixed option
            $genderGroups->push('Mixed');
            return $genderGroups;
        });
    }

    public function getAgeGroupsMap() {

        return [
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
    }
} 