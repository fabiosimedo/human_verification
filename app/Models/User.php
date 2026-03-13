<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use MustVerifyEmailTrait;

    protected $table = 'users';

    protected $fillable = [
        'firebase_uid',
        'email',
        'timezone',
        'password',
        'email_verified',
        'name',
        'slug',
        'phone',
        'avatar_url',
        'provider',
        'roles',
        'disabled',
        'last_login_at',
        'last_seen_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
        'roles' => 'array',
        'disabled' => 'boolean',
        'last_login_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active');
    }

    public function media(): HasMany
    {
        return $this->hasMany(UserMedia::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(UserLink::class)->orderBy('position');
    }

    public function activeLinks(): HasMany
    {
        return $this->hasMany(UserLink::class)
            ->where('is_active', true)
            ->orderBy('position');
    }

    public function publicProfileMedia(): HasMany
    {
        return $this->media()
            ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
            ->where('media_type', UserMedia::TYPE_IMAGE)
            ->orderBy('position');
    }

    public function primaryPublicImage(): HasOne
    {
        return $this->hasOne(UserMedia::class)
            ->where('collection', UserMedia::COLLECTION_PUBLIC_PROFILE)
            ->where('media_type', UserMedia::TYPE_IMAGE)
            ->where('is_primary', true);
    }

    public function hasVerifiedEmail(): bool
    {
        return (bool) $this->email_verified;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified' => true,
            'updated_at' => Carbon::now(),
        ])->save();
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \Illuminate\Auth\Notifications\VerifyEmail);
    }

    public function hasActivePaidSubscription(): bool
    {
        return $this->activeSubscription?->isPaid() ?? false;
    }

    public function publicImageLimit(): int
    {
        return 1;
    }

    public function salesLinksLimit(): int
    {
        return $this->hasActivePaidSubscription() ? 999 : 1;
    }

    public function verificationVideosLimit(): int
    {
        return 0;
    }

    public static function generateUniquePublicSlug(string $name, ?int $ignoreUserId = null): string
    {
        $base = Str::upper('HMN-' . Str::slug($name ?: 'usuario', '-'));

        if ($base === 'HMN-') {
            $base = 'HMN-USUARIO';
        }

        $slug = $base;
        $suffix = 2;

        while (
            static::query()
                ->when($ignoreUserId, fn ($query) => $query->whereKeyNot($ignoreUserId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base . '-' . $suffix++;
        }

        return $slug;
    }
}