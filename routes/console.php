<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

// Download the latest zip files from Ratings Central at random times early in the day
// Artisan::command('download:zips', function () {
//     Log::info('Downloading latest zip files from Ratings Central');
//     (new \App\Console\Commands\DownloadRatingsCentralZips)->handle();
// })->purpose('Download the latest zip files from Ratings Central')
//   ->cron(sprintf('0 %d %d */2 * *', rand(0, 59), rand(3, 5)));