<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'display_name',
        'photo_url',
        'phone',
        'whatsapp',
        'verified_phone_at',
    ];

    protected $casts = [
        'verified_phone_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
