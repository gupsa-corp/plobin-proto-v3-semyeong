<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $table = 'project_pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'project_id',
        'parent_id',
        'user_id',
        'sort_order',
        'sandbox_folder',
        'custom_screen_settings'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'custom_screen_settings' => 'json',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')->orderBy('sort_order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPathAttribute(): string
    {
        $path = [];
        $page = $this;

        while ($page) {
            $path[] = $page->slug;
            $page = $page->parent;
        }

        return implode('/', array_reverse($path));
    }

    public function scopeRootPages($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
