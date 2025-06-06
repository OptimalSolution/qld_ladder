<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Athlete;
use Illuminate\Support\Facades\Http;

class UpdatePlayerEventInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:update-player-event-info {--batch-size=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update event info for individual players from RatingsCentral';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batch_size = $this->option('batch-size');
        $this->info('Updating event info for individual players from RatingsCentral');

        $athletes = Athlete::recentlyPlayed()->where(function($query) {
            $query->whereDoesntHave('eventInfo')
                  ->orWhereHas('eventInfo', function($subQuery) {
                      $subQuery->where('number_of_recent_events', '<', 2);
                  });
        })
        ->leftJoin('event_infos', 'athletes.ratings_central_id', '=', 'event_infos.athlete_id')
        ->orderBy('event_infos.updated_at', 'asc')
        ->select('athletes.*');

        $waiting_athletes = $athletes->count();
        $this->info('Athletes waiting to be processed: ' . $waiting_athletes);

        $athletes = $athletes->take($batch_size)->get();

        $tally = 0;
        foreach ($athletes as $athlete) {

            sleep(rand(3, 10));
            $tally++;
            $this->info("========  {$athlete->name} ({$tally}/{$batch_size}) ======== ");

            try {
                $response = Http::get('https://www.ratingscentral.com/PlayerHistory.php?PlayerID=' . $athlete->ratings_central_id . '&CSV_Output=Text');
            } catch (\Exception $e) {
                $this->error("Error fetching data for {$athlete->name}: {$e->getMessage()}. Pausing for a bit");
                sleep(rand(10, 30));
                continue;
            }
            
            // Convert CSV to array where keys are from the header line
            $events = $this->csvToArray($response->body());
            $number_of_events = count($events);
            
            $this->info("Number of events: {$number_of_events}");
            if (!empty($events)) {
                
                $latestEvent = $events[0];
                $number_of_recent_events = count($this->discardOldEvents($events));
                $this->info("Number of recent events: {$number_of_recent_events}");
                $this->info("Latest event: {$latestEvent['EventName']}");

                $athlete->eventInfo()->create([
                    'athlete_id' => $athlete->ratings_central_id,
                    'number_of_events' => $number_of_events,
                    'number_of_recent_events' => $number_of_recent_events,
                    'last_event_id' => $latestEvent['EventID'],
                    'last_event_date' => $latestEvent['EventDate'],
                    'last_event_name' => $latestEvent['EventName'],
                    'point_change' => $latestEvent['PointChange'],
                ]);
            }
        }
    }

    /**
     * Convert CSV string to an array of records with keys from the header line
     * and sort by EventDate in descending order
     *
     * @param string $csvString
     * @return array
     */
    private function csvToArray(string $csvString): array
    {
        $lines = explode("\n", trim($csvString));
        if (empty($lines)) {
            return [];
        }
        
        // Get headers from the first line
        $headers = str_getcsv($lines[0]);
        
        $records = [];
        for ($i = 1; $i < count($lines); $i++) {
            if (empty(trim($lines[$i]))) continue;
            
            $values = str_getcsv($lines[$i]);
            if (count($values) === count($headers)) {
                $records[] = array_combine($headers, $values);
            }
        }
        
        return $records;
    }

    private function discardOldEvents(array $events): array
    {
        // Sort records by Date in descending order
        usort($events, function ($a, $b) {
            $dateField = 'EventDate';
            $dateA = strtotime($a[$dateField]);
            $dateB = strtotime($b[$dateField]);
            return $dateB <=> $dateA;
        });

        // Filter out events older than one year from start of current year
        $cutoffDate = now()->startOfYear()->subYear()->timestamp;
        $events = array_filter($events, function($event) use ($cutoffDate) {
            $eventDate = strtotime($event['EventDate']);
            return $eventDate >= $cutoffDate;
        });

        $events = array_values($events);
        return $events;
    }
}
