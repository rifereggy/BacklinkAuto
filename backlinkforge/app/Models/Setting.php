<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'encrypted',
        'type',
        'description',
        'group',
    ];

    protected $casts = [
        'encrypted' => 'boolean',
    ];

    /**
     * Get the decrypted value
     */
    public function getDecryptedValueAttribute()
    {
        if ($this->encrypted) {
            return decrypt($this->value);
        }
        return $this->value;
    }

    /**
     * Set the value with encryption if needed
     */
    public function setValueAttribute($value)
    {
        if ($this->encrypted) {
            $this->attributes['value'] = encrypt($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

    /**
     * Scope by group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for encrypted settings
     */
    public function scopeEncrypted($query)
    {
        return $query->where('encrypted', true);
    }

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $setting->decrypted_value;
    }

    /**
     * Set setting value by key
     */
    public static function setValue($key, $value, $encrypted = false, $type = 'string', $group = 'general')
    {
        $setting = static::firstOrNew(['key' => $key]);
        
        $setting->fill([
            'value' => $value,
            'encrypted' => $encrypted,
            'type' => $type,
            'group' => $group,
        ]);

        return $setting->save();
    }
}
