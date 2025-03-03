<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class DownloadRatingsCentralZips extends Command
{
    protected $signature = 'download:zips';
    protected $description = 'Logs into Ratings Central and retrieves the latest zip files';

    public function handle()
    {
        $loginUrl = 'https://www.ratingscentral.com/Support.php';

        // **Step 1: Fetch the login page to extract CSRF token**
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
        ])->get($loginUrl);

        // Extract CSRF token from cookies (or parse HTML if needed)
        preg_match('/CSRFtoken=([^;]+)/', $response->headers()['Set-Cookie'][0] ?? '', $matches);
        $csrfToken = $matches[1] ?? null;

        if (!$csrfToken) {
            $this->error('Failed to retrieve CSRF token!');
            return 1;
        }

        // **Step 2: Send login request**
        $loginResponse = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'Referer' => $loginUrl,
        ])->asMultipart()->withCookies([
            'CSRFtoken' => $csrfToken
        ], '.ratingscentral.com')->post($loginUrl, [
            'LoginID' => '5342',
            'LoginPassword' => 'Zzw7aKZvkBYHam2',
            'CSRFtoken' => $csrfToken
        ]);

        // **Step 3: Extract PHPSESSID**
        preg_match('/PHPSESSID=([^;]+)/', $loginResponse->headers()['Set-Cookie'][0] ?? '', $matches);
        $phpSessionId = $matches[1] ?? null;

        $phpSessionId = 'x6nCsEha6VLJonBNc1yEoyqhESufxGTXk%2Cbrx7uaOs5b-5rv';

        if (!$phpSessionId) {
            $this->error('Failed to retrieve PHPSESSID. Login may have failed.');
            return 1;
        }

        $this->info("Successfully logged in! Session ID: $phpSessionId");

        // **Step 4: Use session for authenticated requests**
        $zipUrl = 'https://www.ratingscentral.com/ZippedListDownload.php?Version=6';  // Update with actual URL

        $zipResponse = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'Referer' => $loginUrl,
        ])->withCookies([
            'PHPSESSID' => $phpSessionId
        ], '.ratingscentral.com')->get($zipUrl);

        $this->info("ZIP file downloaded successfully: " . $zipResponse->body());

        // Check if the request was successful
        if (!$zipResponse->successful()) {
            $this->error("Failed to download ZIP file.");
            return 1;
        }

        // **Save the ZIP file**
        $zipPath = storage_path('app/public/RCLists.zip');  // Change path as needed
        file_put_contents($zipPath, $zipResponse->body());
        $this->info("ZIP file downloaded successfully: $zipPath");
        return 0;
    }
}
