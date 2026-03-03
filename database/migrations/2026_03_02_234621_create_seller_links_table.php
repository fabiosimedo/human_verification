<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->uuid('token');
            $table->string('checkout_url', 2048);
            $table->string('public_slug', 32)->unique();

            $table->string('product_title')->nullable();
            $table->string('product_image_url')->nullable();
            $table->unsignedInteger('price_cents')->nullable();
            $table->unsignedSmallInteger('installments')->nullable();
            $table->string('merchant_name')->nullable();

            $table->timestamp('last_fetched_at')->nullable();
            $table->string('status', 20)->default('active');

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_links');
    }
};
