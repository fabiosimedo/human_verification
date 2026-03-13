<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('firebase_uid')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('timezone')->nullable();
            $table->string('password')->nullable();

            $table->boolean('email_verified')->default(false);

            $table->string('name')->nullable();
            $table->string('slug')->nullable()->unique();
            $table->string('phone', 20)->nullable();

            $table->string('avatar_url')->nullable();
            $table->string('provider', 50)->nullable();

            $table->json('roles')->nullable();
            $table->boolean('disabled')->default(false);

            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug');
            $table->index('phone');
            $table->index('disabled');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};