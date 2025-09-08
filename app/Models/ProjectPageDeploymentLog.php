<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPageDeploymentLog extends Model
{
    protected $fillable = [
        'project_page_id',
        'user_id', 
        'from_status',
        'to_status',
        'reason'
    ];
    
    public function projectPage(): BelongsTo
    {
        return $this->belongsTo(ProjectPage::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}