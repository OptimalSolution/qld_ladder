<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Club;

class import_ratings_central_clubs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:clubs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the club info from the ratings central CSV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Importing club info...');

        $file = storage_path('app/public/RC_Lists/ClubList.csv');
        if (!file_exists($file)) {
            $this->error("Club file not found: $file");
            return 1;
        }

        $club_count = 0;
        $clubs_created = 0;
        $clubs_updated = 0;

        $handle = fopen($file, 'r');
        $header = null;
        while (!feof($handle)) {
            $row = fgetcsv($handle, 1000, ",");
            if ($row) {

                if ($header === null) {
                    $header = $row;
                    // Make sure header is only alphanumeric
                    $header = preg_replace('/[^a-zA-Z0-9]/', '', $header);
                    continue;
                } else {
                    
                    $player_data = array_combine($header, $row);
                    if (! $this->eligible_club($player_data)) {
                        continue;
                    }

                    $club_count++;

                    $club = Club::where('ratings_central_club_id', $player_data['ID'])->first();
                    if ($club) {
                        // $this->info('Updating club: ' . $player_data['Name']);
                        $club->update([
                            'name' => $player_data['Name'],
                            'nickname' => $player_data['Nickname'],
                            'city' => $player_data['City'],
                            'state' => $player_data['State'],
                            'province' => $player_data['Province'],
                            'postal_code' => $player_data['PostalCode'],
                            'website' => $player_data['Website'],
                            'status' => $player_data['Status'],
                        ]);
                        $clubs_updated++;
                    } else {
                        // $this->info('Importing club: ' . $player_data['Name']);
                        $club = Club::create([
                            'name' => $player_data['Name'],
                            'ratings_central_club_id' => $player_data['ID'],
                            'nickname' => $player_data['Nickname'],
                            'city' => $player_data['City'],
                            'state' => $player_data['State'],
                            'province' => $player_data['Province'],
                            'postal_code' => $player_data['PostalCode'],
                            'website' => $player_data['Website'],
                            'status' => $player_data['Status'],
                        ]);
                        $clubs_created++;
                    }
                }
            }
        }
        fclose($handle);
        $this->info('Clubs Created: ' . $clubs_created . ', Updated: ' . $clubs_updated . ' | Total: ' . Club::count());
        \Log::info("[Club Import] Clubs Created: $clubs_created, Updated: $clubs_updated | Total: " . Club::count());
    }

    private function eligible_club($player_data)
    {
        return $player_data['Province'] === 'Queensland' && 
                $player_data['Country'] === 'AUS';
                // $player_data['Status'] === 'Active';
    }
}
