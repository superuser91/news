<?php

namespace Vgplay\News\Actions\Post;

use Illuminate\Http\Request;
use Vgplay\News\Models\Post;

class ListPost
{
    public function list(Request $request)
    {
        $post = Post::fromCache()->all();

        if ($request->ajax() || $request->wantsJson()) {
            return $post;
        }

        return view('posts.index', compact('posts'));
    }
}
