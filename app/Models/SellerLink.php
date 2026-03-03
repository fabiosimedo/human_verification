<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellerLink extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'checkout_url',
        'public_slug',
        'product_title',
        'product_image_url',
        'price_cents',
        'installments',
        'merchant_name',
        'last_fetched_at',
        'status',
    ];

    protected $casts = [
        'token' => 'string',
        'price_cents' => 'integer',
        'installments' => 'integer',
        'last_fetched_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(LinkEvent::class, 'seller_link_id');
    }
}
