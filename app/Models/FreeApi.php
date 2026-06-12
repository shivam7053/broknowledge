<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreeApi extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category',
        'description',
        'documentation_html',
        'auth_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}