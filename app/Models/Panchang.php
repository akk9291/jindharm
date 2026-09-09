<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panchang extends Model
{
    protected $fillable = [
        'date',
        'tithi',
        'paksha',
        'maas',
        'nakshatra',
        'sunrise',
        'sunset',
        'notes',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean'
    ];

    /**
     * Get panchang for a specific date.
     */
    public static function forDate(string $date)
    {
        return self::where('date', $date)->where('is_active', true)->first();
    }
}
