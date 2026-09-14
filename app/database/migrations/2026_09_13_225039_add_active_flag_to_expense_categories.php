<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Gives each expense category an active flag.
 *
 * A category that has been used cannot simply be deleted: every expense filed
 * under it, and every report that groups by it, would be describing a category
 * the system claims never existed. Deactivating instead takes it out of the
 * list an operator can file *new* expenses under while leaving the historical
 * data — and the filters that reach it — completely intact.
 *
 * The column changes shape from ["Rent", …] to [{"name":"Rent","active":true}, …].
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->convert(fn (array $stored): array => array_values(array_map(
            static fn (mixed $entry): array => is_array($entry)
                ? $entry
                : ['name' => (string) $entry, 'active' => true],
            $stored,
        )));
    }

    public function down(): void
    {
        $this->convert(fn (array $stored): array => array_values(array_map(
            static fn (mixed $entry): string => is_array($entry) ? (string) ($entry['name'] ?? '') : (string) $entry,
            $stored,
        )));
    }

    /**
     * @param  callable(array<int, mixed>): array<int, mixed>  $transform
     */
    private function convert(callable $transform): void
    {
        foreach (DB::table('organisations')->select('id', 'expense_categories')->orderBy('id')->cursor() as $row) {
            $stored = json_decode((string) $row->expense_categories, true);

            if (! is_array($stored) || $stored === []) {
                continue;
            }

            DB::table('organisations')
                ->where('id', $row->id)
                ->update(['expense_categories' => json_encode($transform($stored))]);
        }
    }
};
