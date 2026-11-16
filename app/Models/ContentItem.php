<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentItem extends Model
{
    protected $fillable = [
        'title', 'slug', 'type', 'content_format', 'excerpt', 'body', 'cover_image',
        'status', 'event_start_at', 'event_location', 'external_url',
        'scenario_data', 'author_id', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'event_start_at' => 'datetime',
            'published_at' => 'datetime',
            'scenario_data' => 'array',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
