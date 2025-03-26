<?php

namespace App\View\Components;

use App\Models\Athlete;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GenderDistribution extends Component
{
    public $genderBreakdown;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        
        $this->genderBreakdown = Athlete::selectRaw('sex, count(*) as count')
            ->groupBy('sex')
            ->pluck('count', 'sex')
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
