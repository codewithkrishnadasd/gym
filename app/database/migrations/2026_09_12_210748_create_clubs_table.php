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
        Schema::create('clubs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('logo_path')->nullable();
            $table->string('status')->default('active');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->jsonb('address')->nullable();
            $table->string('timezone')->nullable();
            $table->jsonb('opening_hours')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['organisation_id', 'code']);
            $table->index(['organisation_id', 'status']);
        });

        DB::statement("ALTER TABLE clubs ADD CONSTRAINT clubs_status_check CHECK (status IN ('active','archived'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
