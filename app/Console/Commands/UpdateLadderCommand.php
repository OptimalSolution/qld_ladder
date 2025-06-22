<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;

class UpdateLadderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:update-ladder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the ladder with the latest ratings, club and event info from Ratings Central';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info('Updating the ladder');

        Bus::chain([
            fn() => Artisan::call('download:ratings-central-zip'),
            fn() => Artisan::call('import:rc-info'),
            fn() => Artisan::call('import:clubs'),
            fn() => Artisan::call('import:players'),
            fn() => Artisan::call('import:regions'),
            fn() => Artisan::call('cache:clear'),
            fn() => Setting::add('rc_zip_last_processed', now(), 'datetime'),
            fn() => Artisan::call('cron:update-player-event-info')
        ])->dispatch();
    }
}
