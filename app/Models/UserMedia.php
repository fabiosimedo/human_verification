<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserMedia extends Model
{
    public const COLLECTION_PUBLIC_PROFILE = 'public_profile';
    public const COLLECTION_IDENTITY_VERIFICATION = 'identity_verification';
    public const COLLECTION_IDENTITY_LIVENESS = 'identity_liveness';
    public const COLLECTION_SALES_GALLERY = 'sales_gallery';

    public const TYPE_IMAGE = 'image';
    public const TYPE_VIDEO = 'video';

    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_PRIVATE = 'private';

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_ARCHIVED = 'archived';

    protected $table = 'user_media';

    protected $fillable = [
        'user_id',
        'collection',
        'media_type',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'extension',
        'size_bytes',
        'width',
        'height',
        'duration_seconds',
        'position',
        'is_primary',
        'visibility',
        'status',
        'metadata',
        'processed_at',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'duration_seconds' => 'decimal:2',
        'position' => 'integer',
        'is_primary' => 'boolean',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublicProfile($query)
    {
        return $query->where('collection', self::COLLECTION_PUBLIC_PROFILE);
    }

    public function scopeImages($query)
    {
        return $query->where('media_type', self::TYPE_IMAGE);
    }

    public function scopeVideos($query)
    {
        return $query->where('media_type', self::TYPE_VIDEO);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', self::VISIBILITY_PUBLIC);
    }

    public function url(): ?string
    {
        if (! $this->path) {
            return null;
        }

        if ($this->disk === 'public_root') {
            return asset(ltrim($this->path, '/'));
        }

        if ($this->disk === 'public') {
            $cleanPath = ltrim(preg_replace('#^storage/#', '', $this->path), '/');
            return asset('storage/' . $cleanPath);
        }

        return Storage::disk($this->disk)->url($this->path);
    }

    public function isImage(): bool
    {
        return $this->media_type === self::TYPE_IMAGE;
    }

    public function isVideo(): bool
    {
        return $this->media_type === self::TYPE_VIDEO;
    }
}