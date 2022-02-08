<?php

namespace Vgplay\News\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vgplay\LaravelRedisModel\Contracts\Cacheable;
use Vgplay\LaravelRedisModel\HasCache;

class Category extends Model implements Cacheable
{
    use HasFactory;
    use HasCache;

    protected $fillable = [
        "name",
        "slug",
        "parent_id",
        "order",
    ];

    public static function primaryCacheKey(): string
    {
        return 'slug';
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function parentName()
    {
        if (is_null($this->parent_id)) return '';

        $parent = static::fromCache()->all()->firstWhere('id', $this->parent_id);

        if (is_null($parent)) return '';

        return $parent->name;
    }
}
