<?php

namespace Vgplay\News\Traits;

use Vgplay\News\Models\Post;

trait Postable
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
