<?php

namespace Vgplay\News\Actions\Category;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Vgplay\News\Models\Category;
use Illuminate\Support\Str;

class CreateCategory
{
    /**
     * create api
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category
    {
        $this->validate($data);

        return $this->createCategory($data);
    }

    /**
     * validate
     *
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    protected function validate(array $data)
    {
        $validator = Validator::make($data, [
            "name" => 'required|string|max:191|unique:categories,name',
            "slug" => 'nullable|alpha_dash|max:191|unique:table,slug',
            "parent_id" => 'nullable|exists:categories,id',
            "order" => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    protected function createCategory(array $data): Category
    {
        $data['slug'] = isset($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']) . '-' . time();

        return Category::create($data);
    }
}
