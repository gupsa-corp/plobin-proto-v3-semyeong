<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermissionGroup extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'display_name',
        'description',
        'sort_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PermissionCategory::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('display_name');
    }
}
