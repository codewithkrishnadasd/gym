<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Append-only. No `updated_at`, and the AuditEvent model exposes no
     * update/delete path — see MEP.md Section 5.13 and technology.md 4.2.
     */
    public function up(): void
    {
        Schema::create('audit_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->foreignId('actor_user_id')->constrained('organisation_users')->restrictOnDelete();
            $table->string('actor_role');
            $table->string('action');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->jsonb('before')->nullable();
            $table->jsonb('after')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['organisation_id', 'entity_type', 'entity_id']);
            $table->index(['organisation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_events');
    }
};
