<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class DailyQuote extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'quote_text',
        'author',
        'image',
        'quote_date',
        'category',
        'tags',
        'is_active',
        'is_featured',
        'views_count',
        'shares_count',
        'downloads_count',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'title' => 'array',
        'quote_text' => 'array',
        'author' => 'array',
        'tags' => 'array',
        'quote_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'shares_count' => 'integer',
        'downloads_count' => 'integer',
    ];

    /**
     * Scope active.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get image with fallback to default Muni image.
     */
    public function getImageAttribute($value)
    {
        return $value ?: '/images/jain/muni_vidyasagar.jpg';
    }

    /**
     * Helper for display title.
     */
    public function getDisplayTitleAttribute()
    {
        return $this->getLocalized('title') ?: 'आज का आध्यात्मिक संदेश';
    }

    /**
     * Helper for display text.
     */
    public function getDisplayTextAttribute()
    {
        return $this->getLocalized('quote_text');
    }

    /**
     * Helper for display author.
     */
    public function getDisplayAuthorAttribute()
    {
        return $this->getLocalized('author') ?: 'भगवान महावीर स्वामी';
    }
}
