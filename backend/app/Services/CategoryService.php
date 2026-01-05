<?php

namespace App\Services;

use App\Models\Category;

class CategoryService implements CategoryServiceInterface
{
    public function createCategory(string $name)
    {
        return Category::create(['name' => $name]);
    }

    public function getAllCategories()
    {
        return Category::all();
    }
}
