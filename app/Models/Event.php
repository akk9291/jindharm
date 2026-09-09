<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes, HasTranslations, \App\Traits\HasAuditFields;

    protected $fillable = [
        'slug',
        'event_name',
        'banner',
        'description',
        'venue',
        'start_date',
        'end_date',
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
        'event_name' => 'array',
        'description' => 'array',
        'venue' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_website' => 'boolean',
        'show_on_app' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Scope active.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
