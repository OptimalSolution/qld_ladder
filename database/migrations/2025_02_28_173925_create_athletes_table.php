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
        Schema::create('athletes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('ratings_central_id')->unique();
            $table->integer('rating');
            $table->integer('club_id');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('province');
            $table->string('postal_code')->nullable();
            $table->string('country');
            $table->date('birth_date');
            $table->string('sex');
            $table->date('last_played')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('athletes');
    }
};
