<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Encryption\Encrypter;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'provider',
        'username',
        'email',
        'password_encrypted',
        'proxy_id',
        'status',
        'metadata',
        'last_used_at',
    ];

    protected $hidden = [
        'password_encrypted',
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_used_at' => 'datetime',
    ];

    /**
     * Get the campaign that owns the account.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the proxy for the account.
     */
    public function proxy(): BelongsTo
    {
        return $this->belongsTo(Proxy::class);
    }

    /**
     * Set the password with encryption
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password_encrypted'] = encrypt($value);
    }

    /**
     * Get the decrypted password
     */
    public function getPasswordAttribute()
    {
        return decrypt($this->password_encrypted);
    }

    /**
     * Scope for active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope by provider
     */
    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    /**
     * Scope for accounts with proxies
     */
    public function scopeWithProxy($query)
    {
        return $query->whereNotNull('proxy_id');
    }
} 