<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_club_history', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('from_club_id')->nullable()->constrained('clubs')->nullOnDelete();
            $table->foreignId('to_club_id')->constrained('clubs')->restrictOnDelete();
            $table->string('reason')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->foreignId('changed_by')->constrained('organisation_users')->restrictOnDelete();

            $table->index(['organisation_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_club_history');
    }
};
