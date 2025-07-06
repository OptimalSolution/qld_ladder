<?php

namespace App\Services;

use App\Models\Athlete;
use App\Models\Club;
use Debugbar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Modules\Tag\Models\Tag;

class ClubService
{
    /**
     * Get athletes who have recently played, with optional filtering
     *
     * @param string|null $gender Gender filter (M, F, or null for all)
     * @param string|null $ageGroup Age group filter (U7, O30, etc.)
     * @param string|null $clubId Club ID filter
     * @return Collection
     */
    public function getClubs(): Collection
    {
        // Create a cache key based on the filter parameters
        $cacheKey = 'clubs-all';
        
        return Cache::remember($cacheKey, 3600, function () {
            return Club::all();
        });
    }

    public function getClubsWithRecentAthletes(): Collection
    {
        return Cache::remember('clubs-with-recent-athletes', 3600, function () {
            return Club::whereHas('athletes', function ($query) {
                $query->recentlyPlayed();
            })
            ->orderBy('name')
            ->get();
        });
    }

    public function getClubRegions(): Collection
    {
        return Cache::remember('club-regions', 3600, function () {
            return Tag::with('clubs')->select('id', 'name')->where('group_name', 'Regions')->where('name', '!=', 'All Regions')->orderBy('name')->get();
        });
    }

    public function getClubSubRegions(): Collection
    {
        return Cache::remember('club-sub-regions', 3600, function () {
            return Tag::with('clubs')->select('id', 'name')->where('group_name', 'Sub Regions')->orderBy('name')->get();
        });
    }
} 