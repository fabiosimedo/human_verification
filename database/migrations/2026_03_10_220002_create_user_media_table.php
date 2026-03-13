<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('collection', 50)->default('public_profile');
            $table->string('media_type', 20)->default('image');

            $table->string('disk', 50)->default('public');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('extension', 20)->nullable();

            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->decimal('duration_seconds', 8, 2)->nullable();

            $table->unsignedInteger('position')->default(1);
            $table->boolean('is_primary')->default(false);

            $table->string('visibility', 20)->default('private');
            $table->string('status', 20)->default('approved');

            $table->json('metadata')->nullable();

            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index(['user_id', 'collection']);
            $table->index(['user_id', 'collection', 'is_primary']);
            $table->index(['user_id', 'media_type']);
            $table->index('status');
            $table->index('visibility');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_media');
    }
};