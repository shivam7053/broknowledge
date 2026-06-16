<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'asset_category_id',
        'title',
        'svg_content',
        'keywords',
    ];

    protected $casts = [
        'keywords' => 'array', // Assuming Filament's TagsInput stores it as JSON
    ];

    /**
     * Get the category that owns the asset.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }
}