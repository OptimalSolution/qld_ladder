<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Club;
use Illuminate\Support\Facades\Cache;
use Modules\Tag\Models\Tag;
use App\Services\ClubService;


class ClubFilter extends Component
{
    public $clubs;
    public $regions;
    public $sub_regions;

    /**
     * Create a new component instance.
     */
    public function __construct(protected ClubService $clubService)
    {
        $this->regions = $this->clubService->getClubRegions();
        $this->sub_regions = $this->clubService->getClubSubRegions();
        $this->clubs = $this->clubService->getClubsWithRecentAthletes();
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
