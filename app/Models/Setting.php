<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'updated_by'
    ];

    /**
     * Get value by key with optional default.
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        if ($setting->type === 'json') {
            return json_decode($setting->value, true) ?? [];
        }

        return $setting->value;
    }

    /**
     * Set value by key.
     */
    public static function set(string $key, $value, string $type = 'text', string $group = 'general'): void
    {
        $rawValue = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
        
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $rawValue,
                'type' => $type,
                'group' => $group,
                'updated_by' => auth()->id()
            ]
        );
    }
}
