<?php

namespace Vgplay\News\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Vgplay\News\Actions\Post\CreatePost;
use Vgplay\News\Actions\Post\UpdatePost;
use Vgplay\News\Models\Category;
use Vgplay\News\Models\Post;

class PostController
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::with('category', 'author')->get();

        return view('vgplay::posts.index', compact('posts'));
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        $categories = Category::fromCache()->all();

        return view('vgplay::posts.create', compact('categories'));
    }

    public function store(Request $request, CreatePost $creater)
    {
        $this->authorize('create', Post::class);

        try {
            $creater->create($request->all());
            session()->flash('status', 'Thêm thành công');
            return redirect(route('posts.index'));
        } catch (ValidationException $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        $categories = Category::fromCache()->all();

        return view('vgplay::posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, UpdatePost $updater, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);

        try {
            $updater->update($post, $request->all());
            session()->flash('status', 'Cập nhật thành công');
            return redirect(route('posts.index'));
        } catch (ValidationException $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('delete', $post);

        try {
            $post->delete();
            session()->flash('status', 'Xóa thành công');
            return redirect(route('posts.index'));
        } catch (\Exception $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput();
        }
    }
}
