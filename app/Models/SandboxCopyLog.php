<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SandboxCopyLog extends Model
{
    protected $fillable = [
        'project_id',
        'source_type',
        'source_id',
        'source_name',
        'target_name',
        'created_by',
        'status',
        'error_message',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function source()
    {
        return $this->source_type === 'template' 
            ? $this->belongsTo(SandboxTemplate::class, 'source_id')
            : $this->belongsTo(ProjectSandbox::class, 'source_id');
    }

    public function targetSandbox(): BelongsTo
    {
        return $this->belongsTo(ProjectSandbox::class, 'target_name', 'name');
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeFromTemplate($query)
    {
        return $query->where('source_type', 'template');
    }

    public function scopeFromSandbox($query)
    {
        return $query->where('source_type', 'sandbox');
    }

    public function markAsSuccess(): void
    {
        $this->update(['status' => 'success']);
    }

    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }
}
