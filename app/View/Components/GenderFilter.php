<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Athlete;
class GenderFilter extends Component
{
    public $genderGroup;
    public $ageGroup;
    public $clubId;
    public $clubSlug;
    public $routeName;
    public $genders;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $genderGroup, ?string $ageGroup, ?string $clubId, ?string $clubSlug, ?string $routeName = 'ladder-filter')
    {
        $this->genderGroup = $genderGroup;
        $this->ageGroup = $ageGroup;
        $this->clubId = $clubId;
        $this->clubSlug = $clubSlug;
        $this->routeName = $routeName;

        // Get unique gender values and add 'Mixed' option
        $genders = Athlete::select('sex')->distinct()->pluck('sex')->toArray();
        $genders[] = 'Mixed';
        
        // Map abbreviations to full names in a single pass
        $this->genders = collect($genders)->map(function($gender) {
            return match($gender) {
                'M' => 'Male',
                'F' => 'Female',
                default => $gender
            };
        })->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.gender-filter', [
            'genders' => $this->genders,
            'genderGroup' => $this->genderGroup,
            'ageGroup' => $this->ageGroup,
            'clubId' => $this->clubId,
            'clubSlug' => $this->clubSlug,
            'routeName' => $this->routeName
        ]);
    }
}
