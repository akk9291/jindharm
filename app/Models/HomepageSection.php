<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    use HasTranslations;

    protected $fillable = [
        'section_key',
        'label',
        'is_active',
        'display_order',
        'show_on_website',
        'show_on_app',
        'settings'
    ];

    protected $casts = [
        'label' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'show_on_website' => 'boolean',
        'show_on_app' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Scope active sections.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }
}
