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
    private const DEFAULT_MAX_READ_BYTES = 8 * 1024 * 1024; // 8 MB

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
        $singleLogPath = $this->resolveSingleLogPath();

        // Keep laravel.log last so default routes open the latest daily log first.
        if ($singleLogPath !== null) {
            $singleIndex = array_search($singleLogPath, $files, true);

            if ($singleIndex !== false) {
                unset($files[$singleIndex]);
                $files[] = $singleLogPath;
                $files = array_values($files);
            }
        }

        $dates = array_map(fn (string $file): string => $this->dateLabelForFile($file), $files);

        if ($withPaths) {
            return array_combine($dates, $files) ?: [];
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
            $path = $this->resolvePathForDate($date);
            $maxReadBytes = max((int) config('log-viewer.max-read-bytes', self::DEFAULT_MAX_READ_BYTES), 1);
            $fileSize = (int) $this->filesystem->size($path);

            if ($fileSize <= $maxReadBytes) {
                return $this->filesystem->get($path);
            }

            return $this->readTail($path, $maxReadBytes);
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
            $singlePath = $this->resolveSingleLogPath();

            if ($singlePath !== null) {
                return $singlePath;
            }

            throw FilesystemException::invalidPath($this->storagePath.DIRECTORY_SEPARATOR.'laravel.log');
        }

        return parent::path($date);
    }

    private function resolveSingleLogPath(): ?string
    {
        $path = $this->storagePath.DIRECTORY_SEPARATOR.'laravel.log';

        if (! $this->filesystem->exists($path)) {
            return null;
        }

        $resolved = realpath($path);

        return $resolved !== false ? $resolved : $path;
    }

    private function readTail(string $path, int $maxBytes): string
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw FilesystemException::invalidPath($path);
        }

        try {
            $size = (int) $this->filesystem->size($path);
            $offset = max($size - $maxBytes, 0);

            if ($offset > 0) {
                fseek($handle, $offset);
            }

            $chunk = stream_get_contents($handle);

            if ($chunk === false) {
                return '';
            }

            if ($offset > 0) {
                $trimmedChunk = preg_replace(
                    '/^.*?(?=\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\])/s',
                    '',
                    $chunk,
                    1
                );

                if (is_string($trimmedChunk)) {
                    $chunk = $trimmedChunk;
                }
            }

            return $chunk;
        } finally {
            fclose($handle);
        }
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
