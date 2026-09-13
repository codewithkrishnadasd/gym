<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Per-organisation overrides for the WhatsApp message bodies (MEP.md 6.8).
 *
 * A row here replaces the built-in default for one action type. Absence means
 * "use the default", so an organisation that never opens the editor keeps
 * working and a customised template can be reverted by deleting the row.
 *
 * `version` increments on every save. It is copied onto each notification's
 * `message_template_version`, so a snapshot always records which wording was
 * in force when it was generated.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();
            $table->string('action_type');
            $table->text('body');
            $table->unsignedInteger('version')->default(1);
            $table->foreignId('updated_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['organisation_id', 'action_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_templates');
    }
};
