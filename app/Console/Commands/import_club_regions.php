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

        $region_count = 0;
        $regions_created = 0;
        $regions_updated = 0;
        $sub_regions_created = 0;
        $sub_regions_updated = 0;

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
                    $region_count++;
                    
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
                            if($regionTag->wasRecentlyCreated) {
                                $regions_created++;
                            } else {
                                $regions_updated++;
                            }
                        }

                        // Find or create the sub-region tag
                        if (!empty($club_data['Sub Region'])) {
                            $subRegionTag = \Modules\Tag\Models\Tag::firstOrCreate([
                                'name' => $club_data['Sub Region'],
                                'group_name' => 'Sub Regions'
                            ]);
                            
                            // Attach the sub-region tag to the club
                            $club->tags()->syncWithoutDetaching([$subRegionTag->id]);
                            if($subRegionTag->wasRecentlyCreated) {
                                $sub_regions_created++;
                            } else {
                                $sub_regions_updated++;
                            }
                        }
                    } else {
                        $this->error('Club not found: ' . $club_data['Club Name'] . ' (ID: ' . $club_data['RC Club ID'] . ')');
                    }
                }
            }
        }
        
        fclose($handle);
        $this->info("Regions Created: $regions_created, Updated: $regions_updated | Sub Regions Created: $sub_regions_created, Updated: $sub_regions_updated");
        \Log::info("[Club Region Import] Regions Created: $regions_created, Updated: $regions_updated | Sub Regions Created: $sub_regions_created, Updated: $sub_regions_updated");
    }
}
