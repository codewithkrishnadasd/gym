<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Which modules the organisation has switched on, as a list of
     * App\Enums\Feature keys. Null means every module — what every
     * organisation created before this existed had.
     */
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->jsonb('features')->nullable()->after('id_prefixes');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->dropColumn('features');
        });
    }
};
