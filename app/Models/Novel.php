<?php

namespace App\Models;

use Database\Factories\NovelFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Novel extends Model
{
    /** @use HasFactory<NovelFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'category_id',
        'status',
        'is_featured',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'view_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'view_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Scope for published and completed novels only.
     *
     * @param  Builder<Novel>  $query
     * @return Builder<Novel>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereIn('status', ['published', 'completed']);
    }
}
