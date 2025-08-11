<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'node_id',
        'job_type',
        'status',
        'payload',
        'result',
        'error_message',
        'attempts',
        'max_attempts',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'result' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the campaign that owns the job log.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the node that owns the job log.
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(CampaignNode::class, 'node_id');
    }

    /**
     * Get the captcha requests for this job.
     */
    public function captchaRequests(): HasMany
    {
        return $this->hasMany(CaptchaRequest::class, 'job_id');
    }

    /**
     * Scope for pending jobs
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed jobs
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for completed jobs
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope by job type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('job_type', $type);
    }

    /**
     * Scope for jobs that can be retried
     */
    public function scopeRetryable($query)
    {
        return $query->where('status', 'failed')
                    ->where('attempts', '<', 'max_attempts');
    }
} 