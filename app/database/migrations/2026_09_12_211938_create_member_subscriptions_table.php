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
        Schema::create('member_subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('club_id')->constrained('clubs')->restrictOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('amount_due_minor');
            $table->unsignedBigInteger('amount_paid_minor')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index(['organisation_id', 'member_id']);
            $table->index(['organisation_id', 'status', 'end_date']);
        });

        DB::statement("ALTER TABLE member_subscriptions ADD CONSTRAINT member_subscriptions_status_check CHECK (status IN ('active','expired','paused','cancelled'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('member_subscriptions');
    }
};
