<?php

namespace Vgplay\News\Actions\Post;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Vgplay\News\Models\Post;
use Illuminate\Support\Str;

class UpdatePost
{
    public function update(Post $post, array $data)
    {
        $this->validate($post, $data);

        return $this->updatePost($post, $data);
    }

    protected function validate(Post $post, array $data)
    {
        $validator = Validator::make($data, [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:191',
            'seo_title' => 'nullable|string|max:191',
            'excerpt' => 'nullable|string|max:512',
            'body' => 'required|string',
            'image' => 'nullable|string|max:2048',
            'slug' => 'nullable|alpha_dash|max:191|unique:posts,slug,' . $post->id,
            'meta_description' => 'nullable|string|max:512',
            'meta_keywords' => 'nullable|string|max:512',
            'status' => 'required|in:' . implode(',', array_keys(config('vgplay.news.posts.statuses'))),
            'featured' => 'nullable',
            'published_at' => 'nullable|string'
        ], [], [
            'category_id' => 'chuyên mục',
            'title' => 'tiêu đề',
            'seo_title' => 'SEO title',
            'excerpt' => 'đoạn tóm tắt',
            'body' => 'nội dung',
            'image' => 'ảnh thumbnail bài viết',
            'status' =>  'trạng thái',
            'featured' => 'nullable',
            'published_at' => 'ngày xuất bản'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    protected function updatePost(Post $post, array $data): Post
    {
        $data['slug'] = isset($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']) . '-' . time();

        $data['published_at'] = isset($data['published_at']) ? Carbon::createFromFormat('d/m/Y H:i', $data['published_at']) : now();

        $data['author_id'] = auth(config('vgplay.posts.guard'))->id();

        if ($post->category_id != $data['category_id']) {
            $post->category->touch();
        }

        $post->update($data);

        $post->category->touch();

        return $post;
    }
}
