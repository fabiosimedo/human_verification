<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('plan', 30)->default('free');
            $table->string('status', 30)->default('active');

            $table->string('pagarme_customer_id')->nullable();
            $table->string('pagarme_subscription_id')->nullable();

            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('grace_period_end')->nullable();

            $table->timestamps();

            $table->unique('user_id');
            $table->index(['plan', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
