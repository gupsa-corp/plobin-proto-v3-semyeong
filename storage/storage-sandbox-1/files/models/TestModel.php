<?php

use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    protected $table = 'test_models';
    
    protected $fillable = [
        'name',
        'description',
        'active'
    ];
    
    protected $casts = [
        'active' => 'boolean'
    ];
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}