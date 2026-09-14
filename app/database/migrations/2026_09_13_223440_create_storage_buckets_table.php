<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Per-organisation object storage. Each gym brings its own bucket and its own
 * credentials, so member documents live in storage the gym controls and can
 * revoke — nothing sensitive is pooled in a platform-owned bucket.
 *
 * An organisation may configure several (one per branch, one for archives, a
 * separate one for identity documents) and pick which to upload into.
 *
 * `access_key` and `secret_key` are encrypted by the model's `encrypted` cast,
 * so a database dump, a replica, or a support query never exposes credentials
 * that can read the gym's files.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_buckets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();

            $table->string('name');

            // Only S3-compatible storage for now; the column exists so adding
            // another provider later is not a schema migration on live data.
            $table->string('driver')->default('s3');

            $table->string('bucket');
            $table->string('region')->nullable();

            // Set for anything that is not Amazon itself — Firebase/Google
            // Cloud Storage, Cloudflare R2, MinIO, Backblaze.
            $table->string('endpoint')->nullable();

            // Google Cloud Storage and MinIO need path-style addressing;
            // Amazon deprecated it.
            $table->boolean('use_path_style')->default(false);

            $table->text('access_key');
            $table->text('secret_key');

            // Confines this organisation's uploads to one prefix, so a bucket
            // shared with something else cannot be walked into.
            $table->string('path_prefix')->nullable();

            $table->boolean('is_default')->default(false);
            $table->string('status')->default('active');

            $table->timestamp('last_verified_at')->nullable();
            $table->string('last_error')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organisation_id', 'status']);
        });

        DB::statement("ALTER TABLE storage_buckets ADD CONSTRAINT storage_buckets_status_check CHECK (status IN ('active','archived'))");

        // At most one default per organisation, enforced by the database rather
        // than by remembering to clear the old one — a second default would
        // make "where does this upload go" ambiguous.
        DB::statement('CREATE UNIQUE INDEX storage_buckets_one_default_per_organisation ON storage_buckets (organisation_id) WHERE is_default');
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_buckets');
    }
};
