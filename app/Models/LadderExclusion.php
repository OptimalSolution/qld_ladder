<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LadderExclusion extends Model
{
    public const CACHE_KEY = 'ineligible-rc-ids';

    protected $fillable = [
        'ratings_central_id',
        'note',
    ];

    protected static function booted(): void
    {
        // Keep the cached lookup used by Athlete::scopeRecentlyPlayed() in sync.
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }
}
