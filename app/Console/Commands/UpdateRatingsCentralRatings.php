<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Services\RatingsService;
use App\Models\Setting;


class UpdateRatingsCentralRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rc-ratings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing athlete ratings from RatingsCentral';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the latest info from RC and save it in app storage
        $url = 'https://www.ratingscentral.com/PlayerList.php?PlayerSport=Any&MinRating=&MaxRating=&MaxCurrentStDev=&MaxLastPlayedStDev=&MinLastPlayed=&MaxLastPlayed=&LastPlayedWindow=&EventID=&MinAge=&MaxAge=&MinBirth=&MaxBirth=&PlayerGender=&PlayerITTF_ID=&TourCircuitDivision=&TourCircuitYear=2025&USA_State=&CanadaProvince=&PlayerProvince=QLD&PlayerPostalCode=&PlayerCountry=AUS&CountryGroup=&ClubID=&Club_USA_State=&ClubCanadaProvince=&ClubProvince=&ClubCountry=&ClubCountryGroup=&AssociationFederation=&SortOrder=Name&AsOfDate=&CurrentRankingCutoff=100&LastPlayedRankingCutoff=&Heading=&StateProvinceDisplay=&ClubDisplay=&CSV_Output=Text&DisplayInstructions=Yes';
        $filename = 'ratings_central_ratings_data.csv';
        $path = storage_path('app/public/csv_data/' . $filename);
        
        // Ensure directory exists before saving the file
        $directory = dirname($path);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        // Get the data from the API, and only save it if it has changed
        $newContent = Http::get($url)->body();
        Setting::add('ratings_last_checked', now(), 'datetime');
        $contentChanged = true;
        if (File::exists($path)) {
            $newHash = md5($newContent);
            $existingHash = md5_file($path);
            
            if ($newHash === $existingHash) {
                $this->info('No changes in RatingsCentral ratings data. Skipping file update.');
                $contentChanged = false;
            }
        }
        
        // Only save and update if content changed or file doesn't exist
        if ($contentChanged) {
            File::put($path, $newContent);
            $this->info('RatingsCentral ratings updated and saved to ' . $path);
            $ratingsService = new RatingsService();
            $result = $ratingsService->updateRatingsCentralRatingsFromStoredFile($path, true);
            $this->info('Rating update result: ' . $result);
            Setting::add('ratings_last_updated', now(), 'datetime');
        }        
    }
}
