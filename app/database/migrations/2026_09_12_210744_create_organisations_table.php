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
        Schema::create('organisations', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->string('status')->default('active');
            $table->string('timezone')->default('UTC');
            $table->string('currency_code', 3)->default('USD');
            $table->string('locale')->default('en');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->jsonb('address')->nullable();

            $table->string('terminology_member_singular')->default('Member');
            $table->string('terminology_member_plural')->default('Members');
            $table->string('terminology_user_singular')->default('Staff');
            $table->string('terminology_user_plural')->default('Staff');
            $table->string('terminology_club_singular')->default('Club');
            $table->string('terminology_club_plural')->default('Clubs');

            $table->foreignId('created_by')->nullable()->constrained('platform_admins')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
        });

        DB::statement("ALTER TABLE organisations ADD CONSTRAINT organisations_status_check CHECK (status IN ('active','suspended','archived'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('organisations');
    }
};
