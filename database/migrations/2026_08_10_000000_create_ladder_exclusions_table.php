<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Manual ladder exclusion list — RatingsCentral IDs an admin has chosen to keep off
     * the ladder. Replaces the hardcoded RatingsService::ineligibleRatingsCentralIDList().
     */
    public function up(): void
    {
        Schema::create('ladder_exclusions', function (Blueprint $table) {
            $table->id();
            $table->string('ratings_central_id')->unique();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        // Seed with the previously hardcoded IDs so nothing changes on deploy.
        $now = now();
        $seed = ['148599', '150170', '149127', '161713', '161712', '148874'];
        DB::table('ladder_exclusions')->insertOrIgnore(
            array_map(fn ($id) => [
                'ratings_central_id' => $id,
                'note' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ], $seed)
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('ladder_exclusions');
    }
};
