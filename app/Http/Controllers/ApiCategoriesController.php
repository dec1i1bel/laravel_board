<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Category;

class ApiCategoriesController extends Controller
{
    /**
     * @return string
     */
    public function getAll(): string
    {
        return Category::all()->toJson(JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Category $category
     * @return string
     */
    public function getCategory(Category $category): string
    {
        return $category->toJson(JSON_UNESCAPED_UNICODE);
    }
}
