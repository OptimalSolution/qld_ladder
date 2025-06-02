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
}
