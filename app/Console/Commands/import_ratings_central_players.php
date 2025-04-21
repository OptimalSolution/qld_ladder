<?php

namespace App\Console\Commands;

use App\Models\Athlete;
use Illuminate\Console\Command;
use App\Services\RatingsService;
use App\Models\Setting;
class import_ratings_central_players extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:players';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the player info from the event coordinators player file';

    /**
     * Execute the console command.
     */
    public function handle()
    {   
        $ratingsService = new RatingsService(); 
        $file = storage_path('app/public/RC_Lists/RatingList.csv');
        if (!file_exists($file)) {
            $this->error("Player file not found: $file");
            return 1;
        }

        $results = $ratingsService->updateRatingsCentralRatingsFromStoredFile($file);
        $this->info($results);
        \Log::info("[Player Import] " . $results);
        Setting::add('full_ratings_last_updated', now(), 'datetime');
    }
}
