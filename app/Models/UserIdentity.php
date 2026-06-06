<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class UserIdentity extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $table = 'user_identity';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'email',
        'provider',
        'hash',
        'is_verified',
        'verification_token',
        'verification_token_expiration',
        'password_recovery_token',
        'password_recovery_token_expiration',
        'refresh_token',
        'role',
    ];

    protected $hidden = [
        'hash',
        'verification_token',
        'password_recovery_token',
        'remember_token',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verification_token_expiration' => 'datetime',
        'password_recovery_token_expiration' => 'datetime',
    ];

    /**
     * Get the password for the user.
     */
    public function getAuthPassword(): string
    {
        return $this->hash ?? '';
    }

    /**
     * Set password attribute (maps to hash).
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['hash'] = $value;
    }

    /**
     * Get password attribute (maps to hash).
     */
    public function getPasswordAttribute()
    {
        return $this->hash;
    }

    /**
     * Get the user profile associated with this identity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    /**
     * Accessor for name property.
     */
    public function getNameAttribute(): string
    {
        return $this->user ? $this->user->full_name : $this->email;
    }

    /**
     * The events that this staff is assigned to manage.
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_staff', 'user_id', 'event_id')
                    ->withTimestamps();
    }
}
