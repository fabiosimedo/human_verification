<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkEvent extends Model
{
    protected $fillable = [
        'seller_link_id',
        'event_type',
        'ip_hash',
        'ua_hash',
    ];

    public function link(): BelongsTo
    {
        return $this->belongsTo(SellerLink::class, 'seller_link_id');
    }
}
