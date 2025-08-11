<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'node_type',
        'name',
        'position',
        'config',
        'status',
        'order_index',
    ];

    protected $casts = [
        'position' => 'array',
        'config' => 'array',
    ];

    /**
     * Get the campaign that owns the node.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the job logs for the node.
     */
    public function jobLogs(): HasMany
    {
        return $this->hasMany(JobLog::class, 'node_id');
    }

    /**
     * Scope for active nodes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed nodes
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope by node type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('node_type', $type);
    }
}
