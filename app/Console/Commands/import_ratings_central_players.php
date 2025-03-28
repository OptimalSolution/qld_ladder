<?php

namespace App\Console\Commands;

use App\Models\Athlete;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
        $this->info('Importing players info...');

        $file = storage_path('app/public/RC_Lists/RatingList.csv');
        $csv = [];
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
                    if (! $this->eligible_athlete($player_data)) {
                        continue;
                    }

                    // Update critical info if athlete exists
                    $player = Athlete::updateOrCreate(
                        ['ratings_central_id' => $player_data['ID']],
                        [
                            'name' => $player_data['Name'],
                            'rating' => $player_data['Rating'],
                            'stdev' => $player_data['StDev'],
                            'club_id' => $player_data['Club'],
                            'city' => $player_data['City'],
                            'state' => $player_data['State'],
                            'province' => $player_data['Province'],
                            'postal_code' => $player_data['PostalCode'],
                            'country' => $player_data['Country'],
                            'birth_date' => $player_data['Birth'],
                            'sex' => empty($player_data['Sex']) ? 'Other' : $player_data['Sex'],
                            'last_played' => $player_data['LastPlayed']
                        ]
                    );

                    $action = $player->wasRecentlyCreated ? 'Importing' : 'Updating';
                    $this->info("{$action} player: " . $player_data['Name'] . ($action === 'Importing' ? ' from ' . $player_data['City'] : ''));
                }
            }
        }
        fclose($handle);
        $this->info('Players info imported successfully. Athlete total: ' . Athlete::count());
    }

    private function eligible_athlete($player_data)
    {
        return $player_data['Province'] === 'QLD' && 
                $player_data['Country'] === 'AUS' && 
                $player_data['Deceased'] === '';
    }


}
