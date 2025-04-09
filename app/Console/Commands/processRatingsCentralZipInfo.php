<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Zip;


class processRatingsCentralZipInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:rc-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the latest zip files from Ratings Central';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // First delete the RC_Lists folder contents
        $files = File::files(storage_path('app/public/RC_Lists'));
        foreach ($files as $file) {
            unlink($file);
        }

        $zipPath = storage_path('app/public/RC_Lists.zip');
        // Extract the zip file if it exists
        if (file_exists($zipPath)) {
            $this->info("Extracting zip file to: " . storage_path('app/public/RC_Lists'));
            $zip = new \ZipArchive();
            $result = $zip->open($zipPath);
            
            if ($result === true) {
                $zip->extractTo(storage_path('app/public/RC_Lists'));
                $zip->close();
                $this->info("Extraction completed successfully.");
            } else {
                $this->error("Failed to open zip file. Error code: " . $result);
            }
            // delete the zip file
            unlink($zipPath);
        } 

        // Import the club info
        $this->info("Importing club info...");
        $this->call('import:clubs');

        // Import the player info
        $this->info("Importing player info...");
        $this->call('import:players');

        // Import regions
        $this->info("Importing regions...");
        $this->call('import:regions');
    }
}
