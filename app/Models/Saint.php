<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Saint extends Model
{
    use SoftDeletes, HasTranslations, \App\Traits\HasAuditFields;

    protected $fillable = [
        'slug',
        'parent_id',
        'name',
        'title',
        'photo',
        'introduction',
        'biography',
        'guru_name',
        'diksha_date',
        'mobile',
        'email',
        'address',
        'is_active',
        'is_featured',
        'show_on_website',
        'show_on_app',
        'display_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'name' => 'array',
        'title' => 'array',
        'introduction' => 'array',
        'biography' => 'array',
        'guru_name' => 'array',
        'address' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'meta_keywords' => 'array',
        'diksha_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_website' => 'boolean',
        'show_on_app' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Parent Saint (Guru).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Saint::class, 'parent_id');
    }

    /**
     * Disciple Saints (Shishya).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Saint::class, 'parent_id')->orderBy('display_order');
    }

    /**
     * Vihars for this Saint.
     */
    public function vihars(): HasMany
    {
        return $this->hasMany(Vihar::class)->orderBy('start_date', 'desc');
    }

    /**
     * Scope active saints.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope featured saints.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
