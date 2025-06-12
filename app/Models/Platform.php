<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Platform extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
        'description',
        'description_ar',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function activeGrades(): HasMany
    {
        return $this->hasMany(Grade::class)->where('is_active', true);
    }

    public function subjects()
    {
        return $this->hasManyThrough(Subject::class, Grade::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}