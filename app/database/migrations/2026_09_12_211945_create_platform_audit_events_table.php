<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Append-only audit trail for root/platform-level actions that are not
     * scoped to any single organisation (creating an organisation, adding a
     * domain, assigning the first admin). See MEP.md Section 3.3.
     */
    public function up(): void
    {
        Schema::create('platform_audit_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('actor_platform_admin_id')->constrained('platform_admins')->restrictOnDelete();
            $table->string('action');
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->jsonb('before')->nullable();
            $table->jsonb('after')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_audit_events');
    }
};
