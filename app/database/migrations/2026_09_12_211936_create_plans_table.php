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
        Schema::create('plans', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price_minor');
            $table->string('currency_code', 3);
            $table->unsignedInteger('duration_days');
            $table->unsignedInteger('session_limit')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['organisation_id', 'status']);
        });

        DB::statement("ALTER TABLE plans ADD CONSTRAINT plans_status_check CHECK (status IN ('active','archived'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
