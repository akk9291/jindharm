<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentType extends Model
{
    use SoftDeletes, HasTranslations, \App\Traits\HasAuditFields;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_active',
        'is_featured',
        'show_on_website',
        'show_on_app',
        'display_order',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_website' => 'boolean',
        'show_on_app' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Categories under this content type.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class)->whereNull('parent_id')->orderBy('display_order');
    }

    /**
     * All categories including subcategories under this content type.
     */
    public function allCategories(): HasMany
    {
        return $this->hasMany(Category::class)->orderBy('display_order');
    }

    /**
     * Contents belonging to this content type.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class)->orderBy('display_order');
    }

    /**
     * Scope active.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
