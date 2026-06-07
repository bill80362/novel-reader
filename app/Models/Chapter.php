<?php

namespace App\Models;

use Database\Factories\ChapterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    /** @use HasFactory<ChapterFactory> */
    use HasFactory;

    protected $fillable = [
        'novel_id',
        'chapter_number',
        'title',
        'slug',
        'content',
        'word_count',
        'view_count',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'word_count' => 'integer',
    ];

    public function novel(): BelongsTo
    {
        return $this->belongsTo(Novel::class);
    }
}
