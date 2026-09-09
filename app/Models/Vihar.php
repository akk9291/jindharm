<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vihar extends Model
{
    use SoftDeletes, HasTranslations, \App\Traits\HasAuditFields;

    protected $fillable = [
        'saint_id',
        'location_title',
        'address',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'google_map_link',
        'contact_person',
        'contact_number',
        'start_date',
        'end_date',
        'type',
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
        'location_title' => 'array',
        'address' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'show_on_website' => 'boolean',
        'show_on_app' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Saint this vihar belongs to.
     */
    public function saint(): BelongsTo
    {
        return $this->belongsTo(Saint::class);
    }

    /**
     * Scope for current vihars.
     */
    public function scopeCurrent($query)
    {
        return $query->where('type', 'current')->where('is_active', true);
    }

    /**
     * Scope for upcoming vihars.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('type', 'upcoming')->where('is_active', true);
    }

    /**
     * Scope for previous vihars.
     */
    public function scopePrevious($query)
    {
        return $query->where('type', 'previous')->where('is_active', true);
    }
}
