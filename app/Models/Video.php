<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    protected $fillable = [
        'category',
        'title',
        'speaker',
        'description',
        'bible_reference',
        'video_url',
        'video_path',
        'likes',
        'shares',
        'comments_count',
    ];

    protected $appends = ['resolved_video_url'];

    /**
     * Resolves the public URL for the video resource.
     * If the video was uploaded locally, it returns the asset path, otherwise the external URL.
     */
    public function getResolvedVideoUrlAttribute(): string
    {
        if ($this->video_path) {
            return asset(Storage::url($this->video_path));
        }
        return $this->video_url ?? '';
    }
}
