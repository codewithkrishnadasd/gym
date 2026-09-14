<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Files attached to a member or a staff member — identity proof, medical
 * clearance, a signed contract, a photo of a waiver.
 *
 * The row records where the file went, not the file itself: `storage_bucket_id`
 * plus `path` is what makes a document retrievable after an organisation adds a
 * second bucket, and why the bucket is captured per document rather than read
 * from whichever one happens to be the default today.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisation_id')->constrained('organisations')->cascadeOnDelete();

            // 'member' or 'user', per the enforced morph map.
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');

            // Restricted, not cascaded: removing a bucket configuration must
            // not silently delete the record of files still sitting in it.
            $table->foreignId('storage_bucket_id')->constrained('storage_buckets')->restrictOnDelete();

            $table->string('title');
            $table->string('category')->nullable();

            $table->string('path');
            $table->string('original_filename');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);

            $table->foreignId('uploaded_by')->nullable()->constrained('organisation_users')->nullOnDelete();
            $table->timestamps();

            $table->index(['organisation_id', 'subject_type', 'subject_id']);
            $table->index(['organisation_id', 'category']);
        });

        DB::statement("ALTER TABLE documents ADD CONSTRAINT documents_subject_type_check CHECK (subject_type IN ('member','user'))");
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
