<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Club;
use Illuminate\Support\Facades\Cache;
use Modules\Tag\Models\Tag;


class ClubFilter extends Component
{
    public $clubs;
    public $regions;
    public $sub_regions;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->regions = Tag::with('clubs')->select('id', 'name')->where('group_name', 'Regions')->where('name', '!=', 'All Regions')->orderBy('name')->get();
        $this->sub_regions = Tag::with('clubs')->select('id', 'name')->where('group_name', 'Sub Regions')->orderBy('name')->get();
        
        // All the clubs that have recently played athletes
        $this->clubs = Cache::remember('all-clubs-with-recent-athletes',0, function () {
            return Club::whereHas('athletes', function ($query) {
                $query->recentlyPlayed();
            })
            ->orderBy('name')
            ->get();
        });
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $request = request();
        $age_group = $request->input('age_group');
        $gender_group = $request->input('gender_group');
        $club_id = $request->input('club_id');
        $club_slug = $request->input('club_slug');
        return view('components.club-filter')
                ->with('club_groups', $this->clubs)
                ->with('regions', $this->regions)
                ->with('sub_regions', $this->sub_regions)
                ->with('age_group', $age_group)
                ->with('gender_group', $gender_group)
                ->with('club_id', $club_id)
                ->with('club_slug', $club_slug);
    }
}
