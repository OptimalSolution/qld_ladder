<?php

namespace App\Console\Commands;

use App\Models\Club;
use Illuminate\Console\Command;

class import_club_regions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:regions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the club regions from the CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Importing club regions...');

        $file = storage_path('app/public/ClubRegions.csv');
        if (!file_exists($file)) {
            $this->error("Region file not found: $file");
            return 1;
        }

        $csv = [];
        $handle = fopen($file, 'r');
        $header = null;
        
        while (!feof($handle)) {
            $row = fgetcsv($handle, 1000, ",");
            if ($row) {
                if ($header === null) {
                    $header = $row;
                    continue;
                } else {
                    $club_data = array_combine($header, $row);
                    
                    // Find the club by Ratings Central ID
                    $club = Club::where('ratings_central_club_id', $club_data['RC Club ID'])->first();
                    // Delete existing tags for this club
                    // if ($club) {
                    //     $club->tags()->detach();
                    //     $this->info('Deleted existing tags for club: ' . $club_data['Club Name']);
                    // }
                    
                    if ($club) {
                        
                        // Here you would update the club with region information
                        // Find or create the region tag
                        if (!empty($club_data['Region'])) {
                            $regionTag = \Modules\Tag\Models\Tag::firstOrCreate([
                                'name' => $club_data['Region'],
                                'group_name' => 'Regions'
                            ]);
                            
                            // Attach the region tag to the club
                            $club->tags()->syncWithoutDetaching([$regionTag->id]);
                            $this->info('Updating club region: ' . $club_data['Club Name'] . ' to ' . $club_data['Region']);
                        }

                        // Find or create the sub-region tag
                        if (!empty($club_data['Sub Region'])) {
                            $subRegionTag = \Modules\Tag\Models\Tag::firstOrCreate([
                                'name' => $club_data['Sub Region'],
                                'group_name' => 'Sub Regions'
                            ]);
                            
                            // Attach the sub-region tag to the club
                            $club->tags()->syncWithoutDetaching([$subRegionTag->id]);
                            $this->info('Updating club sub-region: ' . $club_data['Club Name'] . ' to ' . $club_data['Sub Region']);
                        }
                    } else {
                        $this->warn('Club not found: ' . $club_data['Club Name'] . ' (ID: ' . $club_data['RC Club ID'] . ')');
                    }
                }
            }
        }
        
        fclose($handle);
        $this->info('Club regions imported successfully.');
       
    }
}
