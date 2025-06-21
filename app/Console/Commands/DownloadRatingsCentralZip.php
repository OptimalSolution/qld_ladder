<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DownloadRatingsCentralZip extends Command
{
    protected $signature = 'download:ratings-central-zip';
    protected $description = 'Downloads the latest zip file from Ratings Central';

    public function handle()
    {
        $this->info('Downloading Ratings Central zip files');

        $this->info('Logging in to Ratings Central');


        $this->info('Retrieving zip files');

        $this->info('Done');
    }
}
