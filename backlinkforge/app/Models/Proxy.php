<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proxy extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'port',
        'username',
        'password_encrypted',
        'type',
        'country',
        'status',
        'success_count',
        'failure_count',
        'last_used_at',
        'last_tested_at',
    ];

    protected $hidden = [
        'password_encrypted',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'last_tested_at' => 'datetime',
    ];

    /**
     * Get the accounts using this proxy.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Set the password with encryption
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password_encrypted'] = encrypt($value);
        }
    }

    /**
     * Get the decrypted password
     */
    public function getPasswordAttribute()
    {
        if ($this->password_encrypted) {
            return decrypt($this->password_encrypted);
        }
        return null;
    }

    /**
     * Get the full proxy URL
     */
    public function getUrlAttribute()
    {
        $url = $this->type . '://';
        
        if ($this->username && $this->password) {
            $url .= $this->username . ':' . $this->password . '@';
        }
        
        $url .= $this->ip . ':' . $this->port;
        
        return $url;
    }

    /**
     * Scope for active proxies
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope by country
     */
    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope for proxies with good success rate
     */
    public function scopeReliable($query)
    {
        return $query->where('success_count', '>', 0)
                    ->whereRaw('success_count / (success_count + failure_count) > 0.7');
    }
} 