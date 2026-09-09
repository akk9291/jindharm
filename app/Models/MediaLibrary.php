<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MediaLibrary extends Model
{
    protected $table = 'media_library';

    protected $fillable = [
        'folder_id',
        'name',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'file_type',
        'created_by'
    ];

    /**
     * Folder containing this file.
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(MediaFolder::class, 'folder_id');
    }

    /**
     * User who uploaded the file.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get public URL.
     */
    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }
        return Storage::url($this->file_path);
    }
}
