<?php

namespace App\Support;

use App\Models\Athlete;
use App\Models\Club;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DashboardSegments
{
    public const LADDER_CLUBS = 'ladder_clubs';

    public const LADDER_ATHLETES = 'ladder_athletes';

    public const LADDER_JUNIORS = 'ladder_juniors';

    public const LADDER_SENIORS = 'ladder_seniors';

    public const REGISTERED_LADDER = 'registered_ladder';

    public const ONE_EVENT = 'one_event';

    public const INACCURATE_BIRTHDATE = 'inaccurate_birthdate';

    public const UNCHECKED = 'unchecked';

    /**
     * Upper bound for junior birth dates (inclusive): athletes born this year or later in the junior window.
     */
    public static function ageMinimum(): Carbon
    {
        return now()->subYears(3)->startOfYear();
    }

    /**
     * Birth dates strictly after this (junior side) are juniors; on or before are seniors for ladder splits.
     */
    public static function juniorCutoffDate(): Carbon
    {
        return now()->startOfYear()->subYears(19);
    }

    public static function keys(): array
    {
        return [
            self::LADDER_CLUBS,
            self::LADDER_ATHLETES,
            self::LADDER_JUNIORS,
            self::LADDER_SENIORS,
            self::REGISTERED_LADDER,
            self::ONE_EVENT,
            self::INACCURATE_BIRTHDATE,
            self::UNCHECKED,
        ];
    }

    public static function titles(): array
    {
        return [
            self::LADDER_CLUBS => 'Clubs with ladder athletes',
            self::LADDER_ATHLETES => 'Ladder athletes',
            self::LADDER_JUNIORS => 'Ladder juniors',
            self::LADDER_SENIORS => 'Ladder seniors',
            self::REGISTERED_LADDER => 'TTA registered ladder athletes',
            self::ONE_EVENT => 'Athletes with just 1 event',
            self::INACCURATE_BIRTHDATE => 'Inaccurate birth dates',
            self::UNCHECKED => 'Unchecked athletes',
        ];
    }

    public static function isClubSegment(string $segment): bool
    {
        return $segment === self::LADDER_CLUBS;
    }

    /**
     * All juniors (global), same filters as dashboard denominator for junior %.
     */
    public static function globalJuniorAthletesQuery(): Builder
    {
        $ageMin = self::ageMinimum()->format('Y-m-d');
        $juniorCutoff = self::juniorCutoffDate()->format('Y-m-d');

        return Athlete::whereNotNull('birth_date')->where('birth_date', '!=', '')
            ->where('birth_date', '>=', $juniorCutoff)
            ->where('birth_date', '<=', $ageMin);
    }

    /**
     * All seniors (global), same filters as dashboard denominator for senior %.
     */
    public static function globalSeniorAthletesQuery(): Builder
    {
        $juniorCutoff = self::juniorCutoffDate()->format('Y-m-d');

        return Athlete::whereNotNull('birth_date')->where('birth_date', '!=', '')
            ->where('birth_date', '<=', $juniorCutoff);
    }

    public static function ladderClubsQuery(): Builder
    {
        return Club::whereHas('athletes', function ($query) {
            $query->recentlyPlayed();
        });
    }

    public static function ladderAthletesQuery(): Builder
    {
        return Athlete::query()->recentlyPlayed();
    }

    public static function ladderJuniorsQuery(): Builder
    {
        $ageMin = self::ageMinimum()->format('Y-m-d');
        $juniorCutoff = self::juniorCutoffDate()->format('Y-m-d');

        return Athlete::whereNotNull('birth_date')->where('birth_date', '!=', '')
            ->where('birth_date', '>=', $juniorCutoff)
            ->where('birth_date', '<=', $ageMin)
            ->recentlyPlayed();
    }

    public static function ladderSeniorsQuery(): Builder
    {
        $juniorCutoff = self::juniorCutoffDate()->format('Y-m-d');

        return Athlete::whereNotNull('birth_date')->where('birth_date', '!=', '')
            ->where('birth_date', '<=', $juniorCutoff)
            ->recentlyPlayed();
    }

    public static function registeredLadderAthletesQuery(): Builder
    {
        return Athlete::registeredWithTTA()->recentlyPlayed();
    }

    public static function athletesWithJustOneEventQuery(): Builder
    {
        return Athlete::whereHas('eventInfo', function ($query) {
            $query->where('number_of_events', '=', 1);
        });
    }

    /**
     * Empty birth_date or birth_date after age minimum, scoped to ladder athletes.
     */
    public static function inaccurateBirthdateQuery(): Builder
    {
        $ageMinStr = self::ageMinimum()->format('Y-m-d');

        return Athlete::query()
            ->where(function ($q) use ($ageMinStr) {
                $q->where('birth_date', '')
                    ->orWhere('birth_date', '>', $ageMinStr);
            })
            ->recentlyPlayed();
    }

    public static function uncheckedAthletesQuery(): Builder
    {
        return Athlete::recentlyPlayed()->whereDoesntHave('eventInfo');
    }

    /**
     * @return array{title: string, builder: Builder, entity: 'club'|'athlete'}|null
     */
    public static function resolve(string $segment): ?array
    {
        if (! in_array($segment, self::keys(), true)) {
            return null;
        }

        $title = self::titles()[$segment];

        $builder = match ($segment) {
            self::LADDER_CLUBS => self::ladderClubsQuery(),
            self::LADDER_ATHLETES => self::ladderAthletesQuery(),
            self::LADDER_JUNIORS => self::ladderJuniorsQuery(),
            self::LADDER_SENIORS => self::ladderSeniorsQuery(),
            self::REGISTERED_LADDER => self::registeredLadderAthletesQuery(),
            self::ONE_EVENT => self::athletesWithJustOneEventQuery(),
            self::INACCURATE_BIRTHDATE => self::inaccurateBirthdateQuery(),
            self::UNCHECKED => self::uncheckedAthletesQuery(),
        };

        $entity = self::isClubSegment($segment) ? 'club' : 'athlete';

        return [
            'title' => $title,
            'builder' => $builder,
            'entity' => $entity,
        ];
    }
}
