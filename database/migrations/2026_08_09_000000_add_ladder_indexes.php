<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add indexes for the hot ladder query path. The ladder filters/orders on these
     * columns against the full RatingsCentral import; without indexes SQLite full-scans
     * the ~100 MB athletes/event_infos tables on every cold (post-cron) request.
     */
    public function up(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->index('rating', 'athletes_rating_index');
            $table->index('club_id', 'athletes_club_id_index');
            $table->index('birth_date', 'athletes_birth_date_index');
            // Common path: filter by gender then order by rating.
            $table->index(['sex', 'rating'], 'athletes_sex_rating_index');
        });

        if (Schema::hasColumn('event_infos', 'athlete_id')) {
            Schema::table('event_infos', function (Blueprint $table) {
                $table->index('athlete_id', 'event_infos_athlete_id_index');
            });
        }

        if (Schema::hasColumn('clubs', 'ratings_central_club_id')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->index('ratings_central_club_id', 'clubs_rc_club_id_index');
            });
        }
    }

    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropIndex('athletes_rating_index');
            $table->dropIndex('athletes_club_id_index');
            $table->dropIndex('athletes_birth_date_index');
            $table->dropIndex('athletes_sex_rating_index');
        });

        if (Schema::hasColumn('event_infos', 'athlete_id')) {
            Schema::table('event_infos', function (Blueprint $table) {
                $table->dropIndex('event_infos_athlete_id_index');
            });
        }

        if (Schema::hasColumn('clubs', 'ratings_central_club_id')) {
            Schema::table('clubs', function (Blueprint $table) {
                $table->dropIndex('clubs_rc_club_id_index');
            });
        }
    }
};
