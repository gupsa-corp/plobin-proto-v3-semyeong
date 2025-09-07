<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'display_name', 
        'description',
        'guard_name',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PermissionCategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function getDisplayNameAttribute()
    {
        return $this->attributes['display_name'] ?? $this->name;
    }
}