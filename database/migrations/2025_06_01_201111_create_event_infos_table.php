<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_infos', function (Blueprint $table) {
            $table->id();
            $table->string('athlete_id');
            $table->integer('number_of_events');
            $table->integer('number_of_recent_events');
            $table->string('last_event_id');
            $table->string('last_event_date');
            $table->string('last_event_name');
            $table->integer('point_change');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_infos');
    }
};
