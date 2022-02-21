<?php

namespace Vgplay\News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vgplay\LaravelRedisModel\Contracts\Cacheable;
use Vgplay\LaravelRedisModel\HasCache;

class Post extends Model implements Cacheable
{
    use HasFactory;
    use HasCache;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'seo_title',
        'excerpt',
        'body',
        'image',
        'slug',
        'meta_description',
        'meta_keywords',
        'status',
        'featured',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    protected $refreshs = [
        'category'
    ];

    public static function primaryCacheKey(): string
    {
        return 'slug';
    }

    public function author()
    {
        return $this->belongsTo(config('vgplay.guard_model'), 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categoryName()
    {
        $category = Category::fromCache()->all()->firstWhere('id', $this->category_id);

        if (is_null($category)) return '';

        return $category->name;
    }

    public function relatedPosts($take = 5)
    {
        $posts = static::fromCache()->all();

        return $posts->filter(function ($otherPost) {
            return $otherPost->id != $this->id &&
                !empty(array_intersect(
                    explode(',', $otherPost->meta_keywords),
                    explode(',', $this->meta_keywords)
                ));
        })->where('status', 'PUBLISHED')->take($take);
    }

    public function isPublished()
    {
        return $this->status === 'PUBLISHED';
    }
}
