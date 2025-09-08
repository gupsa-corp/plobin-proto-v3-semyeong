<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SandboxForm extends Model
{
    protected $fillable = [
        'title',
        'description', 
        'form_fields',
        'form_settings',
        'sandbox_id',
        'is_active'
    ];

    protected $casts = [
        'form_fields' => 'array',
        'form_settings' => 'array',
        'is_active' => 'boolean'
    ];
}
