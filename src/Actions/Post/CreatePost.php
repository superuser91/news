<?php

namespace Vgplay\News\Actions\Post;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Vgplay\News\Models\Post;
use Illuminate\Support\Str;

class CreatePost
{
    public function create(array $data): Post
    {
        $this->validate($data);

        return $this->createPost($data);
    }

    protected function validate(array $data)
    {
        $validator = Validator::make($data, [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:191',
            'seo_title' => 'nullable|string|max:191',
            'excerpt' => 'nullable|string|max:512',
            'body' => 'required|string',
            'image' => 'nullable|string|max:2048',
            'slug' => 'nullable|alpha_dash|max:191|unique:posts,slug',
            'meta_description' => 'nullable|string|max:512',
            'meta_keywords' => 'nullable|string|max:512',
            'status' => 'required|in:' . implode(',', array_keys(config('vgplay.news.posts.statuses'))),
            'featured' => 'nullable',
            'published_at' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    protected function createPost(array $data): Post
    {
        $data['slug'] = isset($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']) . '-' . time();

        $data['published_at'] = isset($data['published_at']) ? Carbon::createFromFormat('d/m/Y H:i', $data['published_at']) : now();

        $data['author_id'] = auth(config('vgplay.posts.guard'))->id();

        return Post::create($data);
    }
}
