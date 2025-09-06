<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectPage extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'slug',
        'content',
        'status',
        'parent_id',
        'user_id',
        'sort_order'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProjectPage::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProjectPage::class, 'parent_id');
    }

    // 탭용 페이지들 (parent_id가 null인 것들)
    public function scopeTabs($query)
    {
        return $query->whereNull('parent_id')->orderBy('sort_order');
    }
}
