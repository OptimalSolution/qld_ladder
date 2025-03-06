<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Tag\Models\Tag;

class Club extends Model
{
    protected $fillable = [
        'name',
        'ratings_central_club_id',
        'nickname',
        'city',
        'state',
        'province',
        'postal_code',
        'website',
        'status'
    ];

    public function athletes()
    {
        return $this->hasMany(Athlete::class, 'club_id', 'ratings_central_club_id');
    }

    /**
     * Get the tags associated with the club.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
