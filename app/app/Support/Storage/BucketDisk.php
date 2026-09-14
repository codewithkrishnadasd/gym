<?php

declare(strict_types=1);

namespace App\Support\Storage;

use App\Models\StorageBucket;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Builds a filesystem from an organisation's own stored credentials, rather
 * than from `config/filesystems.php`.
 *
 * Configured disks are process-wide and defined at boot, which is exactly
 * wrong for multi-tenant storage: every organisation has different credentials
 * and they arrive from the database mid-request. `Storage::build()` makes a
 * disk for this call and throws it away, so one gym's credentials are never
 * left configured while another gym's request is served.
 */
final class BucketDisk
{
    public static function for(StorageBucket $bucket): Filesystem
    {
        return Storage::build([
            'driver' => 's3',
            'key' => (string) $bucket->access_key,
            'secret' => (string) $bucket->secret_key,
            'region' => $bucket->region ?: 'auto',
            'bucket' => $bucket->bucket,
            'endpoint' => $bucket->endpoint ?: null,
            'use_path_style_endpoint' => $bucket->use_path_style,
            // Uploads are private. Documents are served by streaming through
            // an authorised route, never by handing out a public object URL.
            'visibility' => 'private',
            'throw' => true,
        ]);
    }

    /**
     * Round-trips a small object to prove the credentials actually work.
     *
     * A write-then-read-then-delete rather than a bucket listing, because
     * list permission and write permission are granted separately on every
     * provider — an operator whose key can list but not write would otherwise
     * be told the configuration is fine and discover otherwise on their first
     * real upload.
     *
     * @return array{ok: bool, error: string|null}
     */
    public static function verify(StorageBucket $bucket): array
    {
        $probe = $bucket->prefixFor('.connection-check/'.bin2hex(random_bytes(8)).'.txt');

        try {
            $disk = self::for($bucket);

            $disk->put($probe, 'ok');

            $readBack = $disk->get($probe);

            $disk->delete($probe);

            if ($readBack !== 'ok') {
                return ['ok' => false, 'error' => 'The test file was written but read back with different contents.'];
            }

            return ['ok' => true, 'error' => null];
        } catch (Throwable $exception) {
            return ['ok' => false, 'error' => self::readableError($exception)];
        }
    }

    /**
     * SDK exceptions arrive as several lines of request context wrapped around
     * one useful sentence. Anything longer than a line is unreadable in a
     * settings panel and risks echoing the request signature back onto screen.
     */
    private static function readableError(Throwable $exception): string
    {
        $message = trim(explode("\n", $exception->getMessage())[0]);

        return mb_strimwidth($message === '' ? $exception::class : $message, 0, 240, '…');
    }
}
