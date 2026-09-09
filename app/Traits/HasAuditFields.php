<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

trait HasAuditFields
{
    /**
     * Boot the trait and register Eloquent event hooks.
     */
    public static function bootHasAuditFields(): void
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $userId = Auth::id();
                if ($model->isFillable('created_by') || array_key_exists('created_by', $model->getAttributes()) || $model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'created_by')) {
                    $model->created_by = $userId;
                }
                if ($model->isFillable('updated_by') || array_key_exists('updated_by', $model->getAttributes()) || $model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'updated_by')) {
                    $model->updated_by = $userId;
                }
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $userId = Auth::id();
                if ($model->isFillable('updated_by') || array_key_exists('updated_by', $model->getAttributes()) || $model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'updated_by')) {
                    $model->updated_by = $userId;
                }
            }
        });

        static::created(function ($model) {
            self::logActivity($model, 'Created', 'नया रिकॉर्ड बनाया गया।');
        });

        static::updated(function ($model) {
            self::logActivity($model, 'Updated', 'रिकॉर्ड अपडेट किया गया।');
        });

        static::deleted(function ($model) {
            // Check if soft deletes is enabled on this model and it's not a force delete
            $isSoftDelete = in_array(SoftDeletes::class, class_uses_recursive(static::class));
            $isForceDelete = $isSoftDelete && $model->isForceDeleting();

            if ($isSoftDelete && !$isForceDelete) {
                if (Auth::check()) {
                    $userId = Auth::id();
                    if ($model->isFillable('deleted_by') || array_key_exists('deleted_by', $model->getAttributes()) || $model->getConnection()->getSchemaBuilder()->hasColumn($model->getTable(), 'deleted_by')) {
                        // Temporarily disable event dispatching to avoid infinite loop when updating deleted_by on delete
                        static::withoutEvents(function () use ($model, $userId) {
                            $model->deleted_by = $userId;
                            $model->save();
                        });
                    }
                }
                self::logActivity($model, 'Deleted (Soft)', 'रिकॉर्ड हटाया गया (सॉफ्ट डिलीट)।');
            } else {
                self::logActivity($model, 'Deleted (Force)', 'रिकॉर्ड पूरी तरह मिटाया गया।');
            }
        });
    }

    /**
     * Helper to log model activity.
     */
    protected static function logActivity($model, string $action, string $defaultDesc): void
    {
        if (!Auth::check()) {
            return;
        }

        $moduleName = self::getFriendlyModuleName($model);
        $recordTitle = self::getModelRecordTitle($model);
        
        $user = Auth::user();
        $roleName = $user->roles->first()->name ?? 'No Role';
        
        $description = "{$user->name} ({$roleName}) ने {$moduleName} '{$recordTitle}' को {$action} किया।";

        // Log using custom static helper or direct db query
        ActivityLog::create([
            'user_id' => $user->id,
            'role' => $roleName,
            'module' => $moduleName,
            'action' => $action,
            'record_id' => $model->id,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now()
        ]);
    }

    /**
     * Get a readable name for the model module.
     */
    protected static function getFriendlyModuleName($model): string
    {
        $class = class_basename($model);
        $map = [
            'Saint' => 'साधु (Saint)',
            'Vihar' => 'विहार (Vihar)',
            'ContentType' => 'कंटेंट प्रकार (Content Type)',
            'Category' => 'श्रेणी (Category)',
            'Content' => 'सामग्री (Content)',
            'Album' => 'एल्बम (Album)',
            'AlbumPhoto' => 'एल्बम फोटो (Album Photo)',
            'Event' => 'आयोजन (Event)',
            'Panchang' => 'पंचांग (Panchang)',
            'Festival' => 'त्योहार (Festival)',
            'Page' => 'पेज (Page)',
            'Menu' => 'मेनू (Menu)',
            'MenuItem' => 'मेनू आइटम (Menu Item)',
            'Setting' => 'वेबसाइट सेटिंग (Setting)',
            'User' => 'उपयोगकर्ता (User)',
            'Role' => 'भूमिका (Role)',
            'Permission' => 'अनुमति (Permission)',
        ];

        return $map[$class] ?? $class;
    }

    /**
     * Get a descriptive title/name from the model.
     */
    protected static function getModelRecordTitle($model): string
    {
        // Try translations or standard attributes
        if (method_exists($model, 'getLocalized')) {
            if ($model->name) {
                return $model->getLocalized('name');
            }
            if ($model->title) {
                return $model->getLocalized('title');
            }
            if ($model->location_title) {
                return $model->getLocalized('location_title');
            }
            if ($model->event_name) {
                return $model->getLocalized('event_name');
            }
            if ($model->festival_name) {
                return $model->getLocalized('festival_name');
            }
        }

        // Check columns
        if (isset($model->name)) {
            return is_array($model->name) ? ($model->name['hi'] ?? $model->name['en'] ?? '') : $model->name;
        }
        if (isset($model->title)) {
            return is_array($model->title) ? ($model->title['hi'] ?? $model->title['en'] ?? '') : $model->title;
        }
        if (isset($model->location_title)) {
            return is_array($model->location_title) ? ($model->location_title['hi'] ?? $model->location_title['en'] ?? '') : $model->location_title;
        }
        if (isset($model->event_name)) {
            return is_array($model->event_name) ? ($model->event_name['hi'] ?? $model->event_name['en'] ?? '') : $model->event_name;
        }
        if (isset($model->festival_name)) {
            return is_array($model->festival_name) ? ($model->festival_name['hi'] ?? $model->festival_name['en'] ?? '') : $model->festival_name;
        }
        if (isset($model->key)) {
            return $model->key;
        }
        if (isset($model->email)) {
            return $model->email;
        }

        return (string)$model->id;
    }
}
