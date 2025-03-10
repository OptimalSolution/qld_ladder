<?php

namespace App\Services;

use App\Models\Athlete;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;

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
    public function getRecentlyPlayedAthletes(?string $gender = null, ?string $ageGroup = null, ?string $clubId = null): Collection
    {
        // Create a cache key based on the filter parameters
        $cacheKey = 'recently-played-athletes-' . md5($gender . '-' . $ageGroup . '-' . $clubId);
        
        $recentlyPlayedAthletes = Cache::remember($cacheKey, 7200, function () use ($gender, $ageGroup, $clubId) {
            $query = Athlete::with('club:ratings_central_club_id,name,website')
                            ->recentlyPlayed();
    
            if ($gender && $gender !== '-') {
                $query->where('sex', $gender);
            }
    
            if ($ageGroup) {
                $this->applyAgeGroupFilter($query, $ageGroup);
            }
    
            if ($clubId) {
                $query->where('club_id', $clubId);
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
    private function applyAgeGroupFilter($query, string $ageGroup): void
    {
        $age_group_parts = explode(' ', $ageGroup);
        if (count($age_group_parts) < 2) {
            return;
        }
        
        $age_group_number = $age_group_parts[1];
        $date_to_compare = now()->startOfYear()->subYears($age_group_number);
        
        $query->where('birth_date', '!=', '');
        
        if (str_starts_with($ageGroup, 'Under')) {
            $date_minimum = now()->subYears(3)->startOfYear();
            $query->where('birth_date', '>=', $date_to_compare->format('Y-m-d'))
                  ->where('birth_date', '<=', $date_minimum->format('Y-m-d'));
        } else if (str_starts_with($ageGroup, 'Over')) {
            $query->where('birth_date', '<=', $date_to_compare);
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
} 