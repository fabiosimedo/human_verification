<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'plan',
        'status',
        'pagarme_customer_id',
        'pagarme_subscription_id',
        'current_period_end',
        'grace_period_end',
    ];

    protected $casts = [
        'current_period_end' => 'datetime',
        'grace_period_end' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isFree(): bool
    {
        return ($this->plan ?? 'free') === 'free';
    }

    public function isActive(): bool
    {
        return ($this->status ?? 'inactive') === 'active';
    }

    public function isPaid(): bool
    {
        return ! $this->isFree() && $this->isActive();
    }
}