<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use App\Models\Setting;



class ProcessRatingsCentralZipCommand extends Command
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
        // First, delete the RC_Lists folder contents
        $rcListsPath = storage_path('app/public/RC_Lists');
        if (!File::exists($rcListsPath)) {
            File::makeDirectory($rcListsPath, 0755, true);
            $this->info("Created directory: " . $rcListsPath);
        } else {
            File::cleanDirectory($rcListsPath);
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
            \Log::info("Ratings Central zip file has been processed");
        } else {
            \Log::error("Zip file not found at: " . $zipPath);
            throw new \Exception("Zip file not found at: " . $zipPath);
        }
    }
}
