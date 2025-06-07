<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventInfo extends Model
{
    protected $fillable = [
        'athlete_id',
        'last_event_id',
        'last_event_date',
        'last_event_name',
        'point_change',
        'number_of_events',
        'number_of_recent_events',
    ];

    public function getRelativePointChangeAttribute()
    {
        if(intval($this->point_change) > 0) {
            return '<span class="text-green-600">▲</span> ' . $this->point_change;
        } elseif(intval($this->point_change) < 0) {
            return '<span class="text-red-500">▼</span> ' . abs($this->point_change);
        } else {
            return 'No change';
        }
    }
}
