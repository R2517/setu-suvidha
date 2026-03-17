<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'user_id', 'blog_category_id', 'title', 'title_mr', 'slug', 'excerpt', 'excerpt_mr',
        'content_json', 'featured_image', 'featured_image_alt', 'featured_image_caption',
        'meta_title', 'meta_title_mr', 'meta_description', 'meta_description_mr',
        'canonical_url', 'robots', 'og_image', 'focus_keyword', 'focus_keyword_mr',
        'language', 'hreflang_en_slug', 'hreflang_mr_slug',
        'status', 'published_at', 'scheduled_at',
        'view_count', 'reading_time_minutes', 'previous_content_json', 'version', 'old_slug'
    ];

    protected $casts = [
        'content_json' => 'array',
        'previous_content_json' => 'array',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'view_count' => 'integer',
        'reading_time_minutes' => 'integer',
        'version' => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function scopeByCategory($query, $slug)
    {
        return $query->whereHas('category', fn($q) => $q->where('slug', $slug));
    }

    public function scopeByTag($query, $slug)
    {
        return $query->whereHas('tags', fn($q) => $q->where('slug', $slug));
    }

    public function scopeByLanguage($query, $lang)
    {
        return $query->where('language', $lang);
    }

    public function getUrlAttribute(): string
    {
        return url("/blog/{$this->slug}");
    }

    public function getCanonicalAttribute(): string
    {
        return $this->canonical_url ?? $this->url;
    }

    public function getMetaTitleComputed(): string
    {
        return $this->meta_title ?? $this->title . ' | SETU Suvidha';
    }

    public function getMetaDescriptionComputed(): string
    {
        return $this->meta_description ?? $this->excerpt ?? Str::limit(strip_tags($this->getFirstParagraph()), 155);
    }

    public function getOgImageComputed(): string
    {
        return $this->og_image ?? $this->featured_image ?? '/images/setu-default-og.jpg';
    }

    public function getFirstParagraph(): string
    {
        $content = $this->content_json ?? [];
        $blocks = $content['blocks'] ?? $content;
        foreach ($blocks as $block) {
            if (($block['type'] ?? '') === 'paragraph' && !empty($block['content'])) {
                return strip_tags($block['content']);
            }
        }
        return $this->title;
    }

    public static function calculateReadingTime(array $contentJson): int
    {
        $blocks = $contentJson['blocks'] ?? $contentJson;
        $wordCount = 0;
        foreach ($blocks as $block) {
            $text = '';
            if (isset($block['content'])) $text .= strip_tags($block['content']);
            if (isset($block['title'])) $text .= ' ' . $block['title'];
            if (isset($block['items']) && is_array($block['items'])) {
                foreach ($block['items'] as $item) {
                    if (is_string($item)) $text .= ' ' . $item;
                    if (is_array($item)) $text .= ' ' . implode(' ', array_values($item));
                }
            }
            $wordCount += str_word_count($text);
        }
        return max(1, (int) ceil($wordCount / 200));
    }

    public static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $counter = 2;
        $query = static::where('slug', $slug);
        if ($excludeId) $query->where('id', '!=', $excludeId);
        while ($query->exists()) {
            $slug = $original . '-' . $counter++;
            $query = static::where('slug', $slug);
            if ($excludeId) $query->where('id', '!=', $excludeId);
        }
        return $slug;
    }

    public function getRelatedPosts(int $limit = 4)
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->where(function ($q) {
                $q->where('blog_category_id', $this->blog_category_id)
                  ->orWhereHas('tags', fn($tq) => $tq->whereIn('blog_tag_id', $this->tags->pluck('id')));
            })
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function incrementViewCount(): void
    {
        $sessionKey = 'blog_viewed_' . $this->id;
        if (!session()->has($sessionKey)) {
            $this->increment('view_count');
            session()->put($sessionKey, true);
        }
    }
}
