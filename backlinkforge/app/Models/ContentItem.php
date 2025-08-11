<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'raw_spintax',
        'generated_text',
        'content_type',
        'ai_used',
        'ai_provider',
        'tokens',
        'metadata',
        'status',
    ];

    protected $casts = [
        'ai_used' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the campaign that owns the content item.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Scope for ready content
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    /**
     * Scope for used content
     */
    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }

    /**
     * Scope by content type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('content_type', $type);
    }

    /**
     * Scope for AI-generated content
     */
    public function scopeAiGenerated($query)
    {
        return $query->where('ai_used', true);
    }

    /**
     * Scope for manually created content
     */
    public function scopeManual($query)
    {
        return $query->where('ai_used', false);
    }
} 