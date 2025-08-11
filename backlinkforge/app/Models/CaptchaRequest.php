<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaptchaRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'method',
        'image_data',
        'image_url',
        'result',
        'confidence',
        'status',
        'metadata',
        'solved_at',
    ];

    protected $casts = [
        'confidence' => 'decimal:2',
        'metadata' => 'array',
        'solved_at' => 'datetime',
    ];

    /**
     * Get the job that owns the captcha request.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(JobLog::class, 'job_id');
    }

    /**
     * Scope for pending captcha requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for solved captcha requests
     */
    public function scopeSolved($query)
    {
        return $query->where('status', 'solved');
    }

    /**
     * Scope for failed captcha requests
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope by method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    /**
     * Scope for high confidence results
     */
    public function scopeHighConfidence($query, $threshold = 80)
    {
        return $query->where('confidence', '>=', $threshold);
    }

    /**
     * Mark as solved
     */
    public function markAsSolved($result, $confidence = null)
    {
        $this->update([
            'result' => $result,
            'confidence' => $confidence,
            'status' => 'solved',
            'solved_at' => now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed',
        ]);
    }
}
