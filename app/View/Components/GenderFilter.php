<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Athlete;
class GenderFilter extends Component
{
    public $selected_gender;
    public $age_group;
    public $routeName;
    public $genders;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($selectedGender, $ageGroup, $routeName = 'age-groups-subgroup')
    {
        $this->selected_gender = $selectedGender;
        $this->age_group = $ageGroup;
        $this->routeName = $routeName;
        
        // Use provided genders or fetch from database if not provided
        if ($genders) {
            $this->genders = $genders;
        } else {
            // Fetch the actual marked genders from all athletes
            $this->genders = array_merge(Athlete::select('sex')->distinct()->pluck('sex')->toArray(), ['Mixed']);
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.gender-filter', [
            'genders' => $this->genders
        ]);
    }
}
