<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'cover_image', 'category', 'tags',
        'status', 'author_id', 'views_count', 'reading_time_minutes',
        'meta_title', 'meta_description', 'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Blog $blog) {
            if (empty($blog->slug)) {
                $blog->slug = static::generateUniqueSlug($blog->title);
            }
            $blog->reading_time_minutes = static::estimateReadingTime($blog->content);
        });

        static::updating(function (Blog $blog) {
            if ($blog->isDirty('content')) {
                $blog->reading_time_minutes = static::estimateReadingTime($blog->content);
            }
        });
    }

    protected static function estimateReadingTime(?string $content): int
    {
        $words = str_word_count(strip_tags($content ?? ''));
        return max(1, (int) ceil($words / 200)); // ~200 wpm average reading speed
    }

    public static function generateUniqueSlug(string $title, $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $i = 2;

        while (
            static::withTrashed()->where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        return $query->where('slug', $value);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function views()
    {
        return $this->hasMany(BlogView::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(fn($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function publish(): void
    {
        $this->update(['status' => 'published', 'published_at' => $this->published_at ?? now()]);
    }

    public function unpublish(): void
    {
        $this->update(['status' => 'draft']);
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
