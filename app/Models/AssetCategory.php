<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the assets for the category.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}