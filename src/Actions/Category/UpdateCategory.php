<?php

namespace Vgplay\News\Actions\Category;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Vgplay\News\Models\Category;
use Illuminate\Support\Str;

class UpdateCategory
{
    public function update(Category $category, array $data)
    {
        $this->validate($category, $data);

        return $this->updateCategory($category, $data);
    }

    protected function validate(Category $category, array $data)
    {
        $validator = Validator::make($data, [
            "name" => 'required|string|max:191|unique:categories,name,' . $category->id,
            "slug" => 'nullable|alpha_dash|max:191|unique:categories,slug,' . $category->id,
            "parent_id" => 'nullable|exists:categories,id',
            "order" => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    protected function updateCategory(Category $category, array $data)
    {
        $data['slug'] = isset($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']) . '-' . time();

        return $category->update($data);
    }
}
