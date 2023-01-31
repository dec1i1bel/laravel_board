<?php

namespace App\Lib\DemoService;

use App\Models\Category;
use Illuminate\Support\Collection;

/**
 * цель класса - демонстрация Dependency Injection
 */
class DemoCategoriesList
{
    private $categories;

    public function __construct()
    {
        $this->categories = Category::all();
    }

    public function listCategoriesHtml()
    {
        $html = '<h3>Список категорий из DemoCategoriesList</h3>';
        foreach ($this->categories as $cat) {
            $html .= '<ul>
            <li>id: '.$cat->id.'</li>
            <li>название: '.$cat->name.'</li>
            <li>количество объявлений: '.$cat->count_posts.'</li>
            </ul>';
        }

        return $html;
    }
}