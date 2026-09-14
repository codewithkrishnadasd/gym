<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The prefix each kind of record is referred to by ("MEM-42", "PMT-17"),
     * configurable per organisation. Null means the built-in prefixes; see
     * Organisation::DEFAULT_ID_PREFIXES.
     */
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->jsonb('id_prefixes')->nullable()->after('expense_categories');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->dropColumn('id_prefixes');
        });
    }
};
