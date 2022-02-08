<?php

namespace Vgplay\News\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Vgplay\News\Actions\Category\CreateCategory;
use Vgplay\News\Actions\Category\UpdateCategory;
use Vgplay\News\Models\Category;

class CategoryController
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::fromCache()->all();

        return view('vgplay::categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', Category::class);

        $categories = Category::fromCache()->all();

        return view('vgplay::categories.create', compact('categories'));
    }

    public function store(Request $request, CreateCategory $creater)
    {
        $this->authorize('create', Category::class);

        try {
            $creater->create($request->all());
            session()->flash('status', 'Thêm thành công');
            return redirect(route('categories.index'));
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
        $category = Category::findOrFail($id);

        $this->authorize('update', $category);

        $categories = Category::fromCache()->all();

        return view('vgplay::categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, UpdateCategory $updater, $id)
    {
        $category = Category::findOrFail($id);

        $this->authorize('update', $category);

        try {
            $updater->update($category, $request->all());
            session()->flash('status', 'Cập nhật thành công');
            return redirect(route('categories.index'));
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
        $category = Category::findOrFail($id);

        $this->authorize('delete', $category);

        try {
            $category->delete();
            session()->flash('status', 'Xóa thành công');
            return redirect(route('categories.index'));
        } catch (\Exception $e) {
            session()->flash('status', $e->getMessage());
            return back()->withInput();
        }
    }
}
