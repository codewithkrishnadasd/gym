<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The favicon is stored as its own file rather than derived on request: it is
 * fetched by the browser on every page including the sign-in page, where there
 * is no session to authorise anything and no time to resize an image.
 *
 * `logo_path` already exists on this table; both are written from the same
 * upload by App\Support\Images\BrandImage.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->string('favicon_path')->nullable()->after('logo_path');
        });
    }

    public function down(): void
    {
        Schema::table('organisations', function (Blueprint $table): void {
            $table->dropColumn('favicon_path');
        });
    }
};
