<?php

namespace App\Services;

use App\Models\Athlete;
use Barryvdh\Debugbar\Facades\Debugbar;
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
       
        return Cache::remember($cacheKey, 0, function () use ($gender, $age_group, $club_id) {
            $query = Athlete::with(['club:ratings_central_club_id,name,website', 'eventInfo'])
                            ->recentlyPlayed();
    
            DebugBar::info('Recent Athletes - Unfiltered: ' . $query->count());
            if ($gender !== 'Mixed') {
                // TODO: Change this to a validated gender that exists in the source data
                $query->where('sex', $gender[0]); 
                DebugBar::info('Recent Athletes: Filtered by gender - ' . $gender . ' - ' . $query->count());
            }
    
            if ($age_group) {
                $this->applyAgeGroupFilter($query, $age_group);
                DebugBar::info('Recent Athletes: Filtered by age group - ' . $age_group . ' - ' . $query->count());
            }

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
                Debugbar::info('Recent Athletes: Filtered by region - ' . $region_id);

            } else if ($club_id !== 'all') {
                $query->where('club_id', $club_id);
                Debugbar::info('Recent Athletes: Filtered by club - ' . $club_id);
            }
            
            return $query->orderByDesc('rating')->get();
        });
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
        if (str_contains($ageGroup, ' to ')) {
            $age_group_parts = explode(' to ', $ageGroup);
        } else {
            $age_group_parts = explode(' ', $ageGroup);
        }

        if (count($age_group_parts) < 2) {
            return;
        }
        
        $age_group_number = (int) $age_group_parts[1];
        $query->where('birth_date', '!=', '');
        if (str_starts_with($ageGroup, 'Under') || str_starts_with($ageGroup, 'Up')) {
            // For "Under X", get people born after Jan 1 of the year when they would turn X
            $date_to_compare = now()->startOfYear()->subYears($age_group_number);
            $date_minimum = now()->subYears(3)->startOfYear();
            $query->where('birth_date', '>=', $date_to_compare->format('Y-m-d'))
                  ->where('birth_date', '<=', $date_minimum->format('Y-m-d'));
        } else if (str_starts_with($ageGroup, 'Over')) {
            // For "Over X", get people born on or before Dec 31 of the year when they turn X
            $date_to_compare = now()->endOfYear()->subYears($age_group_number);
            $query->where('birth_date', '<=', $date_to_compare->format('Y-m-d'));
        } else {
            DebugBar::info('Age band filter: ' . $age_group_parts[0] . ' - ' . $age_group_parts[1]);
            $age_group_minimum = (int) $age_group_parts[0];
            $age_group_maximum = (int) $age_group_parts[1];
            $date_to_compare = now()->startOfYear()->subYears($age_group_maximum);
            $date_minimum = now()->subYears($age_group_minimum)->endOfYear();
            $query->where('birth_date', '>=', $date_to_compare->format('Y-m-d'))
                  ->where('birth_date', '<=', $date_minimum->format('Y-m-d'));

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
        return Cache::remember('unique-gender-groups', 7200, function () {
            $genderGroups = Athlete::recentlyPlayed()
                                ->pluck('sex')
                                ->unique()
                                ->map(function($sex) {
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

    /**
     * Calculate the age range for a given age based on defined age groups
     *
     * @param int $age The athlete's age
     * @return string The age range description
     */
    public function calculateAgeRange(int $age): string {
        // Handle specific age ranges based on examples
        if ($age < 7) {
            return "Under 7";
        } elseif ($age >= 7 && $age < 9) {
            return "7-8";
        } elseif ($age >= 9 && $age < 11) {
            return "9-10";
        } elseif ($age >= 11 && $age < 13) {
            return "11-12";
        } elseif ($age >= 13 && $age < 15) {
            return "13-14";
        } elseif ($age >= 15 && $age < 17) {
            return "15-16";
        } elseif ($age >= 17 && $age < 19) {
            return "17-18";
        } elseif ($age >= 19 && $age < 21) {
            return "19-20";
        } elseif ($age >= 21 && $age < 30) {
            return "21-29";
        } elseif ($age >= 30 && $age < 40) {
            return "30-39";
        } elseif ($age >= 40 && $age < 50) {
            return "40-49";
        } elseif ($age >= 50 && $age < 60) {
            return "50-59";
        } elseif ($age >= 60 && $age < 65) {
            return "60-64";
        } elseif ($age >= 65 && $age < 70) {
            return "65-69";
        } elseif ($age >= 70 && $age < 75) {
            return "70-74";
        } elseif ($age >= 75 && $age < 80) {
            return "75-79";
        } elseif ($age >= 80 && $age < 85) {
            return "80-84";
        } else {
            return "85+";
        }
    }

    public function getAgeGroupsMap() {

        $base_age_groups = [
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
            'Open' => 'Open',
            'open' => 'Open',
        ];

        $junior_age_bands = $this->getJuniorAgeBandsMap();
        $senior_age_bands = $this->getSeniorAgeBandsMap();

        return array_merge($base_age_groups, $junior_age_bands, $senior_age_bands);
    }
    public function getJuniorAgeGroupsMap() {
        return [
            'U7' => 'Under 7',
            'U9' => 'Under 9',
            'U11' => 'Under 11',
            'U13' => 'Under 13',
            'U15' => 'Under 15',
            'U17' => 'Under 17',
            'U19' => 'Under 19',
        ];
    }

    public function getSeniorAgeGroupsMap() {
        return [
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
        ];
    }

    public function getJuniorAgeBandsMap() {
        return [
            'U7' => 'Under 7',
            'U9' => 'Under 9',
            'U11' => 'Under 11',
            '12-13' => '12 to 13',
            '14-15' => '14 to 15',
            '16-17' => '16 to 17',
            '18-19' => '18 to 19',
        ];
    }

    public function getSeniorAgeBandsMap() {
        return [
            '20-21' => '20 to 21',
            '22-29' => '22 to 29',
            '30-39' => '30 to 39',
            '40-49' => '40 to 49',
            '50-59' => '50 to 59',
            '60-64' => '60 to 64',
            '65-69' => '65 to 69',
            '70-74' => '70 to 74',
            '75-79' => '75 to 79',
            '80-84' => '80 to 84',
            '85+' => 'Over 85',
        ];
    }
} 