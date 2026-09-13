<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('hostname');
            $table->string('status')->default('pending');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['organisation_id', 'status']);
        });

        // Case-insensitive hostname uniqueness at the database level — resolution
        // in ResolveTenant middleware always lower-cases the incoming host first.
        DB::statement('CREATE UNIQUE INDEX domains_hostname_lower_unique ON domains (lower(hostname))');
        DB::statement("ALTER TABLE domains ADD CONSTRAINT domains_status_check CHECK (status IN ('active','pending','disabled','reserved'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
