<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasTranslations;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'url',
        'target',
        'icon',
        'classes',
        'type',
        'display_order',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'label' => 'array',
        'display_order' => 'integer'
    ];

    /**
     * Menu container.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Parent menu item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Sub menu items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('display_order');
    }
}
