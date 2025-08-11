<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'graph_json',
        'settings',
        'team_id',
        'user_id',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'graph_json' => 'array',
        'settings' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the team that owns the campaign.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the user that owns the campaign.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the nodes for the campaign.
     */
    public function nodes(): HasMany
    {
        return $this->hasMany(CampaignNode::class);
    }

    /**
     * Get the accounts for the campaign.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get the content items for the campaign.
     */
    public function contentItems(): HasMany
    {
        return $this->hasMany(ContentItem::class);
    }

    /**
     * Get the job logs for the campaign.
     */
    public function jobLogs(): HasMany
    {
        return $this->hasMany(JobLog::class);
    }

    /**
     * Scope for active campaigns
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed campaigns
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
} 