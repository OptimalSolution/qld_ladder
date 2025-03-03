<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Club;
use Illuminate\Support\Facades\Cache;

class ClubFilter extends Component
{
    public $clubs;
    public $something;
    /**
     * Create a new component instance.
     */
    public function __construct($something = null)
    {
        // All the clubs that have recently played athletes
        $this->clubs = Cache::remember('clubs-with-recent-athletes', 7200, function () {
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
        return view('components.club-filter')->with('clubs', $this->clubs)->with('something', $this->something);
    }
}
