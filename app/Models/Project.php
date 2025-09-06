<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'organization_id',
        'user_id'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function rootPages()
    {
        return $this->hasMany(Page::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function projectPages()
    {
        return $this->hasMany(ProjectPage::class)->where('is_active', true)->orderBy('sort_order');
    }
}