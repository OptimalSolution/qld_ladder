<?php

namespace App\LogViewer;

use Arcanedev\LogViewer\Exceptions\FilesystemException;
use Arcanedev\LogViewer\Helpers\LogParser;
use Arcanedev\LogViewer\Utilities\Filesystem;

/**
 * Includes `storage/logs/laravel.log` when using the `single` log driver.
 * Arcanedev LogViewer only matches dated files (`laravel-YYYY-MM-DD.log`) by default.
 *
 * The synthetic route segment `laravel` maps to that file.
 */
class InclusiveLogFilesystem extends Filesystem
{
    public const SINGLE_FILE_DATE_KEY = 'laravel';

    public function logs(): array
    {
        $logs = parent::logs();

        $single = $this->storagePath.DIRECTORY_SEPARATOR.'laravel.log';

        if ($this->filesystem->exists($single)) {
            $real = realpath($single);

            if ($real !== false && ! in_array($real, $logs, true)) {
                $logs[] = $real;
            }
        }

        return $logs;
    }

    public function dates($withPaths = false): array
    {
        $files = array_reverse($this->logs());

        $dates = array_map(fn (string $file): string => $this->dateLabelForFile($file), $files);

        if ($withPaths) {
            return array_combine($dates, $files);
        }

        return $dates;
    }

    public function path($date): string
    {
        return $this->resolvePathForDate($date);
    }

    public function read($date): string
    {
        try {
            return $this->filesystem->get($this->resolvePathForDate($date));
        } catch (FilesystemException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new FilesystemException($e->getMessage());
        }
    }

    public function delete(string $date): bool
    {
        $path = $this->resolvePathForDate($date);

        throw_unless($this->filesystem->delete($path), FilesystemException::cannotDeleteLog());

        return true;
    }

    private function resolvePathForDate(string $date): string
    {
        if ($date === self::SINGLE_FILE_DATE_KEY) {
            $path = $this->storagePath.DIRECTORY_SEPARATOR.'laravel.log';

            if (! $this->filesystem->exists($path)) {
                throw FilesystemException::invalidPath($path);
            }

            $resolved = realpath($path);

            return $resolved !== false ? $resolved : $path;
        }

        return parent::path($date);
    }

    private function dateLabelForFile(string $file): string
    {
        $basename = basename($file);

        if ($basename === 'laravel.log') {
            return self::SINGLE_FILE_DATE_KEY;
        }

        return LogParser::extractDate($basename);
    }
}
