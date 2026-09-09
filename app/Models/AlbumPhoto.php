<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlbumPhoto extends Model
{
    use HasTranslations;

    protected $fillable = [
        'album_id',
        'photo_path',
        'caption',
        'is_active',
        'display_order',
        'created_by'
    ];

    protected $casts = [
        'caption' => 'array',
        'is_active' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Album this photo belongs to.
     */
    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
