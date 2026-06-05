<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model {
    use HasFactory;
    protected $fillable = ['title', 'slug', 'description', 'image'];

    public function topics() {
        return $this->hasMany(Topic::class)->orderBy('sort_order');
    }
}