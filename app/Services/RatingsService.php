<?php

namespace App\Services;

use App\Models\Athlete;

class RatingsService
{
    public function updateRatingsCentralRatingsFromStoredFile($file, $update_existing_only = false)
    {
        if (!file_exists($file)) {
            return "Error: File not found: $file";
        }
        
        $handle = fopen($file, 'r');
        if (!$handle) {
            return "Error: Unable to open file: $file";
        }
        
        $header = null;
        $created_count = 0;
        $updated_count = 0;
        
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
                    if (! $this->eligibleRatingsCentralAthlete($player_data)) {
                        continue;
                    }

                    // NOTE: PrimaryClub is for ratings updates while Club is from Director zips
                    $club_id = 1380; // Default club ID (TTA)
                    if (!empty($player_data['PrimaryClub'])) {
                        $club_id = $player_data['PrimaryClub'];
                    } elseif (!empty($player_data['Club'])) {
                        $club_id = $player_data['Club'];
                    }
                    
                    $update_data = [
                        'name' => $player_data['Name'],
                        'rating' => $player_data['Rating'],
                        'stdev' => $player_data['StDev'],
                        'club_id' => $club_id,
                        'city' => $player_data['City'],
                        'state' => $player_data['State'],
                        'province' => $player_data['Province'],
                        'postal_code' => $player_data['PostalCode'],
                        'country' => $player_data['Country'],
                        'sex' => empty($player_data['Sex']) ? 'Other' : $player_data['Sex'],
                        'last_played' => $player_data['LastPlayed'],
                        'tta_id' => $player_data['TTA'],
                    ];

                    if ($update_existing_only) {
                        $player = Athlete::where('ratings_central_id', $player_data['ID'])->first();
                        if ($player) {
                            $player->update($update_data);
                            $updated_count++;
                        }   
                    } else {
                        // Add birth_date to update data when creating or updating
                        $update_data['birth_date'] = !empty($player_data['Birth']) ? $player_data['Birth'] : now();
                        // Update critical info if athlete exists
                        $player = Athlete::updateOrCreate(
                            ['ratings_central_id' => $player_data['ID']],
                            $update_data
                        );

                        if ($player->wasRecentlyCreated) {
                            $created_count++;
                        } else {
                            $updated_count++;
                        }
                    }
                }
            }
        }
        fclose($handle);
        return "Players Created: $created_count, Updated: $updated_count, Total: " . Athlete::count();
    }

    private function eligibleRatingsCentralAthlete($player_data)
    {
        return $player_data['Province'] === 'QLD' && 
                $player_data['Country'] === 'AUS' && 
                $player_data['Deceased'] === '' &&
                !in_array($player_data['ID'], $this->ineligibleRatingsCentralIDList());
    }

    public function ineligibleRatingsCentralIDList()
    {
        return [
            '107402',
            '148599',
            '150170',
            '149127',
            '161713',
            '161712',
            '148874',
        ];
    }
}