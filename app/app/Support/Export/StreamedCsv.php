<?php

declare(strict_types=1);

namespace App\Support\Export;

use Closure;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Streams a CSV straight to the client rather than building it in memory, so
 * an export of a large tenant cannot exhaust PHP's memory limit (MEP.md 8.4).
 *
 * Callers hand over a generator; rows are flushed as they are produced.
 */
final class StreamedCsv
{
    /**
     * @param  array<int, string>  $headings
     * @param  Closure(): iterable<int, array<int, string|int|float|null>>  $rows
     * @param  array<int, string>  $preamble  Context lines written above the table.
     */
    public static function respond(string $filename, array $headings, Closure $rows, array $preamble = []): StreamedResponse
    {
        return response()->streamDownload(function () use ($headings, $rows, $preamble): void {
            $handle = fopen('php://output', 'wb');

            if ($handle === false) {
                return;
            }

            // A BOM so Excel opens UTF-8 names correctly.
            fwrite($handle, "\xEF\xBB\xBF");

            foreach ($preamble as $line) {
                fputcsv($handle, [$line]);
            }

            if ($preamble !== []) {
                fputcsv($handle, []);
            }

            fputcsv($handle, $headings);

            foreach ($rows() as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}
