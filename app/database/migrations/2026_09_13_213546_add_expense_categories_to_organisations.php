<?php

declare(strict_types=1);

use App\Models\Organisation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Moves the expense category list from a hard-coded array in the Expense model
 * to something each organisation manages for itself. A gym's cost structure is
 * its own — one tracks "Trainer commission" and "Franchise fee" where another
 * tracks neither — and category is what every expense report groups by.
 *
 * Existing organisations are seeded with exactly the list they have been using,
 * so nothing they have already recorded changes shape.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->jsonb('expense_categories')->nullable()->after('notification_settings');
        });

        // Seeded rather than left null so the settings screen opens on the
        // real list an operator recognises, not an empty box.
        DB::table('organisations')->update([
            'expense_categories' => json_encode(Organisation::DEFAULT_EXPENSE_CATEGORIES),
        ]);
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->dropColumn('expense_categories');
        });
    }
};
