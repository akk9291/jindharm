<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model
{
    use SoftDeletes, HasTranslations, \App\Traits\HasAuditFields;

    protected $fillable = [
        'content_type_id',
        'category_id',
        'sub_category_id',
        'title',
        'slug',
        'author',
        'publish_date',
        'short_description',
        'full_description',
        'featured_image',
        'media_gallery',
        'videos',
        'audios',
        'pdfs',
        'tags',
        'status',
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
        'title' => 'array',
        'author' => 'array',
        'short_description' => 'array',
        'full_description' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'meta_keywords' => 'array',
        'media_gallery' => 'array',
        'videos' => 'array',
        'audios' => 'array',
        'pdfs' => 'array',
        'tags' => 'array',
        'publish_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_website' => 'boolean',
        'show_on_app' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Content Type this content belongs to.
     */
    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class, 'content_type_id');
    }

    /**
     * Main Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Subcategory.
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    /**
     * Scope active & published contents.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('is_active', true)
            ->where('publish_date', '<=', now()->toDateString());
    }

    /**
     * Scope featured.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
