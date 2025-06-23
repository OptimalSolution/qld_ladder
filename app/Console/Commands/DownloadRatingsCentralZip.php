<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DownloadRatingsCentralZip extends Command
{
    protected $signature = 'download:ratings-central-zip';
    protected $description = 'Downloads the latest zip file from Ratings Central';

    public function handle()
    {
        $this->info('Downloading Ratings Central zip file');
        $output = [];

        $result = exec('node -v', $output, $code);
        Log::info('Node version: ' . $result);
        $result = exec('cd ' . base_path() . '/scripts && node download-rc-zip.js', $output, $code);
    
        if ($code !== 0) {
            Log::error('Download script failed. Output', ['output' => $output]);
            Log::error('Script exit code: ' . $code);
            throw new \Exception('download:ratings-central-zip failed: ' . $result);
        } else {
            $this->info($result);
            $this->info('Download successful');
            Log::info('Ratings Central zip file has been downloaded');
            return Command::SUCCESS;
        }
    }
}
